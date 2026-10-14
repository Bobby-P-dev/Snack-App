<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import {
    ArrowRight,
    MapPin,
    Phone,
    BadgeCheck,
    Package
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
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&display=swap"
            rel="stylesheet"
        />
        <component is="script" type="application/ld+json" v-text="schemaJson" />
    </Head>

    <CustomerLayout>
        <div class="bg-cream-100">
            <div class="max-w-4xl mx-auto px-5 sm:px-8 py-14 sm:py-20">

                <!-- ─── HERO ─── -->
                <header class="mb-14 sm:mb-20">
                    <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-6">
                        <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                        Tentang Padu Kue
                    </p>
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-[3.4rem] text-brown-900 leading-[1.1] tracking-tight mb-6">
                        Kue segar, dibuat dengan ketulusan.
                    </h1>
                    <p class="text-base sm:text-lg text-brown-600 leading-relaxed max-w-2xl">
                        Berakar di Setu, Kabupaten Bekasi, <span class="text-brown-900 font-semibold">{{ cms.company_name || 'Padu Kue' }}</span>
                        lahir dari dapur rumahan yang percaya satu hal sederhana: kue terbaik adalah kue yang dibuat
                        segar di hari ia disajikan.
                    </p>

                    <!-- credential strip -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-12 pt-8 border-t border-brown-900/10">
                        <div class="flex items-start gap-3.5">
                            <MapPin class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm font-semibold text-brown-900">Setu, Kabupaten Bekasi</p>
                                <p class="text-[13px] text-brown-600 mt-0.5">Basis operasional kami</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3.5">
                            <BadgeCheck class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm font-semibold text-brown-900">100% Halal & Fresh Harian</p>
                                <p class="text-[13px] text-brown-600 mt-0.5">Tanpa pengawet, dibuat pagi hari</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3.5">
                            <Package class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm font-semibold text-brown-900">Custom Snack Box</p>
                                <p class="text-[13px] text-brown-600 mt-0.5">Rakit sendiri isi box sesukamu</p>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- ─── CERITA KAMI ─── -->
                <section class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-16 sm:mb-24">
                    <div class="md:col-span-4">
                        <p class="md:sticky md:top-24 flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700">
                            <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                            Cerita kami
                        </p>
                    </div>
                    <div class="md:col-span-8 space-y-5 text-[15px] sm:text-base text-brown-600 leading-[1.85]">
                        <p>
                            Setiap perayaan — arisan keluarga, syukuran aqiqah, pengajian, hingga rapat penting perusahaan —
                            selalu ditandai dengan satu hal: hidangan pembuka yang menyatukan obrolan hangat.
                        </p>
                        <p>
                            Kami memahami betul bahwa memilih kue untuk puluhan hingga ratusan tamu sering kali
                            membingungkan. Paket snack box di pasaran kerap kaku, dengan pilihan yang itu-itu saja.
                        </p>
                        <p>
                            Karena itulah kami memperkenalkan konsep
                            <span class="text-brown-900 font-semibold">Custom Snack Box Fleksibel</span>:
                            Anda memegang kendali penuh memilih kombinasi kue manis dan gurih favorit —
                            sesuai selera tamu dan anggaran acara — diproduksi segar pada hari pengiriman.
                        </p>
                    </div>
                </section>

                <!-- ─── PRINSIP KAMI ─── -->
                <section class="mb-16 sm:mb-24">
                    <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-2">
                        <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                        Yang kami pegang
                    </p>
                    <h2 class="font-display text-3xl sm:text-4xl text-brown-900 tracking-tight mb-10">
                        Empat hal yang tidak kami tawar.
                    </h2>

                    <div class="border-t border-brown-900/10">
                        <div class="grid grid-cols-12 gap-4 py-7 border-b border-brown-900/10">
                            <span class="col-span-2 sm:col-span-1 font-display text-lg text-brand-600">01</span>
                            <div class="col-span-10 sm:col-span-11">
                                <h3 class="text-base sm:text-lg font-semibold text-brown-900 mb-1.5">Kebebasan memilih isi box</h3>
                                <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed max-w-2xl">
                                    Susun sendiri 2 hingga 5 varian kue per kotak — dari kue basah tradisional,
                                    jajanan pasar, donat aneka topping, hingga pastry renyah.
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 py-7 border-b border-brown-900/10">
                            <span class="col-span-2 sm:col-span-1 font-display text-lg text-brand-600">02</span>
                            <div class="col-span-10 sm:col-span-11">
                                <h3 class="text-base sm:text-lg font-semibold text-brown-900 mb-1.5">Bahan yang jujur</h3>
                                <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed max-w-2xl">
                                    Bahan baku alami dan higienis, tanpa pengawet sintetis.
                                    Aman dinikmati anak-anak hingga orang tua.
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 py-7 border-b border-brown-900/10">
                            <span class="col-span-2 sm:col-span-1 font-display text-lg text-brand-600">03</span>
                            <div class="col-span-10 sm:col-span-11">
                                <h3 class="text-base sm:text-lg font-semibold text-brown-900 mb-1.5">Kemasan yang pantas</h3>
                                <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed max-w-2xl">
                                    Box karton kokoh dengan tatakan higienis — rapi dan berkelas,
                                    untuk acara formal maupun santai.
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 py-7 border-b border-brown-900/10">
                            <span class="col-span-2 sm:col-span-1 font-display text-lg text-brand-600">04</span>
                            <div class="col-span-10 sm:col-span-11">
                                <h3 class="text-base sm:text-lg font-semibold text-brown-900 mb-1.5">Tepat waktu, se-Bekasi</h3>
                                <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed max-w-2xl">
                                    Pengiriman terorganisir ke Setu, Tambun, Cikarang, Cibitung,
                                    Mustika Jaya, hingga pusat Kota Bekasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ─── UNTUK SETIAP ACARA ─── -->
                <section class="mb-16 sm:mb-24">
                    <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-2">
                        <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                        Untuk setiap acara
                    </p>
                    <h2 class="font-display text-3xl sm:text-4xl text-brown-900 tracking-tight mb-4">
                        Dari rapat kantor sampai hajatan.
                    </h2>
                    <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed max-w-2xl mb-10">
                        Melayani pesanan puluhan hingga ratusan box dengan standar kualitas yang konsisten.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10">
                        <div class="py-5 border-t border-brown-900/10">
                            <h3 class="text-[15px] font-semibold text-brown-900 mb-1">Rapat Kantor & Corporate</h3>
                            <p class="text-sm text-brown-600 leading-relaxed">Meeting, coffee break, seminar, dan workshop instansi maupun swasta.</p>
                        </div>
                        <div class="py-5 border-t border-brown-900/10">
                            <h3 class="text-[15px] font-semibold text-brown-900 mb-1">Pengajian & Tahlilan</h3>
                            <p class="text-sm text-brown-600 leading-relaxed">Kue basah tradisional favorit jamaah, praktis dibagikan.</p>
                        </div>
                        <div class="py-5 border-t border-brown-900/10">
                            <h3 class="text-[15px] font-semibold text-brown-900 mb-1">Arisan & Kumpul Warga</h3>
                            <p class="text-sm text-brown-600 leading-relaxed">Donat, bolu, dan jajanan pasar yang bikin suasana makin akrab.</p>
                        </div>
                        <div class="py-5 border-t border-brown-900/10 sm:border-b">
                            <h3 class="text-[15px] font-semibold text-brown-900 mb-1">Syukuran, Aqiqah & Hajatan</h3>
                            <p class="text-sm text-brown-600 leading-relaxed">Bingkisan snack box rapi untuk momen bahagia bersama keluarga besar.</p>
                        </div>
                    </div>
                </section>

                <!-- ─── CARA MEMESAN ─── -->
                <section class="mb-16 sm:mb-24">
                    <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-2">
                        <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                        Cara memesan
                    </p>
                    <h2 class="font-display text-3xl sm:text-4xl text-brown-900 tracking-tight mb-10">
                        Tiga langkah, tanpa perlu login.
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                        <div class="border-t-2 border-brand-600 pt-5">
                            <span class="font-display text-4xl text-brand-200">1</span>
                            <h3 class="text-[15px] font-semibold text-brown-900 mt-3 mb-1.5">Pilih box atau kue satuan</h3>
                            <p class="text-sm text-brown-600 leading-relaxed">Rakit sendiri snack box impianmu, atau pilih aneka kue di katalog satuan.</p>
                        </div>
                        <div class="border-t-2 border-brand-200 pt-5">
                            <span class="font-display text-4xl text-brand-200">2</span>
                            <h3 class="text-[15px] font-semibold text-brown-900 mt-3 mb-1.5">Tentukan tanggal & alamat</h3>
                            <p class="text-sm text-brown-600 leading-relaxed">Antar ke rumah/kantor di Bekasi, atau ambil langsung di workshop kami.</p>
                        </div>
                        <div class="border-t-2 border-brand-200 pt-5">
                            <span class="font-display text-4xl text-brand-200">3</span>
                            <h3 class="text-[15px] font-semibold text-brown-900 mt-3 mb-1.5">Konfirmasi via WhatsApp</h3>
                            <p class="text-sm text-brown-600 leading-relaxed">Rincian pesanan tersusun rapi, tinggal kirim ke admin kami. Cepat dan aman.</p>
                        </div>
                    </div>
                </section>

                <!-- ─── CTA ─── -->
                <section class="text-center py-14 sm:py-16 border-t border-brown-900/10">
                    <h2 class="font-display text-3xl sm:text-4xl text-brown-900 tracking-tight mb-4">
                        Siap menyiapkan snack box untuk acaramu?
                    </h2>
                    <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed max-w-xl mx-auto mb-8">
                        Mulai pilih aneka kue favorit sekarang, atau konsultasikan dulu jumlah dan menu dengan tim kami.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <Link
                            href="/snack-box"
                            class="inline-flex items-center gap-2 px-7 py-3.5 bg-brand-600 hover:bg-brand-700 text-white rounded-full font-semibold text-sm transition-colors"
                        >
                            <span>Rakit Snack Box</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                        <a
                            :href="waConsultUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-7 py-3.5 border border-brown-900/20 hover:border-brown-900/40 text-brown-900 rounded-full font-semibold text-sm transition-colors"
                        >
                            <Phone class="w-4 h-4" />
                            <span>Chat WhatsApp</span>
                        </a>
                    </div>
                </section>

            </div>
        </div>

        <Footer />
    </CustomerLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
}
</style>
