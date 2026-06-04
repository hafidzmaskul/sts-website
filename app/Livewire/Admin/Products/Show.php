<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;

class Show extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product->load([
            'categories',
            'images' => function ($query) {
                $query->orderBy('sequence');
            },
            'attachments',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.products.show')->title('Product Details');
    }
}
