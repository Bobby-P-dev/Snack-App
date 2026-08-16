<template>
    <CustomerLayout>
        <div class="min-h-screen bg-cream-100">
            <!-- Header / Search Section -->
            <TrackingHeader 
                :searched-order-number="searchedOrderNumber"
                @search="handleSearch"
            />

            <!-- Result Section -->
            <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pb-20 mt-8" v-if="order || error">
                <div v-if="error" class="bg-white rounded-2xl p-8 text-center shadow-sm border border-red-100">
                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pencarian Gagal</h3>
                    <p class="text-gray-500">{{ error }}</p>
                    <button @click="resetSearch" class="mt-6 px-6 py-2 bg-brand-500 text-white rounded-full font-medium hover:bg-brand-600 transition">
                        Coba Lagi
                    </button>
                </div>
                
                <TrackingResult 
                    v-else-if="order" 
                    :order="order"
                    @reset="resetSearch"
                />
            </div>
            
            <!-- Default illustration / empty space if no search yet -->
            <div v-else class="w-full mx-auto px-4 sm:px-6 lg:px-8 pb-24 text-center opacity-70">
                <p class="text-gray-400 mt-12">Silakan masukkan Order Number Anda pada form di atas.</p>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import TrackingHeader from './Landing/Header.vue';
import TrackingResult from './Landing/TrackingResult.vue';

const props = defineProps({
    cms: {
        type: Object,
        default: () => ({})
    },
    searchedOrderNumber: {
        type: String,
        default: null
    },
    order: {
        type: Object,
        default: null
    },
    error: {
        type: String,
        default: null
    }
});

const handleSearch = (orderNumber) => {
    if (!orderNumber) return;
    
    router.get('/tracking', { order_number: orderNumber }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetSearch = () => {
    router.get('/tracking');
};
</script>
