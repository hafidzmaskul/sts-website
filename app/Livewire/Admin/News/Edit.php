<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\NewsCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Title('Edit News Post')]
class Edit extends Component
{
    use WithFileUploads;

    public News $news;
    public $allCategories;

    public $title;
    public $content;
    public $status;
    public $meta_title;
    public $meta_description;
    public $meta_keyword;
    public $image;
    public $existingImage;
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

    public function mount(News $news)
    {
        $this->allCategories = NewsCategory::orderBy('name')->get();
        
        $this->news = $news;
        $this->title = $news->title;
        $this->content = $news->content;
        $this->status = $news->status;
        $this->meta_title = $news->meta_title;
        $this->meta_description = $news->meta_description;
        $this->meta_keyword = $news->meta_keyword;
        $this->existingImage = $news->image;
        $this->selectedCategories = $news->categories->pluck('id')->toArray();
    }

    private function generateUniqueSlug($title, $editingId)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        $query = News::where('slug', $slug)->where('id', '!=', $editingId);
        
        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $query = News::where('slug', $slug)->where('id', '!=', $editingId);
            $counter++;
        }
        return $slug;
    }

    public function save()
    {
        $this->validate();

        $slug = $this->generateUniqueSlug($this->title, $this->news->id);
        
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
            if ($this->existingImage) {
                if (Storage::disk('public')->exists($this->existingImage)) {
                    Storage::disk('public')->delete($this->existingImage);
                }
            }
        }

        $this->news->update($data);
        $this->news->categories()->sync($this->selectedCategories);

        session()->flash('alert', ['type' => 'success', 'message' => 'News post updated successfully.']);
        return redirect()->route('admin.news.index');
    }
    
    public function render()
    {
        return view('livewire.admin.news.edit');
    }
}