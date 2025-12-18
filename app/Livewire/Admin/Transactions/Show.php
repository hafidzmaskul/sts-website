<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\Transaction;
use Livewire\Component;

class Show extends Component
{
    public Transaction $transaction;

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction->load(['customer.user', 'items.product']);
    }

    public function render()
    {
        return view('livewire.admin.transactions.show')->title('Transaction ' . $this->transaction->invoice_code);
    }
}
