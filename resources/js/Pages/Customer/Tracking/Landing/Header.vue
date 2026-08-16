<template>
    <div class="px-4 sm:px-6 lg:px-8 w-full mt-8 mb-8">
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-gray-100 w-full mx-auto text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-brown-800 mb-4">Lacak Pesanan Anda</h1>
        <p class="text-brown-600 mb-8 max-w-xl mx-auto">
            Masukkan Order Number Anda di bawah ini untuk melihat status dan rincian pesanan.
        </p>
        <form @submit.prevent="submitSearch" class="max-w-md mx-auto relative">
            <input 
                v-model="orderNumber" 
                type="text" 
                placeholder="Contoh: ORD-..." 
                class="w-full px-6 py-4 rounded-full border-2 border-brand-200 focus:border-brand-500 focus:ring-brand-500 bg-cream-50 text-brown-800 placeholder-gray-400 font-medium pr-32 transition-all outline-none"
            >
            <button 
                type="submit" 
                class="absolute right-2 top-2 bottom-2 px-6 bg-brand-500 text-white rounded-full font-bold hover:bg-brand-600 transition-colors shadow-sm flex items-center justify-center"
            >
                Lacak
            </button>
        </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    searchedOrderNumber: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['search']);

const orderNumber = ref(props.searchedOrderNumber || '');

watch(() => props.searchedOrderNumber, (newVal) => {
    orderNumber.value = newVal || '';
});

const submitSearch = () => {
    if (orderNumber.value.trim()) {
        emit('search', orderNumber.value.trim());
    }
};
</script>
