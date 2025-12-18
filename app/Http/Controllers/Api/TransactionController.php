<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the authenticated customer's transactions.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer.',
            ], 403);
        }

        $transactions = Transaction::where('customer_id', $user->customer->id)
            ->with(['items'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Display the specified transaction.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer.',
            ], 403);
        }

        $transaction = Transaction::where('customer_id', $user->customer->id)
            ->with(['items.product'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }
}