<template>
    <Head title="Katalog Kue Satuan - Padu Kue" />
    <CustomerLayout>
        <div class="min-h-screen bg-cream-50/40">
            <!-- Header -->
            <ShopHeader />

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                    <!-- Sidebar Filter Desktop -->
                    <div class="lg:col-span-1">
                        <ShopSidebar 
                            :categories="categories" 
                            :selected-category="selectedCategory"
                            :selected-price-range="selectedPriceRange"
                            :min-price="minPrice"
                            :max-price="maxPrice"
                            :has-active-filter="hasActiveFilter"
                            @select-category="selectCategory"
                            @select-price-range="selectPriceRange"
                            @apply-custom-price="applyCustomPrice"
                            @reset-filter="resetFilters"
                        />
                    </div>

                    <!-- Products Main Area -->
                    <div class="lg:col-span-3 space-y-4">
                        <!-- Search Bar -->
                        <div class="relative">
                            <Search class="w-4 h-4 text-brown-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" />
                            <input
                                v-model="searchQuery"
                                @input="onSearch"
                                type="text"
                                placeholder="Cari kue favorit, donat, brownies, atau nama suplier..."
                                class="w-full pl-11 pr-10 py-3 rounded-2xl border border-cream-200/90 bg-white text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 placeholder-brown-400 text-brown-900 shadow-2xs transition"
                            />
                            <button
                                v-if="searchQuery"
                                @click="clearSearch"
                                type="button"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-brown-400 hover:text-brown-700 p-1 rounded-full hover:bg-cream-100 transition cursor-pointer"
                                aria-label="Hapus pencarian"
                            >
                                <X class="w-4 h-4" />
                            </button>
                        </div>

                        <!-- Active Filter Chips Bar -->
                        <div v-if="hasActiveFilter" class="flex flex-wrap items-center gap-2 p-3 bg-white rounded-2xl border border-cream-200/80 shadow-2xs">
                            <span class="text-xs font-bold text-brown-500 uppercase tracking-wider pl-1">Filter:</span>

                            <!-- Chip: Search -->
                            <span v-if="searchQuery" class="inline-flex items-center gap-1.5 px-3 py-1 bg-cream-100 text-brown-800 rounded-full text-xs font-medium border border-cream-200">
                                <span>Pencarian: "{{ searchQuery }}"</span>
                                <button type="button" @click="clearSearch" class="hover:text-red-600 transition cursor-pointer"><X class="w-3 h-3" /></button>
                            </span>

                            <!-- Chip: Category -->
                            <span v-if="selectedCategory" class="inline-flex items-center gap-1.5 px-3 py-1 bg-brand-50 text-brand-800 rounded-full text-xs font-semibold border border-brand-200">
                                <span>Kategori: {{ activeCategoryName }}</span>
                                <button type="button" @click="selectCategory('')" class="hover:text-red-600 transition cursor-pointer"><X class="w-3 h-3" /></button>
                            </span>

                            <!-- Chip: Price Range -->
                            <span v-if="selectedPriceRange !== 'all'" class="inline-flex items-center gap-1.5 px-3 py-1 bg-cream-100 text-brown-800 rounded-full text-xs font-medium border border-cream-200">
                                <span>Harga: {{ activePriceLabel }}</span>
                                <button type="button" @click="clearPriceFilter" class="hover:text-red-600 transition cursor-pointer"><X class="w-3 h-3" /></button>
                            </span>

                            <!-- Reset All Text Button -->
                            <button
                                type="button"
                                @click="resetFilters"
                                class="text-xs text-red-600 hover:text-red-700 font-bold ml-auto pl-2 cursor-pointer transition hover:underline"
                            >
                                Reset Semua
                            </button>
                        </div>

                        <!-- Product Grid & Toolbar -->
                        <ShopProduct 
                            :products="products"
                            :pagination="pagination"
                            :current-sort="currentSort"
                            @add-to-cart="handleAddToCart"
                            @reset-filter="resetFilters"
                            @go-to-page="goToPage"
                            @change-sort="handleSortChange"
                            @open-mobile-filter="isMobileFilterOpen = true"
                        />
                    </div>
                </div>
            </div>

            <!-- Mobile Filter Bottom Sheet -->
            <div v-if="isMobileFilterOpen" class="fixed inset-0 z-50 flex flex-col justify-end">
                <!-- Backdrop -->
                <div 
                    class="fixed inset-0 bg-brown-950/40 backdrop-blur-xs transition-opacity"
                    @click="isMobileFilterOpen = false"
                ></div>

                <!-- Sheet Content -->
                <div class="relative bg-white rounded-t-3xl border-t border-cream-200 p-6 shadow-2xl z-10 max-h-[85vh] overflow-y-auto space-y-6">
                    <!-- Handle Bar -->
                    <div class="w-12 h-1.5 bg-cream-200 rounded-full mx-auto -mt-2 mb-2"></div>

                    <div class="flex items-center justify-between pb-3 border-b border-cream-100">
                        <div class="flex items-center gap-2">
                            <SlidersHorizontal class="w-5 h-5 text-brand-500" />
                            <h3 class="font-black text-brown-900 text-lg">Filter & Urutkan</h3>
                        </div>
                        <button 
                            type="button" 
                            @click="isMobileFilterOpen = false"
                            class="p-1.5 rounded-full text-brown-400 hover:text-brown-700 hover:bg-cream-100 transition cursor-pointer"
                            aria-label="Tutup filter"
                        >
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Mobile Filter: Kategori -->

                    <!-- Mobile Filter: Kategori -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-brown-500 mb-3">Kategori Produk</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                @click="selectCategory('')"
                                :class="[
                                    'px-3.5 py-2 rounded-xl text-xs font-bold transition cursor-pointer',
                                    !selectedCategory 
                                        ? 'bg-brand-500 text-white shadow-xs' 
                                        : 'bg-cream-50 text-brown-700 border border-cream-200'
                                ]"
                            >
                                Semua ({{ totalProductsCount }})
                            </button>
                            <button
                                v-for="cat in categories"
                                :key="cat.id"
                                type="button"
                                @click="selectCategory(cat.id)"
                                :class="[
                                    'px-3.5 py-2 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5',
                                    selectedCategory == cat.id 
                                        ? 'bg-brand-500 text-white shadow-xs' 
                                        : 'bg-cream-50 text-brown-700 border border-cream-200'
                                ]"
                            >
                                <span>{{ cat.name }}</span>
                                <span v-if="cat.product_count" class="text-[10px] opacity-75 font-mono">({{ cat.product_count }})</span>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Filter: Rentang Harga -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-brown-500 mb-2">Rentang Harga</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                @click="selectPriceRange('all')"
                                :class="[
                                    'p-2.5 rounded-xl border text-xs font-semibold text-left transition cursor-pointer',
                                    selectedPriceRange === 'all' && !minPrice && !maxPrice
                                        ? 'bg-brand-50 border-brand-300 text-brand-700 font-bold'
                                        : 'border-cream-200 text-brown-700 hover:bg-cream-50'
                                ]"
                            >
                                Semua Harga
                            </button>
                            <button
                                type="button"
                                @click="selectPriceRange('under_10k')"
                                :class="[
                                    'p-2.5 rounded-xl border text-xs font-semibold text-left transition cursor-pointer',
                                    selectedPriceRange === 'under_10k'
                                        ? 'bg-brand-50 border-brand-300 text-brand-700 font-bold'
                                        : 'border-cream-200 text-brown-700 hover:bg-cream-50'
                                ]"
                            >
                                &lt; Rp 10.000
                            </button>
                            <button
                                type="button"
                                @click="selectPriceRange('10k_25k')"
                                :class="[
                                    'p-2.5 rounded-xl border text-xs font-semibold text-left transition cursor-pointer',
                                    selectedPriceRange === '10k_25k'
                                        ? 'bg-brand-50 border-brand-300 text-brand-700 font-bold'
                                        : 'border-cream-200 text-brown-700 hover:bg-cream-50'
                                ]"
                            >
                                Rp 10.000 - 25.000
                            </button>
                            <button
                                type="button"
                                @click="selectPriceRange('above_25k')"
                                :class="[
                                    'p-2.5 rounded-xl border text-xs font-semibold text-left transition cursor-pointer',
                                    selectedPriceRange === 'above_25k'
                                        ? 'bg-brand-50 border-brand-300 text-brand-700 font-bold'
                                        : 'border-cream-200 text-brown-700 hover:bg-cream-50'
                                ]"
                            >
                                &gt; Rp 25.000
                            </button>
                        </div>
                        <!-- Custom Min/Max on Mobile -->
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <input
                                v-model.number="minPrice"
                                type="number"
                                placeholder="Min Rp"
                                class="px-3 py-2 text-xs rounded-xl border border-cream-200 bg-white"
                            />
                            <input
                                v-model.number="maxPrice"
                                type="number"
                                placeholder="Maks Rp"
                                class="px-3 py-2 text-xs rounded-xl border border-cream-200 bg-white"
                            />
                        </div>
                    </div>

                    <!-- Mobile Filter: Urutan -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-brown-500 mb-2">Urutan Tampilan</p>
                        <CustomSelect
                            :model-value="currentSort"
                            :options="[
                                { value: 'latest', label: 'Terbaru', icon: Sparkles, description: 'Produk paling anyar' },
                                { value: 'price_asc', label: 'Termurah', icon: ArrowDownNarrowWide, description: 'Mulai dari harga terendah' },
                                { value: 'price_desc', label: 'Termahal', icon: ArrowUpWideNarrow, description: 'Porsi & kualitas istimewa' },
                                { value: 'name_asc', label: 'Nama A - Z', icon: ArrowDownAZ, description: 'Berdasarkan urutan alfabet' }
                            ]"
                            variant="bakery"
                            size="md"
                            :full-width="true"
                            @change="handleSortChange"
                        />
                    </div>

                    <!-- Mobile Actions -->
                    <div class="pt-2 flex items-center gap-3">
                        <button
                            type="button"
                            @click="resetFilters"
                            class="flex-1 py-3 px-4 rounded-xl border border-cream-200 text-brown-700 font-bold text-sm hover:bg-cream-50 transition cursor-pointer"
                        >
                            Reset Filter
                        </button>
                        <button
                            type="button"
                            @click="applyMobileFilter"
                            class="flex-1 py-3 px-4 rounded-xl bg-brand-500 text-white font-bold text-sm shadow-md shadow-brand-500/25 hover:bg-brand-600 transition cursor-pointer"
                        >
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Floating Selected Items Cart Bar -->
            <section v-if="selectedItems.length > 0" class="sticky bottom-16 md:bottom-0 left-0 right-0 z-40 px-3 sm:px-4 pb-2 md:pb-6 pointer-events-none mt-4">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-brand-50 border border-brand-200 rounded-2xl p-4 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] pointer-events-auto transform transition-transform duration-300">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex-1 w-full">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-bold text-brown-800">{{ selectedItems.length }} produk dipilih</p>
                                    <button @click="sendToCart" class="sm:hidden px-6 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition font-bold shadow-md text-sm">Masuk Keranjang</button>
                                </div>
                                <div class="flex overflow-x-auto gap-2 pb-1 scrollbar-hide">
                                    <div v-for="item in selectedItems" :key="item.id" class="bg-white rounded-lg px-3 py-1.5 flex items-center gap-2 shadow-xs border border-brand-100 flex-shrink-0">
                                        <span class="text-sm font-medium text-gray-900 truncate max-w-[120px]">{{ item.name }}</span>
                                        <span class="text-xs text-brand-600 font-bold bg-brand-50 px-1.5 py-0.5 rounded">{{ item.qty }}x</span>
                                        <button @click="removeSelected(item.id)" class="text-red-400 hover:text-red-600 transition p-0.5 hover:bg-red-50 rounded" aria-label="Hapus produk pilihan">
                                            <X class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden sm:block">
                                <button @click="sendToCart" class="px-8 py-3 bg-brand-500 text-white rounded-xl hover:bg-brand-600 transition font-bold shadow-lg flex items-center gap-2 whitespace-nowrap hover:-translate-y-0.5 duration-200 cursor-pointer">
                                    <ShoppingCart class="w-5 h-5" />
                                    Masukkan ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div v-if="selectedItems.length > 0" class="h-32"></div>

            <!-- Footer -->
            <Footer />
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { X, Search, ShoppingCart, SlidersHorizontal, Sparkles, ArrowDownNarrowWide, ArrowUpWideNarrow, ArrowDownAZ } from 'lucide-vue-next';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import ShopHeader from '@/Pages/Customer/Shop/Landing/Header.vue';
import ShopSidebar from '@/Pages/Customer/Shop/Landing/Sidebar.vue';
import ShopProduct from '@/Pages/Customer/Shop/Landing/Product.vue';
import CustomSelect from '@/Components/UI/CustomSelect.vue';

