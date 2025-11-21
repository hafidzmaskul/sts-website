<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Title('Edit Service')]
class Edit extends Component
{
    use WithFileUploads;

    public Service $service;

    public $name;
    public $short_description;
    public $content;
    public $status;
    public $sequence;
    public $image;
    public $existingImage;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'required|string',
            // accept boolean-ish values; we'll cast on save
            'status' => 'nullable',
            'sequence' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function mount(Service $service)
    {
        $this->service = $service;

        $this->name = $service->name;
        $this->short_description = $service->short_description;
        $this->content = $service->content ?? '';

        // Cast status to integer so it matches your <option value="0|1">
        $this->status = is_null($service->status) ? 0 : (int) $service->status;

        // sequence can be nullable or integer
        $this->sequence = is_null($service->sequence) ? null : (int) $service->sequence;

        $this->existingImage = $service->image;
    }

    private function generateUniqueSlug($name, $editingId)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        $query = Service::where('slug', $slug)->where('id', '!=', $editingId);

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $query = Service::where('slug', $slug)->where('id', '!=', $editingId);
            $counter++;
        }

        return $slug;
    }

    public function save()
    {
        $this->validate();

        $slug = $this->generateUniqueSlug($this->name, $this->service->id);

        $data = [
            'name' => $this->name,
            'slug' => $slug,
            'short_description' => $this->short_description,
            'content' => $this->content,
            // ensure status is stored as integer 0/1
            'status' => (int) $this->status,
            'sequence' => is_null($this->sequence) ? null : (int) $this->sequence,
            'user_id' => auth()->id(),
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('services', 'public');

            if ($this->existingImage) {
                if (Storage::disk('public')->exists($this->existingImage)) {
                    Storage::disk('public')->delete($this->existingImage);
                }
            }
        }

        $this->service->update($data);

        session()->flash('alert', ['type' => 'success', 'message' => 'Service updated successfully.']);
        return redirect()->route('admin.services.index');
    }

    public function render()
    {
        return view('livewire.admin.services.edit');
    }
}
