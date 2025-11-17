<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\NewsCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Title('Create News Post')]
class Create extends Component
{
    use WithFileUploads;

    public $allCategories;

    public $title;
    public $content;
    public $status = 'draft';
    public $meta_title;
    public $meta_description;
    public $meta_keyword;
    public $image;
    public $selectedCategories = [];

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'selectedCategories' => 'array',
            'selectedCategories.*' => 'exists:news_categories,id',
        ];
    }

    public function mount()
    {
        $this->allCategories = NewsCategory::orderBy('name')->get();
    }
    
    private function generateUniqueSlug($title)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (News::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    public function save()
    {
        $this->validate();

        $slug = $this->generateUniqueSlug($this->title);
        
        $data = [
            'title' => $this->title,
            'slug' => $slug,
            'content' => $this->content,
            'status' => $this->status,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keyword' => $this->meta_keyword,
            'user_id' => auth()->id(),
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('news', 'public');
        }

        $news = News::create($data);
        $news->categories()->sync($this->selectedCategories);

        session()->flash('alert', ['type' => 'success', 'message' => 'News post created successfully.']);
        return redirect()->route('admin.news.index');
    }
    
    public function render()
    {
        return view('livewire.admin.news.create');
    }
}