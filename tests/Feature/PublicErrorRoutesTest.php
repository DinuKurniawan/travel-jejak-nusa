<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicErrorRoutesTest extends TestCase
{
    /**
     * Test that all defined error routes return HTTP 200 with SPA welcome view.
     */
    public function test_all_public_error_routes_return_successful_spa_response(): void
    {
        $routes = [
            '/404',
            '/500',
            '/403',
            '/maintenance',
            '/offline',
            '/perlu-login',
            '/paket-tidak-tersedia',
            '/booking-kedaluwarsa',
            '/bukti-bayar-ditolak',
            '/upload-gagal',
            '/form-gagal',
            '/status-error',
            '/rute-tidak-dikenal-untuk-uji-404-fallback',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertViewIs('welcome');
        }
    }

    /**
     * Test that all 11 error SVG illustrations exist in public directory.
     */
    public function test_all_error_svg_illustrations_exist_in_public_directory(): void
    {
        $illustrations = [
            'illustration-404.svg',
            'illustration-500.svg',
            'illustration-403.svg',
            'illustration-maintenance.svg',
            'illustration-offline.svg',
            'illustration-auth-required.svg',
            'illustration-package-unavailable.svg',
            'illustration-booking-expired.svg',
            'illustration-payment-rejected.svg',
            'illustration-upload-failed.svg',
            'illustration-form-error.svg',
        ];

        foreach ($illustrations as $file) {
            $fullPath = public_path("images/illustrations/{$file}");
            $this->assertFileExists($fullPath, "Illustration file {$file} must exist in public/images/illustrations/");
            $this->assertStringContainsString('<svg', file_get_contents($fullPath));
        }
    }
}
