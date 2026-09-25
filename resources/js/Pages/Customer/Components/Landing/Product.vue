<script setup>
import { computed } from 'vue';
import { Package, ArrowRight, CheckCircle2, ShoppingBag } from 'lucide-vue-next';
import { useSelectedItemsStore } from '@/Stores/SelectedItemsStore.js';
import { Link } from '@inertiajs/vue3';
import ProductCard from '@/Pages/Customer/Components/ProductCard.vue';

const props = defineProps({
    products: { type: Array, default: () => [] },
    snackBoxPackages: { type: Array, default: () => [] },
    cms: { type: Object, default: () => ({}) }
});

const { addItem } = useSelectedItemsStore();

const minBox = computed(() => parseInt(props.cms?.min_order_box) || 10);
const minSatuan = computed(() => parseInt(props.cms?.min_order_satuan) || 10);

const defaultPackages = [
    { id: 2, name: 'Snack Box 3 Kue', slug: 'snack-box-3-kue', capacity: 3, description: 'Paket hemat untuk arisan, pengajian, atau coffee break santai.' },
    { id: 3, name: 'Snack Box 4 Kue', slug: 'snack-box-4-kue', capacity: 4, description: 'Paling populer! Kombinasi pas 2 kue manis, 1 asin gurih, dan 1 puding/roti.' },
    { id: 4, name: 'Snack Box 5 Kue', slug: 'snack-box-5-kue', capacity: 5, description: 'Paket komplit premium untuk rapat resmi, seminar, atau syukuran.' },
];

const availablePackages = computed(() => {
    return (props.snackBoxPackages && props.snackBoxPackages.length > 0)
        ? props.snackBoxPackages
        : defaultPackages;
});

const handleAddToCart = (product) => {
    addItem(product, minSatuan.value, 'satuan');
};
</script>

