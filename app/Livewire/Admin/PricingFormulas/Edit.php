<?php

namespace App\Livewire\Admin\PricingFormulas;

use App\Enums\PricingFormulaType;
use App\Models\PricingFormula;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public PricingFormula $pricingFormula;
    public string $label = '';
    public string $type = '';
    public string $value = '';

    public function mount(PricingFormula $pricingFormula)
    {
        $this->authorize('pricing-formulas.view');

        $this->pricingFormula = $pricingFormula;
        $this->label = $pricingFormula->label;
        $this->type = $pricingFormula->type->value;
        $this->value = (string) $pricingFormula->value;
    }

    public function save()
    {
        $this->authorize('pricing-formulas.edit');

        $validated = $this->validate([
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(PricingFormulaType::class)],
            'value' => ['required', 'numeric', 'min:0'],
        ]);

        $this->pricingFormula->update([
            'label' => $this->label,
            'type' => $this->type,
            'value' => $this->value,
            // user_id is NOT updated on edit, but history tracks the updater.
            // But wait, the model's history tracking logic uses Auth::id() so it's fine.
        ]);

        $this->dispatch('notify', variant: 'success', message: 'Pricing Formula updated successfully.');

        return $this->redirect(route('admin.pricing-formulas.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.pricing-formulas.edit', [
            'types' => PricingFormulaType::cases(),
        ])->title('Edit Pricing Formula');
    }
}
