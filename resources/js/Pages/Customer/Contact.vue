<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import {
    Phone,
    Mail,
    MapPin,
    Clock,
    Send,
    MessageCircle,
    Truck,
    HelpCircle,
    CheckCircle2,
    Calendar,
    Users,
    Package,
    Store,
    ExternalLink
} from 'lucide-vue-next';

const props = defineProps({
    deliveryAreas: { type: Array, default: () => [] },
});

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});

const companyPhone = computed(() => cms.value.contact_phone || '6285155337991');
const companyPhoneDisplay = computed(() => cms.value.contact_phone_display || '085155337991');
const companyEmail = computed(() => cms.value.contact_email || 'admin@padukue.store');
const operationalAddress = computed(() => cms.value.contact_address || 'Jl. Grand Residence Boulevard No.122, Cijengkol, Kec. Setu, Kabupaten Bekasi, Jawa Barat 17320');
const storeAddress = computed(() => cms.value.company_address || 'Jl. Raya Bekasi Timur Regensi No.19, RT.001/RW.010, Burangkeng, Kec. Setu, Kabupaten Bekasi, Jawa Barat 17155');
const operatingHours = computed(() => cms.value.operating_hours || '24 Jam (Setiap Hari)');

// Form State untuk Interactive WhatsApp Consultation
const purpose = ref('snackbox');
const senderName = ref('');
const eventDate = ref('');
const boxEstimate = ref('');
const messageNotes = ref('');

const purposeLabels = {
    snackbox: 'Konsultasi Custom Snack Box Acara',
    satuan: 'Pemesanan Kue Satuan / Donat Jumlah Banyak',
    ongkir: 'Cek Ongkir & Jadwal Pengiriman Wilayah Bekasi',
    other: 'Pertanyaan / Kerja Sama Lainnya',
};

const sendWhatsAppMessage = () => {
    const cleanPhone = companyPhone.value.replace(/[^0-9]/g, '');
    const greeting = `Halo ${cms.value.company_name || 'Padu Kue'}, saya ingin konsultasi pemesanan.`;
    
    let text = `${greeting}\n\n`;
    text += `*Keperluan:* ${purposeLabels[purpose.value] || purpose.value}\n`;
    if (senderName.value.trim()) {
        text += `*Nama:* ${senderName.value.trim()}\n`;
    }
    if (eventDate.value) {
        text += `*Rencana Tanggal Acara:* ${eventDate.value}\n`;
    }
    if (boxEstimate.value.trim()) {
        text += `*Perkiraan Jumlah:* ${boxEstimate.value.trim()} box/pcs\n`;
    }
    if (messageNotes.value.trim()) {
        text += `*Catatan/Permintaan:* ${messageNotes.value.trim()}\n`;
    }
    text += `\nMohon info ketersediaan menu dan rekomendasi terbaiknya ya. Terima kasih!`;

    const waUrl = `https://wa.me/${cleanPhone}?text=${encodeURIComponent(text)}`;
    window.open(waUrl, '_blank');
};

const gmapsWorkshopUrl = computed(() => {
    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(operationalAddress.value)}`;
});

const gmapsStoreUrl = computed(() => {
    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(storeAddress.value)}`;
});

// JSON-LD Structured Data untuk SEO Google
const schemaJson = computed(() => JSON.stringify({
    "@context": "https://schema.org",
    "@type": "Bakery",
    "name": cms.value.company_name || "Padu Kue",
    "image": "https://padukue.store/images/padukue-logo.png",
    "telephone": companyPhoneDisplay.value,
    "email": companyEmail.value,
    "url": "https://padukue.store/contact",
    "address": [
        {
            "@type": "PostalAddress",
            "streetAddress": storeAddress.value,
            "addressLocality": "Setu, Kabupaten Bekasi",
            "addressRegion": "Jawa Barat",
            "postalCode": "17155",
            "addressCountry": "ID"
        },
        {
            "@type": "PostalAddress",
            "streetAddress": operationalAddress.value,
            "addressLocality": "Setu, Kabupaten Bekasi",
            "addressRegion": "Jawa Barat",
            "postalCode": "17320",
            "addressCountry": "ID"
        }
    ],
    "openingHoursSpecification": [
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            "opens": "00:00",
            "closes": "23:59"
        }
    ],
    "areaServed": [
        { "@type": "AdministrativeArea", "name": "Setu, Kabupaten Bekasi" },
        { "@type": "AdministrativeArea", "name": "Cikarang, Kabupaten Bekasi" },
        { "@type": "AdministrativeArea", "name": "Tambun, Kabupaten Bekasi" },
        { "@type": "AdministrativeArea", "name": "Kota Bekasi" }
    ],
    "priceRange": "Rp 2.000 - Rp 50.000"
}));
</script>

