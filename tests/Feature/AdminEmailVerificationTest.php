<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use App\Models\Product;
use App\Models\QuoteBuilder;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminEmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Admin Email Setting
        Setting::create(['key' => 'email_notification_admin', 'value' => 'admin@globalfire.co.uk']);

        // Setup User
        $this->user = User::factory()->create();
    }

    public function test_rma_request_sends_email()
    {
        Mail::shouldReceive('raw')
            ->once() // Expect 1 call
            ->andReturn(true); // Mock return

        $response = $this->actingAs($this->user)->postJson('/api/rma-requests', [
            'orderNumber' => 'ORD-123',
            'productName' => 'Test Product',
            'returnReason' => 'Defective',
            'returnType' => 'Refund',
            'comments' => 'It is broken.',
            'proofOfPurchase' => \Illuminate\Http\UploadedFile::fake()->create('invoice.pdf', 100)
        ]);

        // If 401, debug why actingAs isn't working or middleware is strict
        if ($response->status() === 401) {
            // For debugging purposes only
            fwrite(STDERR, "RMA 401 Response: " . $response->getContent() . "\n");
        }

        $response->assertStatus(201);

        // For Mail::raw, asserting via closure can be tricky with type hints.
        // We will assert that 1 email was sent in total.
    }

    public function test_feedback_sends_email()
    {
        Mail::shouldReceive('raw')
            ->once()
            ->andReturn(true);

        $response = $this->actingAs($this->user)->postJson('/api/feedback', [
            'message' => 'Great website!'
        ]);

        $response->assertStatus(201);
    }

    public function test_newsletter_subscription_sends_email()
    {
        Mail::shouldReceive('raw')
            ->once()
            ->andReturn(true);

        $response = $this->postJson('/api/newsletter-subscription', [
            'email' => 'newsubscriber@example.com'
        ]);

        $response->assertStatus(201);
    }

    public function test_quote_builder_sends_email()
    {
        Mail::shouldReceive('raw')
            ->once()
            ->andReturn(true);

        // Manual product creation if factory doesn't exist
        $product = new Product();
        $product->title = 'Test Product';
        $product->slug = 'test-product';
        $product->base_price = 100;
        $product->created_by = $this->user->id; // Fix integrity constraint
        $product->status = 'active'; // Ensure status validation if any
        $product->save();

        $response = $this->actingAs($this->user)->postJson('/api/quote-builders', [
            'name' => 'My Quote',
            'product_id' => [$product->id],
            // QuoteBuilder logic: 'product_id' => array of IDs
        ]);

        $response->assertStatus(201);
    }



    public function test_contact_submission_sends_email()
    {
        Mail::shouldReceive('raw')
            ->once()
            ->andReturn(true);

        $response = $this->postJson('/api/contact', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'subject' => 'Inquiry',
            'message' => 'Hello there.'
        ]);

        $response->assertStatus(201);
    }

    public function test_career_submission_sends_email()
    {
        Mail::shouldReceive('raw')
            ->once()
            ->andReturn(true);

        // Career requires a valid career_id
        $career = \App\Models\Career::factory()->create();

        $response = $this->postJson('/api/careers', [
            'career_id' => $career->id,
            'full_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'mobile_phone' => '555-123-4567',
            'resume' => \Illuminate\Http\UploadedFile::fake()->create('resume.pdf', 100),
            'message' => 'I am interested in this position.',
        ]);

        $response->assertStatus(201);
    }

    public function test_transaction_sends_email()
    {
        Mail::shouldReceive('raw')
            ->once()
            ->andReturn(true);

        // Transaction requires settings to calculate correctly
        Setting::create(['key' => 'transaction_tax', 'value' => '10']);
        Setting::create(['key' => 'shipping_method_1_name', 'value' => 'Standard']);
        Setting::create(['key' => 'shipping_method_1_price', 'value' => '5.00']);
        Setting::create(['key' => 'payment_method_1_name', 'value' => 'Credit Card']); // Added setting

        // Create a customer profile for the user
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);

        $product = new Product();
        $product->title = 'Test Product';
        $product->slug = 'test-product';
        $product->base_price = 100;
        $product->created_by = $this->user->id;
        $product->status = 'active';
        $product->save();

        $response = $this->actingAs($this->user)->postJson('/api/transactions', [
            'contact_email' => 'john@example.com',
            'shipping_first_name' => 'John',
            'shipping_last_name' => 'Doe',
            'shipping_address' => '123 St',
            'shipping_city' => 'Metropolis',
            'shipping_postal_code' => '12345',
            'shipping_country' => 'USA',
            'shipping_phone_number' => '111222333',
            'shipping_method' => 'Standard',
            'shipping_payment_method' => 'Credit Card',
            'total_amount' => 115.50,
            'quantity' => 1,
            'product_id' => $product->id
        ]);

        // If status is 400, it might be total mismatch
        if ($response->status() === 400) {
            fwrite(STDERR, "Transaction 400 Response: " . $response->getContent() . "\n");
        }

        $response->assertStatus(201);
    }
}
