<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'slug' => fake()->unique()->slug(),
            'category' => fake()->randomElement(['Tips Perjalanan', 'Destinasi', 'Kuliner', 'Budaya', 'Panduan']),
            'author' => fake()->name(),
            'read_time' => '5 Menit Baca',
            'image' => '/images/img-13.jpg',
            'excerpt' => fake()->sentence(12),
            'body' => fake()->paragraphs(3, true),
            'status' => 'published',
            'featured' => false,
        ];
    }
}
