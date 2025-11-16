<?php

declare(strict_types=1);

it('renders the services page', function () {
    $response = $this->get(route('services'));

    $response->assertSuccessful();
});
