<?php

declare(strict_types=1);

it('renders the products page', function () {
    $response = $this->get(route('products'));

    $response->assertSuccessful();
});
