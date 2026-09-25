<template>
    <div>
        <!-- Desktop Header -->
        <div class="hidden sm:flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 border-b border-cream-200/60 pb-4">
            <p class="text-sm text-brown-600">Menampilkan {{ pagination.from || 1 }} - {{ pagination.to || products.length }} dari {{ pagination.total || products.length }} produk</p>
            <div class="flex items-center justify-end gap-4 w-auto">
                <div class="flex items-center gap-2">
                    <CustomSelect
                        :model-value="currentSort"
                        :options="sortOptions"
                        variant="bakery"
                        size="sm"
                        prefix="Urutkan:"
                        menu-width="w-56"
                        align="right"
                        @change="$emit('change-sort', $event)"
                    />
                </div>
                <div class="flex border border-cream-200 rounded-xl overflow-hidden shrink-0 shadow-2xs">
                    <button class="p-2 bg-brand-50 text-brand-600" aria-label="Tampilan Grid"><LayoutGrid class="w-4 h-4" /></button>
                    <button class="p-2 text-brown-400 hover:text-brown-700 bg-white transition" aria-label="Tampilan List"><List class="w-4 h-4" /></button>
                </div>
            </div>
        </div>

        <!-- Mobile Filter & Sort Row -->
        <div class="flex sm:hidden items-center gap-2.5 mb-6">
            <button 
                type="button"
                @click="$emit('open-mobile-filter')" 
                class="flex-1 flex items-center justify-center gap-2 py-2 px-4 rounded-full border border-cream-200 bg-white font-semibold text-brown-800 text-xs sm:text-sm shadow-2xs hover:border-brand-300 hover:bg-cream-50/40 transition cursor-pointer"
            >
                <SlidersHorizontal class="w-4 h-4 text-brand-500" />
                <span>Filter</span>
            </button>
            <div class="flex-1">
                <CustomSelect
                    :model-value="currentSort"
                    :options="sortOptions"
                    variant="pill"
                    size="md"
                    :full-width="true"
                    menu-width="w-full"
                    placeholder="Urutkan"
                    align="right"
                    @change="$emit('change-sort', $event)"
                />
            </div>
        </div>

        <!-- Products Grid (2 cols mobile, 3-4 cols desktop) -->
        <div v-if="products.length > 0" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 md:gap-5">
            <ProductCard 
                v-for="product in products" 
                :key="product.id" 
                :product="product" 
                @add-to-cart="$emit('add-to-cart', $event)" 
            />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <PackageSearch class="w-16 h-16 mx-auto text-gray-300 mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-gray-500 mb-4 text-sm">Coba ubah pencarian atau kategori Anda</p>
            <button @click="$emit('reset-filter')" class="px-6 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition">Reset Filter</button>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.last_page > 1" class="mt-8">
            <!-- Desktop -->
            <div class="hidden sm:flex items-center justify-between">
                <p class="text-sm text-gray-500">Halaman {{ pagination.current_page }} dari {{ pagination.last_page }} ({{ pagination.total }} produk)</p>
                <div class="flex gap-2">
                    <button :disabled="pagination.current_page <= 1" @click="$emit('go-to-page', pagination.current_page - 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
                        <ChevronLeft class="w-4 h-4" />
                        Prev
                    </button>
                    <button :disabled="pagination.current_page >= pagination.last_page" @click="$emit('go-to-page', pagination.current_page + 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
                        Next
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- Mobile -->
            <div class="flex sm:hidden items-center justify-center gap-3">
                <button :disabled="pagination.current_page <= 1" @click="$emit('go-to-page', pagination.current_page - 1)" class="w-8 h-8 flex items-center justify-center text-gray-500 disabled:opacity-50 hover:text-gray-900" aria-label="Halaman sebelumnya">
                    <ChevronLeft class="w-5 h-5" />
                </button>
                
                <button 
                    v-for="page in Math.min(3, pagination.last_page)" 
                    :key="page"
                    @click="$emit('go-to-page', page)"
                    :class="[
                        'w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition',
                        pagination.current_page === page ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-50'
                    ]"
                >
                    {{ page }}
                </button>
                
                <button :disabled="pagination.current_page >= pagination.last_page" @click="$emit('go-to-page', pagination.current_page + 1)" class="w-8 h-8 flex items-center justify-center text-gray-500 disabled:opacity-50 hover:text-gray-900" aria-label="Halaman selanjutnya">
                    <ChevronRight class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { LayoutGrid, List, SlidersHorizontal, PackageSearch, ChevronLeft, ChevronRight, Sparkles, ArrowDownNarrowWide, ArrowUpWideNarrow, ArrowDownAZ } from 'lucide-vue-next';
import ProductCard from '@/Pages/Customer/Components/ProductCard.vue';
import CustomSelect from '@/Components/UI/CustomSelect.vue';

defineProps({
    products: {
        type: Array,
        required: true
    },
    pagination: {
        type: Object,
        required: true
    },
    currentSort: {
        type: String,
        default: 'latest'
    }
});

defineEmits(['add-to-cart', 'reset-filter', 'go-to-page', 'change-sort', 'open-mobile-filter']);

const sortOptions = [
    { value: 'latest', label: 'Terbaru', icon: Sparkles, description: 'Produk paling anyar' },
    { value: 'price_asc', label: 'Termurah', icon: ArrowDownNarrowWide, description: 'Mulai dari harga terendah' },
    { value: 'price_desc', label: 'Termahal', icon: ArrowUpWideNarrow, description: 'Porsi & kualitas istimewa' },
    { value: 'name_asc', label: 'Nama A - Z', icon: ArrowDownAZ, description: 'Berdasarkan urutan alfabet' },
];
</script>
