<template>
    <Head title="Shop - Snack Box" />
    <CustomerLayout>
        <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
            <!-- Header -->
            <section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 md:py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2">Jelajahi Koleksi Kami</h1>
                    <p class="text-base md:text-lg text-blue-100">Pilih snack dan kue favorit Anda dari berbagai supplier terbaik</p>
                </div>
            </section>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
                <!-- Tipe Produk Tab -->
                <section class="-mt-8 relative z-20 mb-6">
                    <div class="bg-white rounded-2xl shadow-xl p-2 md:p-3">
                        <div class="flex gap-2">
                            <button @click="activeType = 'box'" class="flex-1 py-3 md:py-4 rounded-xl font-bold transition text-sm md:text-base" :class="activeType === 'box' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">Snack Box</button>
                            <button @click="activeType = 'satuan'" class="flex-1 py-3 md:py-4 rounded-xl font-bold transition text-sm md:text-base" :class="activeType === 'satuan' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">Kue Satuan</button>
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                    <!-- Sidebar Categories -->
                    <div class="lg:col-span-1">
                        <div class="hidden lg:block bg-white rounded-xl shadow-sm p-6 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori</h3>
                            <nav class="space-y-2">
                                <button @click="selectCategory('')" :class="['w-full text-left px-4 py-3 rounded-lg transition font-medium', !selectedCategory ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100']">Semua Kategori</button>
                                <button v-for="category in categories" :key="category.id" @click="selectCategory(category.id)" :class="['w-full text-left px-4 py-3 rounded-lg transition font-medium', selectedCategory == category.id ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100']">{{ category.name }} <span class="text-sm ml-2">({{ category.product_count }})</span></button>
                            </nav>
                        </div>
                        <!-- Mobile Categories -->
                        <div class="lg:hidden overflow-x-auto pb-2 mb-6">
                            <div class="flex space-x-2">
                                <button @click="selectCategory('')" :class="['px-4 py-2 rounded-full font-medium whitespace-nowrap transition', !selectedCategory ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200']">Semua</button>
                                <button v-for="category in categories" :key="category.id" @click="selectCategory(category.id)" :class="['px-4 py-2 rounded-full font-medium whitespace-nowrap transition', selectedCategory == category.id ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200']">{{ category.name }}</button>
                            </div>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="lg:col-span-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                            <h2 class="text-2xl font-bold text-gray-900">{{ products.length }} Produk</h2>
                            <input v-model="searchQuery" @input="onSearch" type="text" placeholder="Cari produk..." class="w-full sm:max-w-xs px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm" />
                        </div>

                        <!-- Products Grid -->
                        <div v-if="products.length > 0" class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                            <div v-for="product in products" :key="product.id" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="h-28 sm:h-32 bg-gray-100 flex items-center justify-center overflow-hidden">
                                    <img v-if="product.image_url" :src="getImageUrl(product.image_url)" :alt="product.name" class="w-full h-full object-cover" />
                                    <svg v-else class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ product.category?.name }}</p>
                                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1">{{ product.name }}</h3>
                                    <p class="font-bold text-blue-600 text-base mb-3">Rp {{ formatNumber(product.sell_price) }}</p>
                                    <div class="flex items-center gap-2 mb-2">
                                        <button @click="decQty(product)" class="w-9 h-9 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-bold text-lg flex items-center justify-center flex-shrink-0">−</button>
                                        <input :value="getQty(product)" @input="(e) => { const v = parseInt(e.target.value); if (v >= minOrderQty) quantities[product.id] = v; }" type="number" class="flex-1 min-w-0 text-center font-bold border border-gray-300 rounded-lg py-1.5 focus:outline-none focus:border-blue-500" :min="minOrderQty" />
                                        <button @click="incQty(product)" class="w-9 h-9 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-bold text-lg flex items-center justify-center flex-shrink-0">+</button>
                                    </div>
                                    <button @click="addSelected(product, getQty(product), activeType === 'box' ? 'kustom_box' : 'satuan'); quantities[product.id] = minOrderQty.value;" class="w-full py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition">Tambah</button>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                            <p class="text-gray-500 mb-4 text-sm">Coba ubah pencarian atau kategori Anda</p>
                            <button @click="searchQuery = ''; selectedCategory = ''; fetchProducts(1)" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Reset Filter</button>
                        </div>

                        <!-- Pagination -->
                        <div v-if="pagination.last_page > 1" class="flex items-center justify-between mt-8">
                            <p class="text-sm text-gray-500">Halaman {{ pagination.current_page }} dari {{ pagination.last_page }} ({{ pagination.total }} produk)</p>
                            <div class="flex gap-2">
                                <button :disabled="pagination.current_page <= 1" @click="goToPage(pagination.current_page - 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Prev
                                </button>
                                <button :disabled="pagination.current_page >= pagination.last_page" @click="goToPage(pagination.current_page + 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
                                    Next
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Selected Items Panel -->
            <section v-if="selectedItems.length > 0" class="sticky bottom-0 left-0 right-0 z-40 px-4 pb-4 md:pb-6 pointer-events-none mt-4">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] pointer-events-auto transform transition-transform duration-300">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex-1 w-full">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-bold text-blue-900">{{ selectedItems.length }} produk dipilih</p>
                                    <button @click="sendToCart" class="sm:hidden px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-md text-sm">Masuk Keranjang</button>
                                </div>
                                <!-- Tags Produk (Scrollable kalau banyak) -->
                                <div class="flex overflow-x-auto gap-2 pb-1 scrollbar-hide">
                                    <div v-for="item in selectedItems" :key="item.id" class="bg-white rounded-lg px-3 py-1.5 flex items-center gap-2 shadow-sm border border-blue-100 flex-shrink-0">
                                        <span class="text-sm font-medium text-gray-900 truncate max-w-[120px]">{{ item.name }}</span>
                                        <span class="text-xs text-blue-600 font-bold bg-blue-50 px-1.5 py-0.5 rounded">{{ item.qty }}x</span>
                                        <button @click="removeSelected(item.id)" class="text-red-400 hover:text-red-600 transition p-0.5 hover:bg-red-50 rounded">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden sm:block">
                                <button @click="sendToCart" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold shadow-lg flex items-center gap-2 whitespace-nowrap hover:-translate-y-0.5 duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                    Masukkan ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div v-if="selectedItems.length > 0" class="h-32"></div>

            <!-- FOOTER -->
            <Footer />
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import ProductCard from '@/Pages/Customer/Components/ProductCard.vue';
import { useCartStore } from '@/Stores/CartStore.js';
import { useSelectedItemsStore } from '@/Stores/SelectedItemsStore.js';

