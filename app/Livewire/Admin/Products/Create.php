<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\PricingFormula;

use Livewire\Attributes\Title;

#[Title('Create Product')]
class Create extends Component
{
    use WithFileUploads;

    // Form Fields
    public $brand_id = null;
    public $pricing_formula_id = null;
    public $title = '';
    public $slug = '';
    public $is_sign_up_for_pricing = false;
    public $base_price = null;
    public $special_price = null;
    public $status = 'active';

    public $showAdvancePricing = false;
    public $customerPrices = []; // [['user_id' => 1, 'price' => 100]]

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
    public $storedImages = []; // Not used in create but kept for compatibility with form partial

    public function mount()
    {
        // Add one empty image slot by default? No, let user add.
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

    public function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'pricing_formula_id' => 'nullable|exists:pricing_formulas,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')],
            'base_price' => 'nullable|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'selectedCategories' => 'array',

            // New Image Validation
            'newImages.*.image' => 'required|image|max:2048',
            'newImages.*.sequence' => 'required|integer|min:0',

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
            'customerPrices.*.user_id' => 'required_with:customerPrices.*.price|exists:users,id', // Removed distinct for now to avoid complexity, but it's good practice
            'customerPrices.*.price' => 'required_with:customerPrices.*.user_id|numeric|min:0',
        ];
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function addCustomerPrice()
    {
        $this->customerPrices[] = ['user_id' => null, 'price' => null];
    }

    public function removeCustomerPrice($index)
    {
        unset($this->customerPrices[$index]);
        $this->customerPrices = array_values($this->customerPrices);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'brand_id' => $this->brand_id,
            'pricing_formula_id' => $this->pricing_formula_id,
            'title' => $this->title,
            'slug' => $this->slug,
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
            'created_by' => auth()->id(),
        ];

        $product = Product::create($data);
        $product->categories()->sync($this->selectedCategories);

        // Attach Advance Pricing
        if ($this->showAdvancePricing && !empty($this->customerPrices)) {
            foreach ($this->customerPrices as $cp) {
                if (!empty($cp['user_id']) && $cp['price'] !== null) {
                    $product->customerPrices()->attach($cp['user_id'], ['price' => $cp['price']]);
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
            'customers' => \App\Models\User::role('customer')->orderBy('name')->get(),
        ]);
    }
}
