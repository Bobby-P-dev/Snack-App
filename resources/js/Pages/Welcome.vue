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

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);
</script>

<template>
    <Head title="Snack Box - Custom Snack Boxes" />
    <CustomerLayout>
        <!-- ─── WHATSAPP FLOATING ─── -->
        <a
            :href="`https://wa.me/${cms.contact_phone}?text=Halo%20${cms.company_name}%2C%20saya%20ingin%20bertanya%20tentang%20produk%20Anda`"
            target="_blank" rel="noopener noreferrer"
            class="fixed bottom-6 right-6 z-50 bg-green-600 hover:bg-green-700 text-white p-4 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center gap-3 group"
        >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" />
            </svg>
            <span class="hidden group-hover:inline-block text-sm font-semibold whitespace-nowrap">Tanya via WhatsApp</span>
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
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] pointer-events-auto transform transition-transform duration-300">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex-1 w-full">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-bold text-blue-900">{{ selectedItems.length }} produk dipilih</p>
                                <button @click="sendToCart" class="sm:hidden px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-md text-sm">Masuk Keranjang</button>
                            </div>
                            <div class="flex overflow-x-auto gap-2 pb-1 scrollbar-hide">
                                <div v-for="item in selectedItems" :key="item.id" class="bg-white rounded-lg px-3 py-1.5 flex items-center gap-2 shadow-sm border border-blue-100 flex-shrink-0">
                                    <span class="text-sm font-medium text-gray-900 truncate max-w-[120px]">{{ item.name }}</span>
                                    <span class="text-xs text-blue-600 font-bold bg-blue-50 px-1.5 py-0.5 rounded">{{ item.qty }}x</span>
                                    <button @click="removeSelected(item.id)" class="text-red-400 hover:text-red-600 transition p-0.5 hover:bg-red-50 rounded">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="hidden sm:block">
                            <button @click="sendToCart" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold shadow-lg flex items-center gap-2 whitespace-nowrap hover:-translate-y-0.5 duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
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
