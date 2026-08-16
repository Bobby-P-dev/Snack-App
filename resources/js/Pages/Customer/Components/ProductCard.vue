<template>
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-row sm:flex-col group h-[130px] sm:h-auto">
        <!-- Image Container -->
        <div class="w-[120px] shrink-0 sm:w-auto sm:aspect-[4/3] bg-gray-100 relative overflow-hidden">
            <img v-if="product.image_url" :src="getImageUrl(product.image_url)" :alt="product.name" class="w-full h-full object-cover transition-transform duration-500 sm:group-hover:scale-105" />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            
            <div v-if="product.badge" class="absolute top-2 left-2 sm:top-3 sm:left-3">
                <span :class="['px-2 py-0.5 sm:px-3 sm:py-1 text-[9px] sm:text-xs font-bold rounded-full text-white shadow-sm', product.badge === 'Baru' ? 'bg-green-500' : 'bg-brand-500']">
                    {{ product.badge }}
                </span>
            </div>
        </div>

        <!-- Card Content -->
        <div class="p-3 sm:p-5 flex flex-col flex-1 min-w-0">
            <h3 class="font-bold text-brown-800 text-xs sm:text-base mb-1 line-clamp-1">{{ product.name }}</h3>
            <p class="text-[10px] sm:text-xs text-gray-500 line-clamp-2 mb-2 sm:mb-4 flex-1 leading-tight sm:leading-relaxed">
                {{ product.description || 'Pilihan lengkap untuk acara spesial Anda dengan cita rasa premium.' }}
            </p>
            
            <p class="text-xs sm:text-sm font-bold text-brown-800 mb-2 sm:mb-5">Rp {{ formatNumber(product.sell_price) }}</p>
            
            <div class="flex items-center gap-2 mt-auto">
                <Link :href="'/shop/' + product.id" class="flex-1 py-1.5 sm:py-2 px-2 sm:px-4 text-center border border-gray-200 sm:border-2 text-brown-800 font-bold rounded-lg sm:rounded-xl hover:border-gray-300 hover:bg-gray-50 transition text-[10px] sm:text-xs">
                    Lihat Detail
                </Link>
                <button @click="$emit('add-to-cart', product)" class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center border border-gray-200 sm:border-2 text-brown-800 rounded-lg sm:rounded-xl hover:border-gray-300 hover:bg-gray-50 transition shrink-0" title="Tambah ke Keranjang">
                    <ShoppingCart class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { getImageUrl } from '@/helpers.js';

defineProps({
    product: {
        type: Object,
        required: true
    }
});

defineEmits(['add-to-cart']);

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);
</script>