import { getImageUrl } from '@/helpers.js';

const props = defineProps({
    products: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    pagination: { type: Object, default: () => ({ current_page: 1, last_page: 1, total: 0, per_page: 10 }) },
    filters: { type: Object, default: () => ({ search: '', category_id: '' }) },
});

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});
const minOrderBox = computed(() => parseInt(cms.value.min_order_box) || 10);
const minOrderSatuan = computed(() => parseInt(cms.value.min_order_satuan) || 10);
const minOrderQty = computed(() => activeType.value === 'box' ? minOrderBox.value : minOrderSatuan.value);

const { addToCart } = useCartStore();
const { items: selectedItems, count: selectedCount, addItem: addSelected, removeItem: removeSelected, clearItems: clearSelected } = useSelectedItemsStore();

const activeType = ref('box');
const searchQuery = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category_id || '');

// Fetch via Inertia with server-side pagination & filtering
const fetchProducts = (page = 1) => {
    router.get('/shop', {
        page,
        search: searchQuery.value,
        category_id: selectedCategory.value,
    }, { preserveState: true, replace: true });
};

let searchTimeout = null
const onSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => fetchProducts(1), 400)
}

const selectCategory = (categoryId) => {
    selectedCategory.value = categoryId
    fetchProducts(1)
}

const goToPage = (page) => {
    fetchProducts(page)
}

const currentPage = computed(() => props.pagination.current_page || 1)

// Qtys (dynamic min from CMS)
const quantities = ref({});
const getQty = (product) => quantities.value[product.id] || minOrderQty.value;
const incQty = (product) => { const c = quantities.value[product.id] || minOrderQty.value; quantities.value[product.id] = c + 1; };
const decQty = (product) => { const c = quantities.value[product.id] || minOrderQty.value; if (c > minOrderQty.value) quantities.value[product.id] = c - 1; };

// Reset quantities when type changes
watch(activeType, () => { quantities.value = {}; });

// Selected (using shared store)
const sendToCart = () => {
    if (selectedItems.value.length === 0) return;
    selectedItems.value.forEach(item => {
        addToCart(item, item.type || 'satuan');
    });
    clearSelected();
};

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);
</script>
