<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Title('Create Service')]
class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $content;
    public $status = false;
    public $sequence = 0;
    public $image;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'boolean',
            'sequence' => 'integer',
            'image' => 'nullable|image|max:2048',
        ];
    }

    private function generateUniqueSlug($name)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Service::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    public function save()
    {
        $this->validate();

        $slug = $this->generateUniqueSlug($this->name);
        
        $data = [
            'name' => $this->name,
            'slug' => $slug,
            'content' => $this->content,
            'status' => $this->status,
            'sequence' => $this->sequence,
            'user_id' => auth()->id(),
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('services', 'public');
        }

        Service::create($data);

        session()->flash('alert', ['type' => 'success', 'message' => 'Service created successfully.']);
        return redirect()->route('admin.services.index');
    }
    
    public function render()
    {
        return view('livewire.admin.services.create');
    }
}