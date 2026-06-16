<?php

namespace App\Livewire\Admin\QuoteBuilders;

use App\Models\QuoteBuilder;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * Search query for filtering quote builders.
     */
    public string $search = '';

    /**
     * Start date for filtering by creation date.
     */
    public string $dateStart = '';

    /**
     * End date for filtering by creation date.
     */
    public string $dateEnd = '';

    /**
     * Reset pagination when search query changes.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when start date filter changes.
     */
    public function updatingDateStart(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when end date filter changes.
     */
    public function updatingDateEnd(): void
    {
        $this->resetPage();
    }

    /**
     * Reset all filters.
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->dateStart = '';
        $this->dateEnd = '';
        $this->resetPage();
    }

    /**
     * Delete a quote builder draft by its ID.
     */
    public function delete(int $id): void
    {
        $quoteBuilder = QuoteBuilder::findOrFail($id);
        $quoteBuilder->delete();

        $this->dispatch('notify', type: 'success', message: 'Quote Builder deleted successfully.');
    }

    /**
     * Render the Livewire component view.
     */
    public function render(): View
    {
        $query = QuoteBuilder::query()
            ->with(['user.customer.company', 'products'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($userQ) {
                            $userQ->where('name', 'like', '%'.$this->search.'%')
                                ->orWhere('email', 'like', '%'.$this->search.'%')
                                ->orWhereHas('customer', function ($custQ) {
                                    $custQ->whereHas('company', function ($compQ) {
                                        $compQ->where('name', 'like', '%'.$this->search.'%');
                                    });
                                });
                        });
                });
            })
            ->when($this->dateStart, fn ($q) => $q->whereDate('created_at', '>=', $this->dateStart))
            ->when($this->dateEnd, fn ($q) => $q->whereDate('created_at', '<=', $this->dateEnd));

        $quoteBuilders = $query->clone()
            ->orderByDesc('created_at')
            ->paginate(10);

        $totalBuilders = QuoteBuilder::count();
        $buildersToday = QuoteBuilder::whereDate('created_at', now())->count();
        $totalItems = \Illuminate\Support\Facades\DB::table('product_quote_builder')->count();

        return view('livewire.admin.quote-builders.index', [
            'quoteBuilders' => $quoteBuilders,
            'totalBuilders' => $totalBuilders,
            'buildersToday' => $buildersToday,
            'totalItems' => $totalItems,
        ])->title('Quote Builders');
    }
}
