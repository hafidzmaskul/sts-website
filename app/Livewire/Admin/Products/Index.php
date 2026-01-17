<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        $this->dispatch('notify', type: 'success', message: 'Product deleted successfully.');
    }

    public function render()
    {
        $products = Product::with('categories')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('brand', function ($subQ) {
                            $subQ->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.products.index', [
            'products' => $products,
        ])->title('Products');
    }
}
