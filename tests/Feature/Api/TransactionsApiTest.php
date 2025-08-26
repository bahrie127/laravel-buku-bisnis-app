<?php

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;

beforeEach(function () {
    freezeTime();
});

afterEach(function () {
    unfreezeTime();
});

describe('Transaction API Index', function () {
    test('it should return only authenticated user transactions', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        $otherAccount = Account::factory()->for($otherUser)->create();
        $otherCategory = Category::factory()->for($otherUser)->create(['type' => 'expense']);

        $userTransaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 100.00,
        ]);

        Transaction::factory()->for($otherUser)->create([
            'account_id' => $otherAccount->id,
            'category_id' => $otherCategory->id,
            'type' => 'expense',
            'amount' => 200.00,
        ]);

        actingAsApi($user)
            ->getJson('/api/transactions')
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'account_id',
                        'category_id',
                        'type',
                        'date',
                        'amount',
                        'note',
                        'counterparty',
                        'transfer_group_id',
                        'created_at',
                        'updated_at',
                        'account',
                        'category',
                    ],
                ],
                'pagination',
            ])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $userTransaction->id);
    });

    test('it should filter transactions by account', function () {
        $user = User::factory()->create();
        $account1 = Account::factory()->for($user)->create();
        $account2 = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        $transaction1 = Transaction::factory()->for($user)->create([
            'account_id' => $account1->id,
            'category_id' => $category->id,
            'type' => 'expense',
        ]);

        Transaction::factory()->for($user)->create([
            'account_id' => $account2->id,
            'category_id' => $category->id,
            'type' => 'expense',
        ]);

        actingAsApi($user)
            ->getJson("/api/transactions?account_id={$account1->id}")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $transaction1->id);
    });

    test('it should filter transactions by type', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $incomeCategory = Category::factory()->for($user)->create(['type' => 'income']);
        $expenseCategory = Category::factory()->for($user)->create(['type' => 'expense']);

        $incomeTransaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $incomeCategory->id,
            'type' => 'income',
        ]);

        Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
        ]);

        actingAsApi($user)
            ->getJson('/api/transactions?type=income')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $incomeTransaction->id);
    });

    test('it should filter transactions by date range', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        $oldTransaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'date' => '2024-01-01',
        ]);

        $newTransaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'date' => '2024-06-01',
        ]);

        actingAsApi($user)
            ->getJson('/api/transactions?from_date=2024-05-01&to_date=2024-12-31')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $newTransaction->id);
    });

    test('it should search transactions by note and counterparty', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        $transaction1 = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'note' => 'Grocery shopping',
            'counterparty' => null,
        ]);

        $transaction2 = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'note' => 'Payment to supplier',
            'counterparty' => 'ABC Company',
        ]);

        Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'note' => 'Different expense',
            'counterparty' => 'XYZ Corp',
        ]);

        actingAsApi($user)
            ->getJson('/api/transactions?q=grocery')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $transaction1->id);

        actingAsApi($user)
            ->getJson('/api/transactions?q=ABC')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $transaction2->id);
    });
});

describe('Transaction API Store', function () {
    test('it should create transaction successfully with valid data', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        $transactionData = [
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'date' => now()->format('Y-m-d'),
            'amount' => 100.50,
            'note' => 'Test transaction',
            'counterparty' => 'Test Company',
        ];

        actingAsApi($user)
            ->postJson('/api/transactions', $transactionData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'account_id',
                    'category_id',
                    'type',
                    'date',
                    'amount',
                    'note',
                    'counterparty',
                    'account',
                    'category',
                ],
            ])
            ->assertJsonPath('data.account_id', $account->id)
            ->assertJsonPath('data.category_id', $category->id)
            ->assertJsonPath('data.type', 'expense')
            ->assertJsonPath('data.amount', '100.50');

        expect(Transaction::count())->toBe(1);
    });

    test('it should require all mandatory fields', function () {
        $user = User::factory()->create();

        actingAsApi($user)
            ->postJson('/api/transactions', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['account_id', 'category_id', 'type', 'date', 'amount']);
    });

    test('it should validate account belongs to user', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherAccount = Account::factory()->for($otherUser)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        actingAsApi($user)
            ->postJson('/api/transactions', [
                'account_id' => $otherAccount->id,
                'category_id' => $category->id,
                'type' => 'expense',
                'date' => now()->format('Y-m-d'),
                'amount' => 100.00,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['account_id']);
    });

    test('it should validate category belongs to user', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $otherCategory = Category::factory()->for($otherUser)->create(['type' => 'expense']);

        actingAsApi($user)
            ->postJson('/api/transactions', [
                'account_id' => $account->id,
                'category_id' => $otherCategory->id,
                'type' => 'expense',
                'date' => now()->format('Y-m-d'),
                'amount' => 100.00,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    });

    test('it should validate category type matches transaction type', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $incomeCategory = Category::factory()->for($user)->create(['type' => 'income']);

        actingAsApi($user)
            ->postJson('/api/transactions', [
                'account_id' => $account->id,
                'category_id' => $incomeCategory->id,
                'type' => 'expense',
                'date' => now()->format('Y-m-d'),
                'amount' => 100.00,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    });

    test('it should validate transaction type', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        actingAsApi($user)
            ->postJson('/api/transactions', [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'type' => 'invalid_type',
                'date' => now()->format('Y-m-d'),
                'amount' => 100.00,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    });

    test('it should validate amount is positive', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        actingAsApi($user)
            ->postJson('/api/transactions', [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'type' => 'expense',
                'date' => now()->format('Y-m-d'),
                'amount' => -100.00,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['amount']);
    });

    test('it should validate date is not in future', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        actingAsApi($user)
            ->postJson('/api/transactions', [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'type' => 'expense',
                'date' => now()->addDay()->format('Y-m-d'),
                'amount' => 100.00,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['date']);
    });
});

describe('Transaction API Show', function () {
    test('it should show user own transaction', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);
        $transaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
        ]);

        actingAsApi($user)
            ->getJson("/api/transactions/{$transaction->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'account_id',
                    'category_id',
                    'type',
                    'date',
                    'amount',
                    'note',
                    'counterparty',
                    'account',
                    'category',
                    'attachments',
                ],
            ])
            ->assertJsonPath('data.id', $transaction->id);
    });

    test('it should return 404 for other user transaction', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherAccount = Account::factory()->for($otherUser)->create();
        $otherCategory = Category::factory()->for($otherUser)->create(['type' => 'expense']);
        $otherTransaction = Transaction::factory()->for($otherUser)->create([
            'account_id' => $otherAccount->id,
            'category_id' => $otherCategory->id,
            'type' => 'expense',
        ]);

        actingAsApi($user)
            ->getJson("/api/transactions/{$otherTransaction->id}")
            ->assertStatus(404)
            ->assertJsonPath('message', 'Transaction not found');
    });

    test('it should return 404 for non-existent transaction', function () {
        $user = User::factory()->create();

        actingAsApi($user)
            ->getJson('/api/transactions/999999')
            ->assertStatus(404);
    });
});

describe('Transaction API Update', function () {
    test('it should update user own transaction successfully', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);
        $transaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 100.00,
        ]);

        $updateData = [
            'amount' => 150.00,
            'note' => 'Updated note',
        ];

        actingAsApi($user)
            ->putJson("/api/transactions/{$transaction->id}", $updateData)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'amount',
                    'note',
                    'account',
                    'category',
                ],
            ])
            ->assertJsonPath('data.amount', '150.00')
            ->assertJsonPath('data.note', 'Updated note');

        expect($transaction->fresh()->amount)->toBe('150.00');
    });

    test('it should return 404 when updating other user transaction', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherAccount = Account::factory()->for($otherUser)->create();
        $otherCategory = Category::factory()->for($otherUser)->create(['type' => 'expense']);
        $otherTransaction = Transaction::factory()->for($otherUser)->create([
            'account_id' => $otherAccount->id,
            'category_id' => $otherCategory->id,
            'type' => 'expense',
        ]);

        actingAsApi($user)
            ->putJson("/api/transactions/{$otherTransaction->id}", ['amount' => 200.00])
            ->assertStatus(404)
            ->assertJsonPath('message', 'Transaction not found');
    });

    test('it should prevent updating transfer transactions', function () {
        $user = User::factory()->create();
        $fromAccount = Account::factory()->for($user)->create();
        $toAccount = Account::factory()->for($user)->create();

        [$expenseTransaction, $incomeTransaction] = Transaction::createTransfer(
            $user,
            $fromAccount,
            $toAccount,
            100.00,
            'Test transfer'
        );

        actingAsApi($user)
            ->putJson("/api/transactions/{$expenseTransaction->id}", ['amount' => 200.00])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Transfer transactions cannot be updated through this endpoint');
    });

    test('it should validate update data like store', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);
        $transaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
        ]);

        actingAsApi($user)
            ->putJson("/api/transactions/{$transaction->id}", ['amount' => -100.00])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['amount']);
    });
});

