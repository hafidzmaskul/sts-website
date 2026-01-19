<?php

namespace App\Livewire\Admin\ProductRequests;

use App\Models\ProductRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $dateStart = '';
    public $dateEnd = '';
    public $productId = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDateStart()
    {
        $this->resetPage();
    }

    public function updatedDateEnd()
    {
        $this->resetPage();
    }

    public function updatedProductId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ProductRequest::with('product')->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateStart) {
            $query->whereDate('created_at', '>=', $this->dateStart);
        }

        if ($this->dateEnd) {
            $query->whereDate('created_at', '<=', $this->dateEnd);
        }

        if ($this->productId) {
            $query->where('product_id', $this->productId);
        }

        return view('livewire.admin.product-requests.index', [
            'productRequests' => $query->paginate(10),
            'products' => \App\Models\Product::orderBy('title')->get(),
        ]);
    }
}
