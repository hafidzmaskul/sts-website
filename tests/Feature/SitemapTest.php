<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\News;
use App\Models\Career;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_is_accessible()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function test_sitemap_contains_urls()
    {
        // Create dummy content
        $user = \App\Models\User::factory()->create();

        $product = new Product();
        $product->title = 'Test Product';
        $product->slug = 'test-product';
        $product->base_price = 100;
        $product->created_by = $user->id;
        $product->status = 'active';
        $product->save();
        $news = News::factory()->create(['slug' => 'test-news', 'status' => 'published']);
        $career = Career::factory()->create(['title' => 'Test Job', 'slug' => 'test-job', 'is_active' => true]);

        $response = $this->get('/sitemap.xml');

        $response->assertSee('test-product');
        $response->assertSee('test-news');
        $response->assertSee('test-job');
    }
}
