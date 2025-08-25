<?php

use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    // Freeze time to have consistent timestamps
    Carbon::setTestNow('2025-08-26 10:00:00');

    // Create two test users
    $this->userA = User::factory()->create([
        'name' => 'User A',
        'email' => 'usera@example.com',
    ]);

    $this->userB = User::factory()->create([
        'name' => 'User B',
        'email' => 'userb@example.com',
    ]);

    // Create accounts for User A
    $this->accountsUserA = [
        Account::factory()->create([
            'user_id' => $this->userA->id,
            'name' => 'Cash Wallet A',
            'type' => 'cash',
            'starting_balance' => 1000.50,
            'is_active' => true,
        ]),
        Account::factory()->create([
            'user_id' => $this->userA->id,
            'name' => 'Bank Account A',
            'type' => 'bank',
            'starting_balance' => 5000.00,
            'is_active' => true,
        ]),
        Account::factory()->create([
            'user_id' => $this->userA->id,
            'name' => 'Inactive Account A',
            'type' => 'ewallet',
            'starting_balance' => 250.75,
            'is_active' => false,
        ]),
    ];

    // Create accounts for User B
    $this->accountsUserB = [
        Account::factory()->create([
            'user_id' => $this->userB->id,
            'name' => 'Cash Wallet B',
            'type' => 'cash',
            'starting_balance' => 800.25,
            'is_active' => true,
        ]),
        Account::factory()->create([
            'user_id' => $this->userB->id,
            'name' => 'Bank Account B',
            'type' => 'bank',
            'starting_balance' => 3000.00,
            'is_active' => false,
        ]),
    ];
});

afterEach(function () {
    Carbon::setTestNow();
});

describe('Account API Index', function () {
    it('should return only authenticated user accounts', function () {
        $response = actingAsApi($this->userA)
            ->getJson('/api/accounts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'name',
                        'type',
                        'starting_balance',
                        'is_active',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'meta' => [
                    'total',
                    'filters_applied' => [
                        'search',
                        'is_active',
                    ]
                ]
            ]);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(3);

        // Verify all returned accounts belong to userA
        foreach ($responseData as $account) {
            expect($account['user_id'])->toBe($this->userA->id);
            expect($account['starting_balance'])->toBeString(); // Should be string due to decimal casting
        }

        // Verify userB accounts are not included
        $accountIds = collect($responseData)->pluck('id')->toArray();
        foreach ($this->accountsUserB as $accountB) {
            expect($accountIds)->not->toContain($accountB->id);
        }
    });

    it('should filter accounts by search query', function () {
        $response = actingAsApi($this->userA)
            ->getJson('/api/accounts?q=Cash');

        $response->assertStatus(200);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(1);
        expect($responseData[0]['name'])->toBe('Cash Wallet A');
        expect($response->json('meta.filters_applied.search'))->toBe('Cash');
    });

    it('should filter accounts by active status', function () {
        $response = actingAsApi($this->userA)
            ->getJson('/api/accounts?is_active=1');

        $response->assertStatus(200);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(2); // Only active accounts

        foreach ($responseData as $account) {
            expect($account['is_active'])->toBeTrue();
        }
        expect($response->json('meta.filters_applied.is_active'))->toBe('1');
    });

    it('should filter accounts by inactive status', function () {
        $response = actingAsApi($this->userA)
            ->getJson('/api/accounts?is_active=0');

        $response->assertStatus(200);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(1); // Only inactive account
        expect($responseData[0]['is_active'])->toBeFalse();
        expect($responseData[0]['name'])->toBe('Inactive Account A');
    });

    it('should combine search and active filters', function () {
        $response = actingAsApi($this->userA)
            ->getJson('/api/accounts?q=Bank&is_active=1');

        $response->assertStatus(200);

        $responseData = $response->json('data');
        expect($responseData)->toHaveCount(1);
        expect($responseData[0]['name'])->toBe('Bank Account A');
        expect($responseData[0]['is_active'])->toBeTrue();

        expect($response->json('meta.filters_applied.search'))->toBe('Bank');
        expect($response->json('meta.filters_applied.is_active'))->toBe('1');
    });
});

