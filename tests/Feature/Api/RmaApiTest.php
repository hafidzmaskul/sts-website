<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('it can submit rma request with valid data', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('receipt.jpg');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->postJson('/api/rma-requests', [
        'orderNumber' => 'ORDER-123',
        'productName' => 'Broken Widget',
        'returnReason' => 'It arrived broken',
        'returnType' => 'refund',
        'comments' => 'Please hurry',
        'proofOfPurchase' => $file,
    ], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'RMA request submitted successfully.',
        ]);

    $this->assertDatabaseHas('rma_requests', [
        'order_number' => 'ORDER-123',
        'product_name' => 'Broken Widget',
        'return_reason' => 'It arrived broken',
        'return_type' => 'refund',
        'status' => 'pending',
    ]);
});

test('it fails validation when required fields are missing', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->postJson('/api/rma-requests', [], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['orderNumber', 'productName', 'returnReason', 'returnType', 'proofOfPurchase']);
});
