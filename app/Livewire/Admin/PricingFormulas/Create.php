<?php

namespace App\Livewire\Admin\PricingFormulas;

use App\Models\Brand;
use App\Models\PricingFormula;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public string $label = '';
    public ?float $margin = null;
    public ?float $markup = null;
    public ?float $discount = null;
    public array $brand_ids = [];

    public function save()
    {
        $this->authorize('pricing-formulas.create');

        $validated = $this->validate([
            'label' => ['required', 'string', 'max:255'],
            'margin' => ['nullable', 'numeric', 'min:0', 'max:99.99'],
            'markup' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'brand_ids' => ['array'],
            'brand_ids.*' => ['exists:brands,id'],
        ]);

        $formula = PricingFormula::create([
            'user_id' => Auth::id(),
            'label' => $this->label,
            'margin' => $this->margin === '' ? null : $this->margin,
            'markup' => $this->markup === '' ? null : $this->markup,
            'discount' => $this->discount === '' ? null : $this->discount,
        ]);

        if (!empty($this->brand_ids)) {
            Brand::whereIn('id', $this->brand_ids)->update(['pricing_formula_id' => $formula->id]);
        }

        $this->dispatch('notify', variant: 'success', message: 'Pricing Formula created successfully.');

        return $this->redirect(route('admin.pricing-formulas.index'), navigate: true);
    }

    public function render()
    {
        $this->authorize('pricing-formulas.create');

        return view('livewire.admin.pricing-formulas.create', [
            'brands' => Brand::orderBy('name')->get(),
        ])->title('Create Pricing Formula');
    }
}
