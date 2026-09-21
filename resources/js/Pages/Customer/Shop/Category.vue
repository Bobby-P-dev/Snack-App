<template>
    <Head :title="`${category.name} - Padu Kue`" />
    <CustomerLayout>
        <div class="min-h-screen bg-cream-100 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-orange-100/60 mb-8">
                    <nav class="flex text-sm text-gray-500 mb-3" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-2">
                            <li>
                                <Link href="/" class="hover:text-brand-600 transition">Beranda</Link>
                            </li>
                            <li>/</li>
                            <li>
                                <Link href="/shop" class="hover:text-brand-600 transition">Katalog</Link>
                            </li>
                            <li>/</li>
                            <li class="text-gray-800 font-semibold">{{ category.name }}</li>
                        </ol>
                    </nav>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ category.name }}</h1>
                            <p class="text-gray-500 text-sm mt-1">Daftar pilihan kue lezat dalam kategori {{ category.name }}</p>
                        </div>
                        <span class="bg-orange-50 text-brand-700 px-3.5 py-1.5 rounded-full text-xs font-semibold self-start md:self-auto border border-orange-200">
                            {{ products.length }} Produk Tersedia
                        </span>
                    </div>

                    <!-- Category Pills -->
                    <div class="flex gap-2 overflow-x-auto pt-6 pb-2 scrollbar-none border-t border-gray-100 mt-6">
                        <Link
                            href="/shop"
                            class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                        >
                            Semua Menu
                        </Link>
                        <Link
                            v-for="cat in categories"
                            :key="cat.id"
                            :href="`/shop/category/${cat.slug}`"
                            :class="[
                                'px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition',
                                cat.id === category.id
                                    ? 'bg-brand-500 text-white shadow-sm'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                            ]"
                        >
                            {{ cat.name }}
                        </Link>
                    </div>
                </div>

                <!-- Product Grid -->
                <div v-if="products.length > 0" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between group"
                    >
                        <Link :href="`/shop/${product.id}`" class="block">
                            <div class="aspect-square bg-cream-50 overflow-hidden relative">
                                <img
                                    v-if="product.image_url"
                                    :src="getImageUrl(product.image_url)"
                                    :alt="product.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                    <Image class="w-12 h-12 stroke-1" />
                                </div>
                            </div>
                        </Link>

                        <div class="p-4 flex flex-col flex-1 justify-between">
                            <div>
                                <Link :href="`/shop/${product.id}`">
                                    <h3 class="font-bold text-gray-900 text-sm md:text-base line-clamp-1 group-hover:text-brand-600 transition">
                                        {{ product.name }}
                                    </h3>
                                </Link>
                                <p class="text-brand-600 font-extrabold text-sm md:text-base mt-1 font-mono">
                                    Rp {{ formatNumber(product.sell_price) }}
                                </p>
                            </div>

                            <button
                                @click="addToCart(product, 'satuan')"
                                class="mt-4 w-full bg-cream-50 hover:bg-brand-500 hover:text-white text-brand-700 font-semibold py-2 px-3 rounded-xl border border-orange-200 hover:border-transparent text-xs md:text-sm flex items-center justify-center gap-1.5 transition"
                            >
                                <Plus class="w-4 h-4" />
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                    <PackageSearch class="w-16 h-16 mx-auto text-gray-300 mb-4 stroke-1" />
                    <h3 class="text-lg font-bold text-gray-800">Belum ada produk</h3>
                    <p class="text-sm text-gray-500 mt-1">Produk untuk kategori {{ category.name }} sedang dipersiapkan.</p>
                    <Link
                        href="/shop"
                        class="inline-block mt-4 px-5 py-2.5 bg-brand-500 text-white rounded-xl text-xs font-semibold hover:bg-brand-600 transition"
                    >
                        Lihat Menu Lain
                    </Link>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { useCartStore } from '@/Stores/CartStore.js';
import { getImageUrl } from '@/helpers.js';
import { Image, Plus, PackageSearch } from 'lucide-vue-next';

defineProps({
    category: {
        type: Object,
        required: true,
    },
    products: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    title: {
        type: String,
        default: 'Kategori Produk',
    },
});

const { addToCart } = useCartStore();

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val || 0);
</script>
