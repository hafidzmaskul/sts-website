<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public $image;
    public string $status = 'draft';
    public ?string $published_at = null;
    public array $selectedCategories = [];

    public ?string $seo_title = null;
    public ?string $seo_description = null;
    public ?string $seo_keywords = null;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('news', 'slug')->ignore($this->editingId)],
            'content' => 'required|string',
            'image' => ['nullable', 'image', 'max:2048'], // 2MB Max
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'selectedCategories' => 'array',
            'selectedCategories.*' => 'exists:news_categories,id',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
        ];
    }

    public function updatedTitle($value)
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->authorize('news.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $this->authorize('news.edit');
        $news = News::with('categories')->findOrFail($id);
        $this->editingId = $news->id;
        $this->title = $news->title;
        $this->slug = $news->slug;
        $this->content = $news->content;
        $this->status = $news->status;
        $this->published_at = $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : null;
        $this->selectedCategories = $news->categories->pluck('id')->toArray();
        $this->seo_title = $news->seo_title;
        $this->seo_description = $news->seo_description;
        $this->seo_keywords = $news->seo_keywords;
        $this->showForm = true;
    }

    public function save()
    {
        $this->authorize($this->editingId ? 'news.edit' : 'news.create');
        $this->validate();

        if ($this->editingId) {
            $news = News::findOrFail($this->editingId);
        } else {
            $news = new News();
            $news->created_by = auth()->id();
        }

        $news->title = $this->title;
        $news->slug = Str::slug($this->slug);
        $news->content = $this->content;
        $news->status = $this->status;
        $news->published_at = $this->published_at ?: null;
        $news->seo_title = $this->seo_title;
        $news->seo_description = $this->seo_description;
        $news->seo_keywords = $this->seo_keywords;

        if ($this->image) {
            if ($news->image_path) {
                Storage::delete($news->image_path);
            }
            $news->image_path = $this->image->store('news', 'public');
        }

        $news->save();
        $news->categories()->sync($this->selectedCategories);

        $this->resetForm();
        $this->dispatch('notify', type: 'success', message: 'News article saved successfully.');
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        $this->authorize('news.delete');
        $news = News::findOrFail($id);

        if ($news->image_path) {
            Storage::delete($news->image_path);
        }

        $news->delete();
        $this->dispatch('notify', type: 'success', message: 'News article deleted successfully.');

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
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->image = null;
        $this->status = 'draft';
        $this->published_at = null;
        $this->selectedCategories = [];
        $this->seo_title = null;
        $this->seo_description = null;
        $this->seo_keywords = null;
        $this->resetValidation();
    }

    public function render()
    {
        $this->authorize('news.view');

        $news = News::query()
            ->with(['categories', 'creator'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $categories = NewsCategory::orderBy('name')->get();

        return view('livewire.admin.news.index', [
            'news' => $news,
            'categories' => $categories,
        ])->title('News Articles');
    }
}
