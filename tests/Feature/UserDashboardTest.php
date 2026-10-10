<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_user_dashboard(): void
    {
        $this->getJson('/api/v1/me/dashboard')->assertUnauthorized();
    }

    public function test_login_with_email_and_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);

        $this->postJson('/api/v1/auth/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertOk()->assertJsonPath('email', $admin->email);
        $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'password'])
            ->assertOk()->assertJsonPath('email', $user->email);
        $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertStatus(422);
    }

    public function test_dashboard_returns_scoped_stats_and_bookings(): void
    {
        $user = User::factory()->create();
        Booking::factory()->create(['user_id' => $user->id, 'guest_email' => $user->email]);
        Booking::factory()->create(); // milik orang lain, tidak boleh bocor

        $response = $this->actingAs($user)->getJson('/api/v1/me/dashboard');

        $response->assertOk()->assertJsonStructure(['stats' => ['finished', 'pending_payment', 'vouchers'], 'bookings']);
        $this->assertCount(1, $response->json('bookings'));
    }

    public function test_booking_detail_cancel_and_reschedule(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id, 'guest_email' => $user->email]);

        $this->actingAs($user)->getJson("/api/v1/me/bookings/{$booking->code}")->assertOk();

        $this->actingAs($user)->postJson("/api/v1/me/bookings/{$booking->code}/cancel", ['reason' => 'Jadwal bentrok'])
            ->assertOk()->assertJsonPath('status', 'cancelled');

        $this->actingAs($user)->postJson("/api/v1/me/bookings/{$booking->code}/reschedule", ['departure_date' => now()->addMonth()->format('Y-m-d')])
            ->assertOk()->assertJsonPath('status', 'pending');
    }

    public function test_wishlist_toggle_and_travelers_crud(): void
    {
        $user = User::factory()->create();
        $package = Package::factory()->create();

        $this->actingAs($user)->postJson('/api/v1/me/wishlist/toggle', ['package_id' => $package->id])
            ->assertCreated()->assertJsonPath('saved', true);
        $this->actingAs($user)->getJson('/api/v1/me/wishlist')->assertOk();
        $this->actingAs($user)->postJson('/api/v1/me/wishlist/toggle', ['package_id' => $package->id])
            ->assertOk()->assertJsonPath('saved', false);

        $created = $this->actingAs($user)->postJson('/api/v1/me/travelers', [
            'name' => 'Sinta Ayu', 'id_number' => '3174000001', 'phone' => '0813000001', 'tag' => 'Pasangan',
        ])->assertCreated();
        $this->actingAs($user)->getJson('/api/v1/me/travelers')->assertOk();
        $this->actingAs($user)->deleteJson("/api/v1/me/travelers/{$created->json('id')}")->assertNoContent();
    }

    public function test_voucher_claim_review_profile_and_password(): void
    {
        $user = User::factory()->create();
        Voucher::create(['code' => 'KOMODOHEMAT', 'title' => 'Diskon Komodo', 'discount' => 200000]);

        $this->actingAs($user)->postJson('/api/v1/me/vouchers/claim', ['code' => 'komodohemat'])->assertCreated();
        $this->actingAs($user)->getJson('/api/v1/me/vouchers')->assertOk();

        $this->actingAs($user)->postJson('/api/v1/me/reviews', ['rating' => 5, 'review' => 'Luar biasa!'])
            ->assertCreated();
        $this->actingAs($user)->getJson('/api/v1/me/reviews')->assertOk();

        $this->actingAs($user)->putJson('/api/v1/me/profile', ['name' => 'Nama Baru', 'email' => $user->email])
            ->assertOk()->assertJsonPath('name', 'Nama Baru');

        $this->actingAs($user)->putJson('/api/v1/me/password', ['current' => 'password', 'next' => 'rahasia-baru-123'])
            ->assertOk();
    }
}
