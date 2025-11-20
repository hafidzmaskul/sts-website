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
            'image' => 'images/example.jpg',
            'status' => 'published',
            'user_id' => User::factory(),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->sentence(10),
            'meta_keyword' => implode(',', $this->faker->words(3)),
        ];
    }
}
