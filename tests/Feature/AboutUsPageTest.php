<?php

declare(strict_types=1);

it('renders the about us page', function () {
    $response = $this->get(route('about'));

    $response->assertSuccessful();
});
