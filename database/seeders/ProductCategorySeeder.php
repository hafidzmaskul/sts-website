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
            [
                'name' => 'Deep Categories 1',
                'slug' => 'deep-categories-1',
            ],
            [
                'name' => 'Deep Categories 2',
                'slug' => 'deep-categories-2',
            ],
        ];

        $userId = \App\Models\User::first()->id ?? 1;

        foreach ($categories as $category) {
            $parent = ProductCategory::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name'], 'created_by' => $userId]
            );

            // Buat child level 1, level 2, dan level 3 jika belum ada
            if ($parent->children()->count() === 0) {
                // Level 1: Child
                $children = ProductCategory::factory()->count(4)->create([
                    'parent_id' => $parent->id,
                    'created_by' => $userId,
                ]);

                foreach ($children as $child) {
                    // Level 2: Grandchild
                    $grandchildren = ProductCategory::factory()->count(3)->create([
                        'parent_id' => $child->id,
                        'created_by' => $userId,
                    ]);

                    foreach ($grandchildren as $grandchild) {
                        // Level 3: Great-grandchild
                        ProductCategory::factory()->count(2)->create([
                            'parent_id' => $grandchild->id,
                            'created_by' => $userId,
                        ]);
                    }
                }
            }
        }
    }
}
