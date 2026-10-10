<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_requires_auth(): void
    {
        Storage::fake('public');

        $this->postJson('/api/v1/admin/uploads', [
            'image' => UploadedFile::fake()->image('paket.jpg'),
        ])->assertUnauthorized();
    }

    public function test_upload_validates_and_stores_image(): void
    {
        Storage::fake('public');
        User::factory()->create(['email' => 'admin@jejaknusa.test']);
        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@jejaknusa.test',
            'password' => 'password',
        ])->assertOk();

        $this->postJson('/api/v1/admin/uploads', [
            'image' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ])->assertStatus(422);

        $res = $this->post('/api/v1/admin/uploads', [
            'image' => UploadedFile::fake()->image('paket.jpg'),
            'folder' => 'packages',
        ])->assertCreated();
        $this->assertStringStartsWith('/storage/packages/', $res->json('url'));
    }
}
