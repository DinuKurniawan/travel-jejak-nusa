<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_require_auth(): void
    {
        $this->getJson('/api/v1/admin/packages')->assertUnauthorized();
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_session_login_guards_and_crud_package(): void
    {
        $user = User::factory()->create(['email' => 'admin@jejaknusa.test']);

        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@jejaknusa.test',
            'password' => 'password',
        ])->assertOk();

        $created = $this->postJson('/api/v1/admin/packages', [
            'title' => 'Sailing Komodo 3D2N',
            'location' => 'Labuan Bajo, NTT',
            'price' => 2850000,
            'status' => 'draft',
        ])->assertCreated()->assertJsonPath('slug', 'sailing-komodo-3d2n');

        $id = $created->json('id');
        $this->putJson("/api/v1/admin/packages/{$id}", [
            'status' => 'published',
            'featured' => true,
        ])->assertOk()->assertJsonPath('status', 'published');

        $this->getJson('/api/v1/admin/packages')->assertOk();
        $this->deleteJson("/api/v1/admin/packages/{$id}")->assertNoContent();
        $this->assertDatabaseMissing('packages', ['id' => $id]);

        Package::factory()->create(['status' => 'published']);
        $this->postJson('/api/v1/auth/logout', [])->assertNoContent();
    }
}
