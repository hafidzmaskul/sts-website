<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
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
        $this->authorize('customers.view');

        $customers = User::role('customer')
            ->with('customer')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('status', $this->status);
                });
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => User::role('customer')->count(),
            'active' => \App\Models\Customer::where('status', 'active')->count(),
            'suspended' => \App\Models\Customer::where('status', 'suspended')->count(),
        ];

        return view('livewire.admin.customers.index', [
            'customers' => $customers,
            'stats' => $stats,
        ])->title('Customers');
    }
}