describe('Account API Store', function () {
    it('should create account successfully with valid data', function () {
        $accountData = [
            'name' => 'New Test Account',
            'type' => 'bank',
            'starting_balance' => 1500.25,
            'is_active' => true,
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'name',
                    'type',
                    'starting_balance',
                    'is_active',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $createdAccount = $response->json('data');
        expect($createdAccount['user_id'])->toBe($this->userA->id);
        expect($createdAccount['name'])->toBe('New Test Account');
        expect($createdAccount['type'])->toBe('bank');
        expect($createdAccount['starting_balance'])->toBe('1500.25');
        expect($createdAccount['is_active'])->toBeTrue();

        // Verify in database
        $this->assertDatabaseHas('accounts', [
            'id' => $createdAccount['id'],
            'user_id' => $this->userA->id,
            'name' => 'New Test Account',
            'type' => 'bank',
            'starting_balance' => 1500.25,
            'is_active' => true,
        ]);
    });

    it('should set is_active to true by default when not provided', function () {
        $accountData = [
            'name' => 'Default Active Account',
            'type' => 'cash',
            'starting_balance' => 100.00,
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(201);
        expect($response->json('data.is_active'))->toBeTrue();
    });

    it('should require name field', function () {
        $accountData = [
            'type' => 'cash',
            'starting_balance' => 100.00,
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    it('should require type field', function () {
        $accountData = [
            'name' => 'Test Account',
            'starting_balance' => 100.00,
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    });

    it('should validate type is in allowed values', function () {
        $accountData = [
            'name' => 'Test Account',
            'type' => 'invalid_type',
            'starting_balance' => 100.00,
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    });

    it('should require starting_balance field', function () {
        $accountData = [
            'name' => 'Test Account',
            'type' => 'cash',
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['starting_balance']);
    });

    it('should validate starting_balance is not negative', function () {
        $accountData = [
            'name' => 'Test Account',
            'type' => 'cash',
            'starting_balance' => -100.00,
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['starting_balance']);
    });

    it('should validate is_active is boolean', function () {
        $accountData = [
            'name' => 'Test Account',
            'type' => 'cash',
            'starting_balance' => 100.00,
            'is_active' => 'not_boolean',
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_active']);
    });

    it('should accept all valid account types', function () {
        $validTypes = ['cash', 'bank', 'ewallet', 'other'];

        foreach ($validTypes as $type) {
            $accountData = [
                'name' => "Test {$type} Account",
                'type' => $type,
                'starting_balance' => 100.00,
            ];

            $response = actingAsApi($this->userA)
                ->postJson('/api/accounts', $accountData);

            $response->assertStatus(201);
            expect($response->json('data.type'))->toBe($type);
        }
    });
});

describe('Account API Show', function () {
    it('should show user own account', function () {
        $account = $this->accountsUserA[0];

        $response = actingAsApi($this->userA)
            ->getJson("/api/accounts/{$account->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'name',
                    'type',
                    'starting_balance',
                    'is_active',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $responseData = $response->json('data');
        expect($responseData['id'])->toBe($account->id);
        expect($responseData['user_id'])->toBe($this->userA->id);
        expect($responseData['name'])->toBe($account->name);
        expect($responseData['starting_balance'])->toBe('1000.50');
    });

    it('should return 404 for other user account', function () {
        $otherUserAccount = $this->accountsUserB[0];

        $response = actingAsApi($this->userA)
            ->getJson("/api/accounts/{$otherUserAccount->id}");

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Account not found'
            ]);
    });

    it('should return 404 for non-existent account', function () {
        $response = actingAsApi($this->userA)
            ->getJson('/api/accounts/99999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Account not found'
            ]);
    });
});

describe('Account API Update', function () {
    it('should update user own account successfully', function () {
        $account = $this->accountsUserA[0];
        $updateData = [
            'name' => 'Updated Account Name',
            'type' => 'bank',
            'starting_balance' => 2000.75,
            'is_active' => false,
        ];

        $response = actingAsApi($this->userA)
            ->putJson("/api/accounts/{$account->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'name',
                    'type',
                    'starting_balance',
                    'is_active',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $updatedAccount = $response->json('data');
        expect($updatedAccount['name'])->toBe('Updated Account Name');
        expect($updatedAccount['type'])->toBe('bank');
        expect($updatedAccount['starting_balance'])->toBe('2000.75');
        expect($updatedAccount['is_active'])->toBeFalse();

        // Verify in database
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'Updated Account Name',
            'type' => 'bank',
            'starting_balance' => 2000.75,
            'is_active' => false,
        ]);
    });

    it('should return 404 when updating other user account', function () {
        $otherUserAccount = $this->accountsUserB[0];
        $updateData = [
            'name' => 'Hacked Account',
            'type' => 'cash',
            'starting_balance' => 9999.99,
        ];

        $response = actingAsApi($this->userA)
            ->putJson("/api/accounts/{$otherUserAccount->id}", $updateData);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Account not found'
            ]);

        // Verify original data unchanged
        $this->assertDatabaseHas('accounts', [
            'id' => $otherUserAccount->id,
            'name' => 'Cash Wallet B', // Original name
            'user_id' => $this->userB->id, // Still belongs to userB
        ]);
    });

    it('should validate update data like store', function () {
        $account = $this->accountsUserA[0];
        $invalidData = [
            'name' => '', // Required
            'type' => 'invalid_type', // Invalid type
            'starting_balance' => -100, // Negative
            'is_active' => 'not_boolean', // Not boolean
        ];

        $response = actingAsApi($this->userA)
            ->putJson("/api/accounts/{$account->id}", $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'type', 'starting_balance', 'is_active']);
    });
});

describe('Account API Delete', function () {
    it('should delete user own account successfully', function () {
        $account = $this->accountsUserA[0];

        $response = actingAsApi($this->userA)
            ->deleteJson("/api/accounts/{$account->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Account deleted successfully'
            ]);

        // Verify account is deleted from database
        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
        ]);
    });

    it('should return 404 when deleting other user account', function () {
        $otherUserAccount = $this->accountsUserB[0];

        $response = actingAsApi($this->userA)
            ->deleteJson("/api/accounts/{$otherUserAccount->id}");

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Account not found'
            ]);

        // Verify account still exists
        $this->assertDatabaseHas('accounts', [
            'id' => $otherUserAccount->id,
            'user_id' => $this->userB->id,
        ]);
    });

    it('should return 422 when deleting account with transactions', function () {
        $account = $this->accountsUserA[0];

        // Create a category first
        $category = \App\Models\Category::factory()->create([
            'user_id' => $this->userA->id,
            'name' => 'Test Category',
            'type' => 'income',
        ]);

        // Create a transaction for this account
        $account->transactions()->create([
            'user_id' => $this->userA->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => 100.00,
            'note' => 'Test transaction',
            'date' => now(),
        ]);

        $response = actingAsApi($this->userA)
            ->deleteJson("/api/accounts/{$account->id}");

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Cannot delete account that has transactions'
            ]);

        // Verify account still exists
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
        ]);
    });
});

