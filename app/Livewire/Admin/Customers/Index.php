<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $isRegistered = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingIsRegistered()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('customers.view');

        $customers = Customer::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                    $q->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->isRegistered !== '', function ($query) {
                if ($this->isRegistered === 'yes') {
                    $query->whereNotNull('user_id');
                } else {
                    $query->whereNull('user_id');
                }
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Customer::count(),
            'active' => Customer::where('status', 'active')->count(),
            'suspended' => Customer::where('status', 'suspended')->count(),
        ];

        return view('livewire.admin.customers.index', [
            'customers' => $customers,
            'stats' => $stats,
        ])->title('Customers');
    }
}
