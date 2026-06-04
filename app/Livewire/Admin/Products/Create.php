<?php

namespace App\Livewire\Admin\Products;

use App\Enums\PricingFormulaType;
use App\Models\Brand;
use App\Models\PricingFormula;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Create Product')]
class Create extends Component
{
    use WithFileUploads;

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

    public $customerPrices = []; // [['user_id' => 1, 'price' => 100]]

    public $quantityPrices = []; // [['quantity' => 1, 'price' => 50]]

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

    public $storedImages = []; // Not used in create but kept for compatibility with form partial

    // Attachments
    public $newAttachments = []; // Array of ['file' => file, 'name' => string, 'key' => unique_id]

    public $storedAttachments = []; // Not used in create but kept for compatibility

    public function mount()
    {
        // Add one empty image slot by default? No, let user add.
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
                $formula = $brand->pricingFormula;
                $this->brandFormulaLabel = $formula->label;

                if ($formula->discount !== null) {
                    $this->brandFormulaType = 'Discount';
                    $this->brandFormulaValue = $formula->discount;
                } elseif ($formula->margin !== null) {
                    $this->brandFormulaType = 'Margin';
                    $this->brandFormulaValue = $formula->margin;
                } elseif ($formula->markup !== null) {
                    $this->brandFormulaType = 'Markup';
                    $this->brandFormulaValue = $formula->markup;
                }
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

        $cost = $this->cost ? (float) $this->cost : null;
        $rsp = $this->rsp ? (float) $this->rsp : null;

        // Auto mode: use override or brand formula
        if ($this->override_enabled && $this->override_method && $this->override_value) {
            $method = PricingFormulaType::tryFrom($this->override_method);
            $value = (float) $this->override_value;

            if (! $method || $value <= 0) {
                return null;
            }

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
        } else {
            // Look up brand formula
            $brand = $this->brand_id ? Brand::with('pricingFormula')->find($this->brand_id) : null;
            $formula = $brand?->pricingFormula;
            if (! $formula) {
                return null;
            }

            if ($rsp && $formula->discount !== null && $formula->discount > 0) {
                return round($rsp * (1 - $formula->discount / 100), 2);
            }

            if ($cost && $formula->margin !== null && $formula->margin > 0 && $formula->margin < 100) {
                return round($cost / (1 - $formula->margin / 100), 2);
            }

            if ($cost && $formula->markup !== null && $formula->markup > 0) {
                return round($cost * (1 + $formula->markup / 100), 2);
            }

            return null;
        }
    }

    public function addImage()
    {
        $this->newImages[] = [
            'image' => null,
            'sequence' => count($this->newImages) + 1,
            'key' => Str::random(10),
        ];
    }

    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
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

    public function addQuantityPrice()
    {
        $this->quantityPrices[] = [
            'quantity' => null,
            'price' => null,
        ];
    }

    public function removeQuantityPrice($index)
    {
        unset($this->quantityPrices[$index]);
        $this->quantityPrices = array_values($this->quantityPrices);
    }

    public function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'pricing_formula_id' => 'nullable|exists:pricing_formulas,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')],
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

            // New Image Validation
            'newImages.*.image' => 'required|image|max:2048',
            'newImages.*.sequence' => 'required|integer|min:0',

            // Attachment Validation
            'newAttachments.*.file' => 'required|file|max:10240', // 10MB max
            'newAttachments.*.name' => 'required|string|max:255',
            'newAttachments.*.is_public' => 'boolean',

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

            // Quantity Pricing Validation
            'quantityPrices' => 'array',
            'quantityPrices.*.quantity' => 'required|integer|min:1',
            'quantityPrices.*.price' => 'required|numeric|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
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
            'created_by' => auth()->id(),
        ];

        $product = Product::create($data);
        $product->categories()->sync($this->selectedCategories);

        // Attach Advance Pricing
        if ($this->showAdvancePricing && ! empty($this->customerPrices)) {
            foreach ($this->customerPrices as $cp) {
                if (! empty($cp['user_id']) && $cp['price'] !== null) {
                    $product->customerPrices()->attach($cp['user_id'], ['price' => $cp['price']]);
                }
            }
        }

        // Attach Quantity Pricing
        if (! empty($this->quantityPrices)) {
            foreach ($this->quantityPrices as $qp) {
                if ($qp['quantity'] !== null && $qp['price'] !== null) {
                    $product->quantityPrices()->create([
                        'quantity' => $qp['quantity'],
                        'price' => $qp['price'],
                    ]);
                }
            }
        }

        foreach ($this->newImages as $imgData) {
            if ($imgData['image']) {
                $path = $imgData['image']->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'sequence' => $imgData['sequence'],
                ]);
            }
        }

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

        session()->flash('success', 'Product created successfully.');

        return redirect()->route('admin.products.index');
    }

    public function deleteImage($id)
    {
        // No-op for create
    }

    public function render()
    {
        return view('livewire.admin.products.create', [
            'categories' => ProductCategory::orderBy('parent_id')->orderBy('name')->get(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'pricingFormulas' => PricingFormula::orderBy('label')->get(),
            'customers' => \App\Models\User::role(['customer', 'trade account', 'credit facilities account'])->orderBy('name')->get(),
            'pricingMethods' => PricingFormulaType::cases(),
        ]);
    }
}
