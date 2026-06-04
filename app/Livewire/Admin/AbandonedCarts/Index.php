<?php

namespace App\Livewire\Admin\AbandonedCarts;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {

        // Calculate totals manually or via query if needed, but for now simple display
        // Actually, the requirement says "Total Product in Carts".
        // simple `withCount` gives number of rows.
        // If "Total Product" means sum of quantities, we need `withSum('cart', 'quantity')`

        $users = User::whereHas('cartItems')
            ->with(['cartItems'])
            ->withSum('cartItems', 'quantity')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->paginate(10);

        return view('livewire.admin.abandoned-carts.index', [
            'users' => $users,
        ])->title('Abandoned Carts');
    }
}
