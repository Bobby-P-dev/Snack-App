<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import {
    Heart,
    Sparkles,
    Clock,
    Truck,
    ShieldCheck,
    CheckCircle2,
    Package,
    CakeSlice,
    ArrowRight,
    MapPin,
    Phone,
    Calendar,
    Users,
    Store,
    ChefHat,
    BadgeCheck
} from 'lucide-vue-next';

const props = defineProps({
    categories: { type: Array, default: () => [] },
    snackBoxPackages: { type: Array, default: () => [] },
});

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});

const companyPhone = computed(() => cms.value.contact_phone || '6285155337991');
const companyPhoneDisplay = computed(() => cms.value.contact_phone_display || '085155337991');
const companyAddress = computed(() => cms.value.company_address || 'Jl. Raya Bekasi Timur Regensi No.19, RT.001/RW.010, Burangkeng, Kec. Setu, Kabupaten Bekasi, Jawa Barat 17155');

const waConsultUrl = computed(() => {
    const text = encodeURIComponent(`Halo ${cms.value.company_name || 'Padu Kue'}, saya membaca halaman Tentang Kami dan ingin berkonsultasi mengenai pemesanan snack box / kue untuk acara.`);
    return `https://wa.me/${companyPhone.value}?text=${text}`;
});

// JSON-LD Structured Data untuk SEO Google
const schemaJson = computed(() => JSON.stringify({
    "@context": "https://schema.org",
    "@type": "AboutPage",
    "name": `Tentang Kami - ${cms.value.company_name || 'Padu Kue'}`,
    "description": "Spesialis custom snack box, aneka kue basah tradisional, donat, dan bakery segar di Bekasi.",
    "url": "https://padukue.store/about",
    "mainEntity": {
        "@type": "Bakery",
        "name": cms.value.company_name || "Padu Kue",
        "image": "https://padukue.store/images/padukue-logo.png",
        "telephone": companyPhoneDisplay.value,
        "email": cms.value.contact_email || "admin@padukue.store",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": companyAddress.value,
            "addressLocality": "Setu, Kabupaten Bekasi",
            "addressRegion": "Jawa Barat",
            "postalCode": "17155",
            "addressCountry": "ID"
        },
        "priceRange": "Rp 2.000 - Rp 50.000",
        "servesCuisine": "Indonesian Traditional Cakes, Snack Box Catering, Bakery, Pastry"
    }
}));
</script>

