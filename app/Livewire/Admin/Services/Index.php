<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

#[Title('Services')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function mount()
    {
        if (session()->has('alert')) {
            $alert = session('alert');
            $this->dispatch('alert', type: $alert['type'], message: $alert['message']);
        }
    }

    public function delete($id)
    {
        if (Gate::denies('services.delete')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to delete services.');
            return;
        }

        $service = Service::findOrFail($id);
        if ($service->image) {
            if (Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
        }
        $service->delete();
        $this->dispatch('alert', type: 'success', message: 'Service deleted successfully.');
    }

    public function render()
    {
        $services = Service::with('user')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('sequence', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.services.index', [
            'services' => $services,
        ]);
    }
}