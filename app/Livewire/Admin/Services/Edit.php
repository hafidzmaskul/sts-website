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
            'status' => 'boolean',
            'sequence' => 'integer',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->name = $service->name;
        $this->short_description = $service->short_description;
        $this->content = $service->content;
        $this->status = $service->status;
        $this->sequence = $service->sequence;
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
            'status' => $this->status,
            'sequence' => $this->sequence,
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