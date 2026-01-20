<?php

namespace App\Livewire\Admin\Coupons;

use Livewire\Component;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name;
    public $code;
    public $type = 'redeem'; // 'redeem', 'claim'
    public $status = 'published'; // 'published', 'unpublished'
    public $discount_type = 'fixed';
    public $discount_value;
    public $quota;
    public $start_date;
    public $end_date;
    public $restriction_type = 'none'; // 'role', 'specific_user', 'none'
    public $role_level;
    public $specific_users = [];
    public $userSearch = '';

    public function generateCode()
    {
        $this->code = strtoupper(Str::random(8));
    }

    public function save()
    {
        // If type is claim and no code provided, generate one silently Or if it's hidden in UI but required in DB
        if ($this->type === 'claim' && empty($this->code)) {
            $this->generateCode();
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'code' => ['nullable', 'string', 'max:255', Rule::unique('coupons', 'code')],
            'type' => 'required|in:redeem,claim',
            'status' => 'required|in:published,unpublished',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'restriction_type' => 'required|in:none,role,specific_user',
            'role_level' => [
                'nullable',
                Rule::requiredIf($this->restriction_type === 'role'),
                'in:trade account,credit facilities account,guest'
            ],
            'specific_users' => [
                'nullable',
                Rule::requiredIf($this->restriction_type === 'specific_user'),
                'array'
            ],
            'specific_users.*' => 'exists:users,id',
        ]);

        if ($this->type === 'redeem' && empty($this->code)) {
            $this->addError('code', 'The coupon code field is required when type is redeem.');
            return;
        }

        $coupon = Coupon::create([
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'status' => $this->status,
            'quota' => $this->quota,
            'used_count' => 0,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'restriction_type' => $this->restriction_type === 'none' ? null : $this->restriction_type,
            'role_level' => $this->restriction_type === 'role' ? $this->role_level : null,
        ]);

        if ($this->restriction_type === 'specific_user') {
            $coupon->users()->sync($this->specific_users);
        }

        return redirect()->route('admin.coupons.index');
    }

    public function render()
    {
        $users = [];
        if ($this->restriction_type === 'specific_user') {
            $users = User::query()
                ->when($this->userSearch, function ($query) {
                    $query->where('name', 'like', '%' . $this->userSearch . '%')
                        ->orWhere('email', 'like', '%' . $this->userSearch . '%');
                })
                ->limit(10)
                ->get();
        }

        return view('livewire.admin.coupons.create', [
            'users' => $users
        ]);
    }
}
