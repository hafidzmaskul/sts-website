<?php

declare(strict_types=1);

it('renders the landing page', function () {
    $response = $this->get(route('home'));

    $response->assertSuccessful();
});
