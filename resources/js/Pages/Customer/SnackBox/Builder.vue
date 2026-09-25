<template>
    <Head :title="`Rakit ${package.name} - Padu Kue`" />
    <CustomerLayout>
        <div class="min-h-screen bg-cream-100 py-6 sm:py-10 px-4 sm:px-6 lg:px-8 pb-48 md:pb-12">
            <div class="max-w-7xl mx-auto">
                <!-- Breadcrumbs -->
                <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li>
                            <Link href="/" class="hover:text-brand-600 transition">Beranda</Link>
                        </li>
                        <li>/</li>
                        <li>
                            <Link href="/snack-box" class="hover:text-brand-600 transition">Snack Box</Link>
                        </li>
                        <li>/</li>
                        <li class="text-gray-800 font-semibold">{{ package.name }}</li>
                    </ol>
                </nav>

                <!-- Top Box Progress Header -->
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-orange-100/80 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ package.name }}</h1>
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-bold transition',
                                    isBoxFull
                                        ? 'bg-green-100 text-green-800 border border-green-200'
                                        : 'bg-orange-100 text-brand-800 border border-orange-200'
                                ]">
                                    {{ selectedCount }} / {{ package.capacity }} Kue Dipilih
                                </span>
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                Pilih <strong>{{ package.capacity }} kue</strong> untuk dimasukkan ke dalam setiap box pesanan Anda.
                            </p>
                        </div>

                        <!-- Visual Slots Indicator -->
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <div
                                v-for="slotIdx in package.capacity"
                                :key="slotIdx"
                                :class="[
                                    'w-11 h-11 rounded-2xl flex items-center justify-center font-bold text-xs transition-all duration-300',
                                    slotIdx <= selectedCount
                                        ? 'bg-brand-500 text-white shadow-sm ring-2 ring-brand-300'
                                        : 'bg-gray-100 text-gray-400 border border-dashed border-gray-300'
                                ]"
                            >
                                <span v-if="slotIdx <= selectedCount">✓</span>
                                <span v-else>{{ slotIdx }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Category Pills -->
                    <div class="flex gap-2 overflow-x-auto pt-6 border-t border-gray-100 mt-6 scrollbar-none">
                        <button
                            @click="activeCategory = null"
                            :class="[
                                'px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition',
                                activeCategory === null
                                    ? 'bg-brand-500 text-white shadow-sm'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                            ]"
                        >
                            Semua Kue ({{ products.length }})
                        </button>
                        <button
                            v-for="cat in categories"
                            :key="cat.id"
                            @click="activeCategory = cat.id"
                            :class="[
                                'px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition',
                                activeCategory === cat.id
                                    ? 'bg-brand-500 text-white shadow-sm'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                            ]"
                        >
                            {{ cat.name }}
                        </button>
                    </div>
                </div>

                <!-- Main Layout: 2 Columns on Desktop -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Cake Catalogue Grid (2 Columns) -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div
                                v-for="product in filteredProducts"
                                :key="product.id"
                                @click="toggleItem(product)"
                                :class="[
                                    'bg-white rounded-2xl p-3.5 sm:p-4 border transition-all duration-200 flex flex-col justify-between shadow-sm relative group cursor-pointer select-none',
                                    getItemCount(product.id) > 0
                                        ? 'border-brand-500 ring-2 ring-brand-100 shadow-md bg-brand-50/10'
                                        : isBoxFull
                                            ? 'border-gray-100 opacity-60 hover:opacity-90'
                                            : 'border-gray-100 hover:border-brand-300 hover:shadow-md'
                                ]"
                            >
                                <!-- Product Image -->
                                <div class="aspect-square rounded-xl bg-cream-50 overflow-hidden mb-3 relative">
                                    <img
                                        v-if="product.image_url"
                                        :src="getImageUrl(product.image_url)"
                                        :alt="product.name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                        <Image class="w-10 h-10 text-gray-300 stroke-1" />
                                    </div>

                                    <!-- Picked badge with unselect hint -->
                                    <button
                                        v-if="getItemCount(product.id) > 0"
                                        type="button"
                                        @click.stop="toggleItem(product)"
                                        class="absolute top-2 right-2 bg-brand-500 hover:bg-rose-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-extrabold shadow transition-colors cursor-pointer group/badge"
                                        title="Klik untuk membatalkan pilihan kue ini"
                                        aria-label="Batalkan pilihan"
                                    >
                                        <span class="group-hover/badge:hidden">{{ getItemCount(product.id) }}</span>
                                        <X class="w-3.5 h-3.5 hidden group-hover/badge:block stroke-[2.5]" />
                                    </button>
                                </div>

                                <!-- Product Info -->
                                <div>
                                    <h3 class="font-bold text-gray-900 text-xs sm:text-sm line-clamp-1 mb-1">
                                        {{ product.name }}
                                    </h3>
                                    <p class="text-brand-600 font-extrabold text-xs sm:text-sm font-mono">
                                        Rp {{ formatNumber(product.sell_price) }}
                                    </p>
                                </div>

                                <!-- Controls -->
                                <div @click.stop class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                                    <button
                                        @click="decreaseItem(product.id)"
                                        :disabled="getItemCount(product.id) === 0"
                                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 disabled:opacity-30 disabled:cursor-not-allowed text-gray-700 flex items-center justify-center transition font-bold cursor-pointer"
                                        aria-label="Kurang"
                                    >
                                        -
                                    </button>
                                    <span class="font-bold text-xs sm:text-sm text-gray-800">
                                        {{ getItemCount(product.id) }}
                                    </span>
                                    <button
                                        @click="increaseItem(product)"
                                        :disabled="isBoxFull"
                                        class="w-8 h-8 rounded-lg bg-brand-500 hover:bg-brand-600 disabled:opacity-30 disabled:cursor-not-allowed text-white flex items-center justify-center transition font-bold cursor-pointer"
                                        aria-label="Tambah"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Sticky Order Summary & Box Multiplier (1 Column) -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-3xl p-6 border border-orange-100/80 shadow-sm sticky top-24 space-y-6">
                            <h2 class="font-bold text-gray-900 text-lg border-b border-gray-100 pb-3">
                                Ringkasan Rakitan Box
                            </h2>

                            <!-- Selected Items List -->
                            <div v-if="selectedItemsArray.length > 0" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                <div
                                    v-for="item in selectedItemsArray"
                                    :key="item.product_id"
                                    class="flex items-center justify-between text-xs sm:text-sm py-1.5 px-2 border-b border-gray-100 rounded-xl hover:bg-cream-50/70 transition group/summary-item"
                                >
                                    <div class="flex items-center gap-2 min-w-0 pr-2">
                                        <button
                                            type="button"
                                            @click.stop="removeItem(item.product_id)"
                                            class="w-5 h-5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-500 hover:text-rose-700 flex items-center justify-center shrink-0 transition cursor-pointer"
                                            title="Hapus kue ini dari rakitan box"
                                            aria-label="Hapus kue"
                                        >
                                            <X class="w-3 h-3 stroke-[2.5]" />
                                        </button>
                                        <span class="text-gray-800 font-medium truncate">
                                            {{ item.quantity }}x {{ item.name }}
                                        </span>
                                    </div>
                                    <span class="font-semibold text-gray-900 font-mono shrink-0">
                                        Rp {{ formatNumber(item.price * item.quantity) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-500 pt-1 px-2">
                                    <span>Kemasan Box & Tisu</span>
                                    <span>Rp {{ formatNumber(package.box_price || 2500) }}</span>
                                </div>
                            </div>

                            <div v-else class="text-center py-6 text-gray-400 text-xs">
                                <p>Belum ada kue dipilih.</p>
                                <p class="mt-1">Pilih {{ package.capacity }} kue dari daftar di samping.</p>
                            </div>

                            <!-- Box Multiplier -->
                            <div class="bg-cream-50 p-4 rounded-2xl border border-orange-100 space-y-2.5">
                                <label class="block text-xs font-semibold text-gray-700">Jumlah Box yang Ingin Dipesan</label>
                                <div class="flex items-center gap-3">
                                    <button
                                        type="button"
                                        @click="boxQuantity = Math.max(minBoxQty, boxQuantity - 5)"
                                        :disabled="boxQuantity <= minBoxQty"
                                        class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer"
                                        aria-label="Kurangi 5 box"
                                    >
                                        -5
                                    </button>
                                    <input
                                        v-model.number="boxQuantity"
                                        type="number"
                                        :min="minBoxQty"
                                        @blur="normalizeBoxQuantity"
                                        class="w-full text-center py-2 border border-gray-200 rounded-xl font-bold text-base text-gray-900 focus:ring-2 focus:ring-brand-500 bg-white"
                                    />
                                    <button
                                        type="button"
                                        @click="boxQuantity += 5"
                                        class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition cursor-pointer"
                                        aria-label="Tambah 5 box"
                                    >
                                        +5
                                    </button>
                                </div>
                                <div class="flex items-center justify-between text-[11px] pt-0.5">
                                    <span class="text-brand-600 font-bold flex items-center gap-1">
                                        <Info class="w-3.5 h-3.5 flex-shrink-0" />
                                        Minimal pemesanan {{ minBoxQty }} box
                                    </span>
                                    <span class="text-gray-500">Contoh: 25 - 100 box</span>
                                </div>
                                <!-- Quick Presets -->
                                <div class="flex items-center gap-1.5 pt-1 border-t border-orange-100/60">
                                    <span class="text-[10px] text-gray-500 font-medium mr-0.5">Pilihan cepat:</span>
                                    <button
                                        v-for="preset in [10, 25, 50, 100]"
                                        :key="preset"
                                        type="button"
                                        @click="boxQuantity = preset"
                                        :class="[
                                            'px-2.5 py-1 text-xs font-bold rounded-lg border transition cursor-pointer',
                                            boxQuantity === preset
                                                ? 'bg-brand-500 text-white border-brand-500 shadow-2xs'
                                                : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'
                                        ]"
                                    >
                                        {{ preset }}
                                    </button>
                                </div>
                            </div>

                            <!-- Total & Pricing Details -->
                            <div class="space-y-2 text-sm border-t border-gray-100 pt-4">
                                <div class="flex justify-between text-gray-600 text-xs">
                                    <span>Harga per 1 Box</span>
                                    <span class="font-semibold text-gray-900 font-mono">
                                        Rp {{ formatNumber(pricePerBox) }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-gray-900 font-bold text-base border-t border-dashed border-gray-200 pt-2">
                                    <span>Total ({{ boxQuantity }} Box)</span>
                                    <span class="text-brand-600 font-extrabold font-mono text-lg">
                                        Rp {{ formatNumber(totalOrderAmount) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Add to Cart CTA -->
                            <button
                                @click="handleAddBoxToCart"
                                :disabled="!isBoxFull || isSubmitting || boxQuantity < minBoxQty"
                                class="w-full py-4 px-6 rounded-2xl font-extrabold text-sm sm:text-base text-white shadow-sm transition flex items-center justify-center gap-2"
                                :class="[
                                    isBoxFull && boxQuantity >= minBoxQty
                                        ? 'bg-brand-500 hover:bg-brand-600 active:bg-brand-700 cursor-pointer shadow-md'
                                        : 'bg-gray-300 cursor-not-allowed text-gray-500'
                                ]"
                            >
                                <span v-if="!isBoxFull">
                                    Pilih {{ package.capacity - selectedCount }} Kue Lagi
                                </span>
                                <span v-else-if="boxQuantity < minBoxQty">
                                    Minimal Pemesanan {{ minBoxQty }} Box
                                </span>
                                <span v-else-if="isSubmitting">Menambahkan...</span>
                                <span v-else class="flex items-center gap-2">
                                    <ShoppingCart class="w-5 h-5" />
                                    Tambah {{ boxQuantity }} Box ke Keranjang
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { useCartStore } from '@/Stores/CartStore.js';
import { getImageUrl } from '@/helpers.js';
import { Image, ShoppingCart, Info, X, Check } from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps({
    package: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    products: {
        type: Array,
        default: () => [],
    },
    title: {
        type: String,
        default: 'Rakit Snack Box',
    },
});

const page = usePage();
const minBoxQty = computed(() => {
    const val = parseInt(page.props.cms?.settings?.min_order_box);
    return !isNaN(val) && val > 0 ? val : 10;
});

const activeCategory = ref(null);
const boxQuantity = ref(10); // Default minimal 10 box
const selectedItems = ref({}); // { productId: { product, quantity } }
const isSubmitting = ref(false);

const normalizeBoxQuantity = () => {
    if (!boxQuantity.value || boxQuantity.value < minBoxQty.value) {
        boxQuantity.value = minBoxQty.value;
    }
};

const { openCart, fetchCart, setItems } = useCartStore();

const filteredProducts = computed(() => {
    if (!activeCategory.value) return props.products;
    return props.products.filter(p => p.category?.id === activeCategory.value);
});

const selectedItemsArray = computed(() => Object.values(selectedItems.value));

const selectedCount = computed(() => {
    return selectedItemsArray.value.reduce((sum, item) => sum + item.quantity, 0);
});

const isBoxFull = computed(() => selectedCount.value === props.package.capacity);

const getItemCount = (productId) => {
    return selectedItems.value[productId]?.quantity || 0;
};

const increaseItem = (product) => {
    if (isBoxFull.value) return;

    if (!selectedItems.value[product.id]) {
        selectedItems.value[product.id] = {
            product_id: product.id,
            name: product.name,
            price: Number(product.sell_price),
            image_url: product.image_url,
            quantity: 1,
        };
    } else {
        selectedItems.value[product.id].quantity++;
    }
};

const decreaseItem = (productId) => {
    if (!selectedItems.value[productId]) return;

    selectedItems.value[productId].quantity--;
    if (selectedItems.value[productId].quantity <= 0) {
        delete selectedItems.value[productId];
    }
};

const toggleItem = (product) => {
    if (getItemCount(product.id) > 0) {
        delete selectedItems.value[product.id];
    } else {
        if (!isBoxFull.value) {
            increaseItem(product);
        }
    }
};

const removeItem = (productId) => {
    delete selectedItems.value[productId];
};

const pricePerBox = computed(() => {
    const itemsSum = selectedItemsArray.value.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const boxFee = Number(props.package.box_price || 2500);
    return itemsSum + boxFee;
});

const totalOrderAmount = computed(() => {
    return pricePerBox.value * Math.max(minBoxQty.value, boxQuantity.value || minBoxQty.value);
});

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

const handleAddBoxToCart = () => {
    normalizeBoxQuantity();

    if (!isBoxFull.value) return;

    if (boxQuantity.value < minBoxQty.value) {
        alert(`Minimal pemesanan snack box adalah ${minBoxQty.value} box.`);
        return;
    }

    isSubmitting.value = true;

    const payload = {
        package_id: props.package.id,
        box_quantity: Math.max(minBoxQty.value, boxQuantity.value),
        selected_items: selectedItemsArray.value.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
        })),
    };

    axios.post('/snack-box/add-to-cart', payload, {
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(async response => {
        if (response.data.success) {
            if (response.data.items && Array.isArray(response.data.items)) {
                setItems(response.data.items);
            } else {
                await fetchCart();
            }
            // Open drawer to review cart
            openCart();
        }
    })
    .catch(error => {
        console.error('Error adding snack box to cart:', error);
        alert(error.response?.data?.message || 'Gagal menambahkan snack box ke keranjang');
    })
    .finally(() => {
        isSubmitting.value = false;
    });
};
</script>
