<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="modalStore.state.isOpen && product"
                class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto"
                aria-labelledby="modal-title"
                role="dialog"
                aria-modal="true"
            >
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"
                    @click="close"
                ></div>

                <!-- Modal Dialog Box -->
                <Transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                >
                    <div
                        class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden border border-cream-200/80 z-10 my-auto max-h-[92vh] flex flex-col"
                    >
                        <!-- Close Button -->
                        <button
                            @click="close"
                            class="absolute top-3 right-3 z-20 w-9 h-9 rounded-full bg-white/85 backdrop-blur-md text-brown-800 hover:bg-white hover:text-brand-600 transition flex items-center justify-center shadow-md cursor-pointer active:scale-95"
                            aria-label="Tutup"
                        >
                            <X class="w-5 h-5" />
                        </button>

                        <!-- Scrollable Content -->
                        <div class="overflow-y-auto flex-1 overscroll-contain">
                            <!-- Product Image Container -->
                            <div class="w-full aspect-[16/10] bg-cream-100 relative overflow-hidden flex items-center justify-center">
                                <img
                                    v-if="product.image_url"
                                    :src="getImageUrl(product.image_url)"
                                    :alt="product.name"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="text-center p-6 text-cream-400">
                                    <Image class="w-16 h-16 mx-auto mb-2 text-cream-300 stroke-1" />
                                    <span class="text-xs text-brown-400 font-medium">Foto produk segera hadir</span>
                                </div>

                                <!-- Category Badge -->
                                <div class="absolute bottom-3 left-3 flex gap-2">
                                    <span v-if="product.category" class="bg-white/95 backdrop-blur-md text-brand-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                        {{ product.category.name }}
                                    </span>
                                    <span v-if="product.badge" class="bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                        {{ product.badge }}
                                    </span>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4 sm:p-6 space-y-4">
                                <div>
                                    <div class="flex justify-between items-start gap-2 mb-1">
                                        <h2 id="modal-title" class="text-xl sm:text-2xl font-black text-brown-900 leading-snug">
                                            {{ product.name }}
                                        </h2>
                                    </div>
                                    <p class="text-xs sm:text-sm text-brown-600 leading-relaxed mt-1">
                                        {{ product.description || 'Pilihan jajanan tradisional dan kue lezat dengan bahan bermutu tinggi, cocok untuk snack box, coffee break kantor, arisan, maupun sajian pesta.' }}
                                    </p>
                                </div>

                                <!-- Highlights / Key Features -->
                                <div class="grid grid-cols-2 gap-2 text-[11px] sm:text-xs text-brown-700 bg-cream-50/80 p-3 rounded-2xl border border-cream-200/70">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-[10px]">✓</span>
                                        <span class="font-medium">Fresh Hari H</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-[10px]">✓</span>
                                        <span class="font-medium">100% Halal & Higienis</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-[10px]">✓</span>
                                        <span class="font-medium">Bisa Gabung Box</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-[10px]">✓</span>
                                        <span class="font-medium">Bahan Premium</span>
                                    </div>
                                </div>

                                <!-- Pricing & Min Order Rules -->
                                <div class="bg-cream-50/90 border border-cream-200/90 rounded-2xl p-4">
                                    <div class="flex items-baseline justify-between mb-1.5">
                                        <span class="text-xs text-brown-600 font-semibold uppercase tracking-wider">Harga Satuan</span>
                                        <span class="text-xl sm:text-2xl font-black text-brand-600 font-mono">
                                            Rp {{ formatNumber(product.sell_price) }} <span class="text-xs font-normal text-brown-500">/ pcs</span>
                                        </span>
                                    </div>

                                    <!-- Minimum Order Note -->
                                    <div class="flex items-center gap-1.5 text-xs text-brown-600 bg-cream-100/80 px-3 py-1.5 rounded-xl mt-2 font-medium">
                                        <Info class="w-4 h-4 text-brown-400 flex-shrink-0" />
                                        <span>Minimal pemesanan: <strong class="font-bold text-brown-800">{{ minQty }} pcs</strong></span>
                                    </div>
                                </div>

                                <!-- Quantity Selector & Quick Add -->
                                <div class="space-y-3 pt-1">
                                    <div class="flex items-center justify-between">
                                        <label class="text-xs sm:text-sm font-bold text-brown-800">
                                            Tentukan Jumlah Pesanan:
                                        </label>
                                        <!-- Stepper -->
                                        <div class="flex items-center bg-cream-100 rounded-xl p-1 border border-cream-300">
                                            <button
                                                type="button"
                                                @click="decrement"
                                                :disabled="qty <= minQty"
                                                :class="[
                                                    'w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg transition font-bold text-base select-none',
                                                    qty <= minQty
                                                        ? 'opacity-40 text-brown-400 cursor-not-allowed'
                                                        : 'hover:bg-cream-200 text-brown-800 active:scale-95 cursor-pointer'
                                                ]"
                                                aria-label="Kurangi jumlah"
                                            >
                                                -
                                            </button>

                                            <input
                                                v-model.number="qty"
                                                type="number"
                                                :min="minQty"
                                                @blur="normalizeQty"
                                                class="w-12 sm:w-14 text-center font-black text-brown-900 bg-transparent border-none focus:outline-none text-sm sm:text-base font-mono"
                                            />

                                            <button
                                                type="button"
                                                @click="increment"
                                                class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg hover:bg-cream-200 text-brown-800 transition font-bold text-base select-none active:scale-95 cursor-pointer"
                                                aria-label="Tambah jumlah"
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Quick Addition Pills -->
                                    <div class="flex items-center gap-1.5 sm:gap-2">
                                        <span class="text-[11px] text-brown-500 font-medium mr-1">Cepat:</span>
                                        <button
                                            v-for="step in [5, 10, 20, 50]"
                                            :key="step"
                                            type="button"
                                            @click="qty += step"
                                            class="px-2.5 py-1 text-xs font-bold rounded-lg bg-cream-100 hover:bg-brand-50 hover:text-brand-700 text-brown-700 border border-cream-200 transition active:scale-95 cursor-pointer"
                                        >
                                            +{{ step }}
                                        </button>
                                        <button
                                            v-if="qty > minQty"
                                            type="button"
                                            @click="qty = minQty"
                                            class="text-[11px] text-brown-500 hover:text-red-600 underline ml-auto transition cursor-pointer"
                                        >
                                            Reset ({{ minQty }})
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Sticky Footer Actions -->
                        <div class="p-4 sm:p-5 bg-cream-50/90 border-t border-cream-200 flex items-center justify-between gap-3">
                            <div class="flex flex-col">
                                <span class="text-[11px] text-brown-500 font-medium">Subtotal ({{ qty }} pcs)</span>
                                <span class="text-lg sm:text-xl font-black text-brand-600 font-mono leading-tight">
                                    Rp {{ formatNumber(product.sell_price * qty) }}
                                </span>
                            </div>

                            <button
                                type="button"
                                @click="addToCart"
                                class="flex-1 max-w-[260px] sm:max-w-[280px] bg-brand-500 hover:bg-brand-600 active:scale-[0.98] text-white py-3 sm:py-3.5 px-4 rounded-2xl font-bold flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition text-xs sm:text-sm cursor-pointer"
                            >
                                <ShoppingCart class="w-4 h-4 sm:w-5 sm:h-5" />
                                <span>Tambah ke Keranjang</span>
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useProductModalStore } from '@/Stores/ProductModalStore.js';
import { useCartStore } from '@/Stores/CartStore.js';
import { getImageUrl } from '@/helpers.js';
import { X, Image, Info, ShoppingCart } from 'lucide-vue-next';

const modalStore = useProductModalStore();
const cartStore = useCartStore();

const product = computed(() => modalStore.state.product);
const minQty = 10;
const qty = ref(minQty);

// Reset quantity to minimum when a new product is loaded in modal
watch(
    () => modalStore.state.product,
    (newProduct) => {
        if (newProduct) {
            qty.value = minQty;
        }
    }
);

const increment = () => {
    qty.value += 1;
};

const decrement = () => {
    if (qty.value > minQty) {
        qty.value -= 1;
    }
};

const normalizeQty = () => {
    if (!qty.value || qty.value < minQty) {
        qty.value = minQty;
    }
};

const close = () => {
    modalStore.closeModal();
};

const addToCart = () => {
    normalizeQty();
    if (!product.value) return;

    cartStore.addToCart(
        {
            ...product.value,
            qty: qty.value,
        },
        'satuan'
    );

    close();
};

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num || 0);
</script>