import { useCartStore } from '@/Stores/CartStore.js';
import { useSelectedItemsStore } from '@/Stores/SelectedItemsStore.js';

const props = defineProps({
    products: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    pagination: { type: Object, default: () => ({ current_page: 1, last_page: 1, total: 0, per_page: 8 }) },
    filters: { type: Object, default: () => ({}) },
});

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});
const minOrderSatuan = computed(() => parseInt(cms.value.min_order_satuan) || 10);
const minOrderQty = computed(() => minOrderSatuan.value);

const { addToCart } = useCartStore();
const { items: selectedItems, addItem: addSelected, removeItem: removeSelected, clearItems: clearSelected } = useSelectedItemsStore();

// Filter states initialized from server props
const searchQuery = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category_id || '');
const selectedPriceRange = ref(props.filters?.price_range || 'all');
const minPrice = ref(props.filters?.min_price || '');
const maxPrice = ref(props.filters?.max_price || '');
const currentSort = ref(props.filters?.sort || 'latest');
const isMobileFilterOpen = ref(false);

// Reactive synchronization when props change
watch(() => props.filters, (newFilters) => {
    if (!newFilters) return;
    searchQuery.value = newFilters.search || '';
    selectedCategory.value = newFilters.category_id || '';
    selectedPriceRange.value = newFilters.price_range || 'all';
    minPrice.value = newFilters.min_price || '';
    maxPrice.value = newFilters.max_price || '';
    currentSort.value = newFilters.sort || 'latest';
}, { deep: true });

