<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Tampilkan sitemap XML aplikasi.
     */
    public function index(SitemapService $sitemapService): Response
    {
        $xml = $sitemapService->generateXml();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
