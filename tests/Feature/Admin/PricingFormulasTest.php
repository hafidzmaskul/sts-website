<?php

use App\Models\PricingFormula;
use App\Models\User;

test('pricing formula records history on creation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $formula = PricingFormula::create([
        'user_id' => $user->id,
        'label' => 'Test Margin',
        'margin' => 10,
    ]);

    $this->assertDatabaseHas('pricing_formula_histories', [
        'pricing_formula_id' => $formula->id,
        'user_id' => $user->id,
        'label' => 'Test Margin',
        'margin' => 10,
    ]);
});

test('pricing formula records history on update', function () {
    $user = User::factory()->create();
    $updater = User::factory()->create();
    $this->actingAs($updater);

    $formula = PricingFormula::create([
        'user_id' => $user->id,
        'label' => 'Original',
        'margin' => 10,
    ]);

    // Update
    $formula->update([
        'label' => 'Updated',
        'margin' => 20,
    ]);

    $this->assertDatabaseHas('pricing_formula_histories', [
        'pricing_formula_id' => $formula->id,
        'user_id' => $updater->id, // Should be the updater
        'label' => 'Updated',
        'margin' => 20,
    ]);

    // Check old history still exists
    $this->assertDatabaseHas('pricing_formula_histories', [
        'pricing_formula_id' => $formula->id,
        'label' => 'Original',
        'margin' => 10,
    ]);
});
