<?php

namespace App\Livewire\Admin\AbandonedCarts;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user->load(['cartItems.product', 'customer', 'customer.company']);

        // If user has no cart, maybe redirect? user might have cleared cart since listing.
        // But for now just show empty list.
    }

    public function render()
    {
        return view('livewire.admin.abandoned-carts.show', [
            'cartItems' => $this->user->cartItems,
        ])->title('Abandoned Cart Details');
    }

    public function remindCustomer()
    {
        \Illuminate\Support\Facades\Mail::to($this->user->email)->send(new \App\Mail\AbandonedCartReminder($this->user));

        $this->dispatch('notify', type: 'success', message: 'Reminder email sent successfully.');
    }
}
