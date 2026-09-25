<template>
    <Head :title="`${product.name} - Padu Kue`" />
    <CustomerLayout>
        <div class="min-h-screen bg-cream-100 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Breadcrumbs -->
                <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link href="/" class="hover:text-brand-600 transition">Beranda</Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                <Link href="/shop" class="hover:text-brand-600 transition">Katalog</Link>
                            </div>
                        </li>
                        <li v-if="product.category">
                            <div class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                <Link :href="`/shop/category/${product.category.slug}`" class="hover:text-brand-600 transition">
                                    {{ product.category.name }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                <span class="text-gray-800 font-semibold truncate max-w-[150px] md:max-w-none">{{ product.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Product Detail Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-orange-100/60 overflow-hidden mb-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-10">
                        <!-- Left: Image Gallery -->
                        <div class="flex flex-col items-center">
                            <div class="w-full aspect-square bg-cream-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center relative shadow-inner">
                                <img
                                    v-if="product.image_url"
                                    :src="getImageUrl(product.image_url)"
                                    :alt="product.name"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                />
                                <div v-else class="text-center p-8 text-gray-300">
                                    <Image class="w-20 h-20 mx-auto mb-2 text-orange-200 stroke-1" />
                                    <span class="text-xs text-gray-400">Foto belum tersedia</span>
                                </div>

                                <span v-if="product.category" class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-brand-700 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
                                    {{ product.category.name }}
                                </span>
                            </div>
                        </div>

                        <!-- Right: Product Information & Purchase -->
                        <div class="flex flex-col justify-between space-y-6">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-2">
                                    {{ product.name }}
                                </h1>
                                <p class="text-sm text-gray-500 mb-4">
                                    Kategori: <span class="font-medium text-gray-700">{{ product.category?.name || 'Kue Satuan' }}</span>
                                </p>

                                <div class="p-4 bg-orange-50/60 rounded-xl border border-orange-100 mb-6">
                                    <span class="text-xs text-orange-800 font-medium uppercase tracking-wider block mb-1">Harga Satuan</span>
                                    <p class="text-3xl font-extrabold text-brand-600 font-mono">
                                        Rp {{ formatNumber(product.sell_price) }}
                                    </p>
                                </div>

                                <!-- Trust badges -->
                                <div class="grid grid-cols-2 gap-3 py-3 border-y border-gray-100 text-xs text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">✓</span>
                                        <span>Bahan Berkualitas & Segar</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">✓</span>
                                        <span>Pesan Tanpa Login</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">✓</span>
                                        <span>Bisa Digabung Snack Box</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">✓</span>
                                        <span>Konfirmasi via WhatsApp</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="space-y-4 pt-4">
                                <div class="flex items-center justify-between gap-3 p-3 sm:p-4 bg-cream-50/80 rounded-2xl border border-cream-200/80">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex flex-col">
                                            <span class="text-xs sm:text-sm font-bold text-brown-700">Jumlah:</span>
                                            <span class="text-[11px] text-brown-400 font-medium">min. 10 pcs</span>
                                        </div>
                                        <div class="flex items-center bg-white rounded-xl p-0.5 border border-cream-200 shadow-2xs">
                                            <button
                                                @click="qty = Math.max(10, qty - 1)"
                                                :disabled="qty <= 10"
                                                :class="[
                                                    'w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg font-bold text-base transition',
                                                    qty <= 10 ? 'opacity-30 cursor-not-allowed text-brown-300' : 'hover:bg-cream-100 text-brown-700 cursor-pointer active:scale-95'
                                                ]"
                                                aria-label="Kurangi"
                                            >
                                                -
                                            </button>
                                            <input
                                                v-model.number="qty"
                                                type="number"
                                                inputmode="numeric"
                                                pattern="[0-9]*"
                                                min="10"
                                                @blur="qty = Math.max(10, qty || 10)"
                                                class="w-12 sm:w-14 text-center font-bold text-brown-900 bg-transparent border-none focus:outline-none text-sm sm:text-base font-mono px-1 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            />
                                            <button
                                                @click="qty++"
                                                class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg hover:bg-cream-100 active:bg-cream-200 text-brown-700 transition cursor-pointer font-bold text-base active:scale-95"
                                                aria-label="Tambah"
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <span class="text-[10px] sm:text-xs text-brown-500 block">Subtotal</span>
                                        <span class="text-base sm:text-lg font-extrabold text-brand-600 font-mono">
                                            Rp {{ formatNumber(product.sell_price * qty) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button
                                        @click="handleAddToCart"
                                        class="flex-1 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white py-3.5 sm:py-4 px-6 rounded-xl font-bold flex items-center justify-center gap-2 shadow-md shadow-brand-500/20 active:scale-98 transition text-sm sm:text-base cursor-pointer"
                                    >
                                        <ShoppingCart class="w-5 h-5" />
                                        Tambah ke Keranjang
                                    </button>
                                    <Link
                                        href="/shop"
                                        class="px-5 py-3.5 sm:py-4 rounded-xl border border-cream-300 text-brown-700 font-bold hover:bg-cream-50 transition text-center text-sm sm:text-base"
                                    >
                                        Katalog
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div v-if="relatedProducts && relatedProducts.length > 0" class="mb-12">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Kue Serupa di Kategori Ini</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                        <div
                            v-for="rel in relatedProducts"
                            :key="rel.id"
                            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between group"
                        >
                            <Link :href="`/shop/${rel.id}`" class="block">
                                <div class="aspect-square bg-cream-50 overflow-hidden">
                                    <img
                                        v-if="rel.image_url"
                                        :src="getImageUrl(rel.image_url)"
                                        :alt="rel.name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                        <Image class="w-10 h-10 stroke-1 text-gray-300" />
                                    </div>
                                </div>
                                <div class="p-3.5">
                                    <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-brand-600 transition">{{ rel.name }}</h3>
                                    <p class="text-brand-600 font-bold text-sm mt-1">Rp {{ formatNumber(rel.sell_price) }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { useCartStore } from '@/Stores/CartStore.js';
import { getImageUrl } from '@/helpers.js';
import { ShoppingCart, Image } from 'lucide-vue-next';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    relatedProducts: {
        type: Array,
        default: () => [],
    },
    title: {
        type: String,
        default: 'Detail Produk',
    },
});

const qty = ref(10);
const { addToCart } = useCartStore();

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

const handleAddToCart = () => {
    addToCart({
        id: props.product.id,
        name: props.product.name,
        sell_price: props.product.sell_price,
        image_url: props.product.image_url,
        qty: qty.value,
    }, 'satuan');
};
</script>
