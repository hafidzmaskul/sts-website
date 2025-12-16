<?php

namespace App\Livewire\Admin\Quotes;

use App\Models\Quote;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $dateStart = '';
    public string $dateEnd = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateStart()
    {
        $this->resetPage();
    }

    public function updatingDateEnd()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateStart = '';
        $this->dateEnd = '';
        $this->resetPage();
    }

    public function delete(int $id)
    {
        // $this->authorize('quotes.delete');
        $quote = Quote::findOrFail($id);
        $quote->delete();

        $this->dispatch('notify', type: 'success', message: 'Quote deleted successfully.');
    }

    public function render()
    {
        $query = Quote::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateStart, fn($q) => $q->whereDate('created_at', '>=', $this->dateStart))
            ->when($this->dateEnd, fn($q) => $q->whereDate('created_at', '<=', $this->dateEnd));

        $quotes = $query->clone()
            ->orderByDesc('created_at')
            ->paginate(10);

        // Summary Statistics
        $totalQuotes = Quote::count();
        $quotesToday = Quote::whereDate('created_at', now())->count();
        $quotesThisMonth = Quote::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('livewire.admin.quotes.index', [
            'quotes' => $quotes,
            'totalQuotes' => $totalQuotes,
            'quotesToday' => $quotesToday,
            'quotesThisMonth' => $quotesThisMonth,
        ])->title('Quotes');
    }
}
