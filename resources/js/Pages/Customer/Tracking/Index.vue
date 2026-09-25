<template>
    <Head :title="`Lacak Pesanan - ${cms.company_name || 'Padu Kue'}`" />
    <CustomerLayout>
        <div class="min-h-screen bg-cream-100 py-6 sm:py-10">
            <!-- Header / Search Section -->
            <TrackingHeader 
                :searched-order-number="searchedOrderNumber"
                @search="handleSearch"
            />

            <!-- Main Content Container -->
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-20">
                <!-- 1. Error State -->
                <div v-if="error" class="bg-white rounded-3xl p-8 sm:p-10 text-center shadow-sm border border-cream-200 max-w-2xl mx-auto">
                    <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-brand-200/80 text-brand-600">
                        <AlertCircle class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-brown-900 mb-2">Nomor Pesanan Tidak Ditemukan</h3>
                    <p class="text-sm sm:text-base text-brown-600 leading-relaxed max-w-md mx-auto mb-6">
                        {{ error }}
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Card 1 -->
                        <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-7 rounded-3xl border border-cream-200 shadow-2xs text-center group hover:border-brand-200 transition-colors">
                            <div class="w-13 h-13 rounded-2xl bg-brand-50 border border-brand-200/80 text-brand-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                                <Clock class="w-6 h-6" />
                            </div>
                            <h4 class="font-extrabold text-brown-900 text-base mb-2">Pantauan Real-time</h4>
                            <p class="text-xs sm:text-sm text-brown-600 leading-relaxed">
                                Lacak tahapan produksi dari penerimaan order, perakitan di dapur, hingga siap dikirimkan.
                            </p>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-7 rounded-3xl border border-cream-200 shadow-2xs text-center group hover:border-brand-200 transition-colors">
                            <div class="w-13 h-13 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                                <ShieldCheck class="w-6 h-6" />
                            </div>
                            <h4 class="font-extrabold text-brown-900 text-base mb-2">Privasi Aman Tanpa Akun</h4>
                            <p class="text-xs sm:text-sm text-brown-600 leading-relaxed">
                                Cukup dengan Order Number unik Anda. Seluruh nomor telepon dan data pribadi tetap diproteksi.
                            </p>
                        </div>

                        <!-- Card 3 -->
                        <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-7 rounded-3xl border border-cream-200 shadow-2xs text-center group hover:border-brand-200 transition-colors">
                            <div class="w-13 h-13 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                                <FileText class="w-6 h-6" />
                            </div>
                            <h4 class="font-extrabold text-brown-900 text-base mb-2">Invoice Resmi PDF</h4>
                            <p class="text-xs sm:text-sm text-brown-600 leading-relaxed">
                                Unduh bukti pemesanan berformat PDF lengkap dengan kalkulasi DP untuk kebutuhan administrasi acara Anda.
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
