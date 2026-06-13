<template>
    <Head title="Pesanan Saya - Snack Box" />
    <CustomerLayout>
        <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
            <!-- Header -->
            <section class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Pesanan Saya</h1>
                    <p class="text-gray-500 text-sm md:text-base mt-1">Kelola dan lacak semua pesanan Anda</p>
                </div>
            </section>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
                <!-- Filter Tabs (Mobile: Horizontal Scroll) -->
                <div class="mb-6 overflow-x-auto pb-2">
                    <div class="flex space-x-2">
                        <button
                            @click="filterStatus = null"
                            :class="[
                                'px-4 py-2 rounded-full font-medium whitespace-nowrap transition',
                                filterStatus === null
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            Semua ({{ orders.length }})
                        </button>
                        <button
                            @click="filterStatus = 'pending'"
                            :class="[
                                'px-4 py-2 rounded-full font-medium whitespace-nowrap transition',
                                filterStatus === 'pending'
                                    ? 'bg-yellow-500 text-white'
                                    : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            Menunggu ({{ pendingCount }})
                        </button>
                        <button
                            @click="filterStatus = 'confirmed'"
                            :class="[
                                'px-4 py-2 rounded-full font-medium whitespace-nowrap transition',
                                filterStatus === 'confirmed'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            Dikonfirmasi ({{ confirmedCount }})
                        </button>
                        <button
                            @click="filterStatus = 'completed'"
                            :class="[
                                'px-4 py-2 rounded-full font-medium whitespace-nowrap transition',
                                filterStatus === 'completed'
                                    ? 'bg-green-600 text-white'
                                    : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            Selesai ({{ completedCount }})
                        </button>
                    </div>
                </div>

                <!-- Orders List -->
                <div v-if="filteredOrders.length > 0" class="space-y-4 md:space-y-6">
                    <div
                        v-for="order in filteredOrders"
                        :key="order.id"
                        class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden"
                    >
                        <!-- Order Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 md:px-6 py-4 border-b border-gray-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Order Number -->
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Nomor Pesanan</p>
                                    <p class="font-mono font-bold text-gray-900 text-sm md:text-base mt-1">{{ order.order_number }}</p>
                                </div>

                                <!-- Date -->
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal Pesan</p>
                                    <p class="font-medium text-gray-900 text-sm md:text-base mt-1">{{ formatDate(order.created_at) }}</p>
                                </div>

                                <!-- Status Badge -->
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                                    <div class="mt-1">
                                        <span
                                            :class="[
                                                'inline-block px-3 py-1 rounded-full text-xs md:text-sm font-semibold',
                                                order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '',
                                                order.status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '',
                                                order.status === 'completed' ? 'bg-green-100 text-green-800' : ''
                                            ]"
                                        >
                                            {{ getStatusLabel(order.status) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Total Amount -->
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                                    <p class="font-bold text-blue-600 text-sm md:text-lg mt-1">Rp {{ formatNumber(order.total_amount) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details (Expandable) -->
                        <div class="px-4 md:px-6 py-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <!-- Customer Info -->
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Nama Pemesan</p>
                                    <p class="font-medium text-gray-900 text-sm md:text-base">{{ order.customer_name }}</p>
                                </div>

                                <!-- Pickup Date -->
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Pengambilan</p>
                                    <p class="font-medium text-gray-900 text-sm md:text-base">{{ formatDateTime(order.pickup_date) }}</p>
                                </div>

                                <!-- DP Amount -->
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">DP (50%)</p>
                                    <p class="font-medium text-orange-600 text-sm md:text-base">Rp {{ formatNumber(order.dp_amount) }}</p>
                                </div>

                                <!-- Remaining Amount -->
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Sisa Pembayaran</p>
                                    <p class="font-medium text-blue-600 text-sm md:text-base">Rp {{ formatNumber(order.total_amount - order.dp_amount) }}</p>
                                </div>
                            </div>

                            <!-- Items Section -->
                            <div class="bg-gray-50 rounded-lg p-4 mt-4">
                                <p class="font-semibold text-gray-900 mb-3 text-sm md:text-base">Produk ({{ order.items.length }} item)</p>

                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    <div
                                        v-for="item in order.items"
                                        :key="item.id"
                                        class="flex justify-between items-center text-xs md:text-sm py-2 border-b border-gray-200 last:border-b-0"
                                    >
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 line-clamp-1">{{ item.product.name }}</p>
                                            <p class="text-gray-500">{{ item.quantity }}x @ Rp {{ formatNumber(item.price_at_order) }}</p>
                                        </div>
                                        <p class="font-semibold text-gray-900 ml-2 flex-shrink-0">Rp {{ formatNumber(item.quantity * item.price_at_order) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-2 mt-4">
                                <button
                                    @click="copyOrderNumber(order.order_number)"
                                    class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium text-xs md:text-sm transition"
                                >
                                    📋 Salin No. Pesanan
                                </button>

                                <a
                                    v-if="order.status === 'pending'"
                                    :href="`/order/${order.order_number}/status`"
                                    class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-xs md:text-sm transition text-center"
                                >
                                    ✓ Lihat Detail
                                </a>

                                <a
                                    v-else
                                    :href="`/orders/${order.id}`"
                                    class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-xs md:text-sm transition text-center"
                                >
                                    → Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12 md:py-16">
                    <div class="bg-white rounded-xl shadow-sm p-8 md:p-12 max-w-md mx-auto">
                        <svg class="w-20 h-20 md:w-24 md:h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">Belum Ada Pesanan</h3>
                        <p class="text-gray-500 mb-6 text-sm md:text-base">Anda belum membuat pesanan apapun. Mulai belanja sekarang!</p>
                        <Link
                            href="/shop"
                            class="inline-block px-6 md:px-8 py-2 md:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm md:text-base"
                        >
                            Mulai Belanja
                        </Link>
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
    orders: {
        type: Array,
        default: () => [],
    },
});

const orders = ref(props.orders || []);
const filterStatus = ref(null);

// Filter orders
const filteredOrders = computed(() => {
    if (!filterStatus.value) {
        return orders.value;
    }
    return orders.value.filter(order => order.status === filterStatus.value);
});

// Count by status
const pendingCount = computed(() => orders.value.filter(o => o.status === 'pending').length);
const confirmedCount = computed(() => orders.value.filter(o => o.status === 'confirmed').length);
const completedCount = computed(() => orders.value.filter(o => o.status === 'completed').length);

// Format number to IDR
const formatNumber = (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
};

// Format date
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

// Format date time
const formatDateTime = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Get status label
const getStatusLabel = (status) => {
    const labels = {
        pending: 'Menunggu Konfirmasi',
        confirmed: 'Dikonfirmasi',
        completed: 'Selesai',
    };
    return labels[status] || status;
};

// Copy order number to clipboard
const copyOrderNumber = (orderNumber) => {
    navigator.clipboard.writeText(orderNumber);
};
</script>

<style scoped>
/* Smooth transitions */
button, a {
    transition: all 0.3s ease;
}

/* Scrollable items */
.max-h-40 {
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb #f3f4f6;
}

.max-h-40::-webkit-scrollbar {
    width: 4px;
}

.max-h-40::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 10px;
}

.max-h-40::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 10px;
}

.max-h-40::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}
</style>
