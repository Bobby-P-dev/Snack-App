<template>
    <Head title="Keranjang - Snack Box" />
    <CustomerLayout>
        <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
            <!-- Header -->
            <section class="bg-white border-b border-gray-200 sticky top-16 z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
                </div>
            </section>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
                <div v-if="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 md:px-6 py-4 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-700">{{ cartItems.length }} Produk di Keranjang</p>
                            </div>

                            <!-- Items List -->
                            <div class="divide-y divide-gray-100">
                                <div
                                    v-for="item in cartItems"
                                    :key="item.product_id"
                                    class="px-4 md:px-6 py-3 hover:bg-gray-50 transition"
                                >
                                    <div class="flex gap-3 items-center">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 bg-gray-100 rounded-lg overflow-hidden">
                                            <img
                                                v-if="item.product?.image_url"
                                                :src="getImageUrl(item.product.image_url)"
                                                :alt="item.product?.name || 'Product'"
                                                class="w-full h-full object-cover"
                                            />
                                            <div v-else class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0 flex items-center gap-3">
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ item.product?.name || 'Produk tidak ditemukan' }}
                                                </h3>
                                                <p class="text-xs text-gray-500">{{ item.product?.supplier?.name || '' }}</p>
                                            </div>

                                            <div class="text-right flex-shrink-0">
                                                <p class="text-sm font-bold text-blue-600">
                                                    Rp {{ formatNumber(item.product?.sell_price || 0) }}
                                                </p>
                                                <!-- Quantity Controls -->
                                                <div class="flex items-center bg-gray-100 rounded-md mt-1">
                                                    <button @click="updateQuantity(item.product_id, item.quantity - 1)" class="px-1.5 py-0.5 hover:bg-gray-200 transition rounded-l-md">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                                    </button>
                                                    <span class="px-2 py-0.5 font-semibold text-xs min-w-[20px] text-center">{{ item.quantity || 0 }}</span>
                                                    <button @click="updateQuantity(item.product_id, item.quantity + 1)" class="px-1.5 py-0.5 hover:bg-gray-200 transition rounded-r-md">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <button @click="removeItem(item.product_id)" class="text-red-400 hover:text-red-600 transition flex-shrink-0 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-24">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 md:px-6 py-4 border-b border-gray-200">
                                <h2 class="font-bold text-gray-900 text-lg">Ringkasan Pesanan</h2>
                            </div>

                            <!-- Summary Content -->
                            <div class="px-4 md:px-6 py-6 space-y-4">
                                <!-- Subtotal -->
                                <div class="flex justify-between items-center text-sm md:text-base">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-semibold text-gray-900">Rp {{ formatNumber(subtotal) }}</span>
                                </div>

                                <!-- Tax -->
                                <div class="flex justify-between items-center text-sm md:text-base">
                                    <span class="text-gray-600">Pajak (10%)</span>
                                    <span class="font-semibold text-gray-900">Rp {{ formatNumber(tax) }}</span>
                                </div>

                                <!-- Total -->
                                <div class="border-t-2 border-gray-200 pt-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600 text-sm md:text-base">Total</span>
                                        <span class="text-2xl md:text-3xl font-bold text-blue-600">Rp {{ formatNumber(total) }}</span>
                                    </div>
                                </div>

                                <!-- DP Info -->
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 md:p-4">
                                    <p class="text-xs md:text-sm text-orange-800">
                                        <strong>DP ({{ dpPercentage }}%):</strong> Rp {{ formatNumber(dp) }}
                                    </p>
                                    <p class="text-xs text-orange-700 mt-2">
                                        💡 Bayar DP sekarang via WhatsApp, saldo akhir saat pengambilan
                                    </p>
                                </div>

                                <!-- Checkout Button -->
                                <Link
                                    href="/checkout"
                                    class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition font-semibold text-center text-sm md:text-base mt-6"
                                >
                                    Lanjut ke Checkout
                                </Link>

                                <!-- Clear Cart Button -->
                                <button
                                    @click="clearCart"
                                    class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition font-medium text-sm md:text-base"
                                >
                                    Kosongkan Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart State -->
                <div v-else class="text-center py-12 md:py-20">
                    <div class="bg-white rounded-xl shadow-sm p-8 md:p-12 max-w-md mx-auto">
                        <svg class="w-20 h-20 md:w-24 md:h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">Keranjang Kosong</h3>
                        <p class="text-gray-500 mb-6 text-sm md:text-base">Mulai belanja dan tambahkan produk favorit Anda</p>
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

        <!-- Footer -->
        <Footer />
    </CustomerLayout>
</template>

<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed, watch, reactive } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import { useCartStore } from '@/Stores/CartStore.js';
import { getImageUrl } from '@/helpers.js';

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});
const dpPercentage = computed(() => parseInt(cms.value.dp_percentage) || 70);

