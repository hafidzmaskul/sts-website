<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Discontinued',
                'slug' => 'discontinued',
            ],
            [
                'name' => 'Most Needed',
                'slug' => 'most-needed',
            ],
        ];

        $userId = \App\Models\User::first()->id ?? 1;

        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name'], 'created_by' => $userId]
            );
        }
    }
}
