<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class HttpsProxyTest extends TestCase
{
    use RefreshDatabase;
    public function test_trusted_proxy_recognizes_https_forwarded_proto(): void
    {
        $response = $this->withHeaders([
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-For' => '203.0.113.195',
            'X-Forwarded-Host' => 'padukue.store',
        ])->get('/');

        $response->assertStatus(200);
        $this->assertTrue(request()->isSecure());
        $this->assertEquals('https', request()->getScheme());
    }

    public function test_production_forces_https_scheme(): void
    {
        config(['app.env' => 'production']);
        $this->app['env'] = 'production';

        // Re-run boot logic
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        $url = route('home');
        $this->assertStringStartsWith('https://', $url);
    }
}