// Total products across all categories
const totalProductsCount = computed(() => {
    return props.categories.reduce((sum, c) => sum + (c.product_count || 0), 0);
});

// Active Filter Information Helpers
const hasActiveFilter = computed(() => {
    return Boolean(
        searchQuery.value ||
        selectedCategory.value ||
        (selectedPriceRange.value && selectedPriceRange.value !== 'all') ||
        minPrice.value !== '' ||
        maxPrice.value !== ''
    );
});

const activeCategoryName = computed(() => {
    if (!selectedCategory.value) return '';
    const cat = props.categories.find(c => String(c.id) === String(selectedCategory.value));
    return cat ? cat.name : '';
});

const activePriceLabel = computed(() => {
    if (selectedPriceRange.value === 'under_10k') return '< Rp 10.000';
    if (selectedPriceRange.value === '10k_25k') return 'Rp 10.000 - Rp 25.000';
    if (selectedPriceRange.value === 'above_25k') return '> Rp 25.000';
    if (minPrice.value && maxPrice.value) return `Rp ${formatNumber(minPrice.value)} - Rp ${formatNumber(maxPrice.value)}`;
    if (minPrice.value) return `>= Rp ${formatNumber(minPrice.value)}`;
    if (maxPrice.value) return `<= Rp ${formatNumber(maxPrice.value)}`;
    return '';
});

