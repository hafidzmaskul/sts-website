<?php

namespace App\Livewire\Admin\PricingFormulas;

use App\Models\PricingFormula;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(PricingFormula $pricingFormula)
    {
        $this->authorize('pricing-formulas.delete');

        $pricingFormula->delete();

        $this->dispatch('notify', variant: 'success', message: 'Pricing Formula deleted successfully.');
    }

    public function render()
    {
        $this->authorize('pricing-formulas.view');

        $formulas = PricingFormula::query()
            ->with(['user'])
            ->when($this->search, function ($query) {
                $query->where('label', 'like', '%' . $this->search . '%')
                    ->orWhere('value', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.pricing-formulas.index', [
            'formulas' => $formulas,
        ])->title('Pricing Formulas');
    }
}
