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

    public string $statusReview = '';

    public string $role = '';

    public string $isRegistered = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingStatusReview()
    {
        $this->resetPage();
    }

    public function updatingRole()
    {
        $this->resetPage();
    }

    public function updatingIsRegistered()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'role', 'statusReview', 'isRegistered', 'status']);
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('customers.view');

        $customers = Customer::with(['user', 'company'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($u) {
                        $u->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('email', 'like', '%'.$this->search.'%');
                    });
                    $q->orWhere('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%')
                        ->orWhere('city', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->role, function ($query) {
                $query->where('role_applied', $this->role);
            })
            ->when($this->statusReview, function ($query) {
                $query->where('status_review', $this->statusReview);
            })
            ->when($this->isRegistered !== '', function ($query) {
                if ($this->isRegistered === 'yes') {
                    $query->whereNotNull('user_id');
                } else {
                    $query->whereNull('user_id');
                }
            })
            ->where('role_applied', '!=', 'guest') // Exclude guest customers
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Customer::count(),
            'active' => Customer::where('status', 'active')->count(),
            'pending' => Customer::where('status_review', 'pending')->count(),
        ];

        return view('livewire.admin.customers.index', [
            'customers' => $customers,
            'stats' => $stats,
        ])->title('Customers');
    }
}
