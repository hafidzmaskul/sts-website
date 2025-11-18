<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

#[Title('Products')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function mount()
    {
        if (session()->has('alert')) {
            $alert = session('alert');
            $this->dispatch('alert', type: $alert['type'], message: $alert['message']);
        }
    }

    public function delete($id)
    {
        if (Gate::denies('products.delete')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to delete products.');
            return;
        }

        $product = Product::findOrFail($id);
        
        if ($product->image) {
            if (Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        }
        if ($product->attachment) {
            if (Storage::disk('public')->exists($product->attachment)) {
                Storage::disk('public')->delete($product->attachment);
            }
        }
        
        $product->delete();
        $this->dispatch('alert', type: 'success', message: 'Product deleted successfully.');
    }

    public function render()
    {
        $products = Product::with(['user', 'services'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.products.index', [
            'products' => $products,
        ]);
    }
}