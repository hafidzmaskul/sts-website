<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use App\Models\Customer;
use Livewire\Component;
use Illuminate\Validation\Rule;

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

    public function mount(User $user)
    {
        $this->user = $user->load('customer');

        $this->name = $this->user->name;
        $this->email = $this->user->email;

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
        ];
    }

    public function save()
    {
        $this->authorize('customers.edit');
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
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

        return view('livewire.admin.customers.edit')->title('Edit Customer');
    }
}
