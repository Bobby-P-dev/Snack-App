<template>
    <Head title="Checkout - Snack Box" />
    <CustomerLayout>
        <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
            <!-- Header -->
            <section class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Checkout Pesanan</h1>
                </div>
            </section>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Form -->
                    <div class="lg:col-span-2">
                        <form @submit.prevent="submitOrder" class="space-y-6">
                            <!-- Personal Info Card -->
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 md:px-6 py-4 border-b border-gray-200">
                                    <h2 class="font-bold text-gray-900 text-lg flex items-center">
                                        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm mr-3">1</span>
                                        Informasi Pemesan
                                    </h2>
                                </div>

                                <div class="px-4 md:px-6 py-6 space-y-4">
                                    <!-- Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                        <input
                                            v-model="form.customer_name"
                                            type="text"
                                            required
                                            class="w-full px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm md:text-base"
                                            placeholder="Masukkan nama Anda"
                                        />
                                        <p v-if="errors.customer_name" class="text-red-500 text-xs md:text-sm mt-1">{{ errors.customer_name[0] }}</p>
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                                        <input
                                            v-model="form.customer_phone"
                                            type="tel"
                                            required
                                            class="w-full px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm md:text-base"
                                            placeholder="Contoh: 08123456789"
                                        />
                                        <p v-if="errors.customer_phone" class="text-red-500 text-xs md:text-sm mt-1">{{ errors.customer_phone[0] }}</p>
                                        <p class="text-gray-500 text-xs md:text-sm mt-1">💬 Kami akan menghubungi Anda via WhatsApp</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pickup Info Card -->
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 md:px-6 py-4 border-b border-gray-200">
                                    <h2 class="font-bold text-gray-900 text-lg flex items-center">
                                        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm mr-3">2</span>
                                        Tanggal Pengambilan
                                    </h2>
                                </div>

                                <div class="px-4 md:px-6 py-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal & Jam</label>
                                        <input
                                            v-model="form.pickup_date"
                                            type="datetime-local"
                                            required
                                            class="w-full px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm md:text-base"
                                        />
                                        <p v-if="errors.pickup_date" class="text-red-500 text-xs md:text-sm mt-1">{{ errors.pickup_date[0] }}</p>
                                        <p class="text-gray-500 text-xs md:text-sm mt-2">
                                            ⏰ Pilih tanggal minimal besok, jam kerja kami 09:00 - 18:00
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Card -->
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 md:px-6 py-4 border-b border-gray-200">
                                    <h2 class="font-bold text-gray-900 text-lg flex items-center">
                                        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm mr-3">3</span>
                                        Ringkasan Pesanan ({{ cart.length }} Produk)
                                    </h2>
                                </div>

                                <div class="px-4 md:px-6 py-4 divide-y divide-gray-200 max-h-64 overflow-y-auto">
                                    <div
                                        v-for="item in cart"
                                        :key="item.product_id"
                                        class="py-3 flex justify-between items-center text-sm md:text-base"
                                    >
                                        <div>
                                            <p class="font-medium text-gray-900">{{ item.product.name }}</p>
                                            <p class="text-gray-500 text-xs md:text-sm">{{ item.quantity }}x @ Rp {{ formatNumber(item.product.sell_price) }}</p>
                                        </div>
                                        <p class="font-semibold text-blue-600">Rp {{ formatNumber(item.quantity * item.product.sell_price) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 md:p-6">
                                <label class="flex items-start space-x-3 cursor-pointer">
                                    <input
                                        v-model="form.terms_agreed"
                                        type="checkbox"
                                        required
                                        class="mt-1 w-4 h-4 accent-blue-600 rounded"
                                    />
                                    <span class="text-xs md:text-sm text-gray-700">
                                        Saya setuju untuk membayar DP 50% via WhatsApp dan saldo akhir saat pengambilan. Pesanan tidak dapat dibatalkan setelah dikonfirmasi.
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="loading"
                                class="w-full bg-blue-600 text-white py-3 md:py-4 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition font-semibold text-base md:text-lg"
                            >
                                <span v-if="!loading">Lanjutkan ke WhatsApp</span>
                                <span v-else>Memproses...</span>
                            </button>

                            <!-- Back to Cart -->
                            <Link
                                href="/cart"
                                class="block text-center text-blue-600 hover:text-blue-700 font-medium text-sm md:text-base"
                            >
                                ← Kembali ke Keranjang
                            </Link>
                        </form>
                    </div>

                    <!-- Summary Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-24">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-green-50 to-green-100 px-4 md:px-6 py-4 border-b border-gray-200">
                                <h2 class="font-bold text-gray-900 text-lg">Total Pembayaran</h2>
                            </div>

                            <!-- Summary Content -->
                            <div class="px-4 md:px-6 py-6 space-y-4">
                                <!-- Breakdown -->
                                <div class="space-y-3 pb-4 border-b border-gray-200">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="font-semibold">Rp {{ formatNumber(subtotal) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Pajak (10%)</span>
                                        <span class="font-semibold">Rp {{ formatNumber(tax) }}</span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div>
                                    <p class="text-gray-600 text-sm mb-2">Total Pembayaran</p>
                                    <p class="text-3xl md:text-4xl font-bold text-blue-600">
                                        Rp {{ formatNumber(total) }}
                                    </p>
                                </div>

                                <!-- DP Info -->
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                    <p class="text-sm font-semibold text-orange-900 mb-2">Pembayaran DP</p>
                                    <p class="text-2xl md:text-3xl font-bold text-orange-600 mb-3">
                                        Rp {{ formatNumber(dp) }}
                                    </p>
                                    <p class="text-xs text-orange-800">
                                        Bayar 50% sekarang via WhatsApp, saldo Rp {{ formatNumber(remainingPayment) }} saat pengambilan
                                    </p>
                                </div>

                                <!-- Payment Method -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-sm font-semibold text-blue-900 mb-2">Metode Pembayaran</p>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17 10.5V7a1 1 0 00-1-1H5a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1v-3.5l4 4v-11l-4 4z" />
                                        </svg>
                                        <span class="text-sm font-medium text-blue-900">WhatsApp Chat</span>
                                    </div>
                                </div>

                                <!-- Info Box -->
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="text-xs md:text-sm text-gray-700 space-y-2">
                                        <span class="block">✓ Pesanan akan dikonfirmasi via WhatsApp</span>
                                        <span class="block">✓ Pembayaran DP dikirim langsung ke nomor admin</span>
                                        <span class="block">✓ Saldo akhir dibayarkan saat pengambilan</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    cart: {
        type: Array,
        default: () => [],
    },
});

const cart = ref(props.cart);
const loading = ref(false);
const errors = ref({});

const form = ref({
    customer_name: '',
    customer_phone: '',
    pickup_date: '',
    items: props.cart,
    terms_agreed: false,
});

// Calculate totals
const subtotal = computed(() => {
    return cart.value.reduce((sum, item) => sum + (item.quantity * item.product.sell_price), 0);
});

const tax = computed(() => {
    return Math.round(subtotal.value * 0.1);
});

const total = computed(() => {
    return subtotal.value + tax.value;
});

const dp = computed(() => {
    return Math.round(total.value * 0.5);
});

const remainingPayment = computed(() => {
    return total.value - dp.value;
});

// Format number to IDR
const formatNumber = (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
};

// Submit order
const submitOrder = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const response = await fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();

        if (response.ok) {
            // Redirect to WhatsApp
            if (data.whatsappUrl) {
                window.location.href = data.whatsappUrl;
            }
        } else if (response.status === 422) {
            errors.value = data.errors || {};
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses pesanan');
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
/* Smooth transitions */
button, input, label {
    transition: all 0.3s ease;
}
</style>
