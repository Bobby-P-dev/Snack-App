<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\SnackBoxPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class SitemapService
{
    /**
     * Dapatkan base URL untuk sitemap.
     */
    public function getBaseUrl(): string
    {
        $baseUrl = config('app.url');

        // Jika bernilai default http://localhost atau kosong, gunakan domain produksi resmi
        if (empty($baseUrl) || str_contains($baseUrl, 'localhost')) {
            return 'https://padukue.store';
        }

        return rtrim($baseUrl, '/');
    }

    /**
     * Generate format XML sitemap lengkap dengan halaman statis dan dinamis.
     */
    public function generateXml(): string
    {
        $baseUrl = $this->getBaseUrl();
        $urls = [];

        // 1. Halaman Utama & Fitur Inti Publik
        $urls[] = [
            'loc' => $baseUrl . '/',
            'lastmod' => Carbon::now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        $urls[] = [
            'loc' => $baseUrl . '/shop',
            'lastmod' => Carbon::now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];

        $urls[] = [
            'loc' => $baseUrl . '/snack-box',
            'lastmod' => Carbon::now()->format('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.9',
        ];

        $urls[] = [
            'loc' => $baseUrl . '/tracking',
            'lastmod' => Carbon::now()->format('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.5',
        ];

        // 2. Kategori Produk Aktif
        $categories = Category::all();
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => $baseUrl . '/shop/category/' . urlencode($category->slug),
                'lastmod' => $category->updated_at ? $category->updated_at->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // 3. Produk Satuan Aktif
        $products = Product::where('is_active', true)->get();
        foreach ($products as $product) {
            $urls[] = [
                'loc' => $baseUrl . '/shop/' . $product->id,
                'lastmod' => $product->updated_at ? $product->updated_at->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // 4. Paket Snack Box Aktif
        $packages = SnackBoxPackage::where('is_active', true)->get();
        foreach ($packages as $package) {
            $urls[] = [
                'loc' => $baseUrl . '/snack-box/' . urlencode($package->slug),
                'lastmod' => $package->updated_at ? $package->updated_at->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        return $this->formatXml($urls);
    }

    /**
     * Konversi daftar URL ke string XML standar sitemaps.org.
     */
    protected function formatXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $item) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            if (!empty($item['lastmod'])) {
                $xml .= '    <lastmod>' . $item['lastmod'] . "</lastmod>\n";
            }
            if (!empty($item['changefreq'])) {
                $xml .= '    <changefreq>' . $item['changefreq'] . "</changefreq>\n";
            }
            if (!empty($item['priority'])) {
                $xml .= '    <priority>' . $item['priority'] . "</priority>\n";
            }
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>' . "\n";

        return $xml;
    }

    /**
     * Simpan hasil XML ke berkas statis (contoh: public/sitemap.xml).
     */
    public function generateFile(?string $filePath = null): string
    {
        $filePath = $filePath ?? public_path('sitemap.xml');
        $xml = $this->generateXml();
        File::put($filePath, $xml);

        return $filePath;
    }
}
