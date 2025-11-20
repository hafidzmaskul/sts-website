<?php

declare(strict_types=1);

use App\Models\News;

it('renders the news detail page', function () {
    $news = News::factory()->create();

    $response = $this->get(route('newsDetail', ['slug' => $news->slug]));

    $response->assertSuccessful();
});
