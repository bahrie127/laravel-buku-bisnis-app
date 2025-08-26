<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with(['account:id,name,type', 'category:id,name,type']);

        // Filter by account
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->input('account_id'));
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $type = $request->input('type');
            if (in_array($type, ['income', 'expense'])) {
                $query->where('type', $type);
            }
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->input('to_date'));
        }

        // Search by note or counterparty
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('note', 'like', "%{$search}%")
                    ->orWhere('counterparty', 'like', "%{$search}%");
            });
        }

        // Filter by amount range
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->input('min_amount'));
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->input('max_amount'));
        }

        // Sort by date (newest first by default)
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');

        if (in_array($sortBy, ['date', 'amount', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('date', 'desc');
        }

        // Pagination
        $perPage = min($request->input('per_page', 15), 100);
        $transactions = $query->paginate($perPage);

        return response()->json([
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created transaction.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'account_id' => $request->validated()['account_id'],
                'category_id' => $request->validated()['category_id'],
                'type' => $request->validated()['type'],
                'date' => $request->validated()['date'],
                'amount' => $request->validated()['amount'],
                'note' => $request->validated()['note'] ?? null,
                'counterparty' => $request->validated()['counterparty'] ?? null,
            ]);

            $transaction->load(['account:id,name,type', 'category:id,name,type']);

            DB::commit();

            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $transaction,
            ], 201);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while creating the transaction',
            ], 500);
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        // Check if the transaction belongs to the authenticated user
        if ($transaction->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }

        $transaction->load([
            'account:id,name,type',
            'category:id,name,type',
            'attachments:id,transaction_id,original_name,path,size',
        ]);

        // Include transfer partner if this is a transfer transaction
        if ($transaction->isTransfer()) {
            $transferPartner = $transaction->transferPartner();
            if ($transferPartner) {
                $transferPartner->load(['account:id,name,type']);
                $transaction->transfer_partner = $transferPartner;
            }
        }

        return response()->json([
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction,
        ]);
    }

    /**
     * Update the specified transaction.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        // Check if the transaction belongs to the authenticated user
        if ($transaction->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }

        // Prevent updating transfer transactions through this endpoint
        if ($transaction->isTransfer()) {
            return response()->json([
                'message' => 'Transfer transactions cannot be updated through this endpoint',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $validatedData = $request->validated();
            $transaction->update($validatedData);
            $transaction->load(['account:id,name,type', 'category:id,name,type']);

            DB::commit();

            return response()->json([
                'message' => 'Transaction updated successfully',
                'data' => $transaction,
            ]);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while updating the transaction',
            ], 500);
        }
    }

    /**
     * Remove the specified transaction.
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        // Check if the transaction belongs to the authenticated user
        if ($transaction->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }

        try {
            DB::beginTransaction();

            // If this is a transfer transaction, delete both transactions
            if ($transaction->isTransfer()) {
                $transferPartner = $transaction->transferPartner();

                // Delete attachments for both transactions
                $transaction->attachments()->delete();
                if ($transferPartner) {
                    $transferPartner->attachments()->delete();
                    $transferPartner->delete();
                }
            } else {
                // Delete attachments for regular transaction
                $transaction->attachments()->delete();
            }

            $transaction->delete();

            DB::commit();

            return response()->json([
                'message' => 'Transaction deleted successfully',
            ]);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while deleting the transaction',
            ], 500);
        }
    }

    /**
     * Create a transfer between two accounts.
     */
    public function createTransfer(Request $request): JsonResponse
    {
        $request->validate([
            'from_account_id' => [
                'required',
                'integer',
                'exists:accounts,id',
                'different:to_account_id',
            ],
            'to_account_id' => [
                'required',
                'integer',
                'exists:accounts,id',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999999.99',
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'from_account_id.different' => 'Source and destination accounts must be different.',
            'amount.min' => 'Transfer amount must be at least 0.01.',
            'date.before_or_equal' => 'Transfer date cannot be in the future.',
        ]);

        try {
            // Verify both accounts belong to the user
            $fromAccount = Auth::user()->accounts()->findOrFail($request->from_account_id);
            $toAccount = Auth::user()->accounts()->findOrFail($request->to_account_id);

            DB::beginTransaction();

            [$expenseTransaction, $incomeTransaction] = Transaction::createTransfer(
                Auth::user(),
                $fromAccount,
                $toAccount,
                $request->amount,
                $request->note,
                $request->date
            );

            // Load relationships for response
            $expenseTransaction->load(['account:id,name,type', 'category:id,name,type']);
            $incomeTransaction->load(['account:id,name,type', 'category:id,name,type']);

            DB::commit();

            return response()->json([
                'message' => 'Transfer created successfully',
                'data' => [
                    'transfer_group_id' => $expenseTransaction->transfer_group_id,
                    'from_transaction' => $expenseTransaction,
                    'to_transaction' => $incomeTransaction,
                ],
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'One or both accounts not found or do not belong to you',
            ], 404);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while creating the transfer',
            ], 500);
        }
    }

    /**
     * Get transaction statistics for the authenticated user.
     */
    public function statistics(Request $request): JsonResponse
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'account_id' => 'nullable|integer|exists:accounts,id',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        $query = Transaction::where('user_id', Auth::id());

        // Apply filters
        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->to_date);
        }

        if ($request->filled('account_id')) {
            // Verify account belongs to user
            Auth::user()->accounts()->findOrFail($request->account_id);
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('category_id')) {
            // Verify category belongs to user
            Auth::user()->categories()->findOrFail($request->category_id);
            $query->where('category_id', $request->category_id);
        }

        $totalIncome = (float) (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (float) (clone $query)->where('type', 'expense')->sum('amount');

        $statistics = [
            'total_income' => number_format($totalIncome, 2, '.', ''),
            'total_expense' => number_format($totalExpense, 2, '.', ''),
            'transaction_count' => $query->count(),
            'income_count' => (clone $query)->where('type', 'income')->count(),
            'expense_count' => (clone $query)->where('type', 'expense')->count(),
        ];

        $statistics['net_amount'] = number_format($totalIncome - $totalExpense, 2, '.', '');

        return response()->json([
            'message' => 'Transaction statistics retrieved successfully',
            'data' => $statistics,
        ]);
    }
}
