<?php

namespace App\Livewire\Admin\NewsCategories;

use App\Models\NewsCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public ?int $parent_id = null;

    public ?string $seo_title = null;

    public ?string $seo_description = null;

    public ?string $seo_keywords = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('news_categories', 'slug')->ignore($this->editingId)],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:news_categories,id',
                function ($attribute, $value, $fail) {
                    if ($this->editingId && $value == $this->editingId) {
                        $fail('A category cannot be its own parent.');
                    }
                },
            ],
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
        ];
    }

    public function updatedName($value)
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->authorize('news-categories.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $this->authorize('news-categories.edit');
        $category = NewsCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->parent_id = $category->parent_id;
        $this->seo_title = $category->seo_title;
        $this->seo_description = $category->seo_description;
        $this->seo_keywords = $category->seo_keywords;
        $this->showForm = true;
    }

    public function save()
    {
        $this->authorize($this->editingId ? 'news-categories.edit' : 'news-categories.create');
        $this->validate();

        if ($this->editingId) {
            $category = NewsCategory::findOrFail($this->editingId);
        } else {
            $category = new NewsCategory;
            $category->created_by = auth()->id();
        }

        $category->name = $this->name;
        $category->slug = Str::slug($this->slug); // Ensure slug format
        $category->parent_id = $this->parent_id ?: null; // Handle empty string as null
        $category->seo_title = $this->seo_title;
        $category->seo_description = $this->seo_description;
        $category->seo_keywords = $this->seo_keywords;

        $category->save();

        $this->resetForm();
        $this->dispatch('notify', type: 'success', message: 'Category saved successfully.');
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        $this->authorize('news-categories.delete');
        $category = NewsCategory::findOrFail($id);

        // Optional: Check for children or related news before deleting?
        // For now, standard delete (children parent_id set to null by DB constraint)

        $category->delete();
        $this->dispatch('notify', type: 'success', message: 'Category deleted successfully.');

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
        $this->slug = '';
        $this->parent_id = null;
        $this->seo_title = null;
        $this->seo_description = null;
        $this->seo_keywords = null;
        $this->resetValidation();
    }

    public function render()
    {
        $this->authorize('news-categories.view');

        $categories = NewsCategory::query()
            ->with(['parent', 'creator'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        // For parent dropdown, get all categories except current editing one
        $parentOptions = NewsCategory::query()
            ->when($this->editingId, function ($q) {
                $q->where('id', '!=', $this->editingId);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.admin.news-categories.index', [
            'categories' => $categories,
            'parentOptions' => $parentOptions,
        ])->title('News Categories');
    }
}
