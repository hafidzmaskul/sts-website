<?php

namespace App\Livewire\Admin\Coupons;

use Livewire\Component;
use App\Models\Coupon;

class Show extends Component
{
    public Coupon $coupon;

    public function mount(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function render()
    {
        return view('livewire.admin.coupons.show');
    }
}
