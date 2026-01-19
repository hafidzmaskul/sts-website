<?php

namespace App\Livewire\FixedRole\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $status = '';

    #[Url]
    public $dateStart = '';

    #[Url]
    public $dateEnd = '';

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedDateStart()
    {
        $this->resetPage();
    }

    public function updatedDateEnd()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        \Illuminate\Support\Facades\Log::info('DEBUG RENDER: status=' . $this->status . ', dateStart=' . $this->dateStart);

        if (!$user->customer) {
            return view('livewire.fixed-role.transactions.index', [
                'transactions' => collect([]),
                'totalTransactions' => 0,
                'totalAmount' => 0,
                'openInvoicesCount' => 0,
                'openInvoicesAmount' => 0,
            ])
                ->layout('components.layouts.app')
                ->title('My Transactions');
        }

        $query = $user->customer->transactions()->with('customer.user')->latest();

        // Apply filters
        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->dateStart) {
            $query->where('created_at', '>=', \Illuminate\Support\Carbon::parse($this->dateStart)->startOfDay());
        }

        if ($this->dateEnd) {
            $query->where('created_at', '<=', \Illuminate\Support\Carbon::parse($this->dateEnd)->endOfDay());
        }

        // Calculate stats based on current filters
        $statsQuery = clone $query;
        $totalTransactions = $statsQuery->count();
        $totalAmount = $statsQuery->sum('total_amount');

        // Global stats for open invoices (pending)
        $openInvoicesQuery = $user->customer->transactions()->whereIn('status', ['pending', 'payment_pending']);
        $openInvoicesCount = $openInvoicesQuery->count();
        $openInvoicesAmount = $openInvoicesQuery->sum('total_amount');

        $transactions = $query->paginate(10);

        return view('livewire.fixed-role.transactions.index', [
            'transactions' => $transactions,
            'totalTransactions' => $totalTransactions,
            'totalAmount' => $totalAmount,
            'openInvoicesCount' => $openInvoicesCount,
            'openInvoicesAmount' => $openInvoicesAmount,
        ])
            ->layout('components.layouts.app')
            ->title('My Transactions');
    }
}
