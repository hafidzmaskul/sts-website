<?php

namespace App\Livewire\FixedRole\ChildUsers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            'password' => $this->editingId ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
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
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'parent_id' => auth()->id(),
            ]);

            $user->assignRole('child');
            $this->dispatch('notify', type: 'success', message: 'Child user created.');
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
