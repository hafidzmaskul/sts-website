<?php

namespace App\Livewire\Admin\RmaRequests;

use App\Models\RmaRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $returnType = '';

    public function render()
    {
        $query = RmaRequest::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('product_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->returnType) {
            $query->where('return_type', $this->returnType);
        }

        return view('livewire.admin.rma-requests.index', [
            'rmaRequests' => $query->latest()->paginate(10),
        ]);
    }
}
