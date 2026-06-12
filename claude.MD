# Project Overview

Sistem pemesanan "Snack Box Custom" dan "Kue Satuan" berbasis web. Model bisnis menggunakan sistem aggregator (assembler) di mana admin merakit kue dari berbagai suplier menjadi _snack box_. Tidak ada payment gateway; checkout diarahkan ke WhatsApp admin menggunakan format teks khusus.

# Tech Stack & Infrastructure

- **Backend:** Laravel (versi terbaru)
- **Frontend:** Vue 3 (Composition API, `<script setup>`)
- **Bridge:** Inertia.js
- **Styling:** Tailwind CSS
- **Authentication:** Laravel Breeze (Sanctum) berbasis session/cookie.
- **Database:** PostgreSQL / MySQL
- **Caching & Session:** Redis
- **File Storage:** MinIO (S3-Compatible Object Storage)
- **Deployment:** Docker (VPS)

# Essential Libraries

- **Role & Permission:** `spatie/laravel-permission`
- **Audit & Logging:** `spatie/laravel-activitylog` (Untuk tracking perubahan harga dan status order)
- **Image Processing:** `intervention/image` (Untuk kompresi foto produk sebelum masuk ke S3)
- **Vue Utilities:** `@vueuse/core` (Untuk fungsi reaktif frontend seperti click-outside, dll)

# Backend Architecture (Clean Architecture)

AI harus mematuhi pemisahan tanggung jawab (Separation of Concerns) secara ketat:

1. **Controllers (`app/Http/Controllers/`):** Harus sangat tipis (_thin controller_). Hanya bertugas menerima `Request`, memanggil `Service`, dan me-return `Inertia::render()` atau API Resource.
2. **Form Requests (`app/Http/Requests/`):** Semua validasi input dari klien (baik dari Admin maupun Customer) wajib diletakkan di sini.
3. **Services (`app/Services/`):** Jantung logika bisnis. Perhitungan harga modal vs harga jual, pemrosesan keranjang kustom, dan pembentukan string tagihan WhatsApp diletakkan di sini.
4. **Repositories (`app/Repositories/`):** Bertanggung jawab atas semua query Eloquent ORM. Service dilarang memanggil model Eloquent secara langsung. Wajib menggunakan Eager Loading untuk relasi.
5. **Resources (`app/Http/Resources/`):** Memfilter data dari database sebelum dikirimkan ke frontend (Inertia Props). Wajib menyembunyikan kolom sensitif (seperti `base_price` suplier) dari view Customer.
6. **Helpers (`app/Helpers/`):** Fungsi utilitas statis murni.

# Frontend Architecture (Pragmatic / Feature-Based)

Dilarang menggunakan Atomic Design murni untuk menghindari over-engineering. Gunakan struktur direktori berikut untuk Vue + Inertia:

1. **Pages (`resources/js/Pages/`):** - Pisahkan menjadi dua ranah utama: `/Pages/Admin/` (Manajemen Master Data) dan `/Pages/Customer/` (Katalog Menu & Keranjang).
2. **Layouts (`resources/js/Layouts/`):**
    - Wajib membuat 2 layout terpisah: `AdminLayout.vue` (Sidebar, Header Admin) dan `CustomerLayout.vue` (Navbar, Footer, Mobile-Friendly).
3. **UI Components (`resources/js/Components/UI/`):**
    - Komponen visual murni yang dapat digunakan ulang (Reusable / "Dumb" Components). Contoh: `Button.vue`, `Modal.vue`, `TextInput.vue`. Tidak boleh mengandung logika bisnis.
4. **Domain Components (`resources/js/Components/Domain/`):**
    - Komponen spesifik fitur ("Smart" Components). Contoh: `ProductCard.vue`, `CartSummary.vue`, `SupplierSelect.vue`.

# Database Schema Architecture

Panduan untuk Migration dan Model Eloquent:

- `users`: id, name, email, password, created_at, updated_at (Hapus kolom 'role' manual, gunakan tabel bawaan Spatie Permission)
- `roles` & `permissions`: (Bawaan spatie/laravel-permission)
- `activity_log`: (Bawaan spatie/laravel-activitylog)
- `suppliers`: id, name, phone, address, daily_capacity, created_at, updated_at
- `categories`: id, name, slug, created_at, updated_at
- `products`: id, supplier_id (FK), category_id (FK), name, base_price, sell_price, image_url, is_active, created_at, updated_at (Model ini wajib di-track oleh Activitylog)
- `orders`: id, order_number (unique), customer_name, customer_phone, pickup_date, total_amount, dp_amount, status (enum: pending, confirmed, completed), created_at, updated_at (Model ini wajib di-track oleh Activitylog)
- `order_items`: id, order_id (FK), product_id (FK), box_group_id (nullable, penanda kardus kustom), quantity, price_at_order, type (enum: kustom_box, satuan), created_at, updated_at

# Infrastructure Rules

## Redis

- `CACHE_DRIVER` dan `SESSION_DRIVER` wajib menggunakan Redis.
- Repositories untuk data publik (seperti katalog produk customer) wajib di-cache menggunakan `Cache::tags()` atau `Cache::remember()`.

## MinIO / S3

- Semua unggahan file (foto produk) wajib dikompresi menggunakan Intervention Image terlebih dahulu, lalu diunggah menggunakan facade `Storage::disk('s3')`.
- Konfigurasi S3 di Laravel harus mendukung endpoint MinIO (`AWS_USE_PATH_STYLE_ENDPOINT=true`).

# Output Format Logika Checkout (WhatsApp Plain Text)

Ketika customer melakukan checkout, `OrderService` harus men-generate URL wa.me dengan format pesan URL-encoded berikut:

INVOICE PESANAN - [Nama Usaha]
No Pesanan: {order_number}
Nama: {customer_name}
Pengambilan: {pickup_date}

[1] Pesanan Snack Box (Custom)

- Jumlah: {qty} Box
- Isi per Box: {item_1}, {item_2}...
- Harga per Box: Rp {box_price}
  Subtotal: Rp {subtotal_box}

[2] Pesanan Kue Satuan

- {qty}x {item_name} @ Rp {price}
  Subtotal: Rp {subtotal_satuan}

TOTAL TAGIHAN: Rp {total_amount}
DP (50%): Rp {dp_amount}
