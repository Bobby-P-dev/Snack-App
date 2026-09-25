<template>
    <div>
        <!-- Desktop Sidebar -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-xs border border-cream-200/80 p-5 sticky top-24 space-y-6">
            <!-- Header Sidebar Filter -->
            <div class="flex items-center justify-between pb-3 border-b border-cream-100">
                <div class="flex items-center gap-2 text-brown-900 font-bold">
                    <SlidersHorizontal class="w-4 h-4 text-brand-500" />
                    <span>Filter Produk</span>
                </div>
                <button
                    v-if="hasActiveFilter"
                    @click="$emit('reset-filter')"
                    class="text-xs text-brand-600 hover:text-brand-700 font-semibold cursor-pointer transition"
                >
                    Reset
                </button>
            </div>

            <!-- Kategori -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-brown-500 mb-3">
                    Kategori
                </h3>
                <nav class="space-y-1">
                    <button
                        type="button"
                        @click="$emit('select-category', '')" 
                        :class="[
                            'w-full flex items-center justify-between px-3 py-2 rounded-xl transition text-sm cursor-pointer',
                            !selectedCategory
                                ? 'bg-brand-50 text-brand-700 font-bold'
                                : 'text-brown-700 hover:bg-cream-50 font-medium'
                        ]"
                    >
                        <span>Semua Kategori</span>
                        <span :class="['py-0.5 px-2 rounded-full text-xs font-mono font-bold', !selectedCategory ? 'bg-brand-100 text-brand-700' : 'bg-cream-100 text-brown-600']">
                            {{ totalProducts }}
                        </span>
                    </button>
                    <button
                        v-for="category in categories"
                        :key="category.id"
                        type="button"
                        @click="$emit('select-category', category.id)" 
                        :class="[
                            'w-full flex items-center justify-between px-3 py-2 rounded-xl transition text-sm cursor-pointer',
                            selectedCategory == category.id
                                ? 'bg-brand-50 text-brand-700 font-bold'
                                : 'text-brown-700 hover:bg-cream-50 font-medium'
                        ]"
                    >
                        <span>{{ category.name }}</span>
                        <span :class="['py-0.5 px-2 rounded-full text-xs font-mono font-bold', selectedCategory == category.id ? 'bg-brand-100 text-brand-700' : 'bg-cream-100 text-brown-600']">
                            {{ category.product_count || 0 }}
                        </span>
                    </button>
                </nav>
            </div>

            <!-- Rentang Harga -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-brown-500 mb-3">
                    Rentang Harga
                </h3>
                <div class="space-y-1.5">
                    <button
                        v-for="tier in priceTiers"
                        :key="tier.id"
                        type="button"
                        @click="handlePriceTierSelect(tier.id)"
                        :class="[
                            'w-full flex items-center justify-between px-3 py-2 rounded-xl text-sm transition cursor-pointer text-left',
                            selectedPriceRange === tier.id && !isCustomPriceActive
                                ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200/80'
                                : 'text-brown-700 hover:bg-cream-50 border border-transparent font-medium'
                        ]"
                    >
                        <span>{{ tier.label }}</span>
                        <span class="text-[11px] font-normal text-brown-600 opacity-80">{{ tier.hint }}</span>
                    </button>
                </div>

                <!-- Custom Price Range Inputs -->
                <div class="mt-3 pt-3 border-t border-cream-100">
                    <button
                        type="button"
                        @click="toggleCustomPrice"
                        class="flex items-center justify-between w-full text-xs font-semibold text-brown-600 hover:text-brown-900 mb-2 cursor-pointer"
                    >
                        <span>Atur Harga Spesifik</span>
                        <ChevronDown :class="['w-3.5 h-3.5 transition-transform duration-200', showCustomPriceInputs ? 'rotate-180' : '']" />
                    </button>

                    <div v-show="showCustomPriceInputs || isCustomPriceActive" class="space-y-2 mt-2">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-brown-600 mb-0.5">Min Rp</label>
                                <input
                                    v-model.number="localMinPrice"
                                    type="number"
                                    min="0"
                                    step="1000"
                                    placeholder="0"
                                    class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-cream-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 bg-white"
                                />
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-brown-600 mb-0.5">Maks Rp</label>
                                <input
                                    v-model.number="localMaxPrice"
                                    type="number"
                                    min="0"
                                    step="1000"
                                    placeholder="50.000"
                                    class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-cream-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 bg-white"
                                />
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="applyCustomPrice"
                            class="w-full py-1.5 px-3 bg-brown-900 hover:bg-brown-950 text-white rounded-lg text-xs font-bold transition shadow-2xs cursor-pointer"
                        >
                            Terapkan Harga
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reset Filter Button -->
            <button
                type="button"
                @click="$emit('reset-filter')"
                class="w-full py-2.5 px-4 border border-cream-200 rounded-xl text-xs font-bold text-brown-700 hover:bg-cream-50 transition flex items-center justify-center gap-2 cursor-pointer"
            >
                <RotateCcw class="w-3.5 h-3.5 text-brown-500" />
                <span>Reset Semua Filter</span>
            </button>
        </div>

        <!-- Mobile Categories Pill Bar -->
        <div class="lg:hidden overflow-x-auto pb-2 mb-4 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
            <div class="flex space-x-2">
                <button
                    type="button"
                    @click="$emit('select-category', '')"
                    :class="[
                        'px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer',
                        !selectedCategory
                            ? 'bg-brand-500 text-white font-bold shadow-2xs'
                            : 'bg-white text-brown-700 border border-cream-200 hover:bg-cream-50'
                    ]"
                >
                    Semua Kategori ({{ totalProducts }})
                </button>
                <button
                    v-for="category in categories"
                    :key="category.id"
                    type="button"
                    @click="$emit('select-category', category.id)"
                    :class="[
                        'px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer',
                        selectedCategory == category.id
                            ? 'bg-brand-500 text-white font-bold shadow-2xs'
                            : 'bg-white text-brown-700 border border-cream-200 hover:bg-cream-50'
                    ]"
                >
                    {{ category.name }}
                    <span v-if="category.product_count" class="font-mono text-[10px] ml-1 opacity-80">({{ category.product_count }})</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { RotateCcw, SlidersHorizontal, ChevronDown } from 'lucide-vue-next';

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    },
    selectedCategory: {
        type: [String, Number],
        default: ''
    },
    selectedPriceRange: {
        type: String,
        default: 'all'
    },
    minPrice: {
        type: [String, Number],
        default: ''
    },
    maxPrice: {
        type: [String, Number],
        default: ''
    },
    hasActiveFilter: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits([
    'select-category',
    'select-price-range',
    'apply-custom-price',
    'reset-filter'
]);

