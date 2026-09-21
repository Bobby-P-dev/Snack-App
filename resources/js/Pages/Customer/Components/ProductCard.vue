<template>
    <div class="bg-white rounded-2xl border border-cream-200/80 shadow-2xs hover:shadow-xl hover:border-brand-200 transition-all duration-300 overflow-hidden flex flex-col group h-full">
        <!-- Image Container (Clickable to open Detail Modal) -->
        <div
            @click="openDetail"
            class="w-full aspect-[4/3] bg-cream-100 relative overflow-hidden shrink-0 cursor-pointer"
        >
            <img v-if="product.image_url" :src="getImageUrl(product.image_url)" :alt="product.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
            <div v-else class="w-full h-full flex items-center justify-center text-brown-300">
                <Image class="w-8 h-8 sm:w-12 sm:h-12 text-cream-400 stroke-1" />
            </div>
            
            <div v-if="product.badge" class="absolute top-2 left-2 sm:top-3 sm:left-3">
                <span :class="['px-2 py-0.5 sm:px-3 sm:py-1 text-[9px] sm:text-xs font-bold rounded-full text-white shadow-sm', product.badge === 'Baru' ? 'bg-emerald-600' : 'bg-brand-500']">
                    {{ product.badge }}
                </span>
            </div>
        </div>

        <!-- Card Content -->
        <div class="p-3 sm:p-4 md:p-5 flex flex-col flex-1 min-w-0">
            <h3
                @click="openDetail"
                class="font-bold text-brown-900 text-xs sm:text-base mb-1 line-clamp-1 group-hover:text-brand-600 transition-colors cursor-pointer"
            >
                {{ product.name }}
            </h3>
            <p class="text-[10px] sm:text-xs text-brown-600/80 line-clamp-2 mb-2 sm:mb-3 flex-1 leading-tight sm:leading-relaxed">
                {{ product.description || 'Pilihan lezat untuk acara spesial Anda dengan cita rasa khas dan higienis.' }}
            </p>
            
            <div class="mb-2.5 sm:mb-3.5">
                <div class="flex items-baseline gap-1">
                    <span class="text-xs sm:text-base font-extrabold text-brand-600 font-mono whitespace-nowrap">
                        Rp {{ formatNumber(product.sell_price) }}
                    </span>
                    <span class="text-[10px] sm:text-xs font-normal text-brown-400">/ pcs</span>
                </div>
                <p class="text-[10px] sm:text-xs text-brown-500 font-normal mt-0.5">
                    min. {{ minOrder }} pcs
                </p>
            </div>
            
            <div class="mt-auto pt-2.5 sm:pt-3 border-t border-cream-100">
                <button
                    type="button"
                    @click="openDetail"
                    class="w-full py-2 sm:py-2.5 px-3 flex items-center justify-center gap-1.5 sm:gap-2 bg-brand-50/80 hover:bg-brand-500 text-brand-700 hover:text-white border border-brand-200/90 hover:border-brand-500 font-bold rounded-xl transition-all duration-200 text-xs sm:text-sm active:scale-[0.98] shadow-2xs hover:shadow-md hover:shadow-brand-500/20 cursor-pointer group/btn"
                >
                    <ShoppingCart class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-brand-600 group-hover/btn:text-white transition-colors" />
                    <span>+ Keranjang</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { ShoppingCart, Image } from 'lucide-vue-next';
import { getImageUrl } from '@/helpers.js';
import { useProductModalStore } from '@/Stores/ProductModalStore.js';

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const page = usePage();
const minOrder = computed(() => {
    const val = parseInt(page.props.cms?.settings?.min_order_satuan);
    return !isNaN(val) && val > 0 ? val : 10;
});

const modalStore = useProductModalStore();

const openDetail = () => {
    modalStore.openModal(props.product);
};

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num || 0);
</script>
