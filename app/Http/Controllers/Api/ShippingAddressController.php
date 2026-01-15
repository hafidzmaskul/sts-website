<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customer = $request->user()->customer;

        if (!$customer) {
            return response()->json(['message' => 'Customer profile not found.'], 404);
        }

        return response()->json($customer->shippingAddresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customer = $request->user()->customer;

        if (!$customer) {
            return response()->json(['message' => 'Customer profile not found.'], 404);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'postal_code' => 'required|string|max:20',
        ]);

        $shippingAddress = $customer->shippingAddresses()->create($validated);

        return response()->json($shippingAddress, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $customer = $request->user()->customer;

        if (!$customer) {
            return response()->json(['message' => 'Customer profile not found.'], 404);
        }

        $shippingAddress = $customer->shippingAddresses()->find($id);

        if (!$shippingAddress) {
            return response()->json(['message' => 'Shipping address not found.'], 404);
        }

        return response()->json($shippingAddress);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = $request->user()->customer;

        if (!$customer) {
            return response()->json(['message' => 'Customer profile not found.'], 404);
        }

        $shippingAddress = $customer->shippingAddresses()->find($id);

        if (!$shippingAddress) {
            return response()->json(['message' => 'Shipping address not found.'], 404);
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'postal_code' => 'sometimes|required|string|max:20',
        ]);

        $shippingAddress->update($validated);

        return response()->json($shippingAddress);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $customer = $request->user()->customer;

        if (!$customer) {
            return response()->json(['message' => 'Customer profile not found.'], 404);
        }

        $shippingAddress = $customer->shippingAddresses()->find($id);

        if (!$shippingAddress) {
            return response()->json(['message' => 'Shipping address not found.'], 404);
        }

        $shippingAddress->delete();

        return response()->json(['message' => 'Shipping address deleted.']);
    }
}