// Central Fetch function
const fetchProducts = (targetPage = 1) => {
    const params = {
        page: targetPage,
        sort: currentSort.value,
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (selectedCategory.value) params.category_id = selectedCategory.value;
    if (selectedPriceRange.value && selectedPriceRange.value !== 'all') params.price_range = selectedPriceRange.value;
    if (minPrice.value !== '' && minPrice.value !== null) params.min_price = minPrice.value;
    if (maxPrice.value !== '' && maxPrice.value !== null) params.max_price = maxPrice.value;

    router.get('/shop', params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Search handling with debounce
let searchTimeout = null;
const onSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchProducts(1);
    }, 350);
};

const clearSearch = () => {
    searchQuery.value = '';
    fetchProducts(1);
};

// Category filter
const selectCategory = (categoryId) => {
    selectedCategory.value = categoryId;
    fetchProducts(1);
};

// Price filter handlers
const selectPriceRange = (rangeId) => {
    selectedPriceRange.value = rangeId;
    minPrice.value = '';
    maxPrice.value = '';
    fetchProducts(1);
};

const applyCustomPrice = ({ min, max }) => {
    selectedPriceRange.value = 'custom';
    minPrice.value = min !== '' ? min : '';
    maxPrice.value = max !== '' ? max : '';
    fetchProducts(1);
};

const clearPriceFilter = () => {
    selectedPriceRange.value = 'all';
    minPrice.value = '';
    maxPrice.value = '';
    fetchProducts(1);
};

// Sort handler
const handleSortChange = (sortVal) => {
    currentSort.value = sortVal;
    fetchProducts(1);
};

// Reset all filters
const resetFilters = () => {
    searchQuery.value = '';
    selectedCategory.value = '';
    selectedPriceRange.value = 'all';
    minPrice.value = '';
    maxPrice.value = '';
    currentSort.value = 'latest';
    isMobileFilterOpen.value = false;
    fetchProducts(1);
};

const applyMobileFilter = () => {
    if (minPrice.value || maxPrice.value) {
        selectedPriceRange.value = 'custom';
    }
    isMobileFilterOpen.value = false;
    fetchProducts(1);
};

// Pagination
const goToPage = (targetPage) => {
    fetchProducts(targetPage);
};

// Cart handling
const quantities = ref({});
const handleAddToCart = (product) => {
    addSelected(product, minOrderQty.value, 'satuan');
    quantities.value[product.id] = minOrderQty.value;
};

const sendToCart = () => {
    if (selectedItems.value.length === 0) return;
    selectedItems.value.forEach(item => {
        addToCart(item, 'satuan');
    });
    clearSelected();
};

const formatNumber = (num) => {
    if (!num) return '0';
    return new Intl.NumberFormat('id-ID').format(num);
};
</script>