<template>
    <Head>
        <title>Kontak Kami - Padu Kue | Pesan Snack Box & Kue Basah Bekasi</title>
        <meta
            name="description"
            content="Hubungi Padu Kue via WhatsApp 085155337991 untuk konsultasi custom snack box, pemesanan aneka kue basah acara, cek ongkir dan jadwal pengantaran di Setu, Tambun, Cikarang & seluruh Bekasi."
        />
        <meta
            name="keywords"
            content="kontak padu kue, whatsapp toko kue bekasi, pesan snack box bekasi, alamat toko kue setu bekasi, order kue snack box bekasi, catering kue bekasi, nomor telepon padu kue"
        />
        <meta property="og:title" content="Kontak Kami - Padu Kue | Pesan Snack Box & Kue Basah Bekasi" />
        <meta
            property="og:description"
            content="Konsultasikan kebutuhan snack box dan aneka kue acara Anda langsung dengan admin Padu Kue Bekasi via WhatsApp."
        />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://padukue.store/contact" />
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
                    <span class="text-brown-800 font-semibold">Kontak Kami</span>
                </nav>

                <!-- ─── HERO INTRO SECTION ─── -->
                <section class="mb-12 sm:mb-16">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-bold uppercase tracking-wider mb-4 shadow-2xs">
                            <MessageCircle class="w-3.5 h-3.5 text-brand-600" />
                            <span>Layanan Pelanggan & Konsultasi Acara</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-brown-900 tracking-tight leading-[1.18] mb-4">
                            Hubungi Padu Kue Bekasi
                        </h1>
                        <p class="text-base sm:text-lg text-brown-700 leading-relaxed font-normal">
                            Punya rencana acara syukuran, rapat kantor, atau kumpul keluarga? Tim kami siap membantu menyusun pilihan snack box terbaik sesuai selera tamu dan anggaran Anda.
                        </p>
                    </div>
                </section>

                <!-- ─── MAIN 2-COLUMN SECTION: INFO KONTAK & FORM WA ─── -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-16 sm:mb-24">
                    <!-- Left Column: Informasi Kontak & Alamat (5 cols) -->
                    <div class="lg:col-span-5 space-y-6">
                        <!-- Direct WhatsApp Box -->
                        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-3xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 w-32 h-32 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                                    Fast Response
                                </span>
                                <Phone class="w-6 h-6 text-emerald-200" />
                            </div>
                            <h2 class="text-xl sm:text-2xl font-bold mb-1">WhatsApp Resmi</h2>
                            <p class="text-emerald-100 text-xs sm:text-sm mb-4">
                                Saluran tercepat untuk konsultasi menu, jadwal kirim, dan konfirmasi pesanan.
                            </p>
                            <div class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-5">
                                {{ companyPhoneDisplay }}
                            </div>
                            <a
                                :href="`https://wa.me/${companyPhone}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 w-full py-3.5 bg-white text-emerald-800 rounded-xl font-bold text-sm hover:bg-emerald-50 transition-colors shadow-xs"
                            >
                                <MessageCircle class="w-4 h-4 text-emerald-600" />
                                <span>Buka Chat WhatsApp Sekarang</span>
                            </a>
                        </div>

                        <!-- Info Cards (Email & Hours) -->
                        <div class="bg-white rounded-3xl p-6 border border-cream-200/90 shadow-sm space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shrink-0 mt-0.5">
                                    <Clock class="w-5 h-5" />
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-brown-500 uppercase tracking-wider mb-1">Jam Operasional</h3>
                                    <p class="text-sm sm:text-base font-bold text-brown-900">{{ operatingHours }}</p>
                                    <p class="text-xs text-brown-600 mt-0.5">Pemesanan online & konsultasi chat selalu terbuka.</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-cream-100 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shrink-0 mt-0.5">
                                    <Mail class="w-5 h-5" />
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-brown-500 uppercase tracking-wider mb-1">Email Resmi</h3>
                                    <a :href="`mailto:${companyEmail}`" class="text-sm sm:text-base font-bold text-brown-900 hover:text-brand-600 transition-colors break-all">
                                        {{ companyEmail }}
                                    </a>
                                    <p class="text-xs text-brown-600 mt-0.5">Untuk penawaran kerja sama instansi & invoice resmi.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Address Bento Card -->
                        <div class="bg-white rounded-3xl p-6 border border-cream-200/90 shadow-sm space-y-5">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <MapPin class="w-4 h-4 text-brand-600" />
                                        <h3 class="text-xs font-bold text-brown-500 uppercase tracking-wider">Alamat Workshop / Pengambilan</h3>
                                    </div>
                                    <a :href="gmapsWorkshopUrl" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-brand-600 hover:underline inline-flex items-center gap-1">
                                        <span>Maps</span>
                                        <ExternalLink class="w-3 h-3" />
                                    </a>
                                </div>
                                <p class="text-sm font-semibold text-brown-800 leading-relaxed">
                                    {{ operationalAddress }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-cream-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <Store class="w-4 h-4 text-brand-600" />
                                        <h3 class="text-xs font-bold text-brown-500 uppercase tracking-wider">Alamat Toko</h3>
                                    </div>
                                    <a :href="gmapsStoreUrl" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-brand-600 hover:underline inline-flex items-center gap-1">
                                        <span>Maps</span>
                                        <ExternalLink class="w-3 h-3" />
                                    </a>
                                </div>
                                <p class="text-sm font-semibold text-brown-800 leading-relaxed">
                                    {{ storeAddress }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Interactive WhatsApp Consultation Launcher (7 cols) -->
                    <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-10 border border-cream-200/90 shadow-sm">
                        <div class="flex items-center gap-2.5 text-brand-600 text-xs font-bold uppercase tracking-wider mb-2">
                            <Send class="w-4 h-4" />
                            <span>Formulir Cepat via WhatsApp</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-brown-900 tracking-tight mb-2">
                            Kirim Rincian Acara Anda
                        </h2>
                        <p class="text-sm text-brown-600 mb-6 leading-relaxed">
                            Pilih jenis kebutuhan di bawah ini. Sistem kami akan memformat pesan rapi sehingga admin WhatsApp kami dapat langsung memberikan perkiraan harga dan rekomendasi kue terbaik.
                        </p>

                        <form @submit.prevent="sendWhatsAppMessage" class="space-y-5">
                            <!-- Purpose Selector -->
                            <div>
                                <label class="block text-xs font-bold text-brown-800 uppercase tracking-wider mb-2">
                                    Kebutuhan Anda
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <button
                                        type="button"
                                        @click="purpose = 'snackbox'"
                                        :class="[
                                            'p-3.5 rounded-xl border text-left transition-all text-xs sm:text-sm font-semibold flex items-center gap-2.5 cursor-pointer',
                                            purpose === 'snackbox'
                                                ? 'bg-brand-50 border-brand-500 text-brand-700 shadow-2xs'
                                                : 'bg-white border-cream-200 text-brown-700 hover:bg-cream-50'
                                        ]"
                                    >
                                        <Package class="w-4 h-4 text-brand-600 shrink-0" />
                                        <span>Custom Snack Box</span>
                                    </button>

                                    <button
                                        type="button"
                                        @click="purpose = 'satuan'"
                                        :class="[
                                            'p-3.5 rounded-xl border text-left transition-all text-xs sm:text-sm font-semibold flex items-center gap-2.5 cursor-pointer',
                                            purpose === 'satuan'
                                                ? 'bg-brand-50 border-brand-500 text-brand-700 shadow-2xs'
                                                : 'bg-white border-cream-200 text-brown-700 hover:bg-cream-50'
                                        ]"
                                    >
                                        <Store class="w-4 h-4 text-brand-600 shrink-0" />
                                        <span>Kue Satuan / Donat</span>
                                    </button>

                                    <button
                                        type="button"
                                        @click="purpose = 'ongkir'"
                                        :class="[
                                            'p-3.5 rounded-xl border text-left transition-all text-xs sm:text-sm font-semibold flex items-center gap-2.5 cursor-pointer',
                                            purpose === 'ongkir'
                                                ? 'bg-brand-50 border-brand-500 text-brand-700 shadow-2xs'
                                                : 'bg-white border-cream-200 text-brown-700 hover:bg-cream-50'
                                        ]"
                                    >
                                        <Truck class="w-4 h-4 text-brand-600 shrink-0" />
                                        <span>Cek Ongkir & Jadwal</span>
                                    </button>

                                    <button
                                        type="button"
                                        @click="purpose = 'other'"
                                        :class="[
                                            'p-3.5 rounded-xl border text-left transition-all text-xs sm:text-sm font-semibold flex items-center gap-2.5 cursor-pointer',
                                            purpose === 'other'
                                                ? 'bg-brand-50 border-brand-500 text-brand-700 shadow-2xs'
                                                : 'bg-white border-cream-200 text-brown-700 hover:bg-cream-50'
                                        ]"
                                    >
                                        <MessageCircle class="w-4 h-4 text-brand-600 shrink-0" />
                                        <span>Pertanyaan Lain</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Sender Name -->
                            <div>
                                <label for="senderName" class="block text-xs font-bold text-brown-800 uppercase tracking-wider mb-1.5">
                                    Nama Anda <span class="text-brown-400 font-normal">(opsional)</span>
                                </label>
                                <input
                                    id="senderName"
                                    type="text"
                                    v-model="senderName"
                                    placeholder="Contoh: Ibu Rina / Bpk. Fajar"
                                    class="w-full px-4 py-3 rounded-xl border border-cream-300 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-sm sm:text-base text-brown-900 placeholder:text-brown-400 bg-white"
                                />
                            </div>

                            <!-- Event Date & Box Estimate (2 cols) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="eventDate" class="block text-xs font-bold text-brown-800 uppercase tracking-wider mb-1.5">
                                        Tanggal Acara <span class="text-brown-400 font-normal">(opsional)</span>
                                    </label>
                                    <input
                                        id="eventDate"
                                        type="date"
                                        v-model="eventDate"
                                        class="w-full px-4 py-3 rounded-xl border border-cream-300 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-sm sm:text-base text-brown-900 bg-white"
                                    />
                                </div>
                                <div>
                                    <label for="boxEstimate" class="block text-xs font-bold text-brown-800 uppercase tracking-wider mb-1.5">
                                        Perkiraan Jumlah <span class="text-brown-400 font-normal">(opsional)</span>
                                    </label>
                                    <input
                                        id="boxEstimate"
                                        type="text"
                                        v-model="boxEstimate"
                                        placeholder="Contoh: 50 box / 100 pcs"
                                        class="w-full px-4 py-3 rounded-xl border border-cream-300 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-sm sm:text-base text-brown-900 placeholder:text-brown-400 bg-white"
                                    />
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="messageNotes" class="block text-xs font-bold text-brown-800 uppercase tracking-wider mb-1.5">
                                    Catatan Tambahan / Lokasi Acara
                                </label>
                                <textarea
                                    id="messageNotes"
                                    rows="3"
                                    v-model="messageNotes"
                                    placeholder="Tuliskan jenis kue favorit, lokasi kirim di Bekasi, atau waktu pengantaran yang diinginkan..."
                                    class="w-full px-4 py-3 rounded-xl border border-cream-300 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-sm sm:text-base text-brown-900 placeholder:text-brown-400 bg-white resize-y"
                                ></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full py-4 px-6 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl font-bold text-sm sm:text-base shadow-md shadow-emerald-700/20 flex items-center justify-center gap-2.5 transition-all cursor-pointer"
                            >
                                <MessageCircle class="w-5 h-5" />
                                <span>Kirim Konsultasi ke WhatsApp Kami</span>
                            </button>
                            <p class="text-center text-xs text-brown-500">
                                Pesan akan diformat otomatis dan membuka aplikasi WhatsApp Anda.
                            </p>
                        </form>
                    </div>
                </div>

                <!-- ─── JANGKAUAN WILAYAH PENGIRIMAN (BEKASI DELIVERY COVERAGE) ─── -->
                <section class="bg-white rounded-3xl p-6 sm:p-10 border border-cream-200/90 shadow-sm mb-16 sm:mb-24">
                    <div class="max-w-2xl mb-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 border border-brand-200 text-brand-700 text-xs font-bold uppercase tracking-wider mb-2">
                            <Truck class="w-3.5 h-3.5 text-brand-600" />
                            <span>Jangkauan Wilayah Pengantaran</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-brown-900 tracking-tight mb-2">
                            Siap Antar ke Seluruh Pelosok Bekasi
                        </h2>
                        <p class="text-sm sm:text-base text-brown-600 leading-relaxed">
                            Armada kurir kami berpengalaman mengantar pesanan tepat waktu di pagi hari untuk berbagai kecamatan dan perumahan di Kabupaten maupun Kota Bekasi.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2.5 sm:gap-3 mb-6">
                        <span
                            v-for="(area, index) in deliveryAreas"
                            :key="index"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-cream-50 border border-cream-200 text-brown-800 text-xs sm:text-sm font-semibold hover:border-brand-300 hover:bg-brand-50/50 transition-colors"
                        >
                            <CheckCircle2 class="w-4 h-4 text-brand-600 shrink-0" />
                            <span>{{ area }}</span>
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-start gap-3">
                        <HelpCircle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                        <p class="leading-relaxed">
                            <strong>Lokasi Anda di luar daftar di atas?</strong> Jangan ragu menanyakan ke admin kami. Kami tetap dapat mengatur pengantaran melalui kurir instan/car delivery untuk wilayah Jabodetabek yang dapat dijangkau.
                        </p>
                    </div>
                </section>

                <!-- ─── FAQ SINGKAT KONTAK & PEMESANAN ─── -->
                <section class="mb-12">
                    <div class="text-center max-w-xl mx-auto mb-10">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-brown-900 tracking-tight mb-2">
                            Pertanyaan Umum Seputar Pemesanan
                        </h2>
                        <p class="text-brown-600 text-sm">
                            Informasi cepat mengenai minimal pemesanan dan jadwal pengantaran.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl p-6 border border-cream-200">
                            <h3 class="font-bold text-brown-900 text-sm sm:text-base mb-2">Berapa minimal order snack box?</h3>
                            <p class="text-brown-600 text-xs sm:text-sm leading-relaxed">
                                Minimal order untuk paket Custom Snack Box adalah 10 box. Untuk kue satuan di katalog, minimal 5 pcs per jenis kue.
                            </p>
                        </div>
                        <div class="bg-white rounded-2xl p-6 border border-cream-200">
                            <h3 class="font-bold text-brown-900 text-sm sm:text-base mb-2">Kapan waktu paling lambat memesan?</h3>
                            <p class="text-brown-600 text-xs sm:text-sm leading-relaxed">
                                Kami menyarankan H-2 atau H-1 sebelum acara agar ketersediaan seluruh varian kue terjamin fresh. Untuk pesanan mendesak, silakan langsung hubungi WhatsApp kami.
                            </p>
                        </div>
                        <div class="bg-white rounded-2xl p-6 border border-cream-200">
                            <h3 class="font-bold text-brown-900 text-sm sm:text-base mb-2">Apakah bisa diantar pagi hari?</h3>
                            <p class="text-brown-600 text-xs sm:text-sm leading-relaxed">
                                Bisa! Dapur kami beroperasi sejak subuh sehingga pesanan dapat tiba sebelum pukul 07.00 atau 08.00 pagi sesuai jadwal acara Anda.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <Footer />
    </CustomerLayout>
</template>
