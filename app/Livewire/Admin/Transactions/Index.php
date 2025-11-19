<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Transactions')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $transactions = Transaction::query()
            ->where('invoice_code', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('first_name', 'like', '%' . $this->search . '%')
            ->orWhere('last_name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.transactions.index', [
            'transactions' => $transactions,
        ]);
    }
}