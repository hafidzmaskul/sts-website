<?php

namespace App\Livewire\Admin\Coupons;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Coupon;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        Coupon::findOrFail($id)->delete();
        $this->dispatch('notify', 'Coupon deleted successfully.');
    }

    public function render()
    {
        $coupons = Coupon::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.coupons.index', [
            'coupons' => $coupons
        ]);
    }
}