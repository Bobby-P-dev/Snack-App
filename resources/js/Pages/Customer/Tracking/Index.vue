<template>
    <Head :title="`Lacak Pesanan - ${cms.company_name || 'Padu Kue'}`" />
    <CustomerLayout>
        <div class="min-h-screen bg-gradient-to-b from-cream-50 via-cream-100/80 to-cream-200/50 py-6 sm:py-10 relative overflow-hidden">
            <!-- Ambient Warm Glow Backgrounds -->
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-brand-200/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -left-32 w-96 h-96 bg-amber-200/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header / Search Section -->
            <TrackingHeader 
                :searched-order-number="searchedOrderNumber"
                @search="handleSearch"
            />

            <!-- Main Content Container -->
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-20 relative z-10">
                <!-- 1. Error State -->
                <div v-if="error" class="bg-gradient-to-br from-white via-cream-50/95 to-brand-50/40 rounded-3xl p-8 sm:p-10 text-center shadow-sm border border-cream-200 max-w-2xl mx-auto relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-brand-100/30 blur-xl pointer-events-none"></div>
                    <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-brand-200/80 text-brand-600 relative z-10">
                        <AlertCircle class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-brown-900 mb-2 relative z-10">Nomor Pesanan Tidak Ditemukan</h3>
                    <p class="text-sm sm:text-base text-brown-600 leading-relaxed max-w-md mx-auto mb-6 relative z-10">
                        {{ error }}
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 relative z-10">
                        <button 
                            @click="resetSearch" 
                            class="px-6 py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-bold text-sm hover:from-brand-600 hover:to-brand-700 transition-all shadow-md shadow-brand-500/20 cursor-pointer"
                        >
                            Cari Nomor Lain
                        </button>
                        <a 
                            :href="waNotFoundUrl" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="px-6 py-2.5 bg-emerald-50 hover:bg-emerald-100/80 text-emerald-800 border border-emerald-200 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-2xs"
                        >
                            <svg class="w-4 h-4 fill-emerald-600 shrink-0" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z" />
                            </svg>
                            <span>Bantuan CS WhatsApp</span>
                        </a>
                    </div>
                </div>
                
                <!-- 2. Result State -->
                <TrackingResult 
                    v-else-if="order" 
                    :order="order"
                    @reset="resetSearch"
                />

                <!-- 3. Empty State Guide (Before Searching) -->
                <div v-else class="mt-6 sm:mt-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                        <div 
                            v-for="(card, idx) in infoCards" 
                            :key="idx"
                            class="bg-gradient-to-br from-white via-cream-50/90 to-brand-50/35 p-6 sm:p-7 rounded-2xl border border-cream-200/90 shadow-2xs hover:border-brand-300/80 hover:shadow-xs transition-all duration-300 group flex flex-col items-start text-left relative overflow-hidden"
                        >
                            <!-- Decorative soft corner glow -->
                            <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full bg-brand-100/25 blur-xl pointer-events-none transition-all duration-300 group-hover:scale-125 group-hover:bg-brand-200/35"></div>

                            <!-- Squircle Icon (Harmonious with HeroSection & OrderSteps) -->
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-white/95 border border-cream-200/90 flex items-center justify-center text-brown-800 shrink-0 mb-4 transition-all duration-300 group-hover:scale-105 group-hover:bg-brand-50 group-hover:border-brand-300 shadow-2xs relative z-10">
                                <component :is="card.icon" class="w-5 h-5 sm:w-6 sm:h-6 text-brown-800 transition-colors duration-300 group-hover:text-brand-600" stroke-width="1.6" />
                            </div>

                            <h4 class="font-bold text-brown-900 text-base mb-1.5 group-hover:text-brand-700 transition-colors relative z-10">
                                {{ card.title }}
                            </h4>
                            <p class="text-xs sm:text-sm text-brown-600 leading-relaxed relative z-10">
                                {{ card.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import TrackingHeader from './Landing/Header.vue';
import TrackingResult from './Landing/TrackingResult.vue';
import { Clock, ShieldCheck, FileText, AlertCircle } from 'lucide-vue-next';

const infoCards = [
    {
        icon: Clock,
        title: 'Pantauan Proses Pesanan',
        description: 'Lacak tahapan pembuatan kue di dapur hingga pesanan siap diberangkatkan kurir ke alamat acara Anda.'
    },
    {
        icon: ShieldCheck,
        title: 'Privasi Terjaga Tanpa Akun',
        description: 'Cukup gunakan nomor pesanan dari konfirmasi WhatsApp. Data pribadi dan kontak Anda tetap terlindungi aman.'
    },
    {
        icon: FileText,
        title: 'Invoice Resmi Format PDF',
        description: 'Unduh bukti transaksi resmi berformat PDF lengkap dengan kalkulasi DP untuk kebutuhan administrasi acara.'
    }
];

const props = defineProps({
    cms: {
        type: Object,
        default: () => ({})
    },
    searchedOrderNumber: {
        type: String,
        default: null
    },
    order: {
        type: Object,
        default: null
    },
    error: {
        type: String,
        default: null
    }
});

const page = usePage();
const cmsSettings = computed(() => page.props.cms?.settings || props.cms || {});
const adminPhone = computed(() => {
    const raw = cmsSettings.value.contact_phone || '6281234567890';
    return raw.replace(/[^0-9]/g, '');
});

const waNotFoundUrl = computed(() => {
    const phone = adminPhone.value;
    const company = cmsSettings.value.company_name || 'Padu Kue';
    let message = cmsSettings.value.wa_tracking_not_found_message || 'Halo Admin {company_name}, saya kesulitan menemukan nomor pesanan saya di website. Mohon dibantu.';
    message = message.replace(/\{company_name\}/g, company);
    return `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
});

const handleSearch = (orderNumber) => {
    if (!orderNumber) return;
    
    router.get('/tracking', { order_number: orderNumber }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetSearch = () => {
    router.get('/tracking');
};
</script>
