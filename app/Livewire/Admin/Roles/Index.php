<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public array $selectedPermissions = [];

    protected $rules = [
        'name' => 'required|string|min:2|max:50',
        'selectedPermissions' => 'array',
        'selectedPermissions.*' => 'integer',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->authorize('roles.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $this->authorize('roles.edit');
        $role = Role::findOrFail($id);

        if ($this->isFixedRole($role->name)) {
            $this->dispatch('notify', type: 'error', message: 'This role cannot be modified.');

            return;
        }

        $this->editingId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions()->pluck('id')->map(fn ($v) => (int) $v)->toArray();
        $this->showForm = true;
    }

    public function save()
    {
        $this->authorize($this->editingId ? 'roles.edit' : 'roles.create');
        $this->validate();

        if ($this->editingId) {
            $role = Role::findOrFail($this->editingId);
            if ($this->isFixedRole($role->name)) {
                $this->dispatch('notify', type: 'error', message: 'This role cannot be modified.');

                return;
            }
        } else {
            $role = new Role(['guard_name' => 'web']);
        }

        $role->name = $this->name;
        $role->save();

        $perms = Permission::whereIn('id', $this->selectedPermissions)->get();
        $role->syncPermissions($perms);

        $this->resetForm();
        $this->dispatch('notify', type: 'success', message: 'Role saved.');
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        $this->authorize('roles.delete');
        // if ($id === 1) return; // optional safeguard if you treat id=1 as super-admin

        $role = Role::findOrFail($id);

        if ($this->isFixedRole($role->name)) {
            $this->dispatch('notify', type: 'error', message: 'This role cannot be deleted.');

            return;
        }

        $role->delete();
        $this->dispatch('notify', type: 'success', message: 'Role deleted.');
        if ($this->editingId === $id) {
            $this->resetForm();
        }
    }

    protected function isFixedRole(string $name): bool
    {
        return in_array($name, ['admin', 'guest', 'trade account', 'credit facilities account', 'child']);
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->selectedPermissions = [];
    }

    public function render()
    {
        $this->authorize('roles.view');
        $roles = Role::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        $permissions = Permission::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.roles.index', compact('roles', 'permissions'))
            ->title('Role Management');
    }
}
