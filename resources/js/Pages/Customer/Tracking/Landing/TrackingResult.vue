<template>
    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-cream-200/90 relative">
        <!-- Top Bar: Back Action & Status Badge -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-cream-200">
            <button 
                @click="$emit('reset')" 
                class="inline-flex items-center text-brown-600 hover:text-brand-600 font-bold text-xs sm:text-sm transition-colors group cursor-pointer"
            >
                <ArrowLeft class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" />
                <span>Cari Nomor Pesanan Lain</span>
            </button>

            <!-- Invoice Download Button -->
            <a 
                v-if="order.download_invoice_url" 
                :href="order.download_invoice_url" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-brand-50 hover:bg-brand-100/70 text-brand-600 border border-brand-200/80 rounded-xl font-bold text-xs sm:text-sm transition-all shadow-2xs hover:shadow-sm self-start sm:self-auto"
            >
                <Download class="w-4 h-4" />
                <span>Unduh Invoice PDF</span>
            </a>
        </div>

        <!-- Order Header & Number Banner -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-cream-50/70 p-5 sm:p-6 rounded-2xl border border-cream-200">
            <div>
                <div class="flex items-center gap-2.5 mb-1.5">
                    <span class="text-xs font-bold text-brown-500 uppercase tracking-wider">No. Pesanan:</span>
                    <span class="font-mono font-black text-lg sm:text-xl text-brand-700 bg-white px-3 py-0.5 rounded-lg border border-brand-200/60 shadow-2xs">
                        {{ order.order_number }}
                    </span>
                </div>
                <p class="text-xs text-brown-600">
                    Waktu Checkout: <span class="font-semibold text-brown-800">{{ order.date }}</span>
                </p>
            </div>

            <!-- Dynamic Status Badge -->
            <div class="self-start md:self-auto">
                <span 
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs sm:text-sm font-bold capitalize border shadow-2xs"
                    :class="statusBadgeClass"
                >
                    <span class="w-2 h-2 rounded-full" :class="statusDotClass"></span>
                    <span>Status: {{ statusText }}</span>
                </span>
            </div>
        </div>

        <!-- Timeline Stepper Section -->
        <div class="mb-10 pb-8 border-b border-cream-200">
            <h3 class="text-sm font-bold text-brown-500 uppercase tracking-wider mb-6">Progres Pemesanan</h3>
            <TrackingTimeline :current-step="order.current_step" :status="order.status" />
        </div>

        <!-- Details Grid (3 columns on Desktop) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            <!-- Col 1: Informasi Pemesan & Pengambilan -->
            <div class="bg-cream-50/70 p-6 rounded-2xl border border-cream-200/80 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-brown-900 text-base mb-5 pb-3 border-b border-cream-200 flex items-center gap-2">
                        <User class="w-4 h-4 text-brand-500" />
                        <span>Data Pemesan & Jadwal</span>
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-brown-400 shrink-0 border border-cream-200">
                                <User class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-[11px] text-brown-500 uppercase tracking-wider font-semibold">Nama Pemesan</p>
                                <p class="text-sm font-bold text-brown-900">{{ order.customer_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-brown-400 shrink-0 border border-cream-200">
                                <Phone class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-[11px] text-brown-500 uppercase tracking-wider font-semibold">Nomor WhatsApp</p>
                                <p class="text-sm font-bold text-brown-900 font-mono">{{ order.customer_phone }}</p>
                            </div>
                        </div>
                        <div v-if="order.pickup_date" class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-brand-500 shrink-0 border border-cream-200">
                                <Calendar class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-[11px] text-brand-600 uppercase tracking-wider font-bold">Rencana Pengambilan / Pengiriman</p>
                                <p class="text-sm font-extrabold text-brown-900">{{ order.pickup_date }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-brown-400 shrink-0 border border-cream-200">
                                <MapPin class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-[11px] text-brown-500 uppercase tracking-wider font-semibold">Lokasi / Alamat</p>
                                <p class="text-sm font-medium text-brown-800 leading-relaxed">{{ order.location || 'Diambil di Toko' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan jika ada -->
                <div v-if="order.notes" class="mt-5 pt-4 border-t border-cream-200">
                    <p class="text-[11px] text-brown-500 uppercase tracking-wider font-semibold mb-1">Catatan Pembeli</p>
                    <p class="text-xs text-brown-700 bg-white p-3 rounded-xl border border-cream-200 italic">
                        "{{ order.notes }}"
                    </p>
                </div>
            </div>

            <!-- Col 2: Rincian Produk Pesanan -->
            <div class="bg-cream-50/70 p-6 rounded-2xl border border-cream-200/80 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-brown-900 text-base mb-5 pb-3 border-b border-cream-200 flex items-center gap-2">
                        <Package class="w-4 h-4 text-brand-500" />
                        <span>Rincian Produk ({{ order.items?.length || 0 }})</span>
                    </h3>
                    
                    <div class="space-y-3.5 mb-6 max-h-56 overflow-y-auto pr-1">
                        <div 
                            v-for="(item, index) in order.items" 
                            :key="index" 
                            class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-cream-200 shadow-2xs"
                        >
                            <div class="w-11 h-11 bg-cream-100 rounded-lg overflow-hidden shrink-0 border border-cream-200">
                                <img v-if="item.image_url" :src="getImageUrl(item.image_url)" class="w-full h-full object-cover" :alt="item.name" />
                                <div v-else class="w-full h-full flex items-center justify-center text-brown-300">
                                    <Package class="w-5 h-5 text-cream-400" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold text-brown-900 truncate">{{ item.name }}</h4>
                                <span class="inline-block text-[10px] font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-md mt-0.5">
                                    {{ item.quantity }}x {{ item.type === 'kustom_box' ? 'porsi box' : 'satuan' }}
                                </span>
                            </div>
                            <div class="text-xs sm:text-sm font-extrabold text-brown-900 text-right whitespace-nowrap">
                                Rp {{ formatNumber(item.price * item.quantity) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Calculation Summary -->
                <div class="pt-4 border-t border-cream-200 space-y-2">
                    <div class="flex justify-between text-xs sm:text-sm">
                        <span class="text-brown-600">Subtotal Produk</span>
                        <span class="font-bold text-brown-900">Rp {{ formatNumber(order.subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-xs sm:text-sm">
                        <span class="text-brown-600">Biaya Pengiriman</span>
                        <span class="font-bold text-emerald-600">Gratis / Penyesuaian</span>
                    </div>
                    <div class="flex justify-between text-sm sm:text-base pt-2 border-t border-cream-200">
                        <span class="font-bold text-brown-900">Total Tagihan</span>
                        <span class="font-black text-brand-600">Rp {{ formatNumber(order.total) }}</span>
                    </div>

                    <!-- Payment Breakdown -->
                    <div class="bg-white p-3 rounded-xl border border-cream-200 mt-2 space-y-1">
                        <template v-if="order.is_full || order.remaining_amount == 0">
                            <div class="flex justify-between text-xs items-center">
                                <span class="text-brown-600 font-medium">Metode Pembayaran:</span>
                                <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Bayar Penuh (100%)</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-brown-600 font-medium">Sisa Pelunasan:</span>
                                <span class="font-bold text-emerald-700">Rp 0 (LUNAS)</span>
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex justify-between text-xs">
                                <span class="text-brown-600 font-medium">Uang Muka / DP ({{ order.dp_percentage }}%):</span>
                                <span class="font-bold text-brand-700">Rp {{ formatNumber(order.dp_amount) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-brown-600 font-medium">Sisa Pelunasan:</span>
                                <span class="font-bold text-brown-800">Rp {{ formatNumber(order.remaining_amount) }}</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Col 3: Catatan Layanan & Hubungi Admin -->
            <div class="flex flex-col gap-4">
                <!-- Info Box -->
                <div class="bg-cream-50/70 p-6 rounded-2xl border border-cream-200/80 flex-1">
                    <h3 class="font-bold text-brown-900 text-base mb-3 pb-2 border-b border-cream-200">
                        Informasi Operasional
                    </h3>
                    <p class="text-xs sm:text-sm text-brown-700 leading-relaxed mb-4">
                        Kue dan snack box diproduksi secara higienis menggunakan bahan segar tanpa pengawet. Kami merekomendasikan produk dikonsumsi dalam kurun waktu 1x24 jam sejak diterima.
                    </p>
                    <div class="bg-white p-3.5 rounded-xl border border-cream-200 text-xs text-brown-600 space-y-1">
                        <p class="font-bold text-brown-900">Petunjuk Pembayaran:</p>
                        <p>Transfer DP ke rekening resmi yang tertera pada pesan WhatsApp untuk mempercepat proses pembuatan.</p>
                    </div>
                </div>
                
                <!-- Bantuan WhatsApp Card -->
                <div class="bg-gradient-to-br from-brand-50/70 to-cream-100 p-6 rounded-2xl border border-brand-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="font-bold text-brown-900 text-sm">Ada Pertanyaan / Perubahan?</h4>
                        <p class="text-xs text-brown-600 mt-1">Konsultasikan langsung dengan Customer Service kami</p>
                    </div>
                    <a 
                        :href="waHelpUrl" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl flex items-center gap-2 font-bold text-xs shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all shrink-0"
                    >
                        <MessageCircle class="w-4 h-4" />
                        <span>Chat WhatsApp</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { ArrowLeft, User, Phone, MapPin, Package, Download, MessageCircle, Calendar } from 'lucide-vue-next';
import TrackingTimeline from './TrackingTimeline.vue';
import { getImageUrl } from '@/helpers.js';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    order: {
        type: Object,
        required: true
    }
});

defineEmits(['reset']);

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});

const adminPhone = computed(() => {
    const raw = cms.value.contact_phone || '6281234567890';
    return raw.replace(/[^0-9]/g, '');
});

const waHelpUrl = computed(() => {
    const phone = adminPhone.value;
    const company = cms.value.company_name || 'Padu Kue';
    const orderNumber = props.order.order_number || '';
    let message = cms.value.wa_tracking_help_message || 'Halo Admin {company_name}, saya ingin menanyakan status pesanan dengan nomor {order_number}.';
    message = message.replace(/\{company_name\}/g, company).replace(/\{order_number\}/g, orderNumber);
    return `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
});

const statusText = computed(() => {
    const s = props.order.status || 'pending';
    const dict = {
        'pending': 'Menunggu Konfirmasi',
        'diterima': 'Pesanan Diterima',
        'diproses': 'Sedang Diproses',
        'dikemas': 'Sedang Dikemas',
        'dikirim': 'Siap Dikirim / Diambil',
        'selesai': 'Pesanan Selesai',
        'batal': 'Dibatalkan',
    };
    return dict[s] || s;
});

const statusBadgeClass = computed(() => {
    const s = props.order.status || 'pending';
    if (s === 'selesai') return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    if (s === 'batal') return 'bg-red-50 text-red-700 border-red-200';
    if (s === 'pending') return 'bg-amber-50 text-amber-700 border-amber-200';
    return 'bg-brand-50 text-brand-700 border-brand-200';
});

const statusDotClass = computed(() => {
    const s = props.order.status || 'pending';
    if (s === 'selesai') return 'bg-emerald-500';
    if (s === 'batal') return 'bg-red-500';
    if (s === 'pending') return 'bg-amber-500 animate-pulse';
    return 'bg-brand-500 animate-pulse';
});

const formatNumber = (num) => {
    return Number(num || 0).toLocaleString('id-ID');
};
</script>