describe('Account API Authentication', function () {
    it('should return 401 for all endpoints without authentication', function () {
        $account = $this->accountsUserA[0];

        // Test all endpoints without auth token
        $endpoints = [
            ['GET', '/api/accounts'],
            ['POST', '/api/accounts'],
            ['GET', "/api/accounts/{$account->id}"],
            ['PUT', "/api/accounts/{$account->id}"],
            ['DELETE', "/api/accounts/{$account->id}"],
        ];

        foreach ($endpoints as [$method, $url]) {
            $response = match ($method) {
                'GET' => $this->getJson($url),
                'POST' => $this->postJson($url, [
                    'name' => 'Test',
                    'type' => 'cash',
                    'starting_balance' => 100,
                ]),
                'PUT' => $this->putJson($url, [
                    'name' => 'Updated',
                    'type' => 'bank',
                    'starting_balance' => 200,
                ]),
                'DELETE' => $this->deleteJson($url),
            };

            $response->assertStatus(401);
        }
    });
});

describe('Account JSON Structure and Data Types', function () {
    it('should return correct JSON structure and data types', function () {
        $response = actingAsApi($this->userA)
            ->getJson('/api/accounts');

        $response->assertStatus(200);

        $accounts = $response->json('data');
        expect($accounts)->toBeArray();
        expect($accounts)->not->toBeEmpty();

        $account = $accounts[0];

        // Check data types
        expect($account['id'])->toBeInt();
        expect($account['user_id'])->toBeInt();
        expect($account['name'])->toBeString();
        expect($account['type'])->toBeString();
        expect($account['starting_balance'])->toBeString(); // Due to decimal:2 casting
        expect($account['is_active'])->toBeBool();
        expect($account['created_at'])->toBeString();
        expect($account['updated_at'])->toBeString();

        // Verify decimal formatting
        expect($account['starting_balance'])->toMatch('/^\d+\.\d{2}$/');
    });

    it('should return correct data types in store response', function () {
        $accountData = [
            'name' => 'Type Test Account',
            'type' => 'cash',
            'starting_balance' => 123.45,
            'is_active' => true,
        ];

        $response = actingAsApi($this->userA)
            ->postJson('/api/accounts', $accountData);

        $response->assertStatus(201);

        $account = $response->json('data');

        expect($account['id'])->toBeInt();
        expect($account['user_id'])->toBeInt();
        expect($account['name'])->toBeString();
        expect($account['type'])->toBeString();
        expect($account['starting_balance'])->toBeString();
        expect($account['starting_balance'])->toBe('123.45');
        expect($account['is_active'])->toBeBool();
    });
});
