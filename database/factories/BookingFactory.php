<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(1000000, 8000000);

        return [
            'package_id' => Package::factory(),
            'guest_name' => fake()->name(),
            'guest_email' => fake()->safeEmail(),
            'guest_phone' => fake()->phoneNumber(),
            'departure_date' => fake()->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
            'subtotal' => $subtotal,
            'discount' => 0,
            'total' => $subtotal,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ];
    }
}
