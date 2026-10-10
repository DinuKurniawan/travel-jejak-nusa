<?php

namespace Database\Factories;

use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'image' => '/images/img-41.jpg',
            'region' => 'NTT',
            'tag' => 'Bahari',
            'photographer' => '@jejaknusa',
            'description' => fake()->sentence(),
            'status' => 'published',
        ];
    }
}
