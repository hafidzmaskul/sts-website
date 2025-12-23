<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

use App\Models\PricingFormula;

use Livewire\Attributes\Title;

#[Title('Edit Product')]
class Edit extends Component
{
    use WithFileUploads;

    public $productId;

    // Form Fields
    public $brand_id = null;
    public $pricing_formula_id = null;
    public $title = '';
    public $slug = '';
    public $is_sign_up_for_pricing = false;
    public $base_price = null;
    public $status = 'active';
    public $is_exclusive = false;

    // Rich Text Fields
    public $key_feature = '';
    public $product_overview = '';
    public $main_feature = '';
    public $information = '';
    public $specification = '';

    // SEO
    public $seo_title = '';
    public $seo_description = '';
    public $seo_keywords = '';

    // Relations
    public $selectedCategories = [];

    // Dynamic Image Management
    public $newImages = []; // Array of ['image' => file, 'sequence' => int, 'key' => unique_id]
    public $storedImages = []; // Array of existing images, including their sequence

    public function mount(Product $product)
    {
        $this->productId = $product->id;
        $this->brand_id = $product->brand_id;
        $this->pricing_formula_id = $product->pricing_formula_id;
        $this->title = $product->title;
        $this->slug = $product->slug;
        $this->is_sign_up_for_pricing = $product->is_sign_up_for_pricing;
        $this->base_price = $product->base_price;
        $this->status = $product->status;
        $this->is_exclusive = $product->is_exclusive;
        $this->key_feature = $product->key_feature;
        $this->product_overview = $product->product_overview;
        $this->main_feature = $product->main_feature;
        $this->information = $product->information;
        $this->specification = $product->specification;
        $this->seo_title = $product->seo_title;
        $this->seo_description = $product->seo_description;
        $this->seo_keywords = $product->seo_keywords;

        $this->selectedCategories = $product->categories->pluck('id')->toArray();
        $this->storedImages = $product->images()
            ->orderBy('sequence')
            ->get()
            ->map(function ($img) {
                return [
                    'id' => $img->id,
                    'image_path' => $img->image_path,
                    'sequence' => $img->sequence,
                ];
            })
            ->toArray();
    }

    public function addImage()
    {
        // Calculate next sequence based on existing and new images
        $maxSequence = 0;

        if (!empty($this->storedImages)) {
            $maxSequence = max(array_column($this->storedImages, 'sequence'));
        }

        if (!empty($this->newImages)) {
            $maxSequence = max($maxSequence, max(array_column($this->newImages, 'sequence')));
        }

        $this->newImages[] = [
            'image' => null,
            'sequence' => $maxSequence + 1,
            'key' => Str::random(10),
        ];
    }

    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }

    public function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'pricing_formula_id' => 'nullable|exists:pricing_formulas,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($this->productId)],
            'base_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'selectedCategories' => 'array',

            // Image Validation
            'newImages.*.image' => 'required|image|max:2048',
            'newImages.*.sequence' => 'required|integer|min:0',
            'storedImages.*.sequence' => 'required|integer|min:0',

            'is_sign_up_for_pricing' => 'boolean',
            'is_exclusive' => 'boolean',
            'key_feature' => 'nullable|string',
            'product_overview' => 'nullable|string',
            'main_feature' => 'nullable|string',
            'information' => 'nullable|string',
            'specification' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        $product = Product::findOrFail($this->productId);

        $product->update([
            'brand_id' => $this->brand_id,
            'pricing_formula_id' => $this->pricing_formula_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'is_sign_up_for_pricing' => $this->is_sign_up_for_pricing,
            'base_price' => $this->base_price,
            'status' => $this->status,
            'is_exclusive' => $this->is_exclusive,
            'key_feature' => $this->key_feature,
            'product_overview' => $this->product_overview,
            'main_feature' => $this->main_feature,
            'information' => $this->information,
            'specification' => $this->specification,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
        ]);

        $product->categories()->sync($this->selectedCategories);

        // Update sequences for existing images
        foreach ($this->storedImages as $imgData) {
            ProductImage::where('id', $imgData['id'])->update(['sequence' => $imgData['sequence']]);
        }

        // Add new images
        foreach ($this->newImages as $imgData) {
            if ($imgData['image']) {
                $path = $imgData['image']->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'sequence' => $imgData['sequence'],
                ]);
            }
        }

        session()->flash('success', 'Product updated successfully.');
        return redirect()->route('admin.products.index');
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        if ($image->product_id == $this->productId) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            // Refresh stored images list
            $this->storedImages = Product::findOrFail($this->productId)->images()
                ->orderBy('sequence')
                ->get()
                ->map(function ($img) {
                    return [
                        'id' => $img->id,
                        'image_path' => $img->image_path,
                        'sequence' => $img->sequence,
                    ];
                })
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.admin.products.edit', [
            'categories' => ProductCategory::orderBy('parent_id')->orderBy('name')->get(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'pricingFormulas' => PricingFormula::orderBy('label')->get(),
        ]);
    }
}
