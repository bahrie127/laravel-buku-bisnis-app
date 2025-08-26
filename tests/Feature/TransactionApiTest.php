<?php

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);

    $this->account = Account::factory()->for($this->user)->create();
    $this->category = Category::factory()->for($this->user)->create([
        'type' => 'expense',
    ]);
});

describe('Transaction API', function () {
    it('can list transactions for authenticated user', function () {
        // Create some transactions for the user
        Transaction::factory()
            ->for($this->user)
            ->for($this->account)
            ->for($this->category)
            ->count(3)
            ->create();

        // Create transaction for another user (should not appear)
        $otherUser = User::factory()->create();
        $otherAccount = Account::factory()->for($otherUser)->create();
        $otherCategory = Category::factory()->for($otherUser)->create();
        Transaction::factory()
            ->for($otherUser)
            ->for($otherAccount)
            ->for($otherCategory)
            ->create();

        $response = $this->getJson('/api/transactions');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
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
                ],
                'pagination',
            ])
            ->assertJsonCount(3, 'data');
    });

    it('can create a new transaction', function () {
        $transactionData = [
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'type' => 'expense',
            'date' => '2025-08-25',
            'amount' => 100.50,
            'note' => 'Test transaction',
            'counterparty' => 'Test Shop',
        ];

        $response = $this->postJson('/api/transactions', $transactionData);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
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
            ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'type' => 'expense',
            'amount' => 100.50,
        ]);
    });

    it('validates transaction creation with invalid data', function () {
        $response = $this->postJson('/api/transactions', [
            'account_id' => 999, // Non-existent account
            'category_id' => 999, // Non-existent category
            'type' => 'invalid',
            'date' => 'invalid-date',
            'amount' => -10,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'account_id',
                'category_id',
                'type',
                'date',
                'amount',
            ]);
    });

    it('can show a specific transaction', function () {
        $transaction = Transaction::factory()
            ->for($this->user)
            ->for($this->account)
            ->for($this->category)
            ->create();

        $response = $this->getJson("/api/transactions/{$transaction->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'account_id',
                    'category_id',
                    'type',
                    'date',
                    'amount',
                    'account',
                    'category',
                    'attachments',
                ],
            ]);
    });

    it('cannot show transaction of another user', function () {
        $otherUser = User::factory()->create();
        $otherAccount = Account::factory()->for($otherUser)->create();
        $otherCategory = Category::factory()->for($otherUser)->create();
        $transaction = Transaction::factory()
            ->for($otherUser)
            ->for($otherAccount)
            ->for($otherCategory)
            ->create();

        $response = $this->getJson("/api/transactions/{$transaction->id}");

        $response->assertNotFound();
    });

    it('can update a transaction', function () {
        $transaction = Transaction::factory()
            ->for($this->user)
            ->for($this->account)
            ->for($this->category)
            ->create();

        $updateData = [
            'amount' => 200.75,
            'note' => 'Updated note',
        ];

        $response = $this->putJson("/api/transactions/{$transaction->id}", $updateData);

        $response->assertOk()
            ->assertJsonPath('data.amount', '200.75')
            ->assertJsonPath('data.note', 'Updated note');

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => 200.75,
            'note' => 'Updated note',
        ]);
    });

    it('can delete a transaction', function () {
        $transaction = Transaction::factory()
            ->for($this->user)
            ->for($this->account)
            ->for($this->category)
            ->create();

        $response = $this->deleteJson("/api/transactions/{$transaction->id}");

        $response->assertOk()
            ->assertJsonPath('message', 'Transaction deleted successfully');

        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id,
        ]);
    });

    it('can create a transfer between accounts', function () {
        $fromAccount = Account::factory()->for($this->user)->create(['name' => 'From Account']);
        $toAccount = Account::factory()->for($this->user)->create(['name' => 'To Account']);

        $transferData = [
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => 500.00,
            'date' => '2025-08-25',
            'note' => 'Transfer between accounts',
        ];

        $response = $this->postJson('/api/transactions/transfer', $transferData);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'transfer_group_id',
                    'from_transaction',
                    'to_transaction',
                ],
            ]);

        // Verify both transactions were created
        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'account_id' => $fromAccount->id,
            'type' => 'expense',
            'amount' => 500.00,
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'account_id' => $toAccount->id,
            'type' => 'income',
            'amount' => 500.00,
        ]);
    });

    it('can get transaction statistics', function () {
        // Create some transactions
        Transaction::factory()
            ->for($this->user)
            ->for($this->account)
            ->for($this->category)
            ->create([
                'type' => 'income',
                'amount' => 1000.00,
            ]);

        Transaction::factory()
            ->for($this->user)
            ->for($this->account)
            ->for($this->category)
            ->create([
                'type' => 'expense',
                'amount' => 300.00,
            ]);

        $response = $this->getJson('/api/transactions-statistics');

        $response->assertOk()
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
            ->assertJsonPath('data.total_expense', '300.00')
            ->assertJsonPath('data.net_amount', '700.00');
    });
});
