<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test to verify settings API.
     */
    public function test_settings_api_returns_transaction_statuses(): void
    {
        $response = $this->getJson('/api/settings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'shipping_methods',
                'payment_methods',
                'tax' => [
                    'percentage',
                ],
                'transaction_statuses',
            ])
            ->assertJsonFragment([
                'transaction_statuses' => [
                    'pending',
                    'processing',
                    'left the storage',
                    'in transit',
                    'delivered',
                    'cancelled',
                    'paid',
                    'failed',
                ],
            ]);
    }
}
