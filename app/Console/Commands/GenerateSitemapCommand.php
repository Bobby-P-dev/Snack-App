<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sitemap:generate')]
#[Description('Generate dan simpan berkas sitemap.xml publik ke direktori public/')]
class GenerateSitemapCommand extends Command
{
    /**
     * Jalankan perintah pembuatan sitemap.
     */
    public function handle(SitemapService $sitemapService): int
    {
        $this->info('Membuat berkas sitemap.xml untuk Padu Kue...');

        try {
            $path = $sitemapService->generateFile();
            $this->info("✓ Berkas sitemap berhasil dibuat di: {$path}");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Gagal membuat sitemap: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
