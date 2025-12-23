<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

use App\Models\PricingFormula;

use Livewire\Attributes\Title;

#[Title('Create Brand')]
class Create extends Component
{
    use WithFileUploads;

    public $name = '';
    public $slug = '';
    public $image;
    public $description = '';
    public $website = '';
    public $is_active = true;
    public $sort_order = 0;
    public $pricing_formula_id = null;

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:brands,slug',
        'image' => 'required|image|max:2048', // 2MB Max
        'description' => 'nullable|string',
        'website' => 'nullable|url|max:255',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'pricing_formula_id' => 'nullable|exists:pricing_formulas,id',
    ];

    public function save()
    {
        $this->validate();

        $imagePath = $this->image->store('brands', 'public');

        Brand::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $imagePath,
            'description' => $this->description,
            'website' => $this->website,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'pricing_formula_id' => $this->pricing_formula_id,
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function render()
    {
        return view('livewire.admin.brands.create', [
            'pricingFormulas' => PricingFormula::orderBy('label')->get(),
        ]);
    }
}
