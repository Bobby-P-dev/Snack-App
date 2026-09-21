<?php

namespace Database\Seeders;

use App\Models\CmsSetting;
use App\Models\CmsCarousel;
use App\Models\CmsSocialMedia;
use App\Services\CmsService;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ─── 1. CMS SETTINGS ────────────────────────────────────────────────────────
        $settings = [
            // Branding & Company Profile
            [
                'key' => 'company_name',
                'value' => 'Padu Kue',
                'type' => 'text',
                'description' => 'Nama brand atau toko utama'
            ],
            [
                'key' => 'company_tagline',
                'value' => 'Kombinasi Kue & Custom Snack Box Pilihan Terbaik',
                'type' => 'text',
                'description' => 'Tagline resmi toko'
            ],
            [
                'key' => 'company_description',
                'value' => 'Padu Kue adalah mitra terpercaya untuk pemesanan custom snack box, aneka kue basah & kering, donat, dan catering pastry. Kami menghadirkan sajian lezat dan higienis dari dapur pilihan untuk melengkapi setiap momen berharga Anda.',
                'type' => 'textarea',
                'description' => 'Deskripsi profil bisnis di footer dan meta tag'
            ],

            // Kontak & Alamat
            [
                'key' => 'contact_email',
                'value' => 'halo@padukue.store',
                'type' => 'email',
                'description' => 'Email resmi Padu Kue'
            ],
            [
                'key' => 'contact_phone',
                'value' => '6281234567890',
                'type' => 'phone',
                'description' => 'Nomor WhatsApp admin untuk checkout & konsultasi (format 62...)'
            ],
            [
                'key' => 'contact_phone_display',
                'value' => '0812-3456-7890',
                'type' => 'text',
                'description' => 'Nomor telepon format tampilan ramah baca'
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jl. Boulevard Raya No. 88, Bekasi, Jawa Barat 17144',
                'type' => 'text',
                'description' => 'Alamat operasional toko'
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Boulevard Raya No. 88, Bekasi, Jawa Barat 17144',
                'type' => 'text',
                'description' => 'Alamat yang ditampilkan di footer website'
            ],
            [
                'key' => 'operating_hours',
                'value' => json_encode([
                    'Senin - Jumat' => '07:30 - 17:30 WIB',
                    'Sabtu' => '08:00 - 15:00 WIB',
                    'Minggu' => 'Khusus Pengiriman Pesanan H-1'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'type' => 'json',
                'description' => 'Jadwal jam operasional toko'
            ],
            [
                'key' => 'maps_embed_url',
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8227!3d-6.1753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b071%3A0x6b45e4c0f7f7f7f7!2sJakarta!5e0!3m2!1sen!2sid!4v1234567890',
                'type' => 'url',
                'description' => 'URL embed Google Maps untuk halaman kontak'
            ],

            // Homepage Hero Section
            [
                'key' => 'badge',
                'value' => 'Spesialis Custom Snack Box & Aneka Kue',
                'type' => 'text',
                'description' => 'Teks badge kecil di atas judul hero landing page'
            ],
            [
                'key' => 'hero_title_1',
                'value' => 'Kelezatan Autentik untuk Setiap Momen Spesial',
                'type' => 'text',
                'description' => 'Judul utama di hero landing page'
            ],
            [
                'key' => 'hero_title_2',
                'value' => 'Padu Kue',
                'type' => 'text',
                'description' => 'Highlight judul baris kedua'
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Rakit paket snack box impian Anda dengan puluhan varian kue lezat berkualitas. Praktis, higienis, dan pas untuk meeting kantor, arisan, syukuran, hingga seminar.',
                'type' => 'textarea',
                'description' => 'Sub-judul deskripsi di hero landing page'
            ],
            [
                'key' => 'propotition1',
                'value' => 'Bahan Segar & Higienis',
                'type' => 'text',
                'description' => 'Keunggulan 1 pada hero section'
            ],
            [
                'key' => 'propotition2',
                'value' => 'Custom Box Fleksibel',
                'type' => 'text',
                'description' => 'Keunggulan 2 pada hero section'
            ],
            [
                'key' => 'propotition3',
                'value' => 'Pengiriman Tepat Waktu',
                'type' => 'text',
                'description' => 'Keunggulan 3 pada hero section'
            ],

            // Aturan Pemesanan & Pembayaran (Order Rules)
            [
                'key' => 'dp_percentage',
                'value' => '70',
                'type' => 'number',
                'description' => 'Persentase uang muka (DP) dari total pesanan (contoh: 70)'
            ],
            [
                'key' => 'min_order_box',
                'value' => '10',
                'type' => 'number',
                'description' => 'Minimal pemesanan untuk tipe Custom Snack Box (box)'
            ],
            [
                'key' => 'min_order_satuan',
                'value' => '10',
                'type' => 'number',
                'description' => 'Minimal total pemesanan untuk tipe Kue Satuan (pcs)'
            ],

            // Social Media Direct Links (Digunakan di Footer.vue)
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/padukue',
                'type' => 'url',
                'description' => 'Tautan profil Instagram resmi'
            ],
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/padukue',
                'type' => 'url',
                'description' => 'Tautan halaman Facebook resmi'
            ],
            [
                'key' => 'social_tiktok',
                'value' => 'https://tiktok.com/@padukue',
                'type' => 'url',
                'description' => 'Tautan akun TikTok resmi'
            ],

            // Template Pesan WhatsApp Checkout & Layanan Bantuan
            [
                'key' => 'wa_checkout_template',
                'value' => "INVOICE PESANAN - {company_name}\n"
                    . "No Pesanan: {order_number}\n"
                    . "Nama: {customer_name}\n"
                    . "WhatsApp: {customer_phone}\n"
                    . "Pengambilan: {pickup_date}\n"
                    . "Lokasi: {customer_location}\n"
                    . "Catatan: {notes}\n\n"
                    . "{order_list}\n\n"
                    . "TOTAL TAGIHAN: Rp {total_amount}\n"
                    . "DP ({dp_percentage}%): Rp {dp_amount}\n"
                    . "Sisa bayar: Rp {remaining_amount} (dibayar saat pengambilan)\n\n"
                    . "Download Invoice PDF Anda di sini:\n{invoice_url}\n\n"
                    . "Pantau status pesanan Anda di sini:\n{tracking_url}",
                'type' => 'textarea',
                'description' => 'Template struk tagihan WhatsApp otomatis saat checkout'
            ],
            [
                'key' => 'wa_consultation_message',
                'value' => 'Halo {company_name}, saya ingin konsultasi mengenai pemesanan snack box dan aneka kue.',
                'type' => 'textarea',
                'description' => 'Format teks WhatsApp untuk floating button & konsultasi beranda'
            ],
            [
                'key' => 'wa_tracking_help_message',
                'value' => 'Halo Admin {company_name}, saya ingin menanyakan status pesanan dengan nomor {order_number}.',
                'type' => 'textarea',
                'description' => 'Format teks bantuan WhatsApp di halaman detail tracking pesanan'
            ],
            [
                'key' => 'wa_tracking_not_found_message',
                'value' => 'Halo Admin {company_name}, saya kesulitan menemukan nomor pesanan saya di website. Mohon dibantu.',
                'type' => 'textarea',
                'description' => 'Format teks bantuan WhatsApp saat nomor pesanan tidak ditemukan'
            ],
        ];

        foreach ($settings as $setting) {
            CmsSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // ─── 2. CMS CAROUSELS (HERO SLIDERS) ─────────────────────────────────────────
        $carousels = [
            [
                'title' => 'Custom Snack Box Eksklusif',
                'description' => 'Pilih sendiri aneka kue manis dan asin favorit Anda dalam kemasan box higienis dan elegan.',
                'image_url' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=1200&q=80',
                'link_url' => '/snack-box',
                'order' => 1,
                'duration' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Aneka Kue Basah & Jajanan Tradisional',
                'description' => 'Resep otentik Nusantara dengan cita rasa khas yang selalu menjadi primadona setiap acara.',
                'image_url' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1200&q=80',
                'link_url' => '/shop',
                'order' => 2,
                'duration' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Donat Empuk & Aneka Pastry Kekinian',
                'description' => 'Tekstur lembut dengan varian glaze istimewa, teman pas untuk coffee break dan meeting kantor.',
                'image_url' => 'https://images.unsplash.com/photo-1605807646983-377bc5a76493?w=1200&q=80',
                'link_url' => '/shop',
                'order' => 3,
                'duration' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Fudgy Brownies & Cookies Renyah',
                'description' => 'Dibuat dari cokelat hitam pilihan dengan aroma butter menggugah selera untuk bingkisan spesial.',
                'image_url' => 'https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?w=1200&q=80',
                'link_url' => '/shop',
                'order' => 4,
                'duration' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($carousels as $carousel) {
            CmsCarousel::updateOrCreate(
                ['title' => $carousel['title']],
                $carousel
            );
        }

        // ─── 3. CMS SOCIAL MEDIA ───────────────────────────────────────────────────
        $socials = [
            [
                'platform' => 'Instagram',
                'url' => 'https://instagram.com/padukue',
                'icon_name' => 'instagram',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'platform' => 'Facebook',
                'url' => 'https://facebook.com/padukue',
                'icon_name' => 'facebook',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'platform' => 'TikTok',
                'url' => 'https://tiktok.com/@padukue',
                'icon_name' => 'tiktok',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'platform' => 'WhatsApp',
                'url' => 'https://wa.me/6281234567890',
                'icon_name' => 'whatsapp',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($socials as $social) {
            CmsSocialMedia::updateOrCreate(
                ['platform' => $social['platform']],
                $social
            );
        }

        // Clear CMS Cache
        try {
            app(CmsService::class)->clearCache();
        } catch (\Throwable $e) {
            // Ignore if cache driver not accessible during seeding
        }
    }
}
