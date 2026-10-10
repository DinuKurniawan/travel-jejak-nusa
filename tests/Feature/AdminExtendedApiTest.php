<?php

namespace Tests\Feature;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminExtendedApiTest extends TestCase
{
    use RefreshDatabase;

    private function login(): void
    {
        User::factory()->create(['email' => 'admin@jejaknusa.test']);
        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@jejaknusa.test',
            'password' => 'password',
        ])->assertOk();
    }

    public function test_dashboard_summary_and_audit_list(): void
    {
        $this->login();

        $this->getJson('/api/v1/admin/dashboard')
            ->assertOk()
            ->assertJsonStructure(['bookings_total', 'bookings_pending', 'packages_active', 'messages_unread', 'recent']);

        $this->getJson('/api/v1/admin/audit')->assertOk();
    }

    public function test_settings_save_and_team_crud(): void
    {
        $this->login();

        $this->postJson('/api/v1/admin/settings', [
            'settings' => [
                ['key' => 'site_name', 'value' => 'Jejak Nusa', 'group' => 'general'],
                ['key' => 'whatsapp', 'value' => '6281234567890', 'group' => 'contact'],
            ],
        ])->assertOk();

        $this->getJson('/api/v1/admin/settings?group=contact')
            ->assertOk()
            ->assertJsonFragment(['key' => 'whatsapp']);

        $created = $this->postJson('/api/v1/admin/team', [
            'name' => 'Satria Dewantara',
            'role' => 'Founder',
        ])->assertCreated();
        $id = $created->json('id');

        $this->putJson("/api/v1/admin/team/{$id}", ['role' => 'CEO'])
            ->assertOk()
            ->assertJsonPath('role', 'CEO');

        $this->deleteJson("/api/v1/admin/team/{$id}")->assertNoContent();
        $this->assertDatabaseMissing('team_members', ['id' => $id]);
    }

    public function test_users_roles_and_profile(): void
    {
        $this->login();
        $editor = User::factory()->create(['role' => 'editor']);

        $this->getJson('/api/v1/admin/users')->assertOk();

        $this->putJson("/api/v1/admin/users/{$editor->id}", ['role' => 'admin'])
            ->assertOk()
            ->assertJsonPath('role', 'admin');

        $this->putJson('/api/v1/admin/profile', ['name' => 'Admin Utama'])
            ->assertOk()
            ->assertJsonPath('name', 'Admin Utama');

        $this->getJson('/api/v1/admin/profile')->assertOk();
    }

    public function test_team_factory_exists(): void
    {
        TeamMember::factory()->create();
        $this->assertDatabaseCount('team_members', 1);
    }
}
