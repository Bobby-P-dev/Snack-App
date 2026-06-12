<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Footer from '@/Components/Domain/Footer.vue';
import { useCartStore } from '@/Stores/CartStore.js';

const props = defineProps({
    products: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    productsCount: { type: Number, default: 0 },
    carousels: { type: Array, default: () => [] },
});

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});

const { addToCart } = useCartStore();

// ─── Hero Carousel ───
// Fallback if no carousels in DB
const fallbackImages = [
    { src: 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=1200&q=80', alt: 'Koleksi Snack Box Premium' }
];

const images = computed(() => {
    if (props.carousels && props.carousels.length > 0) {
        return props.carousels.map(c => ({
            src: c.image_url.startsWith('http') ? c.image_url : `/storage/${c.image_url}`,
            alt: c.title,
            link: c.link_url
        }));
    }
    return fallbackImages;
});

const currentSlide = ref(0);
const prevSlide = () => { currentSlide.value = (currentSlide.value - 1 + images.value.length) % images.value.length; };
const nextSlide = () => { currentSlide.value = (currentSlide.value + 1) % images.value.length; };

// ─── Tipe Produk ───
const activeType = ref('box');

const boxProducts = computed(() => props.products.filter((p, i) => i % 2 === 0).slice(0, 10));
const satuanProducts = computed(() => props.products.filter((p, i) => i % 2 !== 0).slice(0, 10));
const displayedProducts = computed(() => activeType.value === 'box' ? boxProducts.value : satuanProducts.value);

// Get dynamic rules from CMS
const minBox = computed(() => parseInt(cms.value.min_order_box) || 10);
const minSatuan = computed(() => parseInt(cms.value.min_order_satuan) || 10);
const currentMinQty = computed(() => activeType.value === 'box' ? minBox.value : minSatuan.value);

// ─── Qty per produk ───
const quantities = ref({});
const getQty = (product) => quantities.value[product.id] || currentMinQty.value;

const incQty = (product) => {
    const current = quantities.value[product.id] || currentMinQty.value;
    quantities.value[product.id] = current + 1;
};

const decQty = (product) => {
    const current = quantities.value[product.id] || currentMinQty.value;
    if (current > currentMinQty.value) {
        quantities.value[product.id] = current - 1;
    }
};

// ─── Selected (yang sudah ditambah ke keranjang) ───
const selectedItems = ref([]);

const addSelected = (product) => {
    const qty = quantities.value[product.id] || currentMinQty.value;
    const existing = selectedItems.value.find(i => i.id === product.id);
    if (existing) {
        existing.qty += qty;
    } else {
        selectedItems.value.push({
            id: product.id,
            name: product.name,
            price: product.sell_price,
            qty,
            type: activeType.value === 'box' ? 'snack_box' : 'satuan',
        });
    }
    quantities.value[product.id] = currentMinQty.value;
};

const removeSelected = (id) => {
    selectedItems.value = selectedItems.value.filter(i => i.id !== id);
};

const sendToCart = () => {
    if (selectedItems.value.length === 0) {
        alert('Pilih minimal 1 produk');
        return;
    }
    selectedItems.value.forEach(item => {
        addToCart(item, item.type);
    });
    selectedItems.value = [];
};

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

        <!-- ─── HERO CAROUSEL ─── -->
        <section class="relative bg-gray-900 overflow-hidden h-[400px] sm:h-[450px] md:h-[550px]">
            <div v-for="(img, idx) in images" :key="idx" class="absolute inset-0 transition-opacity duration-700" :class="idx === currentSlide ? 'opacity-100' : 'opacity-0'">
                <img :src="img.src" :alt="img.alt" class="w-full h-full object-cover opacity-60" />
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
            </div>
            <div class="relative z-10 h-full flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl">
                        <p class="text-blue-400 font-semibold tracking-widest text-sm md:text-base uppercase mb-4">{{ cms.company_name }}</p>
                        <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold text-white mb-4 leading-tight">
                            {{ cms.hero_title_1 }}<br /><span class="text-blue-400">{{ cms.hero_title_2 }}</span>
                        </h1>
                        <p class="text-base md:text-xl text-gray-200 mb-8 max-w-lg">
                            {{ cms.hero_subtitle }}
                        </p>
                        <!-- Jika ada CTA dari Carousel -->
                        <a v-if="images[currentSlide]?.link" :href="images[currentSlide].link" class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                            Lihat Promo
                        </a>
                    </div>
                </div>
            </div>
            <button @click="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white p-3 rounded-full transition backdrop-blur-sm z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <button @click="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white p-3 rounded-full transition backdrop-blur-sm z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-3">
                <button v-for="(_, idx) in images" :key="idx" @click="currentSlide = idx" class="h-2 rounded-full transition-all duration-300" :class="idx === currentSlide ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"></button>
            </div>
        </section>

        <!-- ─── TAB ─── -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
            <div class="bg-white rounded-2xl shadow-xl p-2 md:p-3">
                <div class="flex gap-2">
                    <button @click="activeType = 'box'" class="flex-1 py-3 md:py-4 rounded-xl font-bold transition text-sm md:text-base" :class="activeType === 'box' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">Snack Box</button>
                    <button @click="activeType = 'satuan'" class="flex-1 py-3 md:py-4 rounded-xl font-bold transition text-sm md:text-base" :class="activeType === 'satuan' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">Kue Satuan</button>
                </div>
            </div>
        </section>

        <!-- ─── KETERANGAN ─── -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
            <p v-if="activeType === 'box'" class="text-lg md:text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent mb-2">Snack Box Custom</p>
            <p v-else class="text-lg md:text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-2">Kue Satuan</p>

            <p v-if="activeType === 'box'" class="text-gray-700 text-base md:text-lg font-semibold leading-relaxed">Diisi dengan aneka kue pilihan Anda. Minimal pemesanan <span class="text-blue-600 font-bold">10 box</span>.</p>
            <p v-else class="text-gray-700 text-base md:text-lg font-semibold leading-relaxed">Pilih kue favorit Anda. Minimal pemesanan <span class="text-green-600 font-bold">10 pcs per jenis</span>.</p>
        </section>

        <!-- ─── DAFTAR KUE ─── -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div v-if="displayedProducts.length > 0" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                <div v-for="(product, idx) in displayedProducts" :key="'p-' + idx" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-28 sm:h-32 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-sm md:text-base line-clamp-2 mb-2">{{ product.name }}</h3>
                        <p class="text-lg md:text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent mb-3">Rp {{ formatNumber(product.sell_price) }}</p>
                        <div class="flex items-center gap-2 mb-3">
                            <button @click="decQty(product)" class="w-9 h-9 bg-gray-100 rounded-lg hover:bg-blue-100 transition font-bold text-lg flex items-center justify-center flex-shrink-0 text-gray-700">−</button>
                            <input :value="getQty(product)" @input="(e) => { const v = parseInt(e.target.value); if (v >= 10) quantities[product.id] = v; }" type="number" class="flex-1 min-w-0 text-center font-bold border border-gray-300 rounded-lg py-1.5 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" min="10" />
                            <button @click="incQty(product)" class="w-9 h-9 bg-gray-100 rounded-lg hover:bg-blue-100 transition font-bold text-lg flex items-center justify-center flex-shrink-0 text-gray-700">+</button>
                        </div>
                        <button @click="addSelected(product)" class="w-full py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-md hover:shadow-lg">Tambah</button>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-16"><p class="text-gray-500">Belum ada produk untuk tipe ini</p></div>
        </section>

        <!-- ─── PANEL PRODUK DIPILIH ─── -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
            <div v-if="selectedItems.length > 0" class="bg-blue-50 border border-blue-200 rounded-2xl p-4 md:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                    <div>
                        <p class="font-bold text-blue-900">{{ selectedItems.length }} produk dipilih</p>
                        <p class="text-sm text-blue-700">Klik tombol untuk memasukkan ke keranjang</p>
                    </div>
                    <button @click="sendToCart" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold shadow-lg w-full sm:w-auto">Masukkan ke Keranjang</button>
                </div>
                <div class="flex flex-wrap gap-2">
                    <div v-for="item in selectedItems" :key="item.id" class="bg-white rounded-lg px-3 py-2 flex items-center gap-2 shadow-sm">
                        <span class="text-sm font-medium text-gray-900">{{ item.name }}</span>
                        <span class="text-xs text-blue-600 font-semibold">{{ item.qty }}x</span>
                        <button @click="removeSelected(item.id)" class="text-red-500 hover:text-red-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ─── LIHAT SEMUA ─── -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 text-center">
            <Link href="/shop" class="inline-flex items-center gap-2 px-8 py-3 border-2 border-blue-600 text-blue-600 rounded-xl hover:bg-blue-50 transition font-semibold">
                Lihat Semua Produk
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </Link>
        </section>

        <!-- ─── KEUNGGULAN ─── -->
        <section class="bg-white py-12 md:py-16 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-8">
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4"><svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></div>
                        <h3 class="font-bold text-gray-900 mb-2">Cepat & Mudah</h3>
                        <p class="text-gray-500 text-sm">Pesan langsung via WhatsApp tanpa ribet daftar</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4"><svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        <h3 class="font-bold text-gray-900 mb-2">Kualitas Terjamin</h3>
                        <p class="text-gray-500 text-sm">Supplier pilihan dengan standar terbaik</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4"><svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        <h3 class="font-bold text-gray-900 mb-2">DP {{ cms.dp_percentage || 70 }}%</h3>
                        <p class="text-gray-500 text-sm">Sisa pelunasan saat pengambilan</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ─── CTA ─── -->
        <section class="bg-gradient-to-r from-blue-600 to-blue-700 py-12 md:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl md:text-4xl font-bold text-white mb-4">Ada yang Ingin Ditanyakan?</h2>
                <p class="text-blue-100 text-base md:text-lg mb-8 max-w-lg mx-auto">Konsultasi gratis dengan tim kami. Kami akan membantu Anda memilih snack box yang tepat.</p>
                <a :href="`https://wa.me/${cms.contact_phone}?text=Halo%20${cms.company_name}%2C%20saya%20ingin%20konsultasi%20tentang%20pemesanan`" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-blue-600 rounded-xl hover:bg-gray-100 transition font-bold shadow-xl">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" /></svg>
                    Konsultasi via WhatsApp
                </a>
            </div>
        </section>

        <!-- ─── FOOTER ─── -->
        <Footer />
    </CustomerLayout>
</template>
