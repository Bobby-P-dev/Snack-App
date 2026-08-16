<template>
    <div>
        <!-- Desktop Header -->
        <div class="hidden sm:flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 border-b border-gray-100 pb-4">
            <p class="text-sm text-gray-600">Menampilkan {{ pagination.from || 1 }} - {{ pagination.to || products.length }} dari {{ pagination.total || products.length }} produk</p>
            <div class="flex items-center justify-end gap-4 w-auto">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Urutkan:</span>
                    <select class="text-sm border-gray-200 rounded-lg py-1.5 pl-3 pr-8 focus:ring-brand-500 focus:border-brand-500 font-medium">
                        <option>Terbaru</option>
                        <option>Harga Terendah</option>
                        <option>Harga Tertinggi</option>
                    </select>
                </div>
                <div class="flex border border-gray-200 rounded-lg overflow-hidden shrink-0">
                    <button class="p-2 bg-brand-50 text-brand-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg></button>
                    <button class="p-2 text-gray-400 hover:text-gray-600 bg-white"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg></button>
                </div>
            </div>
        </div>

        <!-- Mobile Filter & Sort Row -->
        <div class="flex sm:hidden items-center gap-3 mb-6">
            <button @click="$emit('open-mobile-filter')" class="flex-1 flex items-center justify-center gap-2 py-2 px-4 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 bg-white shadow-sm hover:bg-gray-50 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                Filter
            </button>
            <div class="flex-1 relative">
                <select class="w-full appearance-none py-2 pl-4 pr-10 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 bg-white shadow-sm hover:bg-gray-50 transition focus:outline-none" style="text-align-last: center;">
                    <option hidden>Urutkan</option>
                    <option>Terbaru</option>
                    <option>Termurah</option>
                    <option>Termahal</option>
                </select>
                <svg class="w-4 h-4 text-gray-500 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
        </div>

        <!-- Products Grid -->
        <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5">
            <ProductCard 
                v-for="product in products" 
                :key="product.id" 
                :product="product" 
                @add-to-cart="$emit('add-to-cart', $event)" 
            />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-gray-500 mb-4 text-sm">Coba ubah pencarian atau kategori Anda</p>
            <button @click="$emit('reset-filter')" class="px-6 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition">Reset Filter</button>
        </div>

        <!-- Pagination -->
        <!-- Pagination -->
        <div v-if="pagination.last_page > 1" class="mt-8">
            <!-- Desktop -->
            <div class="hidden sm:flex items-center justify-between">
                <p class="text-sm text-gray-500">Halaman {{ pagination.current_page }} dari {{ pagination.last_page }} ({{ pagination.total }} produk)</p>
                <div class="flex gap-2">
                    <button :disabled="pagination.current_page <= 1" @click="$emit('go-to-page', pagination.current_page - 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Prev
                    </button>
                    <button :disabled="pagination.current_page >= pagination.last_page" @click="$emit('go-to-page', pagination.current_page + 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
                        Next
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile -->
            <div class="flex sm:hidden items-center justify-center gap-3">
                <button :disabled="pagination.current_page <= 1" @click="$emit('go-to-page', pagination.current_page - 1)" class="w-8 h-8 flex items-center justify-center text-gray-500 disabled:opacity-50 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
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
                
                <button :disabled="pagination.current_page >= pagination.last_page" @click="$emit('go-to-page', pagination.current_page + 1)" class="w-8 h-8 flex items-center justify-center text-gray-500 disabled:opacity-50 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import ProductCard from '@/Pages/Customer/Components/ProductCard.vue';

defineProps({
    products: {
        type: Array,
        required: true
    },
    pagination: {
        type: Object,
        required: true
    }
});

defineEmits(['add-to-cart', 'reset-filter', 'go-to-page']);
</script>