describe('Transaction API Delete', function () {
    test('it should delete user own transaction successfully', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);
        $transaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
        ]);

        actingAsApi($user)
            ->deleteJson("/api/transactions/{$transaction->id}")
            ->assertStatus(200)
            ->assertJsonPath('message', 'Transaction deleted successfully');

        expect(Transaction::find($transaction->id))->toBeNull();
    });

    test('it should return 404 when deleting other user transaction', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherAccount = Account::factory()->for($otherUser)->create();
        $otherCategory = Category::factory()->for($otherUser)->create(['type' => 'expense']);
        $otherTransaction = Transaction::factory()->for($otherUser)->create([
            'account_id' => $otherAccount->id,
            'category_id' => $otherCategory->id,
            'type' => 'expense',
        ]);

        actingAsApi($user)
            ->deleteJson("/api/transactions/{$otherTransaction->id}")
            ->assertStatus(404)
            ->assertJsonPath('message', 'Transaction not found');

        expect(Transaction::find($otherTransaction->id))->not->toBeNull();
    });

    test('it should delete both transactions when deleting transfer', function () {
        $user = User::factory()->create();
        $fromAccount = Account::factory()->for($user)->create();
        $toAccount = Account::factory()->for($user)->create();

        [$expenseTransaction, $incomeTransaction] = Transaction::createTransfer(
            $user,
            $fromAccount,
            $toAccount,
            100.00,
            'Test transfer'
        );

        actingAsApi($user)
            ->deleteJson("/api/transactions/{$expenseTransaction->id}")
            ->assertStatus(200)
            ->assertJsonPath('message', 'Transaction deleted successfully');

        expect(Transaction::find($expenseTransaction->id))->toBeNull();
        expect(Transaction::find($incomeTransaction->id))->toBeNull();
    });
});

describe('Transaction API Transfer', function () {
    test('it should create transfer successfully', function () {
        $user = User::factory()->create();
        $fromAccount = Account::factory()->for($user)->create(['name' => 'From Account']);
        $toAccount = Account::factory()->for($user)->create(['name' => 'To Account']);

        $transferData = [
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => 100.00,
            'date' => now()->format('Y-m-d'),
            'note' => 'Test transfer',
        ];

        actingAsApi($user)
            ->postJson('/api/transactions/transfer', $transferData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'transfer_group_id',
                    'from_transaction' => [
                        'id',
                        'type',
                        'amount',
                        'account',
                        'category',
                    ],
                    'to_transaction' => [
                        'id',
                        'type',
                        'amount',
                        'account',
                        'category',
                    ],
                ],
            ])
            ->assertJsonPath('data.from_transaction.type', 'expense')
            ->assertJsonPath('data.to_transaction.type', 'income')
            ->assertJsonPath('data.from_transaction.amount', '100.00')
            ->assertJsonPath('data.to_transaction.amount', '100.00');

        expect(Transaction::count())->toBe(2);
    });

    test('it should validate transfer data', function () {
        $user = User::factory()->create();

        actingAsApi($user)
            ->postJson('/api/transactions/transfer', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['from_account_id', 'to_account_id', 'amount', 'date']);
    });

    test('it should prevent transfer to same account', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();

        actingAsApi($user)
            ->postJson('/api/transactions/transfer', [
                'from_account_id' => $account->id,
                'to_account_id' => $account->id,
                'amount' => 100.00,
                'date' => now()->format('Y-m-d'),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['from_account_id']);
    });

    test('it should validate accounts belong to user', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $userAccount = Account::factory()->for($user)->create();
        $otherAccount = Account::factory()->for($otherUser)->create();

        actingAsApi($user)
            ->postJson('/api/transactions/transfer', [
                'from_account_id' => $userAccount->id,
                'to_account_id' => $otherAccount->id,
                'amount' => 100.00,
                'date' => now()->format('Y-m-d'),
            ])
            ->assertStatus(404)
            ->assertJsonPath('message', 'One or both accounts not found or do not belong to you');
    });
});

