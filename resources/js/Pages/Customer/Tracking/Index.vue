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
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-20">
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
                            class="px-6 py-2.5 bg-white border border-cream-300 text-brown-700 hover:bg-cream-50 rounded-xl font-bold text-sm transition-all flex items-center gap-2"
                        >
                            <MessageCircle class="w-4 h-4 text-emerald-600" />
                            <span>Bantuan CS</span>
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
import { Clock, ShieldCheck, FileText, AlertCircle, MessageCircle } from 'lucide-vue-next';

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
