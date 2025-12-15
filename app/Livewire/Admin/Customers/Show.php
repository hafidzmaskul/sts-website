<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user->load('customer');
    }

    public function render()
    {
        $this->authorize('customers.view');

        return view('livewire.admin.customers.show')->title('Customer Details');
    }
}
