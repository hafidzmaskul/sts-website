<?php

namespace App\Livewire\Admin\PricingFormulas;

use App\Models\Brand;
use App\Models\PricingFormula;
use Livewire\Component;

class Edit extends Component
{
    public PricingFormula $pricingFormula;

    public string $label = '';

    public ?float $margin = null;

    public ?float $markup = null;

    public ?float $discount = null;

    public array $brand_ids = [];

    public function mount(PricingFormula $pricingFormula)
    {
        $this->authorize('pricing-formulas.view');

        $this->pricingFormula = $pricingFormula;
        $this->label = $pricingFormula->label;
        $this->margin = $pricingFormula->margin;
        $this->markup = $pricingFormula->markup;
        $this->discount = $pricingFormula->discount;
        $this->brand_ids = $pricingFormula->brands()->pluck('id')->toArray();
    }

    public function save()
    {
        $this->authorize('pricing-formulas.edit');

        $validated = $this->validate([
            'label' => ['required', 'string', 'max:255'],
            'margin' => ['nullable', 'numeric', 'min:0', 'max:99.99'],
            'markup' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'brand_ids' => ['array'],
            'brand_ids.*' => ['exists:brands,id'],
        ]);

        $this->pricingFormula->update([
            'label' => $this->label,
            'margin' => $this->margin === '' ? null : $this->margin,
            'markup' => $this->markup === '' ? null : $this->markup,
            'discount' => $this->discount === '' ? null : $this->discount,
        ]);

        // Unassign brands that were unchecked
        Brand::where('pricing_formula_id', $this->pricingFormula->id)
            ->whereNotIn('id', $this->brand_ids)
            ->update(['pricing_formula_id' => null]);

        // Assign checked brands
        if (! empty($this->brand_ids)) {
            Brand::whereIn('id', $this->brand_ids)
                ->update(['pricing_formula_id' => $this->pricingFormula->id]);
        }

        $this->dispatch('notify', variant: 'success', message: 'Pricing Formula updated successfully.');

        return $this->redirect(route('admin.pricing-formulas.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.pricing-formulas.edit', [
            'brands' => Brand::orderBy('name')->get(),
        ])->title('Edit Pricing Formula');
    }
}
