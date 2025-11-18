<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Title('Edit Product')]
class Edit extends Component
{
    use WithFileUploads;

    public Product $product;
    public $allServices;

    public $name;
    public $content;
    public $price;
    public $status;
    public $image;
    public $existingImage;
    public $attachment;
    public $existingAttachment;
    public $selectedServices = [];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'attachment' => 'nullable|file|max:10240',
            'selectedServices' => 'array',
            'selectedServices.*' => 'exists:services,id',
        ];
    }

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->allServices = Service::orderBy('name')->get();
        
        $this->name = $product->name;
        $this->content = $product->content;
        $this->price = $product->price;
        $this->status = $product->status;
        $this->existingImage = $product->image;
        $this->existingAttachment = $product->attachment;
        $this->selectedServices = $product->services->pluck('id')->toArray();
    }

    private function generateUniqueSlug($name, $editingId)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        $query = Product::where('slug', $slug)->where('id', '!=', $editingId);
        
        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $query = Product::where('slug', $slug)->where('id', '!=', $editingId);
            $counter++;
        }
        return $slug;
    }

    public function save()
    {
        $this->validate();

        $slug = $this->generateUniqueSlug($this->name, $this->product->id);
        
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
            if ($this->existingImage) {
                if (Storage::disk('public')->exists($this->existingImage)) {
                    Storage::disk('public')->delete($this->existingImage);
                }
            }
        }
        
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('products/attachments', 'public');
            if ($this->existingAttachment) {
                if (Storage::disk('public')->exists($this->existingAttachment)) {
                    Storage::disk('public')->delete($this->existingAttachment);
                }
            }
        }

        $this->product->update($data);
        $this->product->services()->sync($this->selectedServices);

        session()->flash('alert', ['type' => 'success', 'message' => 'Product updated successfully.']);
        return redirect()->route('admin.products.index');
    }
    
    public function render()
    {
        return view('livewire.admin.products.edit');
    }
}