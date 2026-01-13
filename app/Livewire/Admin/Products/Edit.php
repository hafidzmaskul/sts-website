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
    public $sku = '';
    public $is_sign_up_for_pricing = false;
    public $base_price = null;
    public $special_price = null;
    public $status = 'active';

    public $showAdvancePricing = false;
    public $customerPrices = [];

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

    // Attachments
    public $newAttachments = []; // Array of ['file' => file, 'name' => string, 'key' => unique_id]
    public $storedAttachments = []; // Array of existing attachments

    public function mount(Product $product)
    {
        $this->productId = $product->id;
        $this->brand_id = $product->brand_id;
        $this->pricing_formula_id = $product->pricing_formula_id;
        $this->title = $product->title;
        $this->slug = $product->slug;
        $this->sku = $product->sku;
        $this->is_sign_up_for_pricing = $product->is_sign_up_for_pricing;
        $this->base_price = $product->base_price;
        $this->special_price = $product->special_price;
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

        $this->storedAttachments = $product->attachments()
            ->get()
            ->map(function ($att) {
                return [
                    'id' => $att->id,
                    'name' => $att->name,
                    'file_path' => $att->file_path,
                    'is_public' => $att->is_public,
                ];
            })
            ->toArray();
    }

    public function addAttachment()
    {
        $this->newAttachments[] = [
            'file' => null,
            'name' => '',
            'is_public' => false,
            'key' => Str::random(10),
        ];
    }

    public function addCustomerPrice()
    {
        $this->customerPrices[] = [
            'user_id' => '',
            'price' => null,
        ];
    }

    public function removeCustomerPrice($index)
    {
        unset($this->customerPrices[$index]);
        $this->customerPrices = array_values($this->customerPrices);
    }

    public function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'pricing_formula_id' => 'nullable|exists:pricing_formulas,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($this->productId)],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->productId)],
            'base_price' => 'nullable|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'selectedCategories' => 'array',

            // Image Validation
            'newImages.*.image' => 'required|image|max:2048',
            'newImages.*.sequence' => 'required|integer|min:0',
            'storedImages.*.sequence' => 'required|integer|min:0',

            // Attachment Validation
            'newAttachments.*.file' => 'required|file|max:10240',
            'newAttachments.*.name' => 'required|string|max:255',
            'newAttachments.*.is_public' => 'boolean',
            'storedAttachments.*.name' => 'required|string|max:255',
            'storedAttachments.*.is_public' => 'boolean',

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

            // Advance Pricing Validation
            'customerPrices' => 'array',
            'customerPrices.*.user_id' => 'required_with:customerPrices.*.price|exists:users,id',
            'customerPrices.*.price' => 'required_with:customerPrices.*.user_id|numeric|min:0',
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
            'sku' => $this->sku,
            'is_sign_up_for_pricing' => $this->is_sign_up_for_pricing,
            'base_price' => $this->base_price,
            'special_price' => $this->special_price,
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

        // Sync Advance Pricing
        $syncData = [];
        if ($this->showAdvancePricing && !empty($this->customerPrices)) {
            foreach ($this->customerPrices as $cp) {
                if (!empty($cp['user_id']) && $cp['price'] !== null) {
                    $syncData[$cp['user_id']] = ['price' => $cp['price']];
                }
            }
        }
        $product->customerPrices()->sync($syncData);

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

        // Update existing attachments
        foreach ($this->storedAttachments as $attData) {
            \App\Models\ProductAttachment::where('id', $attData['id'])->update([
                'name' => $attData['name'],
                'is_public' => $attData['is_public'] ?? false,
            ]);
        }

        // Add new attachments
        foreach ($this->newAttachments as $attData) {
            if ($attData['file']) {
                $originalName = $attData['file']->getClientOriginalName();
                $path = $attData['file']->storeAs('product-attachments/' . $product->id, $originalName, 'public');
                $product->attachments()->create([
                    'name' => $attData['name'],
                    'file_path' => $path,
                    'is_public' => $attData['is_public'] ?? false,
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

    public function deleteAttachment($attachmentId)
    {
        $attachment = \App\Models\ProductAttachment::findOrFail($attachmentId);
        if ($attachment->product_id == $this->productId) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();

            // Refresh stored list
            $this->storedAttachments = Product::findOrFail($this->productId)->attachments()
                ->get()
                ->map(function ($att) {
                    return [
                        'id' => $att->id,
                        'name' => $att->name,
                        'file_path' => $att->file_path,
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
            'customers' => \App\Models\User::role(['customer', 'trade account', 'credit facilities account'])->orderBy('name')->get(),
        ]);
    }
}
