<?php

declare(strict_types=1);

use App\Models\News;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the news page', function () {
    $news = News::factory()->count(2)->create();

    $sortedNews = $news->sortByDesc('created_at')->values();

    $response = $this->get(route('news'));

    $response->assertSuccessful()->assertInertia(
        fn (Assert $page) => $page
            ->component('News')
            ->has('news', $news->count())
            ->where('news.0.slug', $sortedNews->first()->slug)
    );
});
