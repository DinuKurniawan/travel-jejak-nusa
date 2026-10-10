<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => fake()->unique()->slug(),
            'location' => fake()->city().', NTT',
            'region' => 'ntt',
            'duration' => '3 Hari 2 Malam',
            'trip_type' => 'open',
            'price' => fake()->numberBetween(900000, 7000000),
            'badge' => 'Best Seller',
            'rating' => fake()->randomFloat(1, 4, 5),
            'reviews_count' => fake()->numberBetween(10, 300),
            'image' => '/images/img-04.jpg',
            'excerpt' => fake()->sentence(),
            'status' => 'published',
            'featured' => false,
        ];
    }
}