<template>
    <section class="py-16 md:py-24 bg-gradient-to-b from-white via-cream-50/50 to-cream-100/60 border-b border-cream-200/80 relative overflow-hidden">
        <!-- Soft Ambient Atmosphere -->
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-brand-100/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 -left-32 w-96 h-96 bg-amber-100/25 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 sm:mb-12 gap-4 pb-5 border-b border-cream-200">
                <div>
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-widest bg-brand-50 px-3.5 py-1 rounded-full border border-brand-200/60 inline-block mb-2.5 shadow-2xs">
                        Layanan & Koleksi Favorit
                    </span>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brown-900 tracking-tight">
                        Pesan Sesuai Kebutuhan Acara Anda
                    </h2>
                    <p class="text-brown-600 text-sm sm:text-base mt-1.5 max-w-2xl leading-relaxed">
                        Rakit custom snack box higienis untuk rapat & acara, atau nikmati aneka kue satuan lezat siap saji.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Link 
                        href="/shop" 
                        class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-brand-600 hover:text-brand-700 bg-brand-50/80 hover:bg-brand-100/80 border border-brand-200/80 px-4 py-2 rounded-xl transition-all shadow-2xs group self-start md:self-auto"
                    >
                        <span>Lihat Semua Katalog</span>
                        <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
                    </Link>
                </div>
            </div>

            <!-- Dual-Showcase Bento Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">
                
                <!-- ═════════ PILAR 1: HERO CUSTOM SNACK BOX (Col 1-5 di Desktop) ═════════ -->
                <div class="lg:col-span-5 flex flex-col justify-between bg-gradient-to-br from-white via-cream-50/95 to-brand-50/45 rounded-3xl p-6 sm:p-8 border border-cream-200/90 shadow-sm relative overflow-hidden group">
                    <!-- Subtle Warm Internal Glow -->
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-brand-100/35 blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-10 -top-10 w-40 h-40 rounded-full bg-amber-100/30 blur-2xl pointer-events-none"></div>

                    <div class="relative z-10">
                        <!-- Top Pill & Title -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-100/70 border border-brand-200/80 text-brand-700 text-xs font-bold uppercase tracking-wider mb-4 shadow-2xs">
                            <Package class="w-3.5 h-3.5 text-brand-600" />
                            <span>Solusi Rapat & Acara</span>
                        </div>

                        <h3 class="text-xl sm:text-2xl font-black text-brown-900 tracking-tight mb-2">
                            Rakit Custom Snack Box
                        </h3>
                        <p class="text-xs sm:text-sm text-brown-600 leading-relaxed mb-6">
                            Bebas pilih kombinasi 3 hingga 5 aneka kue basah & jajanan pasar premium. Dirakit higienis di hari H pengiriman.
                        </p>

                        <!-- Package Dockets List -->
                        <div class="space-y-3 mb-6">
                            <Link 
                                v-for="pkg in availablePackages" 
                                :key="pkg.id || pkg.slug"
                                :href="`/snack-box/${pkg.slug}`"
                                class="flex items-center justify-between p-3.5 sm:p-4 rounded-2xl bg-white/90 hover:bg-white border border-cream-200/90 hover:border-brand-300 shadow-2xs hover:shadow-xs transition-all duration-200 group/pkg"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-cream-50 border border-cream-200/80 flex items-center justify-center text-brown-800 shrink-0 group-hover/pkg:bg-brand-50 group-hover/pkg:text-brand-600 transition-colors">
                                        <Package class="w-5 h-5" stroke-width="1.8" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-brown-900 text-sm group-hover/pkg:text-brand-700 transition-colors truncate">
                                                {{ pkg.name }}
                                            </p>
                                            <span v-if="pkg.capacity === 4" class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-full shrink-0">
                                                Terfavorit
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-brown-500 truncate mt-0.5">
                                            {{ pkg.description || `Kapasitas ${pkg.capacity} kue pilihan` }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-brand-600 shrink-0 ml-2 group-hover/pkg:translate-x-0.5 transition-transform flex items-center gap-1">
                                    <span>Pilih</span>
                                    <ArrowRight class="w-3.5 h-3.5" />
                                </span>
                            </Link>
                        </div>

                        <!-- Value Perks Checklist -->
                        <div class="pt-4 border-t border-cream-200/80 space-y-2 mb-6 text-xs text-brown-600">
                            <div class="flex items-center gap-2">
                                <CheckCircle2 class="w-4 h-4 text-brand-600 shrink-0" />
                                <span>Termasuk kemasan box food-grade, alas doily & tisu</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <CheckCircle2 class="w-4 h-4 text-brand-600 shrink-0" />
                                <span>Gratis air mineral cup per box</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <CheckCircle2 class="w-4 h-4 text-brand-600 shrink-0" />
                                <span>Minimal order hanya {{ minBox }} box untuk seluruh varian</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Button -->
                    <div class="relative z-10 pt-2">
                        <Link 
                            href="/snack-box" 
                            class="w-full py-3.5 px-6 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm sm:text-base flex items-center justify-center gap-2 shadow-md shadow-brand-600/20 transition-all duration-300 hover:-translate-y-0.5 cursor-pointer"
                        >
                            <span>Buka Custom Box Builder</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>

                <!-- ═════════ PILAR 2: KUE SATUAN TERLARIS (Col 6-12 di Desktop) ═════════ -->
                <div class="lg:col-span-7 flex flex-col justify-between">
                    <div>
                        <!-- Column Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                                    <h3 class="text-lg sm:text-xl font-black text-brown-900 tracking-tight">
                                        Kue Satuan Terfavorit
                                    </h3>
                                </div>
                                <p class="text-xs sm:text-sm text-brown-600">
                                    Dibuat fresh setiap hari dengan bahan pilihan (min. {{ minSatuan }} pcs per macam kue).
                                </p>
                            </div>
                        </div>

                        <!-- Product Grid (2 cols on mobile/tablet, up to 3 on desktop) -->
                        <div v-if="products.length > 0" class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-3.5 sm:gap-4.5">
                            <ProductCard 
                                v-for="(product, idx) in products" 
                                :key="product.id || idx" 
                                :product="product" 
                                @add-to-cart="handleAddToCart(product)" 
                            />
                        </div>

                        <!-- Empty State Fallback -->
                        <div v-else class="text-center py-16 bg-cream-50/60 rounded-3xl border border-cream-300/80 border-dashed">
                            <p class="text-brown-600 font-semibold text-base">Belum ada produk kue satuan saat ini.</p>
                            <Link href="/shop" class="mt-3 inline-block text-brand-600 font-bold hover:underline text-sm">
                                Jelajahi katalog toko &rarr;
                            </Link>
                        </div>
                    </div>

                    <!-- Bottom Catalog Banner -->
                    <div class="mt-6 p-4 rounded-2xl bg-white border border-cream-200/90 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-cream-50 border border-cream-200/80 flex items-center justify-center text-brown-800 shrink-0">
                                <ShoppingBag class="w-4 h-4 text-brown-800" />
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm font-bold text-brown-900">Ingin pilihan aneka kue lainnya?</p>
                                <p class="text-[11px] sm:text-xs text-brown-500">Tersedia beragam kue basah tradisional, donat & pastry di katalog.</p>
                            </div>
                        </div>
                        <Link 
                            href="/shop" 
                            class="px-4 py-2 bg-cream-100 hover:bg-cream-200/70 text-brown-800 font-bold text-xs rounded-xl border border-cream-300/80 transition-all flex items-center gap-1.5 shrink-0"
                        >
                            <span>Buka Katalog Toko</span>
                            <ArrowRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>
                </div>

            </div>

        </div>
    </section>
</template>
