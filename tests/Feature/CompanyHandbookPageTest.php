<?php

declare(strict_types=1);

it('renders the company handbook page', function () {
    $response = $this->get(route('companyHandbook'));

    $response->assertSuccessful();
});
