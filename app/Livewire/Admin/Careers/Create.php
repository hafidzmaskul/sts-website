<?php

namespace App\Livewire\Admin\Careers;

use App\Models\Career;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Title('Create Career')]
class Create extends Component
{
    public $title;
    public $level;
    public $employment_type;
    public $department;
    public $location;
    public $content;
    public $status = false;
    public $sequence = 0;

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'content' => 'required|string',
            'status' => 'boolean',
            'sequence' => 'integer',
        ];
    }

    public function save()
    {
        $this->validate();

        // Generate Slug
        $baseSlug = Str::slug($this->title);
        $slug = $baseSlug;
        $counter = 1;
        while (Career::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Career::create([
            'title' => $this->title,
            'slug' => $slug,
            'level' => $this->level,
            'employment_type' => $this->employment_type,
            'department' => $this->department,
            'location' => $this->location,
            'description' => $this->content,
            'status' => $this->status,
            'sequence' => $this->sequence,
            'user_id' => auth()->id(),
        ]);

        session()->flash('alert', ['type' => 'success', 'message' => 'Job created successfully.']);
        return redirect()->route('admin.careers.index');
    }

    public function render()
    {
        return view('livewire.admin.careers.create');
    }
}