<?php

namespace App\Livewire\FixedRole\Transactions;

use App\Models\Transaction;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public Transaction $transaction;

    public function mount(Transaction $transaction)
    {
        $user = Auth::user();

        // Ensure the transaction belongs to the user's customer
        if ($transaction->customer_id !== $user->customer->id) {
            abort(403);
        }

        $this->transaction = $transaction->load(['items.product', 'statusHistory']);
    }

    public function render()
    {
        return view('livewire.fixed-role.transactions.show')
            ->layout('components.layouts.app')
            ->title('Transaction Details');
    }
}
