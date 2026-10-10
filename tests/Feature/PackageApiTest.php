<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_package_list_only_published_with_filters(): void
    {
        Package::factory()->create(['status' => 'published', 'region' => 'ntt', 'trip_type' => 'open', 'title' => 'Komodo Sailing']);
        Package::factory()->create(['status' => 'draft', 'title' => 'Draft Hidden']);

        $this->getJson('/api/v1/packages')
            ->assertOk()
            ->assertJsonFragment(['title' => 'Komodo Sailing'])
            ->assertJsonMissing(['title' => 'Draft Hidden']);

        $this->getJson('/api/v1/packages?search=komodo&region=ntt&type=open')
            ->assertOk()
            ->assertJsonFragment(['title' => 'Komodo Sailing']);
    }

    public function test_featured_and_show_by_slug(): void
    {
        $pkg = Package::factory()->create(['status' => 'published', 'featured' => true]);

        $this->getJson('/api/v1/packages/featured')
            ->assertOk()
            ->assertJsonFragment(['slug' => $pkg->slug]);

        $this->getJson("/api/v1/packages/{$pkg->slug}")
            ->assertOk()
            ->assertJsonFragment(['title' => $pkg->title]);
    }

    public function test_public_catalog_lists(): void
    {
        Article::factory()->create(['status' => 'published']);
        Testimonial::factory()->create(['status' => 'approved']);
        Faq::factory()->create(['status' => 'published']);
        GalleryItem::factory()->create(['status' => 'published']);
        TeamMember::factory()->create(['status' => 'active']);
        SiteSetting::factory()->create(['key' => 'whatsapp', 'group' => 'contact']);

        $this->getJson('/api/v1/articles')->assertOk();
        $this->getJson('/api/v1/testimonials')->assertOk();
        $this->getJson('/api/v1/faqs')->assertOk();
        $this->getJson('/api/v1/gallery')->assertOk();
        $this->getJson('/api/v1/team')->assertOk();
        $this->getJson('/api/v1/settings?group=contact')->assertOk()->assertJsonFragment(['key' => 'whatsapp']);
    }
}
