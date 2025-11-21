<?php

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

#[Title('Testimonials')]
class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $name;
    public $job_title;
    public $description;
    public $status = false;
    public $sequence = 0;
    public $image; 
    public $existingImage; 

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'boolean',
            'sequence' => 'integer',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function create()
    {
        if (Gate::denies('testimonials.create')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to create testimonials.');
            return;
        }
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        if (Gate::denies('testimonials.edit')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to edit testimonials.');
            return;
        }

        $testimonial = Testimonial::findOrFail($id);
        $this->editingId = $testimonial->id;
        $this->name = $testimonial->name;
        $this->job_title = $testimonial->job_title;
        $this->description = $testimonial->description;
        
        // FIX: Cast to integer to match dropdown values (0 or 1)
        $this->status = (int) $testimonial->status;
        
        $this->sequence = $testimonial->sequence;
        $this->existingImage = $testimonial->image;
        $this->image = null; 
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'job_title' => $this->job_title,
            'description' => $this->description,
            'status' => $this->status,
            'sequence' => $this->sequence,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('testimonials', 'public');

            if ($this->editingId && $this->existingImage) {
                if (Storage::disk('public')->exists($this->existingImage)) {
                    Storage::disk('public')->delete($this->existingImage);
                }
            }
        }

        if ($this->editingId) {
            if (Gate::denies('testimonials.edit')) {
                $this->dispatch('alert', type: 'error', message: 'You do not have permission to edit testimonials.');
                return;
            }
            Testimonial::findOrFail($this->editingId)->update($data);
            $this->dispatch('alert', type: 'success', message: 'Testimonial updated successfully.');
        } else {
            if (Gate::denies('testimonials.create')) {
                $this->dispatch('alert', type: 'error', message: 'You do not have permission to create testimonials.');
                return;
            }
            Testimonial::create($data);
            $this->dispatch('alert', type: 'success', message: 'Testimonial created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        if (Gate::denies('testimonials.delete')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to delete testimonials.');
            return;
        }

        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->image) {
            if (Storage::disk('public')->exists($testimonial->image)) {
                Storage::disk('public')->delete($testimonial->image);
            }
        }

        $testimonial->delete();
        $this->dispatch('alert', type: 'success', message: 'Testimonial deleted successfully.');
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->job_title = '';
        $this->description = '';
        $this->status = 0; // Default to Draft (0)
        $this->sequence = 0;
        $this->image = null;
        $this->existingImage = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $testimonials = Testimonial::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('job_title', 'like', '%' . $this->search . '%')
            ->orderBy('sequence', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.testimonials.index', [
            'testimonials' => $testimonials,
        ]);
    }
}