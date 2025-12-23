<?php

namespace App\Livewire\Admin\PricingFormulas;

use App\Enums\PricingFormulaType;
use App\Models\PricingFormula;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public string $label = '';
    public string $type = '';
    public string $value = '';

    public function save()
    {
        $this->authorize('pricing-formulas.create');

        $validated = $this->validate([
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(PricingFormulaType::class)],
            'value' => ['required', 'numeric', 'min:0'],
        ]);

        PricingFormula::create([
            'user_id' => Auth::id(),
            'label' => $this->label,
            'type' => $this->type,
            'value' => $this->value,
        ]);

        $this->dispatch('notify', variant: 'success', message: 'Pricing Formula created successfully.');

        return $this->redirect(route('admin.pricing-formulas.index'), navigate: true);
    }

    public function render()
    {
        $this->authorize('pricing-formulas.create');

        return view('livewire.admin.pricing-formulas.create', [
            'types' => PricingFormulaType::cases(),
        ])->title('Create Pricing Formula');
    }
}
