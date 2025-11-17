<?php

namespace App\Livewire\Admin\NewsCategories;

use App\Models\NewsCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

#[Title('News Categories')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $editingId = null;

    public $name;
    public $parent_id;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:news_categories,id',
        ];
    }

    public function create()
    {
        if (Gate::denies('news-categories.create')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to create categories.');
            return;
        }
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        if (Gate::denies('news-categories.edit')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to edit categories.');
            return;
        }

        $category = NewsCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->parent_id = $category->parent_id;
        $this->showForm = true;
    }

    private function generateUniqueSlug($name, $editingId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        $query = NewsCategory::where('slug', $slug);

        if ($editingId) {
            $query->where('id', '!=', $editingId);
        }

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            
            $query = NewsCategory::where('slug', $slug);
            if ($editingId) {
                $query->where('id', '!=', $editingId);
            }
            
            $counter++;
        }

        return $slug;
    }

    public function save()
    {
        $this->validate(); 

        $slug = $this->generateUniqueSlug($this->name, $this->editingId);

        $data = [
            'name' => $this->name,
            'slug' => $slug,
            'parent_id' => $this->parent_id == '' ? null : $this->parent_id,
            'user_id' => auth()->id(),
        ];

        if ($this->editingId) {
            if (Gate::denies('news-categories.edit')) {
                $this->dispatch('alert', type: 'error', message: 'You do not have permission to edit categories.');
                return;
            }
            NewsCategory::findOrFail($this->editingId)->update($data);
            $this->dispatch('alert', type: 'success', message: 'Category updated successfully.');
        } else {
            if (Gate::denies('news-categories.create')) {
                $this->dispatch('alert', type: 'error', message: 'You do not have permission to create categories.');
                return;
            }
            NewsCategory::create($data);
            $this->dispatch('alert', type: 'success', message: 'Category created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        if (Gate::denies('news-categories.delete')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to delete categories.');
            return;
        }
        
        NewsCategory::findOrFail($id)->delete();
        $this->dispatch('alert', type: 'success', message: 'Category deleted successfully.');
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
        $this->parent_id = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = NewsCategory::with('parent') 
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);
        
        $allCategories = NewsCategory::orderBy('name')
            ->when($this->editingId, function ($query) {
                $query->where('id', '!=', $this->editingId);
            })
            ->get();

        return view('livewire.admin.news-categories.index', [
            'categories' => $categories,
            'allCategories' => $allCategories,
        ]);
    }
}