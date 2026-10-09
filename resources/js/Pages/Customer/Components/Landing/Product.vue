<script setup>
import { computed } from 'vue';
import { 
    Package, 
    ArrowRight, 
    CheckCircle2, 
    ShoppingBag, 
    Sparkles, 
    Droplets, 
    Clock, 
    ShieldCheck, 
    Box,
    Layers
} from 'lucide-vue-next';
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
    { 
        id: 2, 
        name: 'Snack Box 3 Kue', 
        slug: 'snack-box-3-kue', 
        capacity: 3, 
        box_price: 2500,
        occasion: 'Arisan santai, pengajian, atau coffee break',
        description: 'Paket praktis & hemat dengan 3 pilihan kue favorit yang disukai semua kalangan.' 
    },
    { 
        id: 3, 
        name: 'Snack Box 4 Kue', 
        slug: 'snack-box-4-kue', 
        capacity: 4, 
        box_price: 2500,
        occasion: 'Rapat dinas/kantor, seminar & workshop',
        description: 'Paling populer & pas! Kombinasi seimbang 2 kue manis, 1 gurih asin, dan 1 puding/roti.' 
    },
    { 
        id: 4, 
        name: 'Snack Box 5 Kue', 
        slug: 'snack-box-5-kue', 
        capacity: 5, 
        box_price: 3000,
        occasion: 'Jamuan resmi VIP, syukuran & hajatan besar',
        description: 'Paket komplit premium mengenyangkan dengan aneka varian kue untuk tamu istimewa.' 
    },
];

const availablePackages = computed(() => {
    if (!props.snackBoxPackages || props.snackBoxPackages.length === 0) {
        return defaultPackages;
    }
    // Merge backend data with occasion highlights if description doesn't have it
    return props.snackBoxPackages.map((pkg) => {
        const fallback = defaultPackages.find(d => d.capacity === pkg.capacity || d.slug === pkg.slug);
        return {
            ...pkg,
            occasion: fallback?.occasion || `Kapasitas ${pkg.capacity} macam kue pilihan`,
            box_price: pkg.box_price || fallback?.box_price || 2500
        };
    });
});

const handleAddToCart = (product) => {
    addItem(product, minSatuan.value, 'satuan');
};

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num || 0);
</script>

<template>
    <section class="py-16 md:py-24 bg-gradient-to-b from-white via-cream-50/50 to-white border-b border-cream-200/80 relative overflow-hidden">
        <!-- Soft Ambient Atmosphere -->
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-brand-100/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-32 w-96 h-96 bg-amber-100/25 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- ═══════════════════════════════════════════════════════════
                 BLOK 1: SOLUSI CUSTOM SNACK BOX (LAYANAN UTAMA)
            ════════════════════════════════════════════════════════════ -->
            <div id="snack-box-section" class="scroll-mt-24">
                
                <!-- Section Header -->
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 sm:mb-10 gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-extrabold uppercase tracking-wider mb-3 shadow-2xs">
                            <Package class="w-3.5 h-3.5 text-brand-600" />
                            <span>Layanan Utama • Konsumsi Acara & Rapat</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-brown-900 tracking-tight">
                            Rakit Custom Snack Box Sesuai Acara Anda
                        </h2>
                        <p class="text-brown-600 text-sm sm:text-base mt-2 max-w-2xl leading-relaxed">
                            Bebas pilih kombinasi aneka kue basah & gurih favorit. Dikemas rapi & higienis di hari H pengiriman, lengkap dengan air mineral cup dan tisu gratis.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <Link 
                            href="/snack-box" 
                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-brand-600 hover:text-brand-700 bg-brand-50/80 hover:bg-brand-100/80 border border-brand-200/80 px-4 py-2.5 rounded-xl transition-all shadow-2xs group"
                        >
                            <span>Semua Pilihan Box</span>
                            <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
                        </Link>
                    </div>
                </div>

                <!-- 3 Package Bento Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-stretch mb-8">
                    <div 
                        v-for="pkg in availablePackages" 
                        :key="pkg.id || pkg.slug"
                        :class="[
                            'rounded-3xl p-6 sm:p-7 flex flex-col justify-between relative transition-all duration-300 group',
                            pkg.capacity === 4 
                                ? 'bg-gradient-to-b from-white via-brand-50/20 to-cream-50/60 border-2 border-brand-500 shadow-xl shadow-brand-500/10 md:-translate-y-1' 
                                : 'bg-white border border-cream-200/90 shadow-sm hover:shadow-lg hover:border-brand-200 hover:-translate-y-0.5'
                        ]"
                    >
                        <!-- Top Highlight Badge for Best Seller (Capacity 4) -->
                        <div 
                            v-if="pkg.capacity === 4" 
                            class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-brand-600 to-amber-600 text-white text-[11px] font-extrabold uppercase tracking-wider px-3.5 py-1 rounded-full shadow-md flex items-center gap-1.5 whitespace-nowrap"
                        >
                            <Sparkles class="w-3.5 h-3.5 text-amber-200 fill-amber-200" />
                            <span>Paling Favorit • Paling Pas</span>
                        </div>

                        <div>
                            <!-- Card Header: Icon + Capacity Pill -->
                            <div class="flex items-center justify-between mb-4">
                                <div 
                                    :class="[
                                        'w-12 h-12 rounded-2xl flex items-center justify-center transition-colors',
                                        pkg.capacity === 4 
                                            ? 'bg-brand-500 text-white shadow-md shadow-brand-500/25' 
                                            : 'bg-cream-100 text-brown-800 border border-cream-200 group-hover:bg-brand-50 group-hover:text-brand-600'
                                    ]"
                                >
                                    <Package class="w-6 h-6" stroke-width="1.8" />
                                </div>

                                <div class="flex items-center gap-1 bg-cream-50 border border-cream-200/80 px-2.5 py-1 rounded-lg">
                                    <Layers class="w-3.5 h-3.5 text-brand-600" />
                                    <span class="text-xs font-extrabold text-brown-800">{{ pkg.capacity }} Kue</span>
                                </div>
                            </div>

                            <!-- Package Title & Occasion -->
                            <h3 class="text-xl sm:text-2xl font-black text-brown-900 tracking-tight mb-1 group-hover:text-brand-700 transition-colors">
                                {{ pkg.name }}
                            </h3>
                            <div class="inline-block text-[11px] font-bold text-amber-800 bg-amber-50/90 border border-amber-200/80 px-2.5 py-0.5 rounded-md mb-3">
                                {{ pkg.occasion }}
                            </div>
                            <p class="text-xs sm:text-sm text-brown-600 leading-relaxed mb-5">
                                {{ pkg.description }}
                            </p>

                            <!-- Visual Capacity Indicator (Tactile Pastry Slot Indicators) -->
                            <div class="bg-cream-50/80 border border-cream-200/80 rounded-2xl p-3 mb-5">
                                <p class="text-[11px] font-bold text-brown-700 mb-2 flex items-center justify-between">
                                    <span>Komposisi Isi Box:</span>
                                    <span class="text-brand-600 font-extrabold">{{ pkg.capacity }} Slot Pilihan</span>
                                </p>
                                <div class="grid gap-1.5" :style="{ gridTemplateColumns: `repeat(${pkg.capacity}, minmax(0, 1fr))` }">
                                    <div 
                                        v-for="i in pkg.capacity" 
                                        :key="i"
                                        class="h-7 rounded-lg bg-white border border-cream-300/80 flex items-center justify-center text-[10px] font-bold text-brown-600 shadow-2xs"
                                    >
                                        Kue {{ i }}
                                    </div>
                                </div>
                            </div>

                            <!-- Package Inclusions List -->
                            <div class="space-y-2.5 mb-6 text-xs text-brown-600 border-t border-cream-100 pt-4">
                                <div class="flex items-start gap-2">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                    <span>Kemasan dus tebal food-grade + alas renda doily</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                    <span><strong>Gratis</strong> 1 gelas air mineral cup & tisu di tiap box</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                    <span>Bebas tentukan aneka rasa manis & asin gurih</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                    <span>Minimal pemesanan mulai <strong>{{ minBox }} box</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action -->
                        <div class="pt-2 border-t border-cream-100/80">
                            <p class="text-[11px] text-brown-500 text-center mb-3">
                                Biaya kardus box <span class="font-bold text-brown-700">Rp {{ formatNumber(pkg.box_price) }}</span> (harga kue menyesuaikan)
                            </p>
                            <Link 
                                :href="`/snack-box/${pkg.slug}`"
                                :class="[
                                    'w-full py-3 sm:py-3.5 px-4 rounded-2xl font-black text-sm flex items-center justify-center gap-2 transition-all duration-300 shadow-sm cursor-pointer',
                                    pkg.capacity === 4
                                        ? 'bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-600/25 hover:-translate-y-0.5'
                                        : 'bg-cream-100 hover:bg-brand-50 text-brown-900 hover:text-brand-700 border border-cream-200/90 hover:border-brand-300 hover:-translate-y-0.5'
                                ]"
                            >
                                <span>Rakit {{ pkg.name }}</span>
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Trust / Value Proposition Strip -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 p-4 sm:p-5 rounded-3xl bg-cream-50/90 border border-cream-200/80 text-brown-800 shadow-2xs">
                    <div class="flex items-center gap-3 p-2">
                        <div class="w-9 h-9 rounded-xl bg-white border border-cream-200 flex items-center justify-center text-brand-600 shrink-0 shadow-2xs">
                            <Box class="w-4 h-4" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-brown-900">Dus Food-Grade</p>
                            <p class="text-[11px] text-brown-600">Higienis & rapi untuk tamu</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-2">
                        <div class="w-9 h-9 rounded-xl bg-white border border-cream-200 flex items-center justify-center text-brand-600 shrink-0 shadow-2xs">
                            <Droplets class="w-4 h-4" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-brown-900">Gratis Air Cup & Tisu</p>
                            <p class="text-[11px] text-brown-600">Sudah lengkap siap santap</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-2">
                        <div class="w-9 h-9 rounded-xl bg-white border border-cream-200 flex items-center justify-center text-brand-600 shrink-0 shadow-2xs">
                            <Clock class="w-4 h-4" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-brown-900">Dirakit Fresh Hari-H</p>
                            <p class="text-[11px] text-brown-600">Terjaga rasa & aromanya</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-2">
                        <div class="w-9 h-9 rounded-xl bg-white border border-cream-200 flex items-center justify-center text-brand-600 shrink-0 shadow-2xs">
                            <ShieldCheck class="w-4 h-4" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-brown-900">Garansi Tepat Waktu</p>
                            <p class="text-[11px] text-brown-600">Siap antar langsung ke venue</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ═══════════════════════════════════════════════════════════
                 DIVIDER / TRANSISI DENGAN IDENTITAS YANG JELAS
            ════════════════════════════════════════════════════════════ -->
            <div class="my-14 sm:my-18 relative flex items-center justify-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-cream-200/90"></div>
                </div>
                <div class="relative bg-white px-4 py-1.5 rounded-full border border-cream-200/80 shadow-2xs text-xs font-bold text-brown-500 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Pilihan Tambahan • Pesanan Eceran</span>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════════
                 BLOK 2: KUE SATUAN & JAJANAN PASAR (ECERAN / TANPA BOX)
            ════════════════════════════════════════════════════════════ -->
            <div id="kue-satuan-section" class="scroll-mt-24">
                
                <!-- Section Header -->
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 sm:mb-10 gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-amber-50 border border-amber-200/80 text-amber-800 text-xs font-extrabold uppercase tracking-wider mb-3 shadow-2xs">
                            <ShoppingBag class="w-3.5 h-3.5 text-amber-600" />
                            <span>Koleksi Eceran & Sajian Prasmanan</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-brown-900 tracking-tight">
                            Kue Satuan & Jajanan Pasar Terfavorit
                        </h2>
                        <p class="text-brown-600 text-sm sm:text-base mt-2 max-w-2xl leading-relaxed">
                            Pesan tanpa kardus snack box — sangat cocok untuk hidangan piring prasmanan, nampan tampah hajatan, atau camilan santai keluarga (minimal pesan <span class="font-bold text-brown-800">{{ minSatuan }} pcs</span> per macam kue).
                        </p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <Link 
                            href="/shop" 
                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-amber-800 hover:text-amber-900 bg-amber-50/80 hover:bg-amber-100/80 border border-amber-200/80 px-4 py-2.5 rounded-xl transition-all shadow-2xs group"
                        >
                            <span>Lihat Semua Katalog Kue</span>
                            <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
                        </Link>
                    </div>
                </div>

                <!-- Product Grid (2 cols on mobile, 3 cols on md/desktop) -->
                <div v-if="products.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
                    <ProductCard 
                        v-for="(product, idx) in products" 
                        :key="product.id || idx" 
                        :product="product" 
                        @add-to-cart="handleAddToCart(product)" 
                    />
                </div>

                <!-- Empty State Fallback -->
                <div v-else class="text-center py-16 bg-cream-50/60 rounded-3xl border border-cream-300/80 border-dashed mb-8">
                    <ShoppingBag class="w-10 h-10 text-brown-400 mx-auto mb-2" />
                    <p class="text-brown-700 font-bold text-base">Belum ada produk kue satuan saat ini.</p>
                    <Link href="/shop" class="mt-3 inline-block text-brand-600 font-bold hover:underline text-sm">
                        Jelajahi katalog toko &rarr;
                    </Link>
                </div>

                <!-- Bottom Catalog Callout Banner -->
                <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-cream-50 via-white to-amber-50/50 border border-cream-200/90 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-cream-100 border border-cream-200 flex items-center justify-center text-brown-800 shrink-0 shadow-2xs">
                            <ShoppingBag class="w-6 h-6 text-brand-700" />
                        </div>
                        <div>
                            <p class="text-sm sm:text-base font-extrabold text-brown-900">Mencari aneka varian kue lainnya?</p>
                            <p class="text-xs sm:text-sm text-brown-600 mt-0.5">Tersedia puluhan varian kue basah tradisional, lapis, pastel, risoles, bolu gulung & donat di etalase kami.</p>
                        </div>
                    </div>
                    <Link 
                        href="/shop" 
                        class="px-5 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm hover:shadow-md hover:shadow-brand-600/20 transition-all flex items-center gap-2 shrink-0 hover:-translate-y-0.5 cursor-pointer whitespace-nowrap"
                    >
                        <span>Jelajahi Katalog Toko (50+ Kue)</span>
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>

            </div>

        </div>
    </section>
</template>
