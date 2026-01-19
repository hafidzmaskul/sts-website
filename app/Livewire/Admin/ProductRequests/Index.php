<?php

namespace App\Livewire\Admin\ProductRequests;

use App\Models\ProductRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.product-requests.index', [
            'productRequests' => ProductRequest::with('product')->latest()->paginate(10),
        ]);
    }
}
