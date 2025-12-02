<?php

namespace App\Livewire\Admin\Banners;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public $file; // for file upload
    public ?string $cta_url = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'file' => $this->editingId ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'cta_url' => 'nullable|url|max:255',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->authorize('banner.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $this->authorize('banner.edit');
        $banner = Banner::findOrFail($id);
        $this->editingId = $banner->id;
        $this->name = $banner->name;
        $this->cta_url = $banner->cta_url;
        // File is not pre-filled for security/technical reasons
        $this->showForm = true;
    }

    public function save()
    {
        $this->authorize($this->editingId ? 'banner.edit' : 'banner.create');
        $this->validate();

        if ($this->editingId) {
            $banner = Banner::findOrFail($this->editingId);
        } else {
            $banner = new Banner();
            $banner->created_by = auth()->id();
        }

        $banner->name = $this->name;
        $banner->cta_url = $this->cta_url;

        if ($this->file) {
            // Delete old file if updating
            if ($this->editingId && $banner->file_path) {
                Storage::disk('public')->delete($banner->file_path);
            }
            $path = $this->file->store('banners', 'public');
            $banner->file_path = $path;
        }

        $banner->save();

        $this->resetForm();
        $this->dispatch('notify', type: 'success', message: 'Banner saved successfully.');
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        $this->authorize('banner.delete');
        $banner = Banner::findOrFail($id);

        if ($banner->file_path) {
            Storage::disk('public')->delete($banner->file_path);
        }

        $banner->delete();
        $this->dispatch('notify', type: 'success', message: 'Banner deleted successfully.');

        if ($this->editingId === $id) {
            $this->resetForm();
        }
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->file = null;
        $this->cta_url = null;
        $this->resetValidation();
    }

    public function render()
    {
        $this->authorize('banner.view');

        $banners = Banner::query()
            ->with('creator')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.banners.index', [
            'banners' => $banners,
        ])->title('Banner Management');
    }
}
