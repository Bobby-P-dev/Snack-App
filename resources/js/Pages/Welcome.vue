<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Pages/Customer/Components/Landing/Footer.vue';
import { useCartStore } from '@/Stores/CartStore.js';
import { useSelectedItemsStore } from '@/Stores/SelectedItemsStore.js';
import { getImageUrl } from '@/helpers.js';
import HeroSection from '@/Pages/Customer/Components/Landing/HeroSection.vue';
import OrderSteps from '@/Pages/Customer/Components/Landing/OrderSteps.vue';
import Product from '@/Pages/Customer/Components/Landing/Product.vue';
import Testimonial from '@/Pages/Customer/Components/Landing/Testimonial.vue';
import FaqSection from '@/Pages/Customer/Components/Landing/FaqSection.vue';
import WhatsAppCTA from '@/Pages/Customer/Components/Landing/WhatsAppCTA.vue';
import { X, ShoppingCart } from 'lucide-vue-next';

const props = defineProps({
    products: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    productsCount: { type: Number, default: 0 },
    carousels: { type: Array, default: () => [] },
});

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});

const { addToCart } = useCartStore();
const { items: selectedItems, count: selectedCount, addItem: addSelected, removeItem: removeSelected, clearItems: clearSelected } = useSelectedItemsStore();

const floatingWaUrl = computed(() => {
    const phone = (cms.value.contact_phone || '6281234567890').replace(/[^0-9]/g, '');
    const company = cms.value.company_name || 'Padu Kue';
    let message = cms.value.wa_consultation_message || 'Halo {company_name}, saya ingin konsultasi mengenai pemesanan snack box dan aneka kue.';
    message = message.replace(/\{company_name\}/g, company);
    return `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
});

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);
</script>

<template>
    <Head :title="`${cms.company_name || 'Padu Kue'} - Toko Kue & Custom Snack Box`" />
    <CustomerLayout>
        <!-- ─── WHATSAPP FLOATING ─── -->
        <a
            :href="floatingWaUrl"
            target="_blank" rel="noopener noreferrer"
            class="fixed bottom-20 md:bottom-6 right-4 sm:right-6 z-40 bg-emerald-600 hover:bg-emerald-700 text-white p-3.5 sm:p-4 rounded-full shadow-lg hover:shadow-2xl shadow-emerald-700/25 transition-all duration-300 flex items-center gap-2.5 group hover:-translate-y-0.5"
            aria-label="Konsultasi via WhatsApp"
        >
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" />
            </svg>
            <span class="hidden group-hover:inline-block text-sm font-bold whitespace-nowrap pr-1">Tanya via WhatsApp</span>
        </a>

        <!-- ─── HERO SECTION ─── -->
        <HeroSection :cms="cms" :carousels="carousels" />

        <!-- ─── ORDER STEPS ─── -->
        <OrderSteps />

        <!-- ─── PRODUCT SECTION ─── -->
        <Product :products="products" :cms="cms" />

        <!-- ─── TESTIMONIAL ─── -->
        <Testimonial />

        <!-- ─── FAQ ─── -->
        <FaqSection />

        <!-- ─── CTA ─── -->
        <WhatsAppCTA :cms="cms" />

        <!-- ─── PANEL PRODUK DIPILIH ─── -->
        <section v-if="selectedItems.length > 0" class="sticky bottom-0 left-0 right-0 z-40 px-4 pb-4 md:pb-6 pointer-events-none mt-4">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white/95 backdrop-blur-md border border-brand-200/90 rounded-2xl p-4 shadow-[0_8px_30px_rgb(0,0,0,0.08)] pointer-events-auto transform transition-transform duration-300">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex-1 w-full">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-bold text-brown-900 text-sm sm:text-base">{{ selectedItems.length }} produk dipilih</p>
                                <button @click="sendToCart" class="sm:hidden px-5 py-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl hover:from-brand-600 hover:to-brand-700 transition font-bold shadow-md shadow-brand-500/20 text-xs">Masuk Keranjang</button>
                            </div>
                            <div class="flex overflow-x-auto gap-2 pb-1 scrollbar-hide">
                                <div v-for="item in selectedItems" :key="item.id" class="bg-cream-50/80 rounded-xl px-3 py-1.5 flex items-center gap-2 shadow-2xs border border-cream-200 flex-shrink-0">
                                    <span class="text-xs sm:text-sm font-semibold text-brown-900 truncate max-w-[140px]">{{ item.name }}</span>
                                    <span class="text-xs text-brand-600 font-bold bg-brand-50 px-1.5 py-0.5 rounded-lg border border-brand-100">{{ item.qty }}x</span>
                                    <button @click="removeSelected(item.id)" class="text-brown-400 hover:text-red-500 transition p-0.5 rounded" title="Hapus produk">
                                        <X class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="hidden sm:block">
                            <button @click="sendToCart" class="px-7 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl hover:from-brand-600 hover:to-brand-700 transition font-bold shadow-md shadow-brand-500/20 flex items-center gap-2 whitespace-nowrap hover:-translate-y-0.5 duration-200 text-sm">
                                <ShoppingCart class="w-4 h-4" />
                                Masukkan ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div v-if="selectedItems.length > 0" class="h-32"></div>

        <!-- ─── FOOTER ─── -->
        <Footer />
    </CustomerLayout>
</template>
