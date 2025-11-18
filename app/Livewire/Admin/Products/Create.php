<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Title('Create Product')]
class Create extends Component
{
    use WithFileUploads;

    public $allServices;

    public $name;
    public $content;
    public $price = 0;
    public $status = false;
    public $image;
    public $attachment;
    public $selectedServices = [];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'attachment' => 'nullable|file|max:10240', // 10MB Max
            'selectedServices' => 'array',
            'selectedServices.*' => 'exists:services,id',
        ];
    }

    public function mount()
    {
        $this->allServices = Service::orderBy('name')->get();
    }
    
    private function generateUniqueSlug($name)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
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
            'price' => $this->price,
            'status' => $this->status,
            'user_id' => auth()->id(),
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products/images', 'public');
        }
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('products/attachments', 'public');
        }

        $product = Product::create($data);
        $product->services()->sync($this->selectedServices);

        session()->flash('alert', ['type' => 'success', 'message' => 'Product created successfully.']);
        return redirect()->route('admin.products.index');
    }
    
    public function render()
    {
        return view('livewire.admin.products.create');
    }
}