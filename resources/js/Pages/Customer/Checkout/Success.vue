<template>
    <Head title="Pesanan Berhasil - Snack Box" />
    <CustomerLayout>
        <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex items-center justify-center px-4 py-8 md:py-12">
            <div class="w-full max-w-2xl">
                <!-- Success Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Header with Success Icon -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-4 md:px-8 py-12 md:py-16 text-center">
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 md:w-24 md:h-24 bg-white rounded-full flex items-center justify-center animate-bounce">
                                <svg class="w-12 h-12 md:w-14 md:h-14 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Pesanan Berhasil!</h1>
                        <p class="text-green-100 text-base md:text-lg">Terima kasih telah memesan Snack Box Anda</p>
                    </div>

                    <!-- Content -->
                    <div class="px-4 md:px-8 py-8 md:py-12">
                        <!-- Order Details -->
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 md:p-8 mb-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">Nomor Pesanan Anda</h2>

                            <div class="bg-white rounded-lg p-6 md:p-8 border-2 border-blue-300 text-center mb-6">
                                <p class="text-gray-600 text-sm md:text-base mb-2">Order Number</p>
                                <p class="text-3xl md:text-4xl font-bold text-blue-600 font-mono break-all">
                                    {{ order.order_number }}
                                </p>
                            </div>

                            <!-- Order Summary -->
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                    <span class="text-gray-700 text-sm md:text-base">Nama Pemesan</span>
                                    <span class="font-semibold text-gray-900 text-sm md:text-base">{{ order.customer_name }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                    <span class="text-gray-700 text-sm md:text-base">Pengambilan</span>
                                    <span class="font-semibold text-gray-900 text-sm md:text-base">{{ formatDate(order.pickup_date) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-700 text-sm md:text-base">Total Pesanan</span>
                                    <span class="font-bold text-blue-600 text-lg md:text-xl">Rp {{ formatNumber(order.total_amount) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Items List -->
                        <div class="bg-gray-50 rounded-xl p-6 md:p-8 mb-8">
                            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-6">Produk yang Dipesan</h3>

                            <div class="space-y-4 max-h-64 overflow-y-auto">
                                <div
                                    v-for="item in order.items"
                                    :key="item.id"
                                    class="bg-white rounded-lg p-4 flex justify-between items-center"
                                >
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm md:text-base line-clamp-1">{{ item.product.name }}</p>
                                        <p class="text-gray-600 text-xs md:text-sm">{{ item.quantity }}x @ Rp {{ formatNumber(item.price_at_order) }}</p>
                                    </div>
                                    <p class="font-bold text-blue-600 text-sm md:text-base ml-2">Rp {{ formatNumber(item.quantity * item.price_at_order) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-6 md:p-8 mb-8">
                            <h3 class="text-lg md:text-xl font-bold text-orange-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                Informasi Pembayaran
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-orange-800 mb-1">Total DP (50%)</p>
                                    <p class="text-2xl md:text-3xl font-bold text-orange-600">Rp {{ formatNumber(order.dp_amount) }}</p>
                                </div>
                                <div class="text-sm text-orange-800">
                                    💰 <strong>Sisa pembayaran:</strong> Rp {{ formatNumber(order.total_amount - order.dp_amount) }} (dibayarkan saat pengambilan)
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp CTA -->
                        <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 md:p-8 mb-8">
                            <div class="text-center">
                                <h3 class="text-lg md:text-xl font-bold text-green-900 mb-4">Langkah Selanjutnya</h3>
                                <p class="text-green-800 text-sm md:text-base mb-6">
                                    Klik tombol di bawah untuk mengirim pesan ke admin kami dan lakukan pembayaran DP
                                </p>

                                <a
                                    :href="whatsappUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center w-full md:w-auto px-8 py-4 md:py-5 bg-green-600 hover:bg-green-700 text-white font-bold text-base md:text-lg rounded-xl transition duration-300 mb-4 md:mb-0"
                                >
                                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-5.031 1.378c-1.533.916-2.775 2.511-3.466 4.29-.691 1.78-.633 3.709.289 5.447.922 1.738 2.664 3.148 4.6 3.627 1.936.479 4.061.353 5.588-.471 1.527-.823 2.798-2.34 3.402-4.137.604-1.798.474-3.868-.424-5.569-.898-1.7-2.604-3.038-4.597-3.519-.601-.144-1.22-.217-1.843-.216zm10.906-9.569c-5.894-5.894-15.846-5.894-21.74 0-5.893 5.893-5.893 15.845 0 21.74 5.893 5.893 15.846 5.893 21.74 0 5.893-5.895 5.893-15.847 0-21.74zm-2.829 18.911c-2.511 2.511-5.869 3.894-9.436 3.894s-6.925-1.383-9.436-3.894c-2.51-2.511-3.893-5.869-3.893-9.436s1.383-6.925 3.893-9.436c2.511-2.51 5.869-3.893 9.436-3.893s6.925 1.383 9.436 3.893c2.51 2.511 3.893 5.869 3.893 9.436s-1.383 6.925-3.893 9.436z" />
                                    </svg>
                                    Hubungi Admin di WhatsApp
                                </a>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="bg-blue-50 rounded-xl p-6 md:p-8 mb-8">
                            <h3 class="text-lg md:text-xl font-bold text-blue-900 mb-4">Apa yang Akan Terjadi Selanjutnya?</h3>

                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-4 mt-0.5">1</div>
                                    <div>
                                        <p class="font-semibold text-blue-900 text-sm md:text-base">Kirim Pesan WhatsApp</p>
                                        <p class="text-blue-800 text-xs md:text-sm">Klik tombol di atas untuk mengirim detail pesanan</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-4 mt-0.5">2</div>
                                    <div>
                                        <p class="font-semibold text-blue-900 text-sm md:text-base">Admin Akan Mengkonfirmasi</p>
                                        <p class="text-blue-800 text-xs md:text-sm">Admin akan merespons dalam 1-2 jam dengan instruksi pembayaran</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-4 mt-0.5">3</div>
                                    <div>
                                        <p class="font-semibold text-blue-900 text-sm md:text-base">Transfer DP 50%</p>
                                        <p class="text-blue-800 text-xs md:text-sm">Lakukan transfer sesuai nomor rekening yang diberikan admin</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-4 mt-0.5">4</div>
                                    <div>
                                        <p class="font-semibold text-blue-900 text-sm md:text-base">Ambil Pesanan Anda</p>
                                        <p class="text-blue-800 text-xs md:text-sm">Datang pada tanggal dan jam yang telah disepakati</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <Link
                                href="/shop"
                                class="block px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold text-center transition"
                            >
                                ← Lanjut Belanja
                            </Link>
                            <Link
                                href="/orders"
                                class="block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-center transition"
                            >
                                Lihat Pesanan Saya →
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-8 bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">❓ Pertanyaan Umum</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="font-semibold text-gray-900 text-sm md:text-base mb-1">Berapa lama pesanan saya dikonfirmasi?</p>
                            <p class="text-gray-600 text-xs md:text-sm">Admin kami biasanya merespons dalam 1-2 jam di jam kerja (09:00-18:00)</p>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900 text-sm md:text-base mb-1">Apa jika saya ingin membatalkan?</p>
                            <p class="text-gray-600 text-xs md:text-sm">Pesanan dapat dibatalkan sebelum dikonfirmasi oleh admin tanpa biaya apapun</p>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900 text-sm md:text-base mb-1">Bagaimana jika ada masalah dengan pesanan?</p>
                            <p class="text-gray-600 text-xs md:text-sm">Hubungi admin melalui WhatsApp yang sama, kami siap membantu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
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

// Format number to IDR
const formatNumber = (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
};

// Format date
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<style scoped>
/* Animations */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-bounce {
    animation: bounce 2s infinite;
}

/* Smooth transitions */
a, button {
    transition: all 0.3s ease;
}
</style>