describe('Transaction API Statistics', function () {
    test('it should return transaction statistics', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $incomeCategory = Category::factory()->for($user)->create(['type' => 'income']);
        $expenseCategory = Category::factory()->for($user)->create(['type' => 'expense']);

        Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $incomeCategory->id,
            'type' => 'income',
            'amount' => 1000.00,
        ]);

        Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
            'amount' => 300.00,
        ]);

        Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
            'amount' => 200.00,
        ]);

        actingAsApi($user)
            ->getJson('/api/transactions-statistics')
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'total_income',
                    'total_expense',
                    'net_amount',
                    'transaction_count',
                    'income_count',
                    'expense_count',
                ],
            ])
            ->assertJsonPath('data.total_income', '1000.00')
            ->assertJsonPath('data.total_expense', '500.00')
            ->assertJsonPath('data.net_amount', '500.00')
            ->assertJsonPath('data.transaction_count', 3)
            ->assertJsonPath('data.income_count', 1)
            ->assertJsonPath('data.expense_count', 2);
    });

    test('it should filter statistics by date range', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);

        Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 100.00,
            'date' => '2024-01-01',
        ]);

        Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 200.00,
            'date' => '2024-06-01',
        ]);

        actingAsApi($user)
            ->getJson('/api/transactions-statistics?from_date=2024-05-01&to_date=2024-12-31')
            ->assertStatus(200)
            ->assertJsonPath('data.total_expense', '200.00')
            ->assertJsonPath('data.transaction_count', 1);
    });
});

describe('Transaction API Authentication', function () {
    test('it should return 401 for all endpoints without authentication', function () {
        $endpoints = [
            ['GET', '/api/transactions'],
            ['POST', '/api/transactions'],
            ['GET', '/api/transactions/1'],
            ['PUT', '/api/transactions/1'],
            ['DELETE', '/api/transactions/1'],
            ['POST', '/api/transactions/transfer'],
            ['GET', '/api/transactions-statistics'],
        ];

        foreach ($endpoints as [$method, $endpoint]) {
            $this->json($method, $endpoint)
                ->assertStatus(401);
        }
    });
});

describe('Transaction JSON Structure and Data Types', function () {
    test('it should return correct JSON structure and data types', function () {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->create(['type' => 'expense']);
        $transaction = Transaction::factory()->for($user)->create([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 123.45,
            'note' => 'Test note',
            'counterparty' => 'Test counterparty',
        ]);

        $response = actingAsApi($user)
            ->getJson("/api/transactions/{$transaction->id}")
            ->assertStatus(200);

        $data = $response->json('data');

        expect($data['id'])->toBeInt();
        expect($data['user_id'])->toBeInt();
        expect($data['account_id'])->toBeInt();
        expect($data['category_id'])->toBeInt();
        expect($data['type'])->toBeString();
        expect($data['amount'])->toBeString()->and($data['amount'])->toBe('123.45');
        expect($data['note'])->toBeString();
        expect($data['counterparty'])->toBeString();
        expect($data['date'])->toBeString();
        expect($data['created_at'])->toBeString();
        expect($data['updated_at'])->toBeString();
        expect($data['account'])->toBeArray();
        expect($data['category'])->toBeArray();
        expect($data['attachments'])->toBeArray();
    });
});
