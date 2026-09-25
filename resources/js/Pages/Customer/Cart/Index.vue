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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 pb-24 md:pb-12">
                <div v-if="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Cart Items Groups -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Kategori 1: Paket Snack Box (Custom) -->
                        <div v-if="snackBoxItems.length > 0" class="bg-white rounded-2xl shadow-sm border border-cream-200/80 overflow-hidden">
                            <!-- Category Header -->
                            <div class="bg-gradient-to-r from-amber-500/10 via-brand-50 to-cream-100 px-4 md:px-6 py-4 border-b border-amber-200/70 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shadow-2xs">
                                        <Package class="w-4 h-4 text-amber-800" />
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-sm sm:text-base font-bold text-brown-900 leading-tight">Paket Snack Box</h2>
                                            <span class="text-[10px] font-bold text-amber-800 bg-amber-100/90 border border-amber-200 px-2 py-0.5 rounded-full">Custom Box</span>
                                        </div>
                                        <p class="text-xs text-brown-500 font-medium mt-0.5">Kustomisasi aneka kue per box (min. {{ minOrderBox }} box)</p>
                                    </div>
                                </div>
                                <span class="text-xs sm:text-sm font-bold text-brand-700 bg-brand-50 border border-brand-200/80 px-2.5 py-1 rounded-full font-mono">
                                    Rp {{ formatNumber(snackBoxSubtotal) }}
                                </span>
                            </div>

                            <!-- Items List -->
                            <div class="divide-y divide-cream-100">
                                <div
                                    v-for="item in snackBoxItems"
                                    :key="item.box_group_id ? `${item.box_group_id}-${item.product_id}` : item.product_id"
                                    class="px-4 md:px-6 py-4 hover:bg-amber-50/20 transition"
                                >
                                    <div class="flex gap-3 sm:gap-4 items-center">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-cream-100 rounded-xl overflow-hidden border border-cream-200">
                                            <img
                                                v-if="item.product?.image_url"
                                                :src="getImageUrl(item.product.image_url)"
                                                :alt="item.product?.name || 'Product'"
                                                class="w-full h-full object-cover"
                                            />
                                            <div v-else class="w-full h-full flex items-center justify-center bg-cream-100 text-brown-400">
                                                <Image class="w-6 h-6 text-cream-400" />
                                            </div>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-1.5">
                                                    <h3 class="text-sm sm:text-base font-bold text-brown-900 truncate">
                                                        {{ item.product?.name || 'Produk' }}
                                                    </h3>
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-800 bg-amber-100 px-1.5 py-0.5 rounded">
                                                        <Package class="w-2.5 h-2.5" /> Snack Box
                                                    </span>
                                                </div>
                                                <p class="text-xs text-brown-500 mt-0.5">
                                                    Rp {{ formatNumber(item.product?.sell_price || 0) }} / box
                                                </p>
                                            </div>

                                            <div class="flex items-center justify-between sm:justify-end gap-3 sm:gap-4 flex-shrink-0">
                                                <p class="text-sm sm:text-base font-extrabold text-brand-600 font-mono">
                                                    Rp {{ formatNumber((item.product?.sell_price || 0) * (item.quantity || 0)) }}
                                                </p>
                                                <!-- Quantity Controls -->
                                                <div class="flex items-center bg-cream-100/90 rounded-lg p-0.5 border border-cream-200 shadow-2xs">
                                                    <button
                                                        :disabled="item.quantity <= minOrderBox"
                                                        @click="updateQuantity(item.product_id, item.quantity - 1, 'kustom_box', item.box_group_id)"
                                                        :class="[
                                                            'w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center transition rounded-md',
                                                            item.quantity <= minOrderBox
                                                                ? 'opacity-30 cursor-not-allowed text-brown-400'
                                                                : 'hover:bg-cream-200 text-brown-800 active:scale-95 cursor-pointer'
                                                        ]"
                                                        :title="`Minimal ${minOrderBox} box`"
                                                        aria-label="Kurangi jumlah box"
                                                    >
                                                        <Minus class="w-3.5 h-3.5" />
                                                    </button>
                                                    <span class="px-2 font-bold text-xs sm:text-sm min-w-[28px] text-center text-brown-900 font-mono">{{ item.quantity || 0 }}</span>
                                                    <button
                                                        @click="updateQuantity(item.product_id, item.quantity + 1, 'kustom_box', item.box_group_id)"
                                                        class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center hover:bg-cream-200 text-brown-800 transition rounded-md active:scale-95 cursor-pointer"
                                                        aria-label="Tambah jumlah box"
                                                    >
                                                        <Plus class="w-3.5 h-3.5" />
                                                    </button>
                                                </div>

                                                <button
                                                    @click="removeItem(item.product_id, 'kustom_box', item.box_group_id)"
                                                    class="text-red-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition cursor-pointer"
                                                    title="Hapus dari paket"
                                                    aria-label="Hapus item"
                                                >
                                                    <Trash2 class="w-4 h-4 sm:w-5 sm:h-5" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori 2: Kue Satuan & Eceran -->
                        <div v-if="satuanItems.length > 0" class="bg-white rounded-2xl shadow-sm border border-cream-200/80 overflow-hidden">
                            <!-- Category Header -->
                            <div class="bg-gradient-to-r from-rose-500/10 via-cream-50 to-amber-50/60 px-4 md:px-6 py-4 border-b border-rose-200/70 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-800 flex items-center justify-center shadow-2xs">
                                        <CakeSlice class="w-4 h-4 text-rose-800" />
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-sm sm:text-base font-bold text-brown-900 leading-tight">Kue Satuan & Eceran</h2>
                                            <span class="text-[10px] font-bold text-rose-800 bg-rose-100/90 border border-rose-200 px-2 py-0.5 rounded-full">Kue Tradisional & Pastry</span>
                                        </div>
                                        <p class="text-xs text-brown-500 font-medium mt-0.5">Pemesanan satuan eceran (min. {{ minOrderSatuan }} pcs per jenis)</p>
                                    </div>
                                </div>
                                <span class="text-xs sm:text-sm font-bold text-brand-700 bg-brand-50 border border-brand-200/80 px-2.5 py-1 rounded-full font-mono">
                                    Rp {{ formatNumber(satuanSubtotal) }}
                                </span>
                            </div>

                            <!-- Items List -->
                            <div class="divide-y divide-cream-100">
                                <div
                                    v-for="item in satuanItems"
                                    :key="item.product_id"
                                    class="px-4 md:px-6 py-4 hover:bg-cream-50/40 transition"
                                >
                                    <div class="flex gap-3 sm:gap-4 items-center">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-cream-100 rounded-xl overflow-hidden border border-cream-200">
                                            <img
                                                v-if="item.product?.image_url"
                                                :src="getImageUrl(item.product.image_url)"
                                                :alt="item.product?.name || 'Product'"
                                                class="w-full h-full object-cover"
                                            />
                                            <div v-else class="w-full h-full flex items-center justify-center bg-cream-100 text-brown-400">
                                                <Image class="w-6 h-6 text-cream-400" />
                                            </div>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-1.5">
                                                    <h3 class="text-sm sm:text-base font-bold text-brown-900 truncate">
                                                        {{ item.product?.name || 'Produk tidak ditemukan' }}
                                                    </h3>
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-800 bg-rose-100 px-1.5 py-0.5 rounded">
                                                        <CakeSlice class="w-2.5 h-2.5" /> Kue Satuan
                                                    </span>
                                                </div>
                                                <p v-if="item.product?.category" class="text-xs text-brown-500 mt-0.5">
                                                    {{ item.product.category.name }}
                                                </p>
                                            </div>

                                            <div class="flex items-center justify-between sm:justify-end gap-3 sm:gap-4 flex-shrink-0">
                                                <p class="text-sm sm:text-base font-extrabold text-brand-600 font-mono">
                                                    Rp {{ formatNumber((item.product?.sell_price || 0) * (item.quantity || 0)) }}
                                                </p>
                                                <!-- Quantity Controls -->
                                                <div class="flex items-center bg-cream-100/90 rounded-lg p-0.5 border border-cream-200 shadow-2xs">
                                                    <button
                                                        :disabled="item.quantity <= minOrderSatuan"
                                                        @click="updateQuantity(item.product_id, item.quantity - 1, 'satuan')"
                                                        :class="[
                                                            'w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center transition rounded-md',
                                                            item.quantity <= minOrderSatuan
                                                                ? 'opacity-30 cursor-not-allowed text-brown-400'
                                                                : 'hover:bg-cream-200 text-brown-800 active:scale-95 cursor-pointer'
                                                        ]"
                                                        :title="`Minimal ${minOrderSatuan} pcs untuk kue satuan`"
                                                        aria-label="Kurangi jumlah"
                                                    >
                                                        <Minus class="w-3.5 h-3.5" />
                                                    </button>
                                                    <span class="px-2 font-bold text-xs sm:text-sm min-w-[28px] text-center text-brown-900 font-mono">{{ item.quantity || 0 }}</span>
                                                    <button
                                                        @click="updateQuantity(item.product_id, item.quantity + 1, 'satuan')"
                                                        class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center hover:bg-cream-200 text-brown-800 transition rounded-md active:scale-95 cursor-pointer"
                                                        aria-label="Tambah jumlah"
                                                    >
                                                        <Plus class="w-3.5 h-3.5" />
                                                    </button>
                                                </div>

                                                <button
                                                    @click="removeItem(item.product_id, 'satuan')"
                                                    class="text-red-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition cursor-pointer"
                                                    title="Hapus item"
                                                    aria-label="Hapus item"
                                                >
                                                    <Trash2 class="w-4 h-4 sm:w-5 sm:h-5" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm border border-cream-200/80 overflow-hidden sticky top-24">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-cream-50 to-amber-50/60 px-4 md:px-6 py-4 border-b border-cream-200">
                                <h2 class="font-bold text-brown-900 text-base sm:text-lg">Ringkasan Pesanan</h2>
                            </div>

                            <!-- Summary Content -->
                            <div class="px-4 md:px-6 py-6 space-y-4">
                                <!-- Category Subtotals if both exist -->
                                <div v-if="hasBothCategories" class="space-y-2 pb-3 border-b border-cream-200/70 text-xs">
                                    <div class="flex justify-between text-brown-600">
                                        <span class="flex items-center gap-1.5 font-medium"><Package class="w-3.5 h-3.5 text-amber-700" /> Subtotal Snack Box</span>
                                        <span class="font-bold text-brown-800 font-mono">Rp {{ formatNumber(snackBoxSubtotal) }}</span>
                                    </div>
                                    <div class="flex justify-between text-brown-600">
                                        <span class="flex items-center gap-1.5 font-medium"><CakeSlice class="w-3.5 h-3.5 text-rose-700" /> Subtotal Kue Satuan</span>
                                        <span class="font-bold text-brown-800 font-mono">Rp {{ formatNumber(satuanSubtotal) }}</span>
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="flex justify-between items-center text-sm md:text-base">
                                    <span class="text-brown-600">Total Subtotal</span>
                                    <span class="font-bold text-brown-900 font-mono">Rp {{ formatNumber(subtotal) }}</span>
                                </div>

                                <!-- Total -->
                                <div class="border-t border-cream-200 pt-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-brown-600 text-sm md:text-base font-medium">Total Tagihan</span>
                                        <span class="text-2xl md:text-3xl font-extrabold text-brand-600 font-mono">Rp {{ formatNumber(total) }}</span>
                                    </div>
                                </div>

                                <!-- Opsi Pembayaran (DP 70% vs Full) -->
                                <div class="border-t border-cream-200 pt-4 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-xs font-bold text-brown-800 uppercase tracking-wider">Opsi Pembayaran</label>
                                        <span class="text-[11px] font-semibold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-full">
                                            {{ paymentType === 'full' ? 'Bayar Penuh' : `DP (${dpPercentage}%)` }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2.5">
                                        <!-- Opsi DP 70% -->
                                        <button
                                            type="button"
                                            @click="setPaymentType('dp')"
                                            :class="[
                                                'p-3 rounded-2xl border text-left transition-all cursor-pointer relative',
                                                paymentType === 'dp'
                                                    ? 'border-brand-500 bg-brand-50/70 shadow-xs ring-2 ring-brand-500/15'
                                                    : 'border-cream-200 bg-white hover:bg-cream-50/50 text-brown-700'
                                            ]"
                                        >
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-bold text-brown-900">DP {{ dpPercentage }}%</span>
                                                <span v-if="paymentType === 'dp'" class="w-4 h-4 rounded-full bg-brand-500 text-white flex items-center justify-center">
                                                    <Check class="w-2.5 h-2.5" />
                                                </span>
                                            </div>
                                            <p class="text-xs font-black text-brand-600 font-mono">Rp {{ formatNumber(dp) }}</p>
                                            <p class="text-[10px] text-brown-500 mt-1 leading-tight">Sisa saat ambil</p>
                                        </button>

                                        <!-- Opsi Bayar Full (100%) -->
                                        <button
                                            type="button"
                                            @click="setPaymentType('full')"
                                            :class="[
                                                'p-3 rounded-2xl border text-left transition-all cursor-pointer relative',
                                                paymentType === 'full'
                                                    ? 'border-emerald-500 bg-emerald-50/70 shadow-xs ring-2 ring-emerald-500/15'
                                                    : 'border-cream-200 bg-white hover:bg-cream-50/50 text-brown-700'
                                            ]"
                                        >
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-bold text-brown-900">Bayar Full</span>
                                                <span v-if="paymentType === 'full'" class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center">
                                                    <Check class="w-2.5 h-2.5" />
                                                </span>
                                            </div>
                                            <p class="text-xs font-black text-emerald-600 font-mono">Rp {{ formatNumber(total) }}</p>
                                            <p class="text-[10px] text-emerald-600 font-medium mt-1 leading-tight">Lunas langsung (100%)</p>
                                        </button>
                                    </div>
                                </div>

                                <!-- Payment Breakdown Info -->
                                <div 
                                    v-if="paymentType === 'dp'"
                                    class="bg-amber-50/80 border border-amber-200 rounded-xl p-3.5 md:p-4 space-y-1.5"
                                >
                                    <div class="flex justify-between text-xs md:text-sm text-brown-900 font-semibold">
                                        <span>Uang Muka / DP ({{ dpPercentage }}%):</span>
                                        <span class="text-brand-600 font-bold font-mono">Rp {{ formatNumber(dp) }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs text-brown-600 border-t border-dashed border-amber-200/80 pt-1.5 font-medium">
                                        <span>Sisa Pelunasan Saat Ambil:</span>
                                        <span class="font-bold font-mono text-brown-800">Rp {{ formatNumber(Math.max(0, total - dp)) }}</span>
                                    </div>
                                    <p class="text-[11px] text-brown-500 mt-1 leading-relaxed">
                                        💡 Bayar DP via WhatsApp setelah konfirmasi, pelunasan saat pesanan diambil.
                                    </p>
                                </div>
                                <div 
                                    v-else
                                    class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-3.5 md:p-4 space-y-1.5"
                                >
                                    <div class="flex justify-between text-xs md:text-sm text-emerald-900 font-semibold">
                                        <span>Pembayaran Penuh (100%):</span>
                                        <span class="text-emerald-700 font-bold font-mono">Rp {{ formatNumber(total) }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs text-emerald-700 border-t border-dashed border-emerald-200/80 pt-1.5 font-medium">
                                        <span>Sisa Tagihan Saat Ambil:</span>
                                        <span class="font-bold font-mono text-emerald-800">Rp 0 (LUNAS)</span>
                                    </div>
                                    <p class="text-[11px] text-emerald-600 mt-1 leading-relaxed">
                                        ✨ Pesanan langsung lunas, tidak perlu melakukan pembayaran lagi saat pengambilan.
                                    </p>
                                </div>

                                <!-- Checkout Button -->
                                <Link
                                    :href="`/checkout?payment_type=${paymentType}`"
                                    class="block w-full bg-brand-500 hover:bg-brand-600 active:scale-[0.99] text-white py-3.5 px-4 rounded-xl shadow-md hover:shadow-lg transition font-bold text-center text-sm md:text-base mt-4"
                                >
                                    Lanjut ke Checkout
                                </Link>

                                <!-- Clear Cart Button -->
                                <button
                                    @click="clearCart"
                                    class="w-full bg-cream-100 hover:bg-cream-200 text-brown-700 py-2.5 px-4 rounded-xl transition font-medium text-xs sm:text-sm"
                                >
                                    Kosongkan Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart State -->
                <div v-else class="text-center py-12 md:py-20">
                    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-8 md:p-12 max-w-md mx-auto">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-cream-100 rounded-full flex items-center justify-center mx-auto text-brand-500 mb-5">
                            <ShoppingCart class="w-8 h-8 sm:w-10 sm:h-10 text-brand-500" />
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-brown-900 mb-2">Keranjang Belanja Kosong</h3>
                        <p class="text-brown-600 mb-6 text-sm md:text-base leading-relaxed">Yuk pilih aneka snack dan kue lezat atau buat custom snack box Anda sendiri!</p>
                        <Link
                            href="/shop"
                            class="inline-block px-8 py-3 bg-brand-500 hover:bg-brand-600 text-white rounded-xl shadow-md hover:shadow-lg transition font-bold text-sm md:text-base"
                        >
                            Mulai Belanja Sekarang
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
import { Image, Minus, Plus, Trash2, ShoppingCart, Check, Package, CakeSlice } from 'lucide-vue-next';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import { useCartStore } from '@/Stores/CartStore.js';
import { getImageUrl } from '@/helpers.js';

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});
const dpPercentage = computed(() => parseInt(cms.value.dp_percentage) || 70);
const minOrderBox = computed(() => parseInt(cms.value.min_order_box) || 10);
const minOrderSatuan = computed(() => parseInt(cms.value.min_order_satuan) || 10);

