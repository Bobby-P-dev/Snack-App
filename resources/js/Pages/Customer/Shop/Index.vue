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
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                    <!-- Sidebar Categories -->
                    <div class="lg:col-span-1">
                        <div class="hidden lg:block bg-white rounded-xl shadow-sm p-6 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori</h3>
                            <nav class="space-y-2">
                                <button @click="selectedCategory = null; productIndex = 0" :class="['w-full text-left px-4 py-3 rounded-lg transition font-medium', selectedCategory === null ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100']">Semua Kategori</button>
                                <button v-for="category in categories" :key="category.id" @click="selectedCategory = category.id; productIndex = 0" :class="['w-full text-left px-4 py-3 rounded-lg transition font-medium', selectedCategory === category.id ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100']">{{ category.name }} <span class="text-sm ml-2">({{ category.product_count }})</span></button>
                            </nav>
                        </div>
                        <!-- Mobile Categories -->
                        <div class="lg:hidden overflow-x-auto pb-2 mb-6">
                            <div class="flex space-x-2">
                                <button @click="selectedCategory = null; productIndex = 0" :class="['px-4 py-2 rounded-full font-medium whitespace-nowrap transition', selectedCategory === null ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200']">Semua</button>
                                <button v-for="category in categories" :key="category.id" @click="selectedCategory = category.id; productIndex = 0" :class="['px-4 py-2 rounded-full font-medium whitespace-nowrap transition', selectedCategory === category.id ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200']">{{ category.name }}</button>
                            </div>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="lg:col-span-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                            <h2 class="text-2xl font-bold text-gray-900">{{ paginatedItems.length }} Produk</h2>
                            <input v-model="searchQuery" type="text" placeholder="Cari produk..." class="w-full sm:max-w-xs px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm" />
                        </div>

                        <!-- Products Grid -->
                        <div v-if="paginatedItems.length > 0" class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                            <div v-for="product in paginatedItems" :key="product.id" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="h-28 sm:h-32 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ product.category?.name }}</p>
                                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1">{{ product.name }}</h3>
                                    <p class="font-bold text-blue-600 text-base mb-3">Rp {{ formatNumber(product.sell_price) }}</p>
                                    <div class="flex items-center gap-2 mb-2">
                                        <button @click="decQty(product)" class="w-9 h-9 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-bold text-lg flex items-center justify-center flex-shrink-0">−</button>
                                        <input :value="getQty(product)" @input="(e) => { const v = parseInt(e.target.value); if (v >= 10) quantities[product.id] = v; }" type="number" class="flex-1 min-w-0 text-center font-bold border border-gray-300 rounded-lg py-1.5 focus:outline-none focus:border-blue-500" min="10" />
                                        <button @click="incQty(product)" class="w-9 h-9 bg-gray-100 rounded-lg hover:bg-gray-200 transition font-bold text-lg flex items-center justify-center flex-shrink-0">+</button>
                                    </div>
                                    <button @click="addSelected(product)" class="w-full py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition">Tambah</button>
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
                            <button @click="searchQuery = ''; selectedCategory = null" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Reset Filter</button>
                        </div>

                        <!-- Pagination -->
                        <div v-if="totalPages > 1" class="flex justify-center gap-2 mt-8">
                            <button @click="productIndex = Math.max(0, productIndex - perPage)" :disabled="productIndex === 0" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition">Previous</button>
                            <span class="px-4 py-2 text-sm text-gray-600">Page {{ currentPage }} of {{ totalPages }}</span>
                            <button @click="productIndex = Math.min(filteredProducts.length - 1, productIndex + perPage)" :disabled="productIndex + perPage >= filteredProducts.length" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition">Next</button>
                        </div>
                    </div>
                </div>

                <!-- Selected Items Panel -->
                <div v-if="selectedItems.length > 0" class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-4 md:p-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                        <div>
                            <p class="font-bold text-blue-900">{{ selectedItems.length }} produk dipilih</p>
                            <p class="text-sm text-blue-700">Klik tombol untuk memasukkan ke keranjang</p>
                        </div>
                        <button @click="sendToCart" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold shadow-lg w-full sm:w-auto">Masukkan ke Keranjang</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <div v-for="item in selectedItems" :key="item.id" class="bg-white rounded-lg px-3 py-2 flex items-center gap-2 shadow-sm">
                            <span class="text-sm font-medium text-gray-900">{{ item.name }}</span>
                            <span class="text-xs text-blue-600 font-semibold">{{ item.qty }}x</span>
                            <button @click="removeSelected(item.id)" class="text-red-500 hover:text-red-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <Footer />
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Components/Domain/Footer.vue';
import { useCartStore } from '@/Stores/CartStore.js';

const props = defineProps({
    products: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
});

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});
const minOrderQty = computed(() => parseInt(cms.value.min_order_satuan) || 10);

const { addToCart } = useCartStore();

const selectedCategory = ref(null);
const searchQuery = ref('');
const productIndex = ref(0);
const perPage = 12;

// Filter by category + search
const filteredProducts = computed(() => {
    let result = props.products;
    if (selectedCategory.value) {
        result = result.filter(p => p.category.id === selectedCategory.value);
    }
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(p => p.name.toLowerCase().includes(q) || (p.supplier?.name || '').toLowerCase().includes(q));
    }
    return result;
});

const paginatedItems = computed(() => filteredProducts.value.slice(productIndex.value, productIndex.value + perPage));
const totalPages = computed(() => Math.ceil(filteredProducts.value.length / perPage));
const currentPage = computed(() => Math.floor(productIndex.value / perPage) + 1);

// Qtys (dynamic min from CMS)
const quantities = ref({});
const getQty = (product) => quantities.value[product.id] || minOrderQty.value;
const incQty = (product) => { const c = quantities.value[product.id] || minOrderQty.value; quantities.value[product.id] = c + 1; };
const decQty = (product) => { const c = quantities.value[product.id] || minOrderQty.value; if (c > minOrderQty.value) quantities.value[product.id] = c - 1; };

// Selected
const selectedItems = ref([]);
const addSelected = (product) => {
    const qty = quantities.value[product.id] || minOrderQty.value;
    const existing = selectedItems.value.find(i => i.id === product.id);
    if (existing) { existing.qty += qty; }
    else { selectedItems.value.push({ id: product.id, name: product.name, price: product.sell_price, qty }); }
    quantities.value[product.id] = minOrderQty.value;
};
const removeSelected = (id) => { selectedItems.value = selectedItems.value.filter(i => i.id !== id); };
const sendToCart = () => {
    if (selectedItems.value.length === 0) { alert('Pilih minimal 1 produk'); return; }
    selectedItems.value.forEach(item => {
        addToCart(item, 'satuan');
    });
    selectedItems.value = [];
};

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);
</script>
