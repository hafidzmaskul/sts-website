<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\Transaction;
use Livewire\Component;

class Show extends Component
{
    public Transaction $transaction;
    public $newStatus;
    public $notes;

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction->load(['customer.user', 'items.product', 'statusHistory.user', 'coupon']);
        $this->newStatus = $transaction->status;
    }

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $this->transaction->update(['status' => $this->newStatus]);

        $this->transaction->statusHistory()->create([
            'user_id' => auth()->id(),
            'status' => $this->newStatus,
            'notes' => $this->notes,
        ]);

        $this->notes = '';
        $this->dispatch('status-updated');
        session()->flash('message', 'Transaction status updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.transactions.show')->title('Transaction ' . $this->transaction->invoice_code);
    }
}
