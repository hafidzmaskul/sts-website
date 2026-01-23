<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RmaRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RmaRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'orderNumber' => 'required|string',
            'productName' => 'required|string',
            'returnReason' => 'required|string',
            'returnType' => 'required|string',
            'proofOfPurchase' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'comments' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('proofOfPurchase')) {
            $path = $request->file('proofOfPurchase')->store('rma-proofs', 'public');
        }

        $rmaRequest = RmaRequest::create([
            'user_id' => $request->user()?->id,
            'order_number' => $request->orderNumber,
            'product_name' => $request->productName,
            'return_reason' => $request->returnReason,
            'return_type' => $request->returnType,
            'comments' => $request->comments,
            'proof_of_purchase_path' => $path,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'RMA request submitted successfully.',
            'data' => $rmaRequest,
        ], 201);
    }
}
