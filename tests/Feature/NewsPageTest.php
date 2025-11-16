<?php

declare(strict_types=1);

it('renders the news page', function () {
    $response = $this->get(route('news'));

    $response->assertSuccessful();
});
