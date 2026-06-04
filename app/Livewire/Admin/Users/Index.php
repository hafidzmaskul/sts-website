<?php

namespace App\Livewire\Admin\Users;

use App\Models\PricingFormula;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Title('User Management')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public ?int $role_filter = null;

    // form fields
    public string $name = '';

    public string $email = '';

    public ?string $password = null;   // set when creating or resetting

    public array $roleIds = [];

    public array $permissionIds = [];

    public ?int $pricing_formula_id = null;

    protected function rules()
    {
        $uniqueEmail = 'unique:users,email';
        if ($this->editingId) {
            $uniqueEmail .= ','.$this->editingId;
        }

        return [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|'.$uniqueEmail,
            'password' => $this->editingId ? 'nullable|min:6' : 'required|min:6',
            'roleIds' => 'array',
            'roleIds.*' => 'integer',
            'permissionIds' => 'array',
            'permissionIds.*' => 'integer',
            'pricing_formula_id' => 'nullable|exists:pricing_formulas,id',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->authorize('users.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $this->authorize('users.edit');
        $u = User::findOrFail($id);
        $this->editingId = $u->id;
        $this->name = $u->name;
        $this->email = $u->email;
        $this->password = null;
        $this->roleIds = $u->roles()->pluck('id')->map(fn ($v) => (int) $v)->toArray();
        $this->permissionIds = $u->permissions()->pluck('id')->map(fn ($v) => (int) $v)->toArray();
        $this->pricing_formula_id = $u->pricing_formula_id;
        $this->showForm = true;
    }

    public function save()
    {
        $this->authorize($this->editingId ? 'users.edit' : 'users.create');
        $data = $this->validate();

        $user = $this->editingId ? User::findOrFail($this->editingId) : new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->pricing_formula_id = $data['pricing_formula_id'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        } elseif (! $this->editingId) {
            // creating and somehow password is empty (should not happen due to rules)
            $user->password = Hash::make('password');
        }

        $user->save();

        $roles = Role::whereIn('id', $this->roleIds)->get();
        $perms = Permission::whereIn('id', $this->permissionIds)->get();

        $user->syncRoles($roles);
        $user->syncPermissions($perms);

        $this->resetForm();
        $this->dispatch('notify', type: 'success', message: 'User saved.');
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        $this->authorize('users.delete');
        if (auth()->id() === $id) {
            return;
        } // prevent self-delete
        User::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'User deleted.');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = null;
        $this->roleIds = [];
        $this->permissionIds = [];
        $this->pricing_formula_id = null;
    }

    public function render()
    {
        $this->authorize('users.view');

        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where(function ($w) {
                    $w->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->role_filter, function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('id', $this->role_filter);
                });
            })
            ->orderBy('name')
            ->paginate(10);

        $roles = Role::orderBy('name')->get(['id', 'name']);
        $permissions = Permission::orderBy('name')->get(['id', 'name']);
        $pricingFormulas = PricingFormula::orderBy('label')->get();

        return view('livewire.admin.users.index', compact('users', 'roles', 'permissions', 'pricingFormulas'));
    }
}
