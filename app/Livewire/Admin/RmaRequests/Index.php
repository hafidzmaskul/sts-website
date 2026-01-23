<?php

namespace App\Livewire\Admin\RmaRequests;

use App\Models\RmaRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.rma-requests.index', [
            'rmaRequests' => RmaRequest::with('user')->latest()->paginate(10),
        ]);
    }
}
