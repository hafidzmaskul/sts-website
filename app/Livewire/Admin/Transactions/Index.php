<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        // $this->authorize('transactions.view'); // Assuming this permission exists or will be added

        $transactions = Transaction::with(['customer.user'])
            ->when($this->search, function ($query) {
                $query->where('invoice_code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer.user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Transaction::count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'completed' => Transaction::where('status', 'completed')->count(),
        ];

        return view('livewire.admin.transactions.index', [
            'transactions' => $transactions,
            'stats' => $stats,
        ])->title('Transactions');
    }
}
