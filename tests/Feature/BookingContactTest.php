<?php

namespace Tests\Feature;

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_computes_server_side_totals_with_promo(): void
    {
        $package = Package::factory()->create(['status' => 'published', 'price' => 1000000]);

        $response = $this->postJson('/api/v1/bookings', [
            'package_id' => $package->id,
            'guest_name' => 'Dian Rahmawati',
            'guest_email' => 'dian@example.id',
            'guest_phone' => '+6281234567890',
            'departure_date' => now()->addMonth()->format('Y-m-d'),
            'adults' => 2,
            'children' => 1,
            'promo_code' => 'KOMODOHEMAT',
        ]);

        $response->assertCreated()
            ->assertJsonPath('subtotal', 2800000)
            ->assertJsonPath('discount', 200000)
            ->assertJsonPath('total', 2600000);
    }

    public function test_booking_rejects_unpublished_package_and_bad_input(): void
    {
        $draft = Package::factory()->create(['status' => 'draft']);

        $this->postJson('/api/v1/bookings', [
            'package_id' => $draft->id,
            'guest_name' => 'X',
            'guest_email' => 'bukan-email',
            'guest_phone' => '1',
            'departure_date' => '2020-01-01',
            'adults' => 0,
        ])->assertStatus(422);
    }

    public function test_contact_message_validates_and_stores(): void
    {
        $this->postJson('/api/v1/contact', [
            'name' => 'Rian Anggara',
            'email' => 'rian@example.id',
            'subject' => 'open-trip',
            'message' => 'Halo, saya ingin tanya jadwal Labuan Bajo bulan depan.',
        ])->assertCreated();

        $this->postJson('/api/v1/contact', [
            'name' => 'R',
            'email' => 'salah',
            'message' => 'pendek',
        ])->assertStatus(422);
    }

    public function test_public_write_endpoints_are_rate_limited(): void
    {
        $payload = [
            'name' => 'Spam Bot',
            'email' => 'spam@example.id',
            'subject' => 'open-trip',
            'message' => 'Pesan spam berulang untuk menguji throttle.',
        ];
        $throttled = false;
        for ($i = 0; $i < 12; $i++) {
            $res = $this->postJson('/api/v1/contact', $payload);
            if ($res->getStatusCode() === 429) {
                $throttled = true;
                break;
            }
        }
        $this->assertTrue($throttled, 'Contact endpoint tidak me-throttle setelah 12 request.');
    }
}
