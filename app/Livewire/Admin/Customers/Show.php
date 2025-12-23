<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    public Customer $customer;

    // Decline Logic
    public $showDeclineModal = false;
    public $reviewNote = '';

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load('user');
    }

    public function approve()
    {
        $this->authorize('customers.edit');

        if ($this->customer->status_review !== 'pending') {
            return;
        }

        // 1. Create User
        $user = User::create([
            'name' => $this->customer->first_name . ' ' . $this->customer->last_name,
            'email' => $this->customer->email,
            'password' => Hash::make(Str::random(12)), // Generate random password
            'email_verified_at' => now(), // Auto verify since admin approved
        ]);

        // 2. Assign Role
        if ($this->customer->role_applied) {
            $user->assignRole($this->customer->role_applied);
        } else {
            $user->assignRole('customer'); // Default fallback
        }

        // 3. Link Customer to User
        $this->customer->user_id = $user->id;
        $this->customer->status_review = 'approved';
        $this->customer->save();

        // Optional: Send "Account Approved" email to user with password reset link or similar
        // For now, we just approve.

        $this->dispatch('notify', type: 'success', message: 'Customer approved and user account created.');
        $this->customer->refresh();
    }

    public function confirmDecline()
    {
        $this->showDeclineModal = true;
    }

    public function decline()
    {
        $this->authorize('customers.edit');

        $this->validate([
            'reviewNote' => 'required|string|min:5',
        ]);

        $this->customer->status_review = 'declined';
        $this->customer->review_note = $this->reviewNote;
        $this->customer->save();

        // Optional: Send "Application Declined" email

        $this->showDeclineModal = false;
        $this->dispatch('notify', type: 'success', message: 'Customer application declined.');
    }

    public function render()
    {
        $this->authorize('customers.view');

        return view('livewire.admin.customers.show')->title('Customer Details');
    }
}
