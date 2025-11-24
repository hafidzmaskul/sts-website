<?php

declare(strict_types=1);

it('renders the career page', function () {
    $response = $this->get(route('career'));

    $response->assertSuccessful();
});

it('renders a career detail page', function () {
    $response = $this->get(route('career.detail', 1));

    $response->assertSuccessful();
});
