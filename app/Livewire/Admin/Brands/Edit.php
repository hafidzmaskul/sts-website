<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
use App\Models\PricingFormula;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Edit Brand')]
class Edit extends Component
{
    use WithFileUploads;

    public Brand $brand;

    public $name = '';

    public $slug = '';

    public $image; // New image

    public $existingImage; // Old image

    public $description = '';

    public $website = '';

    public $is_active = true;

    public $sort_order = 0;

    public $pricing_formula_id = null;

    public function mount(Brand $brand)
    {
        $this->brand = $brand;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->existingImage = $brand->image;
        $this->description = $brand->description;
        $this->website = $brand->website;
        $this->is_active = $brand->is_active;
        $this->sort_order = $brand->sort_order;
        $this->pricing_formula_id = $brand->pricing_formula_id;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($this->brand->id)],
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'pricing_formula_id' => 'nullable|exists:pricing_formulas,id',
        ];
    }

    public function update()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'website' => $this->website,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'pricing_formula_id' => $this->pricing_formula_id,
        ];

        if ($this->image) {
            // Delete old image if strictly necessary, but often optional.
            // if ($this->brand->image) { Storage::disk('public')->delete($this->brand->image); }
            $data['image'] = $this->image->store('brands', 'public');
        }

        $this->brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.brands.edit', [
            'pricingFormulas' => PricingFormula::orderBy('label')->get(),
        ]);
    }
}
