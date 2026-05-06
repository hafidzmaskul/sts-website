<?php

namespace App\Livewire\Admin\Products;

use App\Enums\PricingFormulaType;
use App\Models\Brand;
use App\Models\PricingFormula;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    public $pricing_mode = 'manual';

    public $cost = null;

    public $rsp = null;

    public $special_price = null;

    public $special_price_start = null;

    public $special_price_end = null;

    public $override_enabled = false;

    public $override_method = null;

    public $override_value = null;

    public $status = 'active';

    public $showAdvancePricing = false;

    public $customerPrices = [];

    public $is_exclusive = false;

    public $is_cta = false;

    // Brand formula info for display
    public $brandFormulaLabel = null;

    public $brandFormulaType = null;

    public $brandFormulaValue = null;

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
        $this->pricing_mode = $product->pricing_mode ?? 'manual';
        $this->cost = $product->cost;
        $this->rsp = $product->rsp;
        $this->base_price = $product->base_price;
        $this->special_price = $product->special_price;
        $this->special_price_start = $product->special_price_start?->format('Y-m-d\TH:i');
        $this->special_price_end = $product->special_price_end?->format('Y-m-d\TH:i');
        $this->override_enabled = $product->override_enabled;
        $this->override_method = $product->override_method;
        $this->override_value = $product->override_value;
        $this->status = $product->status;
        $this->is_exclusive = $product->is_exclusive;
        $this->is_cta = $product->is_cta;
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

        // Load Advance Pricing
        $existingCustomerPrices = $product->customerPrices()->get();
        if ($existingCustomerPrices->isNotEmpty()) {
            $this->showAdvancePricing = true;
            $this->customerPrices = $existingCustomerPrices->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'price' => $user->pivot->price,
                ];
            })->toArray();
        }

        // Load brand formula info
        if ($this->brand_id) {
            $brand = Brand::with('pricingFormula')->find($this->brand_id);
            if ($brand?->pricingFormula) {
                $this->brandFormulaLabel = $brand->pricingFormula->label;
                $this->brandFormulaType = $brand->pricingFormula->type->label();
                $this->brandFormulaValue = $brand->pricingFormula->value;
            }
        }
    }

    /**
     * When brand changes, load the brand's default pricing formula info.
     */
    public function updatedBrandId($value): void
    {
        $this->brandFormulaLabel = null;
        $this->brandFormulaType = null;
        $this->brandFormulaValue = null;

        if ($value) {
            $brand = Brand::with('pricingFormula')->find($value);
            if ($brand?->pricingFormula) {
                $this->brandFormulaLabel = $brand->pricingFormula->label;
                $this->brandFormulaType = $brand->pricingFormula->type->label();
                $this->brandFormulaValue = $brand->pricingFormula->value;
            }
        }
    }

    /**
     * Compute a live preview price based on current form inputs.
     */
    #[Computed]
    public function previewPrice(): ?float
    {
        if ($this->pricing_mode === 'manual') {
            return $this->base_price ? (float) $this->base_price : null;
        }

        // Auto mode: use override or brand formula
        if ($this->override_enabled && $this->override_method && $this->override_value) {
            $method = PricingFormulaType::tryFrom($this->override_method);
            $value = (float) $this->override_value;
        } else {
            $brand = $this->brand_id ? Brand::with('pricingFormula')->find($this->brand_id) : null;
            $formula = $brand?->pricingFormula;
            if (! $formula) {
                return null;
            }
            $method = $formula->type;
            $value = (float) $formula->value;
        }

        if (! $method || $value <= 0) {
            return null;
        }

        $cost = $this->cost ? (float) $this->cost : null;
        $rsp = $this->rsp ? (float) $this->rsp : null;

        return match ($method) {
            PricingFormulaType::MarginPercent => $cost && $value < 100
                ? round($cost / (1 - $value / 100), 2)
                : null,
            PricingFormulaType::MarkupPercent => $cost
                ? round($cost * (1 + $value / 100), 2)
                : null,
            PricingFormulaType::DiscountPercent => $rsp
                ? round($rsp * (1 - $value / 100), 2)
                : null,
        };
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
            'pricing_mode' => 'required|in:auto,manual',
            'cost' => 'nullable|numeric|min:0',
            'rsp' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'special_price_start' => 'nullable|date',
            'special_price_end' => 'nullable|date|after_or_equal:special_price_start',
            'override_enabled' => 'boolean',
            'override_method' => 'nullable|required_if:override_enabled,true',
            'override_value' => 'nullable|required_if:override_enabled,true|numeric|min:0',
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
            'is_cta' => 'boolean',
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
            'pricing_mode' => $this->pricing_mode,
            'cost' => $this->cost,
            'rsp' => $this->rsp,
            'base_price' => $this->base_price,
            'special_price' => $this->special_price,
            'special_price_start' => $this->special_price_start,
            'special_price_end' => $this->special_price_end,
            'override_enabled' => $this->override_enabled,
            'override_method' => $this->override_enabled ? $this->override_method : null,
            'override_value' => $this->override_enabled ? $this->override_value : null,
            'status' => $this->status,
            'is_exclusive' => $this->is_exclusive,
            'is_cta' => $this->is_cta,
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
        if ($this->showAdvancePricing && ! empty($this->customerPrices)) {
            foreach ($this->customerPrices as $cp) {
                if (! empty($cp['user_id']) && $cp['price'] !== null) {
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
                $path = $attData['file']->storeAs('product-attachments/'.$product->id, $originalName, 'public');
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
            'pricingMethods' => PricingFormulaType::cases(),
        ]);
    }
}
