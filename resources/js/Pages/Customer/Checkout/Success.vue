<template>
    <Head title="Pesanan Berhasil - Padu Kue" />
    <CustomerLayout>
        <div class="min-h-screen bg-cream-50/50 flex items-center justify-center px-4 py-8 md:py-12 pb-28 md:pb-16">
            <div class="w-full max-w-2xl">
                <!-- Success Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-cream-200/80 overflow-hidden">
                    <!-- Header with Success Icon -->
                    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 px-4 md:px-8 py-10 md:py-14 text-center text-white relative">
                        <div class="flex justify-center mb-4">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border-2 border-white/40">
                                <Check class="w-9 h-9 md:w-11 md:h-11 text-white" :stroke-width="2.5" />
                            </div>
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-2 tracking-tight">Pesanan Berhasil Dibuat!</h1>
                        <p class="text-emerald-100 text-sm sm:text-base max-w-md mx-auto">
                            Terima kasih telah mempercayakan pesanan Anda di Padu Kue.
                        </p>
                    </div>

                    <!-- Content -->
                    <div class="px-4 sm:px-6 md:px-8 py-6 md:py-10">
                        <!-- Order Number Docket Box -->
                        <div class="bg-cream-50/70 border-2 border-brand-200/80 rounded-2xl p-5 md:p-6 mb-6">
                            <div class="text-center">
                                <span class="inline-block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-brown-600 bg-cream-200/80 px-3 py-1 rounded-full mb-2">
                                    Nomor Pesanan Unik
                                </span>
                                <p class="text-2xl sm:text-3xl md:text-4xl font-black text-brand-600 font-mono tracking-wider break-all select-all">
                                    {{ order.order_number }}
                                </p>
                                <div class="mt-3 flex justify-center">
                                    <button
                                        @click="copyOrderNumber"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2 rounded-full bg-white hover:bg-cream-100 text-brown-800 border border-cream-300 shadow-sm transition active:scale-95"
                                    >
                                        <Check v-if="copied" class="w-4 h-4 text-emerald-600" />
                                        <Copy v-else class="w-4 h-4 text-brown-500" />
                                        <span>{{ copied ? 'Berhasil Disalin!' : 'Salin Nomor Pesanan' }}</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Order Summary Details -->
                            <div class="mt-6 pt-4 border-t border-cream-200 space-y-2.5 text-xs sm:text-sm">
                                <div class="flex justify-between items-center py-1">
                                    <span class="text-brown-600">Nama Pemesan</span>
                                    <span class="font-bold text-brown-900">{{ order.customer_name }}</span>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <span class="text-brown-600">Jadwal Pengambilan</span>
                                    <span class="font-bold text-brown-900">{{ formatDate(order.pickup_date) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-t border-cream-200">
                                    <span class="text-brown-600 font-medium">Total Pesanan</span>
                                    <span class="font-extrabold text-brand-600 text-base sm:text-lg">Rp {{ formatNumber(order.total_amount) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Items List -->
                        <div class="bg-cream-50/40 rounded-2xl p-4 sm:p-6 mb-6 border border-cream-200/70">
                            <h3 class="text-sm sm:text-base font-bold text-brown-900 mb-4 flex items-center justify-between">
                                <span>Rincian Produk ({{ order.items?.length || 0 }})</span>
                            </h3>

                            <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                                <div
                                    v-for="item in order.items"
                                    :key="item.id"
                                    class="bg-white rounded-xl p-3 sm:p-4 flex justify-between items-center border border-cream-100 shadow-2xs"
                                >
                                    <div class="flex-1 min-w-0 pr-2">
                                        <p class="font-bold text-brown-900 text-xs sm:text-sm line-clamp-1">
                                            {{ item.product?.name || 'Produk Pesanan' }}
                                        </p>
                                        <p class="text-brown-500 text-[11px] sm:text-xs mt-0.5">
                                            {{ item.quantity }}x @ Rp {{ formatNumber(item.price_at_order) }}
                                        </p>
                                    </div>
                                    <p class="font-extrabold text-brand-600 text-xs sm:text-sm flex-shrink-0">
                                        Rp {{ formatNumber(item.quantity * item.price_at_order) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment / DP Info -->
                        <div class="bg-amber-50/90 border border-amber-200 rounded-2xl p-4 sm:p-6 mb-6">
                            <h3 class="text-sm sm:text-base font-bold text-brown-900 mb-3 flex items-center">
                                <span class="w-6 h-6 rounded-full bg-amber-200/80 flex items-center justify-center mr-2 text-amber-800">
                                    <Banknote class="w-3.5 h-3.5" />
                                </span>
                                Rincian Pembayaran Uang Muka (DP)
                            </h3>

                            <div class="space-y-2">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-xs sm:text-sm text-brown-700 font-medium">Uang Muka DP yang Perlu Dibayar:</span>
                                    <span class="text-lg sm:text-xl font-extrabold text-amber-700">Rp {{ formatNumber(order.dp_amount) }}</span>
                                </div>
                                <div class="text-xs text-brown-600 pt-2 border-t border-amber-200/70">
                                    Pelunasan sisa <strong class="text-brown-800">Rp {{ formatNumber(order.total_amount - order.dp_amount) }}</strong> dapat dibayarkan langsung saat pengambilan pesanan.
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp CTA -->
                        <div class="bg-emerald-50/80 border-2 border-emerald-200 rounded-2xl p-5 sm:p-6 mb-6 text-center">
                            <h3 class="text-base sm:text-lg font-bold text-emerald-950 mb-1.5">Langkah Penting Selanjutnya</h3>
                            <p class="text-emerald-800 text-xs sm:text-sm mb-4 leading-relaxed max-w-md mx-auto">
                                Klik tombol di bawah untuk mengirim konfirmasi pesanan ke WhatsApp Admin kami dan mendapatkan instruksi pembayaran DP.
                            </p>

                            <a
                                :href="whatsappUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center w-full px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm sm:text-base rounded-xl shadow-md hover:shadow-lg transition duration-200 active:scale-[0.99]"
                            >
                                <svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-5.031 1.378c-1.533.916-2.775 2.511-3.466 4.29-.691 1.78-.633 3.709.289 5.447.922 1.738 2.664 3.148 4.6 3.627 1.936.479 4.061.353 5.588-.471 1.527-.823 2.798-2.34 3.402-4.137.604-1.798.474-3.868-.424-5.569-.898-1.7-2.604-3.038-4.597-3.519-.601-.144-1.22-.217-1.843-.216zm10.906-9.569c-5.894-5.894-15.846-5.894-21.74 0-5.893 5.893-5.893 15.845 0 21.74 5.893 5.893 15.846 5.893 21.74 0 5.893-5.895 5.893-15.847 0-21.74zm-2.829 18.911c-2.511 2.511-5.869 3.894-9.436 3.894s-6.925-1.383-9.436-3.894c-2.51-2.511-3.893-5.869-3.893-9.436s1.383-6.925 3.893-9.436c2.511-2.51 5.869-3.893 9.436-3.893s6.925 1.383 9.436 3.893c2.51 2.511 3.893 5.869 3.893 9.436s-1.383 6.925-3.893 9.436z" />
                                </svg>
                                <span>Hubungi Admin di WhatsApp</span>
                            </a>
                        </div>

                        <!-- Next Steps Roadmap -->
                        <div class="bg-cream-50/60 border border-cream-200/80 rounded-2xl p-4 sm:p-6 mb-6">
                            <h3 class="text-sm sm:text-base font-bold text-brown-900 mb-4">Proses Pemesanan</h3>

                            <div class="space-y-3.5">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-7 h-7 bg-brand-500 text-white rounded-full flex items-center justify-center font-bold text-xs">1</div>
                                    <div>
                                        <p class="font-bold text-brown-900 text-xs sm:text-sm">Kirim Pesan WhatsApp</p>
                                        <p class="text-brown-600 text-[11px] sm:text-xs mt-0.5">Klik tombol di atas untuk mengirim ringkasan pesanan ke admin kami.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-7 h-7 bg-brand-500 text-white rounded-full flex items-center justify-center font-bold text-xs">2</div>
                                    <div>
                                        <p class="font-bold text-brown-900 text-xs sm:text-sm">Admin Mengkonfirmasi</p>
                                        <p class="text-brown-600 text-[11px] sm:text-xs mt-0.5">Admin akan memvalidasi jadwal dan memberikan nomor rekening resmi.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-7 h-7 bg-brand-500 text-white rounded-full flex items-center justify-center font-bold text-xs">3</div>
                                    <div>
                                        <p class="font-bold text-brown-900 text-xs sm:text-sm">Transfer DP</p>
                                        <p class="text-brown-600 text-[11px] sm:text-xs mt-0.5">Lakukan pembayaran DP dan kirimkan bukti transfer ke WhatsApp admin.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-7 h-7 bg-brand-500 text-white rounded-full flex items-center justify-center font-bold text-xs">4</div>
                                    <div>
                                        <p class="font-bold text-brown-900 text-xs sm:text-sm">Pesanan Diproses & Diambil</p>
                                        <p class="text-brown-600 text-[11px] sm:text-xs mt-0.5">Pesanan disiapkan segar dan siap diambil sesuai tanggal yang disepakati.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <Link
                                :href="`/tracking?order_number=${order.order_number}`"
                                class="w-full px-4 py-3 bg-brand-500 hover:bg-brand-600 active:scale-[0.99] text-white rounded-xl font-bold text-xs sm:text-sm text-center shadow-sm transition inline-flex items-center justify-center gap-2"
                            >
                                <Truck class="w-4 h-4" />
                                <span>Lacak Pesanan</span>
                            </Link>

                            <a
                                :href="`/pdf/invoice/${order.order_number}`"
                                target="_blank"
                                class="w-full px-4 py-3 bg-white hover:bg-cream-100 text-brown-800 border border-cream-300 rounded-xl font-bold text-xs sm:text-sm text-center shadow-2xs transition inline-flex items-center justify-center gap-2"
                            >
                                <Download class="w-4 h-4 text-brand-600" />
                                <span>Unduh Invoice (PDF)</span>
                            </a>

                            <Link
                                href="/shop"
                                class="w-full px-4 py-3 bg-cream-200/70 hover:bg-cream-200 text-brown-800 rounded-xl font-bold text-xs sm:text-sm text-center transition inline-flex items-center justify-center gap-2"
                            >
                                <ArrowLeft class="w-4 h-4" />
                                <span>Belanja Lagi</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 bg-white rounded-2xl shadow-2xs border border-cream-200/80 p-5 md:p-6">
                    <h3 class="text-sm sm:text-base font-bold text-brown-900 mb-3 flex items-center gap-2">
                        <HelpCircle class="w-4 h-4 text-brand-600" />
                        <span>Pertanyaan Umum</span>
                    </h3>

                    <div class="space-y-3 text-xs sm:text-sm text-brown-700">
                        <div>
                            <p class="font-bold text-brown-900 mb-0.5">Berapa lama pesanan saya dikonfirmasi?</p>
                            <p class="text-brown-600 text-xs">Admin kami biasanya merespons dalam 15-60 menit pada jam kerja (08:00 - 18:00 WIB).</p>
                        </div>

                        <div>
                            <p class="font-bold text-brown-900 mb-0.5">Bagaimana jika ingin merubah jadwal pengambilan?</p>
                            <p class="text-brown-600 text-xs">Sampaikan kepada admin via WhatsApp sebelum produksi pesanan Anda dimulai.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Check, Copy, Banknote, Truck, Download, ArrowLeft, HelpCircle } from 'lucide-vue-next';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
    whatsappUrl: {
        type: String,
        required: true,
    },
});

const copied = ref(false);

const copyOrderNumber = async () => {
    try {
        await navigator.clipboard.writeText(props.order.order_number);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2500);
    } catch (err) {
        console.error('Failed to copy order number:', err);
    }
};

// Format number to IDR
const formatNumber = (num) => {
    return new Intl.NumberFormat('id-ID').format(num || 0);
};

// Format date
const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>
