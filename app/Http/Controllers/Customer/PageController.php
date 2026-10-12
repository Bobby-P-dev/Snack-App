<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SnackBoxPackage;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Tampilkan halaman Tentang Kami (About Us).
     */
    public function about(): Response
    {
        $categories = Category::all();
        $snackBoxPackages = SnackBoxPackage::where('is_active', true)
            ->orderBy('capacity')
            ->get();

        return Inertia::render('Customer/About', [
            'categories' => $categories,
            'snackBoxPackages' => $snackBoxPackages,
        ]);
    }

    /**
     * Tampilkan halaman Kontak Kami (Contact Us).
     */
    public function contact(): Response
    {
        // Wilayah layanan antar utama di area Bekasi
        $deliveryAreas = [
            'Kecamatan Setu',
            'Grand Residence City',
            'Bekasi Timur Regensi (BTR)',
            'Mustika Jaya & Mustikasari',
            'Tambun Selatan & Tambun Utara',
            'Cibitung & Wanajaya',
            'Cikarang Barat & Cikarang Pusat',
            'Bekasi Timur & Duren Jaya',
            'Bekasi Barat & Galaxy',
            'Rawalumbu & Narogong',
            'Bantar Gebang',
        ];

        return Inertia::render('Customer/Contact', [
            'deliveryAreas' => $deliveryAreas,
        ]);
    }
}
