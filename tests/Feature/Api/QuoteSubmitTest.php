<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteSubmitTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_quote_with_source_page(): void
    {
        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'company_name' => 'Acme Corp',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'country' => 'United Kingdom',
            'postal_code' => 'SW1A 1AA',
            'project_details' => 'Need help with system design.',
            'marketing_opt_in' => true,
            'source_page' => 'System Design',
        ];

        $response = $this->postJson('/api/quotes', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Quote submitted successfully',
            ]);

        $this->assertDatabaseHas('quotes', [
            'email' => 'john@example.com',
            'source_page' => 'System Design',
        ]);
    }

    public function test_can_submit_quote_without_source_page(): void
    {
        $payload = [
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'company_name' => 'Smith Co',
            'email' => 'alice@example.com',
            'phone' => '0987654321',
            'country' => 'United Kingdom',
            'postal_code' => 'EC1A 1BB',
            'project_details' => 'Requesting commissioning services.',
            'marketing_opt_in' => false,
        ];

        $response = $this->postJson('/api/quotes', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('quotes', [
            'email' => 'alice@example.com',
            'source_page' => null,
        ]);
    }

    public function test_quote_submission_validation_fails_with_invalid_source_page(): void
    {
        $payload = [
            'first_name' => 'Invalid',
            'last_name' => 'User',
            'company_name' => 'Invalid Inc',
            'email' => 'invalid@example.com',
            'phone' => '12345',
            'country' => 'United Kingdom',
            'postal_code' => 'EC1A',
            'project_details' => 'Short desc.',
            'source_page' => str_repeat('A', 256), // Exceeds max:255
        ];

        $response = $this->postJson('/api/quotes', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['source_page']);
    }
}
