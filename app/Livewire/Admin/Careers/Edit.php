<?php

namespace App\Livewire\Admin\Careers;

use App\Models\Career;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Title('Edit Career')]
class Edit extends Component
{
    public Career $career;

    public $title;
    public $level;
    public $employment_type;
    public $department;
    public $location;
    public $description;
    public $status;
    public $sequence;

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'status' => 'boolean',
            'sequence' => 'integer',
        ];
    }

    public function mount(Career $career)
    {
        $this->career = $career;
        $this->title = $career->title;
        $this->level = $career->level;
        $this->employment_type = $career->employment_type;
        $this->department = $career->department;
        $this->location = $career->location;
        $this->description = $career->description;
        // Fix: Cast to int for dropdown
        $this->status = (int) $career->status;
        $this->sequence = $career->sequence;
    }

    public function save()
    {
        $this->validate();

        // Update Slug only if unique
        $slug = Str::slug($this->title);
        $baseSlug = $slug;
        $counter = 1;
        while (Career::where('slug', $slug)->where('id', '!=', $this->career->id)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $this->career->update([
            'title' => $this->title,
            'slug' => $slug,
            'level' => $this->level,
            'employment_type' => $this->employment_type,
            'department' => $this->department,
            'location' => $this->location,
            'description' => $this->description,
            'status' => $this->status,
            'sequence' => $this->sequence,
        ]);

        session()->flash('alert', ['type' => 'success', 'message' => 'Job updated successfully.']);
        return redirect()->route('admin.careers.index');
    }

    public function render()
    {
        return view('livewire.admin.careers.edit');
    }
}