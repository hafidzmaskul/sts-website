<?php

use App\Enums\PricingFormulaType;
use App\Models\PricingFormula;
use App\Models\PricingFormulaHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('pricing formula records history on creation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $formula = PricingFormula::create([
        'user_id' => $user->id,
        'label' => 'Test Margin',
        'type' => PricingFormulaType::MarginPercent,
        'value' => 10,
    ]);

    $this->assertDatabaseHas('pricing_formula_histories', [
        'pricing_formula_id' => $formula->id,
        'user_id' => $user->id,
        'label' => 'Test Margin',
        'type' => PricingFormulaType::MarginPercent,
        'value' => 10,
    ]);
});

test('pricing formula records history on update', function () {
    $user = User::factory()->create();
    $updater = User::factory()->create();
    $this->actingAs($updater);

    $formula = PricingFormula::create([
        'user_id' => $user->id,
        'label' => 'Original',
        'type' => PricingFormulaType::MarginPercent,
        'value' => 10,
    ]);

    // Update
    $formula->update([
        'label' => 'Updated',
        'value' => 20,
    ]);

    $this->assertDatabaseHas('pricing_formula_histories', [
        'pricing_formula_id' => $formula->id,
        'user_id' => $updater->id, // Should be the updater
        'label' => 'Updated',
        'value' => 20,
    ]);

    // Check old history still exists
    $this->assertDatabaseHas('pricing_formula_histories', [
        'pricing_formula_id' => $formula->id,
        'label' => 'Original',
        'value' => 10,
    ]);
});

test('pricing formula type casting works', function () {
    $user = User::factory()->create();
    $formula = PricingFormula::create([
        'user_id' => $user->id,
        'label' => 'Test',
        'type' => 'Margin in percent', // Raw string
        'value' => 10,
    ]);

    expect($formula->type)->toBe(PricingFormulaType::MarginPercent);
    expect($formula->type->label())->toBe('Margin (%)');
});
