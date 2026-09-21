<template>
    <div class="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto w-full pt-4 sm:pt-6">
        <div class="bg-gradient-to-br from-white via-cream-50/90 to-brand-50/40 rounded-3xl p-6 sm:p-10 shadow-2xs border border-cream-200/90 text-center relative overflow-hidden">
            <!-- Decorative soft background circle -->
            <div class="absolute -right-12 -top-12 w-36 h-36 rounded-full bg-brand-100/40 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-36 h-36 rounded-full bg-amber-100/40 blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-brand-50 border border-brand-200/70 text-brand-600 text-xs font-bold uppercase tracking-wider mb-4 shadow-2xs">
                    <Sparkles class="w-3.5 h-3.5 text-brand-500" />
                    <span>Layanan Lacak Pesanan Real-time</span>
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-brown-900 mb-3 tracking-tight">
                    Lacak Status Pesanan Anda
                </h1>
                <p class="text-brown-600 text-sm sm:text-base mb-8 max-w-md mx-auto leading-relaxed">
                    Ketahui proses pembuatan kue dan pengiriman pesanan Anda secara transparan cukup dengan memasukkan Nomor Pesanan.
                </p>

                <!-- Search Input Form -->
                <form @submit.prevent="submitSearch" class="max-w-md mx-auto relative">
                    <div class="relative flex items-center">
                        <div class="absolute left-5 sm:left-6 top-1/2 -translate-y-1/2 pointer-events-none text-brown-400 flex items-center justify-center">
                            <Search class="w-5 h-5" />
                        </div>
                        <input 
                            v-model="orderNumber" 
                            type="text" 
                            placeholder="Contoh: PK-7X9KW2MB" 
                            class="w-full pl-14 pr-28 sm:pr-32 py-3.5 sm:py-4 rounded-full border-2 border-cream-300 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 bg-white text-brown-900 placeholder-brown-300 font-semibold text-sm sm:text-base tracking-wide transition-all uppercase outline-none shadow-2xs"
                            maxlength="20"
                            autocomplete="off"
                        >
                        <button 
                            type="submit" 
                            class="absolute right-1.5 top-1.5 bottom-1.5 px-5 sm:px-6 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-full font-bold text-xs sm:text-sm hover:from-brand-600 hover:to-brand-700 transition-all shadow-md shadow-brand-500/25 flex items-center justify-center gap-1.5 hover:-translate-y-0.5 cursor-pointer"
                        >
                            <span>Lacak</span>
                            <ArrowRight class="w-3.5 h-3.5 hidden sm:inline-block" />
                        </button>
                    </div>
                    <p class="text-[11px] sm:text-xs text-brown-500/80 mt-2.5 flex items-center justify-center gap-1.5">
                        <Info class="w-3.5 h-3.5 text-brand-500 shrink-0" />
                        <span>Nomor pesanan (format: <strong class="text-brown-700 font-mono">PK-XXXXXXXX</strong>) terdapat pada pesan WhatsApp saat Anda checkout.</span>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Search, Sparkles, ArrowRight, Info } from 'lucide-vue-next';

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
    const clean = orderNumber.value.trim().toUpperCase();
    if (clean) {
        emit('search', clean);
    }
};
</script>
