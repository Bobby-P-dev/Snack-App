<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import {
    Mail,
    MapPin,
    Clock,
    Send,
    MessageCircle,
    Truck,
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

const purposeOptions = [
    { key: 'snackbox', label: 'Custom Snack Box', icon: Package },
    { key: 'satuan', label: 'Kue Satuan / Donat', icon: Store },
    { key: 'ongkir', label: 'Cek Ongkir & Jadwal', icon: Truck },
    { key: 'other', label: 'Pertanyaan Lain', icon: MessageCircle },
];

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
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&display=swap"
            rel="stylesheet"
        />
        <component is="script" type="application/ld+json" v-text="schemaJson" />
    </Head>

    <CustomerLayout>
        <div class="bg-cream-100">
            <div class="max-w-5xl mx-auto px-5 sm:px-8 py-14 sm:py-20">

                <!-- ─── HERO ─── -->
                <header class="max-w-2xl mb-14 sm:mb-20">
                    <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-6">
                        <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                        Hubungi kami
                    </p>
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-[3.4rem] text-brown-900 leading-[1.1] tracking-tight mb-6">
                        Ceritakan acaramu, kami siapkan kuenya.
                    </h1>
                    <p class="text-base sm:text-lg text-brown-600 leading-relaxed">
                        Rencana syukuran, rapat kantor, atau kumpul keluarga? Tim kami siap membantu menyusun
                        pilihan snack box terbaik — sesuai selera tamu dan anggaranmu.
                    </p>
                </header>

                <!-- ─── KONTAK + FORM ─── -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 mb-16 sm:mb-24">

                    <!-- Kolom kiri: kontak langsung -->
                    <div class="lg:col-span-5">
                        <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-8">
                            <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                            Kontak langsung
                        </p>

                        <!-- WhatsApp -->
                        <div class="pb-8 mb-8 border-b border-brown-900/10">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                                <span class="text-[13px] font-medium text-brown-600">Fast response via WhatsApp</span>
                            </div>
                            <p class="font-display text-3xl sm:text-4xl text-brown-900 tracking-tight mb-5">
                                {{ companyPhoneDisplay }}
                            </p>
                            <a
                                :href="`https://wa.me/${companyPhone}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-full font-semibold text-sm transition-colors"
                            >
                                <MessageCircle class="w-4 h-4" />
                                <span>Buka Chat WhatsApp</span>
                            </a>
                        </div>

                        <!-- Daftar kontak -->
                        <dl>
                            <div class="flex gap-4 py-5 border-b border-brown-900/10">
                                <Clock class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-1">Jam operasional</dt>
                                    <dd class="text-[15px] font-semibold text-brown-900">{{ operatingHours }}</dd>
                                    <dd class="text-[13px] text-brown-600 mt-0.5">Pemesanan online & konsultasi chat selalu terbuka.</dd>
                                </div>
                            </div>
                            <div class="flex gap-4 py-5 border-b border-brown-900/10">
                                <Mail class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-1">Email</dt>
                                    <dd>
                                        <a :href="`mailto:${companyEmail}`" class="text-[15px] font-semibold text-brown-900 hover:text-brand-700 transition-colors break-all">
                                            {{ companyEmail }}
                                        </a>
                                    </dd>
                                    <dd class="text-[13px] text-brown-600 mt-0.5">Untuk kerja sama instansi & invoice resmi.</dd>
                                </div>
                            </div>
                            <div class="flex gap-4 py-5 border-b border-brown-900/10">
                                <MapPin class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-1">
                                        Workshop & pengambilan
                                    </dt>
                                    <dd class="text-sm text-brown-800 leading-relaxed">{{ operationalAddress }}</dd>
                                    <dd class="mt-1.5">
                                        <a :href="gmapsWorkshopUrl" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-[13px] font-semibold text-brand-700 hover:underline">
                                            <span>Lihat di Google Maps</span>
                                            <ExternalLink class="w-3 h-3" />
                                        </a>
                                    </dd>
                                </div>
                            </div>
                            <div class="flex gap-4 py-5">
                                <Store class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-1">Toko</dt>
                                    <dd class="text-sm text-brown-800 leading-relaxed">{{ storeAddress }}</dd>
                                    <dd class="mt-1.5">
                                        <a :href="gmapsStoreUrl" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-[13px] font-semibold text-brand-700 hover:underline">
                                            <span>Lihat di Google Maps</span>
                                            <ExternalLink class="w-3 h-3" />
                                        </a>
                                    </dd>
                                </div>
                            </div>
                        </dl>
                    </div>

                    <!-- Kolom kanan: formulir -->
                    <div class="lg:col-span-7">
                        <div class="bg-white border border-brown-900/10 rounded-2xl p-6 sm:p-9">
                            <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-3">
                                <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                                Formulir konsultasi
                            </p>
                            <h2 class="font-display text-2xl sm:text-3xl text-brown-900 tracking-tight mb-2">
                                Kirim rincian acaramu.
                            </h2>
                            <p class="text-sm text-brown-600 leading-relaxed mb-7">
                                Pilih kebutuhanmu — pesan akan terformat rapi dan langsung membuka WhatsApp,
                                jadi admin bisa cepat kasih perkiraan harga.
                            </p>

                            <form @submit.prevent="sendWhatsAppMessage" class="space-y-5">
                                <div>
                                    <span class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-2.5">
                                        Kebutuhanmu
                                    </span>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <button
                                            v-for="opt in purposeOptions"
                                            :key="opt.key"
                                            type="button"
                                            @click="purpose = opt.key"
                                            :class="[
                                                'flex items-center gap-2.5 px-4 py-3 rounded-xl border text-left text-sm font-medium transition-all cursor-pointer',
                                                purpose === opt.key
                                                    ? 'border-brand-600 bg-brand-50 text-brand-700'
                                                    : 'border-brown-900/15 text-brown-800 hover:border-brown-900/30'
                                            ]"
                                        >
                                            <component :is="opt.icon" class="w-4 h-4 shrink-0" :class="purpose === opt.key ? 'text-brand-600' : 'text-brown-600'" />
                                            <span>{{ opt.label }}</span>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label for="senderName" class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-2">
                                        Nama <span class="normal-case font-normal tracking-normal">(opsional)</span>
                                    </label>
                                    <input
                                        id="senderName"
                                        type="text"
                                        v-model="senderName"
                                        placeholder="Contoh: Ibu Rina / Bpk. Fajar"
                                        class="w-full px-4 py-3 rounded-xl border border-brown-900/15 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/15 text-[15px] text-brown-900 placeholder:text-brown-500/70 bg-white outline-none transition"
                                    />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="eventDate" class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-2">
                                            Tanggal acara <span class="normal-case font-normal tracking-normal">(opsional)</span>
                                        </label>
                                        <input
                                            id="eventDate"
                                            type="date"
                                            v-model="eventDate"
                                            class="w-full px-4 py-3 rounded-xl border border-brown-900/15 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/15 text-[15px] text-brown-900 bg-white outline-none transition"
                                        />
                                    </div>
                                    <div>
                                        <label for="boxEstimate" class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-2">
                                            Perkiraan jumlah <span class="normal-case font-normal tracking-normal">(opsional)</span>
                                        </label>
                                        <input
                                            id="boxEstimate"
                                            type="text"
                                            v-model="boxEstimate"
                                            placeholder="Contoh: 50 box / 100 pcs"
                                            class="w-full px-4 py-3 rounded-xl border border-brown-900/15 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/15 text-[15px] text-brown-900 placeholder:text-brown-500/70 bg-white outline-none transition"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="messageNotes" class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-brown-600 mb-2">
                                        Catatan / lokasi acara
                                    </label>
                                    <textarea
                                        id="messageNotes"
                                        rows="3"
                                        v-model="messageNotes"
                                        placeholder="Jenis kue favorit, lokasi kirim di Bekasi, atau waktu pengantaran yang diinginkan..."
                                        class="w-full px-4 py-3 rounded-xl border border-brown-900/15 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/15 text-[15px] text-brown-900 placeholder:text-brown-500/70 bg-white outline-none transition resize-y"
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2.5 py-4 px-6 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-semibold text-[15px] transition-colors cursor-pointer"
                                >
                                    <Send class="w-4 h-4" />
                                    <span>Kirim Konsultasi via WhatsApp</span>
                                </button>
                                <p class="text-center text-[13px] text-brown-600">
                                    Pesan terformat otomatis dan membuka aplikasi WhatsApp-mu.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ─── JANGKAUAN ─── -->
                <section class="mb-16 sm:mb-24">
                    <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-2">
                        <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                        Jangkauan pengiriman
                    </p>
                    <h2 class="font-display text-3xl sm:text-4xl text-brown-900 tracking-tight mb-4">
                        Kami antar ke seluruh Bekasi.
                    </h2>
                    <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed max-w-2xl mb-8">
                        Kurir kami terbiasa mengantar pagi hari, tepat sebelum acaramu dimulai.
                    </p>

                    <div class="flex flex-wrap gap-2 mb-8">
                        <span
                            v-for="(area, index) in deliveryAreas"
                            :key="index"
                            class="px-4 py-2 rounded-full border border-brown-900/15 text-brown-800 text-[13px] font-medium"
                        >
                            {{ area }}
                        </span>
                    </div>

                    <p class="text-sm text-brown-600 leading-relaxed max-w-2xl border-l-2 border-brand-600 pl-4">
                        Lokasimu di luar daftar? Tanyakan langsung ke admin — kami bisa mengatur pengantaran
                        via kurir instan untuk wilayah Jabodetabek yang terjangkau.
                    </p>
                </section>

                <!-- ─── FAQ ─── -->
                <section class="mb-8">
                    <p class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-brand-700 mb-2">
                        <span class="inline-block w-10 h-px bg-brand-700/50"></span>
                        Sering ditanyakan
                    </p>
                    <h2 class="font-display text-3xl sm:text-4xl text-brown-900 tracking-tight mb-8">
                        Sebelum kamu bertanya.
                    </h2>

                    <div class="border-t border-brown-900/10">
                        <div class="py-6 border-b border-brown-900/10">
                            <h3 class="font-display text-lg sm:text-xl text-brown-900 mb-2">Berapa minimal order snack box?</h3>
                            <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed">
                                Minimal 10 box untuk paket Custom Snack Box, dan 5 pcs per jenis untuk kue satuan di katalog.
                            </p>
                        </div>
                        <div class="py-6 border-b border-brown-900/10">
                            <h3 class="font-display text-lg sm:text-xl text-brown-900 mb-2">Kapan paling lambat bisa memesan?</h3>
                            <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed">
                                Idealnya H-2 atau H-1 agar semua varian kue terjamin fresh. Untuk pesanan mendesak,
                                langsung hubungi WhatsApp kami.
                            </p>
                        </div>
                        <div class="py-6 border-b border-brown-900/10">
                            <h3 class="font-display text-lg sm:text-xl text-brown-900 mb-2">Apakah bisa diantar pagi hari?</h3>
                            <p class="text-sm sm:text-[15px] text-brown-600 leading-relaxed">
                                Bisa. Dapur kami beroperasi sejak subuh, jadi pesanan bisa tiba sebelum pukul 07.00–08.00
                                sesuai jadwal acaramu.
                            </p>
                        </div>
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
