<script setup>
import { ref, computed } from 'vue';
import { Package, CakeSlice, Coffee, Users, ArrowRight } from 'lucide-vue-next';
import { useSelectedItemsStore } from '@/Stores/SelectedItemsStore.js';
import { Link } from '@inertiajs/vue3';
import ProductCard from '@/Pages/Customer/Components/ProductCard.vue';

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
    { id: 'meeting', label: 'Paket Acara', icon: Users },
];

const displayedProducts = computed(() => {
    if (activeCategory.value === 'box') return props.products.filter((p, i) => i % 2 === 0).slice(0, 4);
    if (activeCategory.value === 'satuan') return props.products.filter((p, i) => i % 2 !== 0).slice(0, 4);
    return [];
});

const minBox = computed(() => parseInt(props.cms?.min_order_box) || 10);
const minSatuan = computed(() => parseInt(props.cms?.min_order_satuan) || 10);
const currentMinQty = computed(() => activeCategory.value === 'box' ? minBox.value : minSatuan.value);

const handleAddToCart = (product) => {
    const type = activeCategory.value === 'box' ? 'kustom_box' : 'satuan';
    addItem(product, currentMinQty.value, type);
};
</script>

<template>
    <section class="py-16 md:py-24 bg-white border-b border-cream-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Category Filtering Pills -->
            <div class="mb-14 flex justify-center">
                <div class="inline-flex flex-wrap md:flex-nowrap gap-2.5 sm:gap-3.5 overflow-x-auto pb-2 hide-scrollbar w-full md:w-auto p-1.5 bg-cream-100/70 rounded-2xl border border-cream-200">
                    <button 
                        v-for="cat in categories" 
                        :key="cat.id"
                        @click="activeCategory = cat.id"
                        class="flex-1 md:flex-none min-w-[140px] flex items-center justify-center gap-2.5 py-3 px-5 rounded-xl transition-all duration-200 font-bold text-sm whitespace-nowrap"
                        :class="activeCategory === cat.id 
                            ? 'bg-brand-500 text-white shadow-md shadow-brand-500/25' 
                            : 'bg-transparent text-brown-700 hover:text-brand-600 hover:bg-white/80'"
                    >
                        <component :is="cat.icon" class="w-4 h-4" :class="activeCategory === cat.id ? 'text-white' : 'text-brown-500'" stroke-width="2.2" />
                        <span>{{ cat.label }}</span>
                    </button>
                </div>
            </div>

            <!-- Best Sellers Header -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 pb-4 border-b border-cream-200">
                    <div>
                        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60 inline-block mb-2">
                            Pilihan Favorit
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-brown-900 tracking-tight">Koleksi Terlaris</h2>
                        <p class="text-brown-600 text-sm sm:text-base mt-1">Pilihan snack box dan kue paling disukai pelanggan untuk berbagai acara</p>
                    </div>
                    <Link href="/shop" class="inline-flex items-center gap-2 font-bold text-sm text-brand-600 hover:text-brand-700 transition group self-start sm:self-auto py-2">
                        <span>Lihat Semua Katalog</span>
                        <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
                    </Link>
                </div>

                <!-- Product Grid -->
                <div v-if="displayedProducts.length > 0" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                    <ProductCard 
                        v-for="(product, idx) in displayedProducts" 
                        :key="idx" 
                        :product="product" 
                        @add-to-cart="handleAddToCart(product)" 
                    />
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16 bg-cream-50/60 rounded-3xl border border-cream-300/80 border-dashed">
                    <p class="text-brown-600 font-semibold text-base">Belum ada produk untuk kategori ini saat ini.</p>
                    <Link href="/shop" class="mt-3 inline-block text-brand-600 font-bold hover:underline text-sm">
                        Jelajahi menu lainnya di toko &rarr;
                    </Link>
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