const cartStore = useCartStore();
const { paymentType, setPaymentType } = cartStore;

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

// Category separation
const snackBoxItems = computed(() => cartItems.filter(item => item.type === 'kustom_box'));
const satuanItems = computed(() => cartItems.filter(item => (item.type || 'satuan') !== 'kustom_box'));
const hasBothCategories = computed(() => snackBoxItems.value.length > 0 && satuanItems.value.length > 0);

const snackBoxSubtotal = computed(() => {
    return snackBoxItems.value.reduce((sum, item) => {
        const itemPrice = item?.product?.sell_price || 0;
        const itemQty = item?.quantity || 0;
        return sum + (itemPrice * itemQty);
    }, 0);
});

const satuanSubtotal = computed(() => {
    return satuanItems.value.reduce((sum, item) => {
        const itemPrice = item?.product?.sell_price || 0;
        const itemQty = item?.quantity || 0;
        return sum + (itemPrice * itemQty);
    }, 0);
});

// Calculate totals
const subtotal = computed(() => {
    return cartItems.reduce((sum, item) => {
        const itemPrice = item?.product?.sell_price || 0;
        const itemQty = item?.quantity || 0;
        return sum + (itemPrice * itemQty);
    }, 0);
});

const total = computed(() => {
    return subtotal.value;
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
const updateQuantity = (productId, newQuantity, type = null, boxGroupId = null) => {
    if (newQuantity <= 0) {
        removeItem(productId, type, boxGroupId);
        return;
    }

    // Find item
    const item = cartItems.find(i => 
        parseInt(i.product_id) === parseInt(productId) &&
        (!type || (i.type || 'satuan') === type) &&
        (!boxGroupId || i.box_group_id == boxGroupId)
    );
    if (!item) {
        return;
    }

    const oldQuantity = item.quantity;
    item.quantity = newQuantity;

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
            type: type,
            box_group_id: boxGroupId,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            item.quantity = oldQuantity;
        } else {
            cartStore.updateQty(productId, newQuantity, type, boxGroupId);
        }
    })
    .catch(() => {
        item.quantity = oldQuantity;
    });
};

// Remove item from cart
const removeItem = (productId, type = null, boxGroupId = null) => {
    if (!productId) return;

    const index = cartItems.findIndex(i => 
        parseInt(i.product_id) === parseInt(productId) &&
        (!type || (i.type || 'satuan') === type) &&
        (!boxGroupId || i.box_group_id == boxGroupId)
    );

    if (index === -1) return;

    cartItems.splice(index, 1);

    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            product_id: productId,
            type: type,
            box_group_id: boxGroupId,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            reloadCart();
        } else {
            cartStore.removeItem(productId, type, boxGroupId);
        }
    })
    .catch(() => {
        reloadCart();
    });
};

// Clear cart
const clearCart = () => {
    if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
        const oldCart = JSON.parse(JSON.stringify(cartItems));
        cartItems.length = 0;

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
                cartItems.push(...oldCart);
            } else {
                cartStore.clearCart();
            }
        })
        .catch(() => {
            cartItems.push(...oldCart);
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
