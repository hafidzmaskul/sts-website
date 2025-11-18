<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Product Details')]
class Show extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product->load(['user', 'services']);
    }
    
    public function render()
    {
        return view('livewire.admin.products.show');
    }
}