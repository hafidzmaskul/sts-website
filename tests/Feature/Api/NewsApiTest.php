<?php

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can list published news', function () {
    $publishedNews = News::factory()->create([
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $draftNews = News::factory()->create([
        'status' => 'draft',
        'published_at' => now()->subDay(),
    ]);

    $futureNews = News::factory()->create([
        'status' => 'published',
        'published_at' => now()->addDay(),
    ]);

    $response = $this->getJson('/api/news');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'content',
                        'image_path',
                        'status',
                        'published_at',
                        'created_at',
                    ]
                ]
            ]
        ]);

    $this->assertCount(1, $response->json('data.data'));
    $this->assertEquals($publishedNews->id, $response->json('data.data.0.id'));
});

test('it can show single published news', function () {
    $news = News::factory()->create([
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson("/api/news/{$news->slug}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $news->id,
                'title' => $news->title,
            ]
        ]);
});

test('it returns 404 for unpublished news', function () {
    $draftNews = News::factory()->create([
        'status' => 'draft',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson("/api/news/{$draftNews->slug}");

    $response->assertStatus(404);
});

test('it can search news by title', function () {
    $news1 = News::factory()->create([
        'title' => 'First News',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $news2 = News::factory()->create([
        'title' => 'Second News',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson('/api/news?search=First');

    $response->assertStatus(200);
    $this->assertCount(1, $response->json('data.data'));
    $this->assertEquals($news1->id, $response->json('data.data.0.id'));
});

test('it can filter news by category', function () {
    $category1 = \App\Models\NewsCategory::factory()->create(['slug' => 'tech']);
    $category2 = \App\Models\NewsCategory::factory()->create(['slug' => 'lifestyle']);

    $news1 = News::factory()->create([
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);
    $news1->categories()->attach($category1);

    $news2 = News::factory()->create([
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);
    $news2->categories()->attach($category2);

    $response = $this->getJson('/api/news?category=tech');

    $response->assertStatus(200);
    $this->assertCount(1, $response->json('data.data'));
    $this->assertEquals($news1->id, $response->json('data.data.0.id'));
});

test('it includes full image url', function () {
    $news = News::factory()->create([
        'image_path' => 'news/image.jpg',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson("/api/news/{$news->slug}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'image_url' => url('storage/news/image.jpg'),
            ]
        ]);
});
