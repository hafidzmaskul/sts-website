<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->unique()->slug(),
            'content' => '<p>'.$this->faker->paragraph().'</p>',
            'image_path' => 'images/example.jpg',
            'status' => 'published',
            'created_by' => User::factory(),
            'seo_title' => $this->faker->sentence(),
            'seo_description' => $this->faker->sentence(10),
            'seo_keywords' => implode(',', $this->faker->words(3)),
            'published_at' => now(),
        ];
    }
}