const priceTiers = [
    { id: 'all', label: 'Semua Harga', hint: '' },
    { id: 'under_10k', label: '< Rp 10.000', hint: 'Jajanan Hemat' },
    { id: '10k_25k', label: 'Rp 10.000 - Rp 25.000', hint: 'Kue & Pastry Favorit' },
    { id: 'above_25k', label: '> Rp 25.000', hint: 'Paket / Bolu Spesial' },
];

const showCustomPriceInputs = ref(false);
const localMinPrice = ref(props.minPrice || '');
const localMaxPrice = ref(props.maxPrice || '');

watch(() => props.minPrice, (val) => { localMinPrice.value = val || ''; });
watch(() => props.maxPrice, (val) => { localMaxPrice.value = val || ''; });

const isCustomPriceActive = computed(() => {
    return props.selectedPriceRange === 'custom' || (props.minPrice !== '' && props.minPrice !== null) || (props.maxPrice !== '' && props.maxPrice !== null);
});

const totalProducts = computed(() => {
    return props.categories.reduce((acc, cat) => acc + (cat.product_count || 0), 0);
});

const handlePriceTierSelect = (tierId) => {
    showCustomPriceInputs.value = false;
    emit('select-price-range', tierId);
};

const toggleCustomPrice = () => {
    showCustomPriceInputs.value = !showCustomPriceInputs.value;
};

const applyCustomPrice = () => {
    emit('apply-custom-price', {
        min: localMinPrice.value,
        max: localMaxPrice.value,
    });
};
</script>
