<?php

namespace App\Livewire\Admin\ProductRequests;

use App\Models\ProductRequest;
use Livewire\Component;

class Show extends Component
{
    public ProductRequest $productRequest;

    public function mount(ProductRequest $productRequest)
    {
        $this->productRequest = $productRequest;
    }

    public function render()
    {
        return view('livewire.admin.product-requests.show');
    }
}
