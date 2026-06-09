<?php

namespace App\Livewire\Admin\ProductCategories;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public ?int $parent_id = null;

    public $image;

    public ?string $seo_title = null;

    public ?string $seo_description = null;

    public ?string $seo_keywords = null;

    public bool $is_parent = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('product_categories', 'slug')->ignore($this->editingId)],
            'parent_id' => [
                'nullable',
                'exists:product_categories,id',
                function ($attribute, $value, $fail) {
                    if ($this->editingId && $value == $this->editingId) {
                        $fail('A category cannot be its own parent.');
                    }
                },
            ],
            'is_parent' => 'boolean',
            'image' => ['nullable', 'image', 'max:2048'], // 2MB Max
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
        $this->authorize('product-categories.create');
        $this->resetForm();
        $this->is_parent = true;
        $this->showForm = true;
    }

    public function createSubCategory(int $parentId)
    {
        $this->authorize('product-categories.create');
        $this->resetForm();
        $this->parent_id = $parentId;
        $this->is_parent = false;
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $this->authorize('product-categories.edit');
        $category = ProductCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->parent_id = $category->parent_id;
        $this->is_parent = $category->is_parent;
        $this->seo_title = $category->seo_title;
        $this->seo_description = $category->seo_description;
        $this->seo_keywords = $category->seo_keywords;
        $this->showForm = true;
    }

    public function save()
    {
        $this->authorize($this->editingId ? 'product-categories.edit' : 'product-categories.create');
        $this->validate();

        if ($this->editingId) {
            $category = ProductCategory::findOrFail($this->editingId);
        } else {
            $category = new ProductCategory;
            $category->created_by = auth()->id();
        }

        $category->name = $this->name;
        $category->slug = Str::slug($this->slug);
        $category->parent_id = $this->parent_id;
        $category->is_parent = $this->is_parent;
        $category->seo_title = $this->seo_title;
        $category->seo_description = $this->seo_description;
        $category->seo_keywords = $this->seo_keywords;

        if ($this->image) {
            if ($category->image_path) {
                Storage::delete($category->image_path);
            }
            $category->image_path = $this->image->store('product-categories', 'public');
        }

        $category->save();

        $this->resetForm();
        $this->dispatch('notify', type: 'success', message: 'Category saved successfully.');
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        $this->authorize('product-categories.delete');
        $category = ProductCategory::findOrFail($id);

        if ($category->image_path) {
            Storage::delete($category->image_path);
        }

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
        $this->is_parent = false;
        $this->image = null;
        $this->seo_title = null;
        $this->seo_description = null;
        $this->seo_keywords = null;
        $this->resetValidation();
    }

    public function render()
    {
        $this->authorize('product-categories.view');

        $categoriesQuery = ProductCategory::query()
            ->with(['creator', 'parent', 'children'])
            ->orderByDesc('created_at');

        if ($this->search) {
            $categories = $categoriesQuery
                ->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('slug', 'like', '%'.$this->search.'%');
                })
                ->paginate(10);
            $isSearchActive = true;
        } else {
            $categories = $categoriesQuery
                ->whereNull('parent_id')
                ->paginate(10);
            $isSearchActive = false;
        }

        // For the dropdown (exclude self if editing, avoid deep recursion logic for now)
        $parentCandidates = ProductCategory::query()
            ->when($this->editingId, function ($q) {
                $q->where('id', '!=', $this->editingId);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.admin.product-categories.index', [
            'categories' => $categories,
            'parentCandidates' => $parentCandidates,
            'isSearchActive' => $isSearchActive,
        ])->title('Product Categories');
    }
}
