<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\SnackBoxPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SitemapAndRobotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_route_returns_valid_xml(): void
    {
        $category = Category::create(['name' => 'Kue Basah', 'slug' => 'kue-basah']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Lemper Ayam',
            'base_price' => 3000,
            'sell_price' => 4500,
            'is_active' => true,
        ]);
        $package = SnackBoxPackage::create([
            'name' => 'Paket Hemat 3 Kue',
            'slug' => 'paket-hemat-3-kue',
            'capacity' => 3,
            'box_price' => 2000,
            'is_active' => true,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));

        $content = $response->getContent();
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $content);
        $this->assertStringContainsString('<loc>https://padukue.store/</loc>', $content);
        $this->assertStringContainsString('<loc>https://padukue.store/shop</loc>', $content);
        $this->assertStringContainsString('<loc>https://padukue.store/snack-box</loc>', $content);
        $this->assertStringContainsString('<loc>https://padukue.store/tracking</loc>', $content);
        $this->assertStringContainsString('<loc>https://padukue.store/about</loc>', $content);
        $this->assertStringContainsString('<loc>https://padukue.store/contact</loc>', $content);
        $this->assertStringContainsString('<loc>https://padukue.store/shop/category/kue-basah</loc>', $content);
        $this->assertStringContainsString("<loc>https://padukue.store/shop/{$product->id}</loc>", $content);
        $this->assertStringContainsString('<loc>https://padukue.store/snack-box/paket-hemat-3-kue</loc>', $content);
    }

    public function test_artisan_sitemap_generate_command(): void
    {
        $testPath = storage_path('app/test_sitemap.xml');

        $exitCode = $this->artisan('sitemap:generate');
        $exitCode->assertSuccessful();

        $this->assertFileExists(public_path('sitemap.xml'));
        $content = File::get(public_path('sitemap.xml'));
        $this->assertStringContainsString('https://padukue.store/', $content);
    }

    public function test_robots_txt_contains_required_directives(): void
    {
        $this->assertFileExists(public_path('robots.txt'));
        $content = File::get(public_path('robots.txt'));

        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Allow: /', $content);
        $this->assertStringContainsString('Disallow: /admin/', $content);
        $this->assertStringContainsString('Disallow: /pdf/', $content);
        $this->assertStringContainsString('Sitemap: https://padukue.store/sitemap.xml', $content);
    }
}
