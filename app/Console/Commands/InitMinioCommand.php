<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('minio:init')]
#[Description('Verifikasi dan inisialisasi object storage MinIO / S3 untuk produk dan carousel')]
class InitMinioCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memeriksa konfigurasi S3/MinIO...');

        $bucket = config('filesystems.disks.s3.bucket');
        $endpoint = config('filesystems.disks.s3.endpoint');

        $this->line("Endpoint: {$endpoint}");
        $this->line("Bucket  : {$bucket}");

        try {
            $testKey = '.minio-health-check';
            \Illuminate\Support\Facades\Storage::disk('s3')->put($testKey, 'ok');

            if (!\Illuminate\Support\Facades\Storage::disk('s3')->exists($testKey)) {
                $this->error('Gagal memverifikasi penulisan berkas ke MinIO.');
                return self::FAILURE;
            }

            \Illuminate\Support\Facades\Storage::disk('s3')->delete($testKey);
            $this->info('✓ Koneksi dan hak tulis MinIO/S3 terverifikasi dengan sukses!');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Gagal terhubung ke MinIO/S3: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