<template>
    <Head>
        <title>Tentang Kami - Padu Kue | Toko Kue & Custom Snack Box Bekasi</title>
        <meta
            name="description"
            content="Mengenal Padu Kue: spesialis aneka kue basah, jajanan pasar premium, dan custom snack box enak di Setu, Bekasi. Siap melayani rapat kantor, syukuran, pengajian, dan catering acara dengan pengiriman tepat waktu."
        />
        <meta
            name="keywords"
            content="kue bekasi, snackbox bekasi, snack box bekasi, custom snack box bekasi, toko kue bekasi, kue basah bekasi, snack box setu bekasi, catering kue bekasi, jajan pasar bekasi, donat bekasi"
        />
        <meta property="og:title" content="Tentang Kami - Padu Kue | Toko Kue & Custom Snack Box Bekasi" />
        <meta
            property="og:description"
            content="Pilihan custom snack box dan aneka kue basah segar untuk berbagai acara di wilayah Bekasi dan sekitarnya."
        />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://padukue.store/about" />
        <meta property="og:image" content="https://padukue.store/images/padukue-logo.png" />
        <component is="script" type="application/ld+json" v-text="schemaJson" />
    </Head>

    <CustomerLayout>
        <div class="py-8 sm:py-12 md:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- ─── BREADCRUMB ─── -->
                <nav class="flex items-center gap-2 text-xs text-brown-500 mb-6 font-medium">
                    <Link href="/" class="hover:text-brand-600 transition-colors">Beranda</Link>
                    <span>/</span>
                    <span class="text-brown-800 font-semibold">Tentang Kami</span>
                </nav>

                <!-- ─── HERO INTRO SECTION ─── -->
                <section class="mb-14 sm:mb-20">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-bold uppercase tracking-wider mb-4 shadow-2xs">
                            <Sparkles class="w-3.5 h-3.5 text-brand-600" />
                            <span>Spesialis Snack Box & Aneka Kue Bekasi</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-brown-900 tracking-tight leading-[1.18] mb-5">
                            Menyajikan Kelezatan Segar untuk Setiap Momen Istimewa Anda.
                        </h1>
                        <p class="text-base sm:text-lg text-brown-700 leading-relaxed font-normal">
                            Berakar di Setu, Kabupaten Bekasi, <strong class="text-brown-900 font-semibold">{{ cms.company_name || 'Padu Kue' }}</strong> hadir sebagai solusi tepercaya bagi keluarga, perkantoran, dan panitia acara yang menginginkan suguhan kue basah lezat, donat lembut, dan custom snack box berkualitas tanpa repot.
                        </p>
                    </div>
                </section>

                <!-- ─── EDITORIAL STORY & STATS (ASYMMETRIC BENTO) ─── -->
                <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 mb-16 sm:mb-24 items-stretch">
                    <!-- Left: Narrative Story Card -->
                    <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-10 border border-cream-200/90 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2.5 text-brand-600 text-xs font-bold uppercase tracking-wider mb-3">
                                <ChefHat class="w-4 h-4" />
                                <span>Cerita Kami</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-brown-900 mb-5 tracking-tight leading-snug">
                                Berawal dari Tradisi Kehangatan Dapur dan Rasa Saling Berbagi
                            </h2>
                            <div class="space-y-4 text-brown-700 text-sm sm:text-base leading-relaxed">
                                <p>
                                    Setiap perayaan — mulai dari arisan keluarga, syukuran aqiqah, pengajian, hingga rapat penting perusahaan — selalu ditandai dengan satu hal: hidangan pembuka yang menyatukan obrolan hangat.
                                </p>
                                <p>
                                    Di Padu Kue, kami memahami bahwa memilih kue untuk puluhan hingga ratusan tamu sering kali membingungkan. Paket snack box di pasaran kerap kaku dengan pilihan yang itu-itu saja.
                                </p>
                                <p>
                                    Karena itulah kami memperkenalkan konsep <strong>Custom Snack Box Fleksibel</strong>: Anda memegang kendali penuh untuk memilih sendiri kombinasi kue manis dan gurih favorit sesuai selera tamu dan anggaran acara, diproduksi segar pada hari pengiriman di wilayah Bekasi.
                                </p>
                            </div>
                        </div>

                        <div class="pt-6 sm:pt-8 mt-6 sm:mt-8 border-t border-cream-200/80 flex flex-wrap items-center gap-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600">
                                    <MapPin class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-xs text-brown-500 font-medium">Basis Operasional</p>
                                    <p class="text-sm font-bold text-brown-900">Setu, Bekasi, Jawa Barat</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                                    <BadgeCheck class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-xs text-brown-500 font-medium">Jaminan Rasa & Mutu</p>
                                    <p class="text-sm font-bold text-brown-900">100% Halal & Fresh Harian</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Quick Highlight Bento Pillars -->
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        <!-- Stat Card 1 -->
                        <div class="bg-gradient-to-br from-brand-500 to-brand-600 rounded-3xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden flex-1">
                            <div class="absolute -right-4 -bottom-4 w-32 h-32 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
                            <Package class="w-8 h-8 text-brand-200 mb-4" />
                            <h3 class="text-xl sm:text-2xl font-bold mb-2">
                                Custom Tanpa Batas Menu Kaku
                            </h3>
                            <p class="text-brand-100 text-sm leading-relaxed mb-4">
                                Susun sendiri isi snack box dari pilihan kue basah, jajanan tradisional, donat aneka topping, hingga pastry renyah.
                            </p>
                            <Link
                                href="/snack-box"
                                class="inline-flex items-center gap-2 text-white font-bold text-xs uppercase tracking-wider hover:translate-x-1 transition-transform"
                            >
                                <span>Coba Rakit Box</span>
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>

                        <!-- Stat Card 2 -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream-200/90 shadow-sm flex-1">
                            <Clock class="w-8 h-8 text-emerald-600 mb-4" />
                            <h3 class="text-xl font-bold text-brown-900 mb-2">
                                Produksi Pagi di Hari H
                            </h3>
                            <p class="text-brown-600 text-sm leading-relaxed">
                                Kue tidak diinapkan atau disimpan berhari-hari. Tim kami memproses dan mengemas pesanan tepat sebelum waktu antar untuk cita rasa terbaik.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- ─── 4 PILAR KEUNGGULAN (BENTO GRID) ─── -->
                <section class="mb-16 sm:mb-24">
                    <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60 inline-block mb-2">
                            Komitmen Layanan
                        </span>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-brown-900 tracking-tight">
                            Mengapa Pelanggan di Bekasi Memilih Padu Kue?
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Pillar 1 -->
                        <div class="bg-white rounded-2xl p-6 border border-cream-200 hover:border-brand-300 transition-all shadow-2xs hover:shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mb-5">
                                <Sparkles class="w-6 h-6" />
                            </div>
                            <h3 class="text-lg font-bold text-brown-900 mb-2">Rakit Box Bebas</h3>
                            <p class="text-brown-600 text-sm leading-relaxed">
                                Kebebasan memilih isi 2 hingga 5 varian kue per kotak. Sesuaikan komposisi rasa manis, gurih, dan asin sesuai preferensi tamu Anda.
                            </p>
                        </div>

                        <!-- Pillar 2 -->
                        <div class="bg-white rounded-2xl p-6 border border-cream-200 hover:border-brand-300 transition-all shadow-2xs hover:shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                                <ShieldCheck class="w-6 h-6" />
                            </div>
                            <h3 class="text-lg font-bold text-brown-900 mb-2">Bahan Berkualitas & Halal</h3>
                            <p class="text-brown-600 text-sm leading-relaxed">
                                Mengutamakan bahan baku alami, higienis, dan tanpa bahan pengawet sintetis. Aman dan nikmat untuk anak-anak hingga orang tua.
                            </p>
                        </div>

                        <!-- Pillar 3 -->
                        <div class="bg-white rounded-2xl p-6 border border-cream-200 hover:border-brand-300 transition-all shadow-2xs hover:shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                                <Package class="w-6 h-6" />
                            </div>
                            <h3 class="text-lg font-bold text-brown-900 mb-2">Kemasan Rapi & Eksklusif</h3>
                            <p class="text-brown-600 text-sm leading-relaxed">
                                Box karton kokoh dengan desain anggun dan tatakan higienis. Memberikan kesan berkelas dan profesional untuk acara formal maupun santai.
                            </p>
                        </div>

                        <!-- Pillar 4 -->
                        <div class="bg-white rounded-2xl p-6 border border-cream-200 hover:border-brand-300 transition-all shadow-2xs hover:shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                                <Truck class="w-6 h-6" />
                            </div>
                            <h3 class="text-lg font-bold text-brown-900 mb-2">Tepat Waktu se-Bekasi</h3>
                            <p class="text-brown-600 text-sm leading-relaxed">
                                Pengiriman terorganisir ke seluruh Setu, Grand Residence, Tambun, Cikarang, Cibitung, Mustika Jaya, hingga pusat Kota Bekasi.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- ─── ACARA YANG COCOK DILAYANI ─── -->
                <section class="bg-white rounded-3xl p-6 sm:p-10 lg:p-12 border border-cream-200/90 shadow-sm mb-16 sm:mb-24">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60 inline-block mb-3">
                                Siap untuk Ragam Acara
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-brown-900 mb-4 tracking-tight">
                                Solusi Catering Snack Box untuk Berbagai Kebutuhan
                            </h2>
                            <p class="text-brown-600 text-sm sm:text-base leading-relaxed mb-6">
                                Kami siap melayani pesanan snack box skala kecil puluhan box hingga pesanan massal ratusan box dengan standar kualitas yang konsisten.
                            </p>
                            <a
                                :href="waConsultUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2.5 px-6 py-3.5 bg-brand-500 hover:bg-brand-600 text-white rounded-xl font-bold text-sm shadow-md shadow-brand-500/20 transition-all"
                            >
                                <Phone class="w-4 h-4" />
                                <span>Konsultasikan Acara Anda</span>
                            </a>
                        </div>

                        <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-cream-50/70 border border-cream-200 flex items-start gap-3.5">
                                <CheckCircle2 class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-bold text-brown-900 text-sm mb-1">Rapat Kantor & Corporate</h4>
                                    <p class="text-brown-600 text-xs leading-relaxed">Meeting, coffee break, seminar, dan workshop instansi pemerintah maupun swasta.</p>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-cream-50/70 border border-cream-200 flex items-start gap-3.5">
                                <CheckCircle2 class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-bold text-brown-900 text-sm mb-1">Pengajian & Tahlilan</h4>
                                    <p class="text-brown-600 text-xs leading-relaxed">Suguhan kue basah tradisional yang disukai para jamaah, praktis dibagikan.</p>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-cream-50/70 border border-cream-200 flex items-start gap-3.5">
                                <CheckCircle2 class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-bold text-brown-900 text-sm mb-1">Arisan & Kumpul Warga</h4>
                                    <p class="text-brown-600 text-xs leading-relaxed">Pilihan donat, bolu, dan jajanan pasar lezat yang membuat suasana semakin akrab.</p>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-cream-50/70 border border-cream-200 flex items-start gap-3.5">
                                <CheckCircle2 class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-bold text-brown-900 text-sm mb-1">Syukuran, Aqiqah & Hajatan</h4>
                                    <p class="text-brown-600 text-xs leading-relaxed">Bingkisan snack box rapi untuk mempermanis momen bahagia bersama keluarga besar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ─── ALUR PEMESANAN MUDAH (NO FORCED LOGIN) ─── -->
                <section class="mb-16 sm:mb-24">
                    <div class="text-center max-w-xl mx-auto mb-10">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-brown-900 tracking-tight mb-2">
                            Pesan Mudah Tanpa Perlu Login
                        </h2>
                        <p class="text-brown-600 text-sm">
                            Nikmati pengalaman belanja modern langsung dari ponsel Anda dalam 3 langkah singkat.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl p-6 border border-cream-200 relative text-center">
                            <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 font-black text-sm flex items-center justify-center mx-auto mb-4 border border-brand-200">
                                1
                            </div>
                            <h3 class="font-bold text-brown-900 text-base mb-2">Pilih Box atau Kue Satuan</h3>
                            <p class="text-brown-600 text-xs sm:text-sm leading-relaxed">
                                Masuk ke menu Custom Snack Box untuk merakit sendiri, atau pilih aneka kue di katalog satuan.
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl p-6 border border-cream-200 relative text-center">
                            <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 font-black text-sm flex items-center justify-center mx-auto mb-4 border border-brand-200">
                                2
                            </div>
                            <h3 class="font-bold text-brown-900 text-base mb-2">Tentukan Tanggal & Alamat</h3>
                            <p class="text-brown-600 text-xs sm:text-sm leading-relaxed">
                                Pilih metode pengantaran ke rumah/kantor di Bekasi atau pengambilan langsung di workshop kami.
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl p-6 border border-cream-200 relative text-center">
                            <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 font-black text-sm flex items-center justify-center mx-auto mb-4 border border-brand-200">
                                3
                            </div>
                            <h3 class="font-bold text-brown-900 text-base mb-2">Konfirmasi Instan via WhatsApp</h3>
                            <p class="text-brown-600 text-xs sm:text-sm leading-relaxed">
                                Rincian pesanan langsung tersusun rapi untuk dikonfirmasi ke admin WhatsApp kami. Cepat dan aman.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- ─── BOTTOM CTA BANNER ─── -->
                <section class="bg-gradient-to-r from-brown-900 via-brown-800 to-brown-900 rounded-3xl p-8 sm:p-12 text-white text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6 shadow-lg mb-12">
                    <div class="max-w-xl">
                        <h2 class="text-2xl sm:text-3xl font-extrabold mb-3 tracking-tight">
                            Siap Menyiapkan Snack Box untuk Acara Anda?
                        </h2>
                        <p class="text-cream-200 text-sm sm:text-base leading-relaxed">
                            Mulai pilih aneka kue favorit sekarang atau hubungi tim kami untuk konsultasi jumlah dan rekomendasi menu.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-3.5 w-full sm:w-auto">
                        <Link
                            href="/snack-box"
                            class="w-full sm:w-auto px-6 py-3.5 bg-brand-500 hover:bg-brand-600 text-white rounded-xl font-bold text-sm text-center shadow-md shadow-brand-500/20 transition-all"
                        >
                            Rakit Snack Box Sekarang
                        </Link>
                        <Link
                            href="/contact"
                            class="w-full sm:w-auto px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-semibold text-sm text-center transition-all"
                        >
                            Hubungi Kontak Kami
                        </Link>
                    </div>
                </section>
            </div>
        </div>

        <Footer />
    </CustomerLayout>
</template>
