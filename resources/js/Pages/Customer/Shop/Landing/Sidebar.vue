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
                <RotateCcw class="w-4 h-4" />
                Reset Filter
            </button>
        </div>

        <div class="lg:hidden overflow-x-auto pb-2 mb-6 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
            <div class="flex space-x-2">
                <button @click="$emit('select-category', '')" :class="['px-4 py-2 rounded-xl text-xs sm:text-sm whitespace-nowrap transition cursor-pointer', !selectedCategory ? 'bg-brand-500 text-white font-bold shadow-sm shadow-brand-500/25' : 'bg-white text-brown-700 font-semibold border border-cream-200 shadow-2xs hover:bg-cream-50']">Semua Kategori</button>
                <button v-for="category in categories" :key="category.id" @click="$emit('select-category', category.id)" :class="['px-4 py-2 rounded-xl text-xs sm:text-sm whitespace-nowrap transition cursor-pointer', selectedCategory == category.id ? 'bg-brand-500 text-white font-bold shadow-sm shadow-brand-500/25' : 'bg-white text-brown-700 font-semibold border border-cream-200 shadow-2xs hover:bg-cream-50']">{{ category.name }}</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { RotateCcw } from 'lucide-vue-next';

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
