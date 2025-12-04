<?php

declare(strict_types=1);

it('renders the product detail page', function () {
    $response = $this->get(route('products.detail', 'smart-cctv-camera-pro'));

    $response->assertSuccessful();
});
