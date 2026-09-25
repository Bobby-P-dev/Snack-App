<template>
    <div class="bg-gradient-to-br from-white via-cream-50/90 to-brand-50/25 rounded-3xl p-6 sm:p-10 shadow-sm border border-cream-200/90 relative overflow-hidden">
        <!-- Decorative soft background glow -->
        <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-brand-100/30 blur-2xl pointer-events-none"></div>

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

            <!-- Dynamic Status & Package Type Badges -->
            <div class="flex items-center gap-2 flex-wrap self-start md:self-auto">
                <span 
                    v-if="order.package_type === 'snack_box'"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80 shadow-2xs"
                >
                    <span>Paket Snack Box</span>
                </span>
                <span 
                    v-else-if="order.package_type === 'campuran'"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80 shadow-2xs"
                >
                    <span>Campuran (Box + Satuan)</span>
                </span>
                <span 
                    v-else
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs"
                >
                    <span>Kue Satuan</span>
                </span>

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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch">
            
            <!-- Col 1: Informasi Pemesan & Pengambilan -->
            <div class="bg-cream-50/70 p-5 sm:p-6 rounded-2xl border border-cream-200/80 flex flex-col justify-between">
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
                            <div class="min-w-0">
                                <p class="text-[11px] text-brown-500 uppercase tracking-wider font-semibold">Nama Pemesan</p>
                                <p class="text-sm font-bold text-brown-900 truncate">{{ order.customer_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-brown-400 shrink-0 border border-cream-200">
                                <Phone class="w-4 h-4" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] text-brown-500 uppercase tracking-wider font-semibold">Nomor WhatsApp</p>
                                <p class="text-sm font-bold text-brown-900 font-mono">{{ order.customer_phone }}</p>
                            </div>
                        </div>
                        <div v-if="order.pickup_date" class="flex items-start gap-3 bg-brand-50/60 p-3 rounded-xl border border-brand-200/70">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-brand-500 shrink-0 border border-brand-200/60 shadow-2xs">
                                <Calendar class="w-4 h-4" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-brand-600 uppercase tracking-wider font-extrabold">Rencana Pengambilan / Kirim</p>
                                <p class="text-xs sm:text-sm font-extrabold text-brown-900 leading-snug">{{ order.pickup_date }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-brown-400 shrink-0 border border-cream-200">
                                <MapPin class="w-4 h-4" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] text-brown-500 uppercase tracking-wider font-semibold">Lokasi / Alamat</p>
                                <p class="text-xs sm:text-sm font-medium text-brown-800 leading-relaxed">{{ order.location || 'Diambil di Toko' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan Pembeli -->
                <div class="mt-5 pt-4 border-t border-cream-200">
                    <p class="text-[11px] text-brown-500 uppercase tracking-wider font-semibold mb-1.5 flex items-center gap-1.5">
                        <FileText class="w-3.5 h-3.5 text-brown-400" />
                        <span>Catatan Pembeli</span>
                    </p>
                    <div v-if="order.notes" class="text-xs text-brown-700 bg-white p-3 rounded-xl border border-cream-200 italic leading-relaxed">
                        "{{ order.notes }}"
                    </div>
                    <div v-else class="text-xs text-brown-400 bg-white/70 p-2.5 rounded-xl border border-cream-200/80 flex items-center gap-1.5">
                        <CheckCircle2 class="w-3.5 h-3.5 text-brown-300 shrink-0" />
                        <span>Tidak ada catatan khusus dari pemesan.</span>
                    </div>
                </div>
            </div>

            <!-- Col 2: Rincian Produk Pesanan -->
            <div class="bg-cream-50/70 p-5 sm:p-6 rounded-2xl border border-cream-200/80 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-brown-900 text-base mb-5 pb-3 border-b border-cream-200 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Package class="w-4 h-4 text-brand-500" />
                            <span>Rincian Produk</span>
                        </div>
                        <span class="text-xs font-extrabold text-brand-600 bg-brand-50 px-2.5 py-0.5 rounded-full border border-brand-200/60">
                            {{ order.items?.length || 0 }} item
                        </span>
                    </h3>
                    
                    <div class="space-y-3 mb-5 max-h-64 overflow-y-auto pr-1">
                        <div 
                            v-for="(item, index) in order.items" 
                            :key="index" 
                            class="flex items-center gap-3 bg-white p-2.5 sm:p-3 rounded-xl border border-cream-200 shadow-2xs"
                        >
                            <div class="w-12 h-12 bg-cream-100 rounded-lg overflow-hidden shrink-0 border border-cream-200">
                                <img v-if="item.image_url" :src="getImageUrl(item.image_url)" class="w-full h-full object-cover" :alt="item.name" />
                                <div v-else class="w-full h-full flex items-center justify-center text-brown-300">
                                    <Package class="w-5 h-5 text-cream-400" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold text-brown-900 line-clamp-2 leading-snug">{{ item.name }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-block text-[10px] font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-md">
                                        {{ item.quantity }}x {{ item.type === 'kustom_box' ? 'porsi box' : 'satuan' }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-xs sm:text-sm font-extrabold text-brown-900 text-right whitespace-nowrap pl-2">
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
                    <div class="bg-white p-3 rounded-xl border border-cream-200 mt-2 space-y-1.5">
                        <template v-if="order.is_full || order.remaining_amount == 0">
                            <div class="flex justify-between text-xs items-center">
                                <span class="text-brown-600 font-medium">Metode Pembayaran:</span>
                                <span class="font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Bayar Penuh (100%)</span>
                            </div>
                            <div class="flex justify-between text-xs items-center pt-1 border-t border-cream-100">
                                <span class="text-brown-600 font-medium">Sisa Pelunasan:</span>
                                <span class="font-bold text-emerald-700">Rp 0 (LUNAS)</span>
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex justify-between text-xs items-center">
                                <span class="text-brown-600 font-medium">Uang Muka / DP ({{ order.dp_percentage }}%):</span>
                                <span class="font-bold text-brand-700">Rp {{ formatNumber(order.dp_amount) }}</span>
                            </div>
                            <div class="flex justify-between text-xs items-center pt-1 border-t border-cream-100">
                                <span class="text-brown-600 font-medium">Sisa Pelunasan:</span>
                                <span class="font-bold text-brown-800">Rp {{ formatNumber(order.remaining_amount) }}</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Col 3: Catatan Layanan & Hubungi Admin -->
            <div class="flex flex-col justify-between gap-4">
                <!-- Info Box: Informasi Operasional -->
                <div class="bg-cream-50/70 p-5 sm:p-6 rounded-2xl border border-cream-200/80 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-brown-900 text-base mb-3 pb-2.5 border-b border-cream-200 flex items-center gap-2">
                            <ShieldCheck class="w-4 h-4 text-emerald-600" />
                            <span>Informasi Operasional</span>
                        </h3>
                        <p class="text-xs sm:text-sm text-brown-700 leading-relaxed mb-4">
                            Kue dan snack box diproduksi secara higienis menggunakan bahan segar tanpa pengawet. Kami merekomendasikan produk dikonsumsi dalam kurun waktu <strong>1x24 jam</strong> sejak diterima.
                        </p>
                    </div>
                    <div class="bg-white p-3.5 rounded-xl border border-cream-200 text-xs text-brown-600 space-y-1">
                        <p class="font-bold text-brown-900 flex items-center gap-1.5">
                            <Info class="w-3.5 h-3.5 text-brand-500" />
                            <span>Petunjuk Pembayaran:</span>
                        </p>
                        <p class="leading-relaxed">Transfer DP ke rekening resmi yang tertera pada pesan WhatsApp untuk mempercepat proses pembuatan di dapur kami.</p>
                    </div>
                </div>
                
                <!-- Bantuan WhatsApp Card (Text-only, no bloated graphics) -->
                <div class="bg-gradient-to-br from-emerald-50/90 via-cream-50 to-emerald-50/50 p-5 sm:p-6 rounded-2xl border-2 border-emerald-200 shadow-2xs flex flex-col justify-between gap-4">
                    <div>
                        <h4 class="font-bold text-brown-900 text-base mb-1.5">Ada Pertanyaan / Perubahan?</h4>
                        <p class="text-xs text-brown-600 leading-relaxed">
                            Konsultasikan langsung dengan Customer Service kami jika ingin mengubah jadwal, melunasi, atau menambah pesanan.
                        </p>
                    </div>
                    <a 
                        :href="waHelpUrl" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-md shadow-emerald-600/25 hover:shadow-lg transition-all"
                    >
                        <span>Chat via WhatsApp</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { ArrowLeft, User, Phone, MapPin, Package, Download, Calendar, ShieldCheck, Info, FileText, CheckCircle2 } from 'lucide-vue-next';
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
