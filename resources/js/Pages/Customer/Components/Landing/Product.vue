<script setup>
import { ref, computed } from 'vue';
import { Package, CakeSlice, Coffee, Users, ShoppingCart, ArrowRight } from 'lucide-vue-next';
import { useSelectedItemsStore } from '@/Stores/SelectedItemsStore.js';
import { getImageUrl } from '@/helpers.js';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    products: { type: Array, default: () => [] },
    cms: { type: Object, default: () => ({}) }
});

const { addItem } = useSelectedItemsStore();

const activeCategory = ref('box');

const categories = [
    { id: 'box', label: 'Snack Box', icon: Package },
    { id: 'satuan', label: 'Kue Satuan', icon: CakeSlice },
    { id: 'minuman', label: 'Minuman', icon: Coffee },
    { id: 'meeting', label: 'Paket Meeting', icon: Users },
];

const displayedProducts = computed(() => {
    if (activeCategory.value === 'box') return props.products.filter((p, i) => i % 2 === 0).slice(0, 4);
    if (activeCategory.value === 'satuan') return props.products.filter((p, i) => i % 2 !== 0).slice(0, 4);
    return [];
});

const minBox = computed(() => parseInt(props.cms?.min_order_box) || 10);
const minSatuan = computed(() => parseInt(props.cms?.min_order_satuan) || 10);
const currentMinQty = computed(() => activeCategory.value === 'box' ? minBox.value : minSatuan.value);

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);

const handleAddToCart = (product) => {
    const type = activeCategory.value === 'box' ? 'kustom_box' : 'satuan';
    addItem(product, currentMinQty.value, type);
};
</script>

<template>
    <section class="py-16 md:py-24 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Category Filtering -->
            <div class="mb-16 flex justify-center">
                <div class="inline-flex flex-wrap md:flex-nowrap gap-3 md:gap-4 overflow-x-auto pb-2 hide-scrollbar w-full md:w-auto">
                    <button 
                        v-for="cat in categories" 
                        :key="cat.id"
                        @click="activeCategory = cat.id"
                        class="flex-1 md:flex-none min-w-[150px] flex items-center justify-center gap-3 py-3 md:py-4 px-6 rounded-xl border-2 transition-all duration-300 font-bold text-sm md:text-base whitespace-nowrap"
                        :class="activeCategory === cat.id ? 'border-blue-500 bg-blue-50/50 text-blue-700 shadow-sm' : 'border-gray-100 bg-white text-gray-600 hover:border-gray-200'"
                    >
                        <component :is="cat.icon" class="w-5 h-5" :class="activeCategory === cat.id ? 'text-blue-600' : 'text-gray-500'" stroke-width="2" />
                        {{ cat.label }}
                    </button>
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-px bg-gray-200 mb-12"></div>

            <!-- Best Sellers -->
            <div>
                <div class="flex flex-col lg:flex-row justify-between mb-8 gap-4">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">Produk Terlaris</h2>
                        <p class="text-gray-500 text-sm md:text-base">Pilihan snack box favorit pelanggan kami</p>
                    </div>
                    <Link href="/shop" class="inline-flex items-center gap-2 font-bold text-sm text-gray-900 hover:text-blue-600 transition group mt-2 lg:mt-0">
                        Lihat Semua 
                        <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition" />
                    </Link>
                </div>

                <!-- Product Grid -->
                <div v-if="displayedProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="(product, idx) in displayedProducts" :key="idx" class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group">
                        
                        <!-- Image Container -->
                        <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                            <img v-if="product.image_url" :src="getImageUrl(product.image_url)" :alt="product.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="font-bold text-gray-900 text-base mb-1 line-clamp-1">{{ product.name }}</h3>
                            <p class="text-xs text-gray-500 line-clamp-2 mb-4 flex-1 leading-relaxed">
                                {{ product.description || 'Pilihan lengkap untuk acara spesial Anda dengan cita rasa premium.' }}
                            </p>
                            
                            <p class="text-sm font-bold text-gray-900 mb-5">Rp {{ formatNumber(product.sell_price) }}</p>
                            
                            <div class="flex items-center gap-2 mt-auto">
                                <Link :href="'/shop/' + product.id" class="flex-1 py-2 px-4 text-center border-2 border-gray-200 text-gray-900 font-bold rounded-xl hover:border-gray-300 hover:bg-gray-50 transition text-xs">
                                    Lihat Detail
                                </Link>
                                <button @click="handleAddToCart(product)" class="w-9 h-9 flex items-center justify-center border-2 border-gray-200 text-gray-900 rounded-xl hover:border-gray-300 hover:bg-gray-50 transition shrink-0" title="Tambah ke Keranjang">
                                    <ShoppingCart class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-20 bg-white rounded-2xl border border-gray-100 border-dashed">
                    <p class="text-gray-500 font-medium text-lg">Belum ada produk untuk kategori ini</p>
                </div>
            </div>

        </div>
    </section>
</template>
<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
