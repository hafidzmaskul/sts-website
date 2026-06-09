<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        return [
            'name' => ucwords($name),
            'slug' => \Illuminate\Support\Str::slug($name) . '-' . uniqid(),
            'seo_title' => ucwords($name) . ' Category',
            'seo_description' => $this->faker->sentence(),
            'created_by' => 1, // Assuming admin user ID is 1
        ];
    }
}
