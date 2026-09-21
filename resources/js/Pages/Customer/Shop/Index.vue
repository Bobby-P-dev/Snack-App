<template>
    <Head title="Shop - Snack Box" />
    <CustomerLayout>
        <div class="min-h-screen bg-white">
            <!-- Header -->
            <ShopHeader :active-type="activeType" />

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">


                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                    <!-- Sidebar Categories -->
                    <div class="lg:col-span-1">
                        <ShopSidebar 
                            :categories="categories" 
                            :selected-category="selectedCategory"
                            @select-category="selectCategory"
                            @reset-filter="resetFilters"
                        />
                    </div>

                    <!-- Products -->
                    <div class="lg:col-span-3">
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
                            <h3 class="font-black text-brown-900 text-lg">Filter Produk</h3>
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

                    <!-- Filter: Kategori -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-brown-500 mb-3">Pilih Kategori</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                @click="selectCategory('')"
                                :class="[
                                    'px-3.5 py-2 rounded-xl text-xs font-bold transition cursor-pointer',
                                    !selectedCategory 
                                        ? 'bg-brand-500 text-white shadow-sm shadow-brand-500/25' 
                                        : 'bg-cream-50 text-brown-700 border border-cream-200 hover:bg-cream-100'
                                ]"
                            >
                                Semua Kategori
                            </button>
                            <button
                                v-for="cat in categories"
                                :key="cat.id"
                                type="button"
                                @click="selectCategory(cat.id)"
                                :class="[
                                    'px-3.5 py-2 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5',
                                    selectedCategory == cat.id 
                                        ? 'bg-brand-500 text-white shadow-sm shadow-brand-500/25' 
                                        : 'bg-cream-50 text-brown-700 border border-cream-200 hover:bg-cream-100'
                                ]"
                            >
                                <span>{{ cat.name }}</span>
                                <span v-if="cat.product_count" class="text-[10px] opacity-75 font-mono">({{ cat.product_count }})</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filter: Urutan -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-brown-500 mb-2">Urutan Tampilan</p>
                        <CustomSelect
                            :model-value="currentSort"
                            :options="[
                                { value: 'latest', label: 'Terbaru', icon: Sparkles, description: 'Produk paling anyar' },
                                { value: 'price_asc', label: 'Termurah', icon: ArrowDownNarrowWide, description: 'Mulai dari harga terendah' },
                                { value: 'price_desc', label: 'Termahal', icon: ArrowUpWideNarrow, description: 'Porsi & kualitas istimewa' }
                            ]"
                            variant="bakery"
                            size="md"
                            :full-width="true"
                            @change="handleSortChange"
                        />
                    </div>

                    <!-- Footer Actions -->
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
                            @click="isMobileFilterOpen = false"
                            class="flex-1 py-3 px-4 rounded-xl bg-brand-500 text-white font-bold text-sm shadow-md shadow-brand-500/25 hover:bg-brand-600 transition cursor-pointer"
                        >
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Selected Items Panel -->
            <section v-if="selectedItems.length > 0" class="sticky bottom-16 md:bottom-0 left-0 right-0 z-40 px-3 sm:px-4 pb-2 md:pb-6 pointer-events-none mt-4">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-brand-50 border border-brand-200 rounded-2xl p-4 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] pointer-events-auto transform transition-transform duration-300">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex-1 w-full">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-bold text-brown-800">{{ selectedItems.length }} produk dipilih</p>
                                    <button @click="sendToCart" class="sm:hidden px-6 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition font-bold shadow-md text-sm">Masuk Keranjang</button>
                                </div>
                                <!-- Tags Produk (Scrollable kalau banyak) -->
                                <div class="flex overflow-x-auto gap-2 pb-1 scrollbar-hide">
                                    <div v-for="item in selectedItems" :key="item.id" class="bg-white rounded-lg px-3 py-1.5 flex items-center gap-2 shadow-sm border border-brand-100 flex-shrink-0">
                                        <span class="text-sm font-medium text-gray-900 truncate max-w-[120px]">{{ item.name }}</span>
                                        <span class="text-xs text-brand-600 font-bold bg-brand-50 px-1.5 py-0.5 rounded">{{ item.qty }}x</span>
                                        <button @click="removeSelected(item.id)" class="text-red-400 hover:text-red-600 transition p-0.5 hover:bg-red-50 rounded" aria-label="Hapus produk pilihan">
                                            <X class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden sm:block">
                                <button @click="sendToCart" class="px-8 py-3 bg-brand-500 text-white rounded-xl hover:bg-brand-600 transition font-bold shadow-lg flex items-center gap-2 whitespace-nowrap hover:-translate-y-0.5 duration-200">
                                    <ShoppingCart class="w-5 h-5" />
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
import { X, ShoppingCart, SlidersHorizontal, Sparkles, ArrowDownNarrowWide, ArrowUpWideNarrow } from 'lucide-vue-next';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import ShopHeader from '@/Pages/Customer/Shop/Landing/Header.vue';
import ShopSidebar from '@/Pages/Customer/Shop/Landing/Sidebar.vue';
import ShopProduct from '@/Pages/Customer/Shop/Landing/Product.vue';
import CustomSelect from '@/Components/UI/CustomSelect.vue';

import { useCartStore } from '@/Stores/CartStore.js';
import { useSelectedItemsStore } from '@/Stores/SelectedItemsStore.js';

import { getImageUrl } from '@/helpers.js';

const props = defineProps({
    products: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    pagination: { type: Object, default: () => ({ current_page: 1, last_page: 1, total: 0, per_page: 10 }) },
    filters: { type: Object, default: () => ({ search: '', category_id: '', sort: 'latest' }) },
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
const currentSort = ref(props.filters?.sort || 'latest');
const isMobileFilterOpen = ref(false);

// Fetch via Inertia with server-side pagination, sorting & filtering
const fetchProducts = (page = 1) => {
    router.get('/shop', {
        page,
        search: searchQuery.value,
        category_id: selectedCategory.value,
        sort: currentSort.value,
    }, { preserveState: true, replace: true });
};

const handleSortChange = (sortVal) => {
    currentSort.value = sortVal;
    fetchProducts(1);
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

const resetFilters = () => {
    searchQuery.value = '';
    selectedCategory.value = '';
    currentSort.value = 'latest';
    isMobileFilterOpen.value = false;
    fetchProducts(1);
}

const handleAddToCart = (product) => {
    addSelected(product, minOrderQty.value, activeType.value === 'box' ? 'kustom_box' : 'satuan');
    quantities.value[product.id] = minOrderQty.value;
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
