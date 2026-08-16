<template>
    <div>
        <!-- Desktop Sidebar -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
            <!-- Kategori -->
            <div class="mb-6">
                <h3 class="text-base font-bold text-brown-800 mb-3">Kategori</h3>
                <nav class="space-y-1">
                    <button @click="$emit('select-category', '')" 
                        :class="['w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition text-sm', !selectedCategory ? 'bg-brand-50 text-brand-700 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium']">
                        <span>Semua Kategori</span>
                        <span v-if="!selectedCategory" class="bg-brand-100 text-brand-700 py-0.5 px-2.5 rounded-full text-xs font-bold">{{ totalProducts }}</span>
                    </button>
                    <button v-for="category in categories" :key="category.id" @click="$emit('select-category', category.id)" 
                        :class="['w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition text-sm', selectedCategory == category.id ? 'bg-brand-50 text-brand-700 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium']">
                        <span>{{ category.name }}</span>
                        <span :class="['py-0.5 px-2.5 rounded-full text-xs font-bold', selectedCategory == category.id ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500']">{{ category.product_count }}</span>
                    </button>
                </nav>
            </div>

            <!-- Harga (Static UI as placeholder) -->
            <div class="mb-6">
                <h3 class="text-base font-bold text-brown-800 mb-3">Harga</h3>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        <span class="font-medium">Semua Harga</span>
                    </label>
                    <label class="flex items-center space-x-3 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        <span>Rp 0 - Rp 25.000</span>
                    </label>
                    <label class="flex items-center space-x-3 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        <span>Rp 25.000 - Rp 50.000</span>
                    </label>
                    <label class="flex items-center space-x-3 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        <span>> Rp 50.000</span>
                    </label>
                </div>
            </div>

            <!-- Tipe (Box / Satuan) -->
            <div class="mb-6">
                <h3 class="text-base font-bold text-brown-800 mb-3">Tipe</h3>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        <span class="font-medium">Semua Tipe</span>
                    </label>
                    <label class="flex items-center space-x-3 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        <span>Snack Box</span>
                    </label>
                    <label class="flex items-center space-x-3 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        <span>Kue Satuan</span>
                    </label>
                </div>
            </div>

            <!-- Reset Filter -->
            <button @click="$emit('reset-filter')" class="w-full py-2.5 px-4 border border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                Reset Filter
            </button>
        </div>

        <div class="lg:hidden overflow-x-auto pb-2 mb-6 scrollbar-hide">
            <div class="flex space-x-2">
                <button @click="$emit('select-category', '')" :class="['px-4 py-2.5 rounded-full text-sm font-medium whitespace-nowrap transition', !selectedCategory ? 'bg-brand-500 text-white' : 'bg-white text-gray-700 border border-gray-200 shadow-sm']">Semua</button>
                <button v-for="category in categories" :key="category.id" @click="$emit('select-category', category.id)" :class="['px-4 py-2.5 rounded-full text-sm font-medium whitespace-nowrap transition', selectedCategory == category.id ? 'bg-brand-500 text-white' : 'bg-white text-gray-700 border border-gray-200 shadow-sm']">{{ category.name }}</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    },
    selectedCategory: {
        type: [String, Number],
        default: ''
    }
});

defineEmits(['select-category', 'reset-filter']);

const totalProducts = computed(() => {
    return props.categories.reduce((acc, cat) => acc + (cat.product_count || 0), 0);
});
</script>