const props = defineProps({
    cart: {
        type: [Array, Object], // Terima Array atau Object (jika index session Laravel bolong)
        default: () => [],
    },
});

// Helper untuk merubah data menjadi array aman
const getCartArray = (data) => {
    if (!data) return [];
    return Array.isArray(data) ? data : Object.values(data);
};

// Gunakan nama 'cartItems' agar tidak bentrok (shadowing) dengan props 'cart'
const cartItems = reactive([...getCartArray(props.cart)]);

// Watch for props changes (when page re-renders from server)
watch(() => props.cart, (newCart) => {
    cartItems.length = 0;
    cartItems.push(...getCartArray(newCart));
}, { deep: true });

// Calculate totals
const subtotal = computed(() => {
    return cartItems.reduce((sum, item) => {
        const itemPrice = item?.product?.sell_price || 0;
        const itemQty = item?.quantity || 0;
        return sum + (itemPrice * itemQty);
    }, 0);
});

const tax = computed(() => {
    return Math.round(subtotal.value * 0.1);
});

const total = computed(() => {
    return subtotal.value + tax.value;
});

const dp = computed(() => {
    return Math.round(total.value * (dpPercentage.value / 100));
});

// Format number to IDR
const formatNumber = (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
};

// Reload cart page
const reloadCart = () => {
    router.visit('/cart', {
        method: 'get',
        preserveState: false,
    });
};

// Update quantity
const updateQuantity = (productId, newQuantity) => {
    console.log('Updating quantity for product:', productId, 'New qty:', newQuantity);

    if (newQuantity <= 0) {
        removeItem(productId);
        return;
    }

    // Find item
    const item = cartItems.find(i => parseInt(i.product_id) === parseInt(productId));
    if (!item) {
        console.warn('Item not found:', productId);
        return;
    }

    // Store old quantity for rollback
    const oldQuantity = item.quantity;

    // Immediately update UI
    item.quantity = newQuantity;
    console.log('Updated quantity locally:', productId, 'to', newQuantity);

    // Then sync with server
    fetch('/cart/update-quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: newQuantity,
        }),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Update quantity response:', data);
        if (!data.success) {
            // If server update failed, rollback
            item.quantity = oldQuantity;
            console.warn('Server rejected update, rolled back to:', oldQuantity);
        }
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
        // On error, rollback
        item.quantity = oldQuantity;
        console.warn('Network error, rolled back to:', oldQuantity);
    });
};

// Remove item from cart
const removeItem = (productId) => {
    console.clear();
    console.log('%c=== REMOVE ITEM CALLED ===', 'color: red; font-size: 14px; font-weight: bold;');
    console.log('Button clicked with productId:', productId);
    console.log('productId type:', typeof productId);
    console.log('Current cart array:', JSON.stringify(cart));
    console.log('Cart length:', cart.length);

    if (!productId) {
        console.error('❌ productId is empty/undefined!');
        return;
    }

    console.log('Starting removal process...');

    // Find the index - be very explicit about type coercion
    const productIdNum = Number(productId);
    console.log('Converting productId to number:', productIdNum);

    const index = cartItems.findIndex((item, idx) => {
        const itemIdNum = Number(item.product_id);
        const match = itemIdNum === productIdNum;
        return match;
    });

    if (index === -1) {
        console.error('❌ Item NOT found in cart! This is the problem!');
        return;
    }

    // Remove using splice
    const removed = cartItems.splice(index, 1);

    // Sync with server
    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            product_id: productId,
        }),
    })
    .then(response => {
        console.log('Server response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Server response data:', data);
        if (!data.success) {
            console.warn('❌ Server rejected removal');
            reloadCart();
        } else {
            console.log('✓ Server confirmed removal');
        }
    })
    .catch(error => {
        console.error('❌ Fetch error:', error);
        reloadCart();
    })
    .finally(() => {
        console.log('%c=== REMOVE ITEM END ===', 'color: red; font-size: 14px; font-weight: bold;');
    });
};

// Clear cart
const clearCart = () => {
    if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
        // Store old cart for rollback
        const oldCart = JSON.parse(JSON.stringify(cartItems));

        // Immediately clear UI (optimistic update)
        cartItems.length = 0;

        // Then sync with server
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                // If server clear failed, rollback
                cartItems.length = 0;
                cartItems.push(...oldCart);
                console.warn('Server rejected clear, rolled back');
            }
        })
        .catch(error => {
            console.error('Error clearing cart:', error);
            // On error, rollback
            cartItems.length = 0;
            cartItems.push(...oldCart);
            console.warn('Network error, rolled back');
        });
    }
};
</script>

<style scoped>
/* Smooth transitions */
button {
    transition: all 0.3s ease;
}
</style>
