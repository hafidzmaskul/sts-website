<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use App\Models\PricingFormula;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Customer')]
class Edit extends Component
{
    public User $user;

    public $name;

    public $email;

    public $phone;

    public $address;

    public $city;

    public $postal_code;

    public $country;

    public $status;

    public $pricing_formula_id;

    public function mount(User $user)
    {
        $this->user = $user->load('customer');

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->pricing_formula_id = $this->user->pricing_formula_id;

        $customerProfile = $this->user->customer;

        $this->phone = $customerProfile?->phone;
        $this->address = $customerProfile?->address;
        $this->city = $customerProfile?->city;
        $this->postal_code = $customerProfile?->postal_code;
        $this->country = $customerProfile?->country;
        $this->status = $customerProfile?->status ?? 'active';
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,suspended',
            'pricing_formula_id' => 'nullable|exists:pricing_formulas,id',
        ];
    }

    public function save()
    {
        $this->authorize('customers.edit');
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'pricing_formula_id' => $this->pricing_formula_id,
        ]);

        // Create or update customer profile
        Customer::updateOrCreate(
            ['user_id' => $this->user->id],
            [
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'status' => $this->status,
            ]
        );

        $this->dispatch('notify', type: 'success', message: 'Customer updated successfully.');

        return redirect()->route('admin.customers.index');
    }

    public function render()
    {
        $this->authorize('customers.edit');

        return view('livewire.admin.customers.edit', [
            'pricingFormulas' => PricingFormula::orderBy('label')->get(),
        ]);
    }
}
