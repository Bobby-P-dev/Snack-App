<?php

namespace Database\Seeders;

use App\Models\CmsSetting;
use App\Models\CmsCarousel;
use App\Models\CmsSocialMedia;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // Default Settings
        $settings = [
            [
                'key' => 'company_name',
                'value' => 'Snack Box',
                'type' => 'text',
                'description' => 'Nama brand atau perusahaan utama'
            ],
            [
                'key' => 'company_description',
                'value' => 'Mitra terpercaya untuk snack box custom dan kue satuan. Kami merakit produk berkualitas dari supplier terbaik.',
                'type' => 'text',
                'description' => 'Deskripsi singkat perusahaan di footer'
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@snackbox.id',
                'type' => 'email',
                'description' => 'Email utama perusahaan'
            ],
            [
                'key' => 'contact_phone',
                'value' => '6285155337991',
                'type' => 'phone',
                'description' => 'Nomor WhatsApp utama (format 62...)'
            ],
            [
                'key' => 'contact_phone_display',
                'value' => '0851-5533-7991',
                'type' => 'text',
                'description' => 'Format nomor telepon untuk ditampilkan'
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jakarta, Indonesia',
                'type' => 'text',
                'description' => 'Alamat singkat perusahaan'
            ],
            [
                'key' => 'hero_title_1',
                'value' => 'Pesan Snack Box',
                'type' => 'text',
                'description' => 'Teks judul utama (baris 1)'
            ],
            [
                'key' => 'hero_title_2',
                'value' => 'Impian Anda',
                'type' => 'text',
                'description' => 'Teks judul utama (baris 2 - warna biru)'
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Kami merakit snack box dari aneka kue pilihan supplier terbaik. Cocok untuk arisan, rapat, acara spesial, atau hadiah untuk orang tersayang.',
                'type' => 'text',
                'description' => 'Sub-judul di homepage'
            ],
            [
                'key' => 'maps_embed_url',
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8227!3d-6.1753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b071%3A0x6b45e4c0f7f7f7f7!2sJakarta!5e0!3m2!1sen!2sid!4v1234567890',
                'type' => 'text',
                'description' => 'URL src untuk iframe Google Maps'
            ],
            [
                'key' => 'operating_hours',
                'value' => json_encode([
                    'Senin - Jumat' => '09:00 - 18:00',
                    'Sabtu' => '10:00 - 16:00',
                    'Minggu' => 'Libur'
                ]),
                'type' => 'json',
                'description' => 'Jam operasional (JSON format)'
            ],
            // NEW RULES SETTINGS
            [
                'key' => 'dp_percentage',
                'value' => '70',
                'type' => 'number',
                'description' => 'Persentase DP yang harus dibayar customer (contoh: 70)'
            ],
            [
                'key' => 'min_order_box',
                'value' => '10',
                'type' => 'number',
                'description' => 'Minimal pemesanan untuk tipe Snack Box Custom'
            ],
            [
                'key' => 'min_order_satuan',
                'value' => '10',
                'type' => 'number',
                'description' => 'Minimal pemesanan untuk tipe Kue Satuan'
            ],
            // NEW WA TEMPLATES
            [
                'key' => 'wa_checkout_template',
                'value' => 'INVOICE PESANAN - {company_name}
No Pesanan: {order_number}
Nama: {customer_name}
WhatsApp: {customer_phone}
Pengambilan: {pickup_date}
Lokasi: {customer_location}
Catatan: {notes}

{order_list}
TOTAL TAGIHAN: Rp {total_amount}
DP ({dp_percentage}%): Rp {dp_amount}
Sisa bayar: Rp {remaining_amount} (dibayar saat pengambilan)',
                'type' => 'textarea',
                'description' => 'Template struk tagihan WhatsApp saat checkout keranjang'
            ]
        ];

        foreach ($settings as $setting) {
            CmsSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // Default Carousels
        $carousels = [
            [
                'title' => 'Koleksi Snack Box Premium',
                'image_url' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=1200&q=80',
                'order' => 1
            ],
            [
                'title' => 'Aneka Kue Pilihan',
                'image_url' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1200&q=80',
                'order' => 2
            ],
            [
                'title' => 'Donat dan Kue Satuan',
                'image_url' => 'https://images.unsplash.com/photo-1605807646983-377bc5a76493?w=1200&q=80',
                'order' => 3
            ],
            [
                'title' => 'Brownies Coklat',
                'image_url' => 'https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?w=1200&q=80',
                'order' => 4
            ]
        ];

        foreach ($carousels as $carousel) {
            CmsCarousel::firstOrCreate(['title' => $carousel['title']], $carousel);
        }

        // Default Social Media
        $socials = [
            [
                'platform' => 'Instagram',
                'url' => 'https://instagram.com/snackbox',
                'icon_name' => 'instagram',
                'order' => 1
            ],
            [
                'platform' => 'Facebook',
                'url' => 'https://facebook.com/snackbox',
                'icon_name' => 'facebook',
                'order' => 2
            ],
            [
                'platform' => 'TikTok',
                'url' => 'https://tiktok.com/@snackbox',
                'icon_name' => 'tiktok',
                'order' => 3
            ]
        ];

        foreach ($socials as $social) {
            CmsSocialMedia::firstOrCreate(['platform' => $social['platform']], $social);
        }
    }
}
