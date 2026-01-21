<?php

namespace App\Livewire\FixedRole\ChildUsers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewStaffUserCredentials;

class Index extends Component
{
    use WithPagination;

    public $firstName;
    public $listingFor = 'Child User';

    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingId),
            ],
            'password' => 'nullable|min:8|confirmed',
        ];
    }

    public function mount()
    {
        // Safety check: ensure only allowed roles access this
        if (!auth()->user()->hasAnyRole(['trade account', 'credit facilities account'])) {
            abort(403, 'Unauthorized');
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $child = auth()->user()->children()->findOrFail($id);
        $this->editingId = $child->id;
        $this->name = $child->name;
        $this->email = $child->email;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $user = auth()->user()->children()->findOrFail($this->editingId);
            $user->name = $this->name;
            $user->email = $this->email;
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            $this->dispatch('notify', type: 'success', message: 'User updated.');
        } else {
            $generatedPassword = Str::random(10);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($generatedPassword),
                'parent_id' => auth()->id(),
            ]);

            // Assign same role as parent (Head Account)
            $parentRole = auth()->user()->roles->first()->name;
            $user->assignRole($parentRole);

            // Create Customer record linked to same company
            $parentCustomer = auth()->user()->customer;
            if ($parentCustomer && $parentCustomer->company_id) {
                // Split name into first and last name
                $nameParts = explode(' ', $this->name, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';

                \App\Models\Customer::create([
                    'user_id' => $user->id,
                    'company_id' => $parentCustomer->company_id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $this->email,
                    'account_level' => 'staff',
                    'status_review' => 'approved',
                ]);
            }

            // Send credentials via email
            Mail::to($this->email)->send(new NewStaffUserCredentials($user, $generatedPassword));

            $this->dispatch('notify', type: 'success', message: 'Staff user created. Check email for credentials.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        $child = auth()->user()->children()->findOrFail($id);
        $child->delete();
        $this->dispatch('notify', type: 'success', message: 'User deleted.');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = auth()->user()->children()->latest()->paginate(10);

        return view('livewire.fixed-role.child-users.index', [
            'users' => $users
        ])->title('Manage Users');
    }
}
