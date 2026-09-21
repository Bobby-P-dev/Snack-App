<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Leaf, PackageOpen, Truck, ArrowRight, Award } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';
import { getImageUrl } from '@/helpers.js';

const props = defineProps({
    cms: { type: Object, required: true },
    carousels: { type: Array, default: () => [] },
});

const fallbackImages = [
    {
        src: 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=1200&q=80',
        alt: 'Koleksi Snack Box Premium',
        title: 'Custom Snack Box Eksklusif',
        description: 'Pilihan aneka kue manis & gurih dalam kemasan box higienis, pas untuk rapat & seminar.',
        link: '/snack-box',
        duration: 5,
    },
    {
        src: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1200&q=80',
        alt: 'Aneka Jajanan Pasar Tradisional',
        title: 'Jajanan Pasar Tradisional',
        description: 'Resep otentik warisan Nusantara, dipanggang dan dikukus segar setiap pagi.',
        link: '/shop',
        duration: 5,
    },
    {
        src: 'https://images.unsplash.com/photo-1605807646983-377bc5a76493?w=1200&q=80',
        alt: 'Pastry & Donat Lembut',
        title: 'Pastry & Donat Lembut',
        description: 'Tekstur empuk berpadu glaze istimewa, teman sempurna coffee break kantor.',
        link: '/shop',
        duration: 5,
    },
];

const images = computed(() => {
    if (props.carousels && props.carousels.length > 0) {
        return props.carousels.map(c => ({
            src: getImageUrl(c.image_url),
            alt: c.title || 'Padu Kue Showcase',
            title: c.title || 'Koleksi Spesial',
            description: c.description || 'Pilihan lezat berkualitas untuk melengkapi setiap momen Anda.',
            link: c.link_url || '/shop',
            duration: c.duration || 5,
        }));
    }
    return fallbackImages;
});

const currentSlide = ref(0);
let autoSlideTimer = null;

// Touch Swipe Handling for Mobile
const touchStartX = ref(0);
const touchStartY = ref(0);
const isSwiped = ref(false);

const onTouchStart = (e) => {
    touchStartX.value = e.touches[0].clientX;
    touchStartY.value = e.touches[0].clientY;
    isSwiped.value = false;
    stopAutoSlide();
};

const onTouchEnd = (e) => {
    const touchEndX = e.changedTouches[0].clientX;
    const touchEndY = e.changedTouches[0].clientY;
    const diffX = touchEndX - touchStartX.value;
    const diffY = touchEndY - touchStartY.value;

    // Detect horizontal swipe with minimum threshold
    if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY)) {
        isSwiped.value = true;
        if (diffX < 0) {
            nextSlide();
        } else {
            prevSlide();
        }
    }
    startAutoSlide();
};

const handleSlideClick = (link) => {
    if (isSwiped.value) {
        isSwiped.value = false;
        return;
    }
    if (link) {
        router.visit(link);
    }
};

const nextSlide = () => {
    if (images.value.length <= 1) return;
    currentSlide.value = (currentSlide.value + 1) % images.value.length;
};

const prevSlide = () => {
    if (images.value.length <= 1) return;
    currentSlide.value = (currentSlide.value - 1 + images.value.length) % images.value.length;
};

const goToSlide = (idx) => {
    currentSlide.value = idx;
};

const startAutoSlide = () => {
    stopAutoSlide();
    if (images.value.length <= 1) return;
    const duration = (images.value[currentSlide.value]?.duration || 5) * 1000;
    autoSlideTimer = setInterval(() => {
        nextSlide();
    }, duration);
};

const stopAutoSlide = () => {
    if (autoSlideTimer) {
        clearInterval(autoSlideTimer);
        autoSlideTimer = null;
    }
};

watch(currentSlide, () => {
    startAutoSlide();
});

onMounted(() => {
    startAutoSlide();
});

onUnmounted(() => {
    stopAutoSlide();
});
</script>

<template>
    <section class="relative bg-gradient-to-b from-cream-50 via-cream-100/70 to-cream-200/50 overflow-hidden border-b border-cream-200/80">
        <!-- Background Ambient Warm Glow -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-brand-200/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:block min-h-[500px] lg:min-h-[580px] xl:min-h-[660px]">
            
            <!-- ─── KIRI: KONTEN TEKS & AKSI ─── -->
            <div class="w-full lg:w-[50%] flex items-center pt-8 pb-10 lg:py-16 xl:py-20 px-4 sm:px-6 lg:pl-10 lg:pr-6 xl:pl-16 xl:pr-10 2xl:pl-24 z-10 relative lg:min-h-[580px] xl:min-h-[660px]">
                <div class="max-w-xl w-full mx-auto lg:mx-0">
                    
                    <!-- Artisan Craft Seal Badge -->
                    <div class="inline-flex items-center gap-2 self-start px-3.5 py-1.5 bg-amber-100/80 text-amber-950 font-bold tracking-wide text-xs rounded-full border border-amber-300/80 shadow-2xs mb-4">
                        <Award class="w-3.5 h-3.5 text-brand-600 shrink-0" />
                        <span>{{ cms.badge || 'Dapur Mitra Terkurasi • Resep Warisan Otentik' }}</span>
                    </div>

                    <!-- Editorial Headline -->
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[2.75rem] xl:text-[3.25rem] font-black text-brown-900 mb-4 leading-[1.15] tracking-tight">
                        {{ cms.hero_title_1 || 'Kelezatan Autentik untuk Setiap Momen Spesial' }}
                    </h1>

                    <!-- Story Subtitle -->
                    <p class="text-sm sm:text-base md:text-lg text-brown-700/90 mb-6 sm:mb-8 max-w-xl leading-relaxed font-normal">
                        {{ cms.hero_subtitle || 'Rakit paket snack box impian Anda dengan puluhan varian kue lezat berkualitas. Praktis, higienis, dan pas untuk meeting kantor, seminar, arisan, hingga syukuran.' }}
                    </p>

                    <!-- Mobile Pure Carousel (Direct image showcase, no white card wrapper behind image) -->
                    <div class="block lg:hidden my-3 mb-6">
                        <div
                            class="relative w-full aspect-[16/10] sm:aspect-[4/3] rounded-2xl overflow-hidden shadow-lg shadow-amber-950/10 bg-cream-100 cursor-pointer select-none"
                            @touchstart="onTouchStart"
                            @touchend="onTouchEnd"
                        >
                            <div
                                v-for="(img, idx) in images"
                                :key="idx"
                                class="absolute inset-0 transition-opacity duration-700 ease-out"
                                :class="idx === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
                                @click="handleSlideClick(img.link)"
                            >
                                <img
                                    :src="img.src"
                                    :alt="img.alt"
                                    class="w-full h-full object-cover select-none"
                                    loading="eager"
                                />
                            </div>

                            <!-- Minimalist Floating Indicator Capsule (Mobile) -->
                            <div
                                v-if="images.length > 1"
                                class="absolute bottom-2.5 inset-x-0 z-20 flex justify-center items-center pointer-events-none"
                            >
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/35 backdrop-blur-md border border-white/20 shadow-sm pointer-events-auto">
                                    <button
                                        v-for="(_, idx) in images"
                                        :key="idx"
                                        type="button"
                                        @click.stop="goToSlide(idx)"
                                        class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                                        :class="idx === currentSlide ? 'w-5 bg-white shadow-xs' : 'w-1.5 bg-white/50 hover:bg-white/80'"
                                        :aria-label="`Slide ${idx + 1}`"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-8">
                        <Link 
                            href="/snack-box" 
                            class="inline-flex items-center justify-center gap-2 px-8 py-3.5 md:py-4 text-base font-bold text-white bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 rounded-xl shadow-md shadow-brand-500/25 active:scale-[0.98] transition-all duration-200 group text-center"
                        >
                            <span>Rakit Snack Box Anda</span>
                            <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
                        </Link>
                        <Link 
                            href="/shop" 
                            class="inline-flex items-center justify-center px-8 py-3.5 md:py-4 text-base font-bold text-brown-800 bg-white hover:bg-cream-50 border-2 border-cream-300 hover:border-brand-300 rounded-xl transition-all duration-200 active:scale-[0.98] shadow-2xs text-center"
                        >
                            Lihat Katalog Menu
                        </Link>
                    </div>

                    <!-- Integrated Bento Micro-Trust Bar -->
                    <div class="pt-5 border-t border-cream-300/80">
                        <div class="grid grid-cols-3 gap-2 sm:gap-3 text-center sm:text-left">
                            <div class="flex flex-col sm:flex-row items-center gap-2 p-2 sm:p-2.5 rounded-xl bg-white/60 border border-cream-200">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                                    <Leaf class="w-4 h-4" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-brown-900 leading-tight">{{ cms.propotition1 || 'Bahan Segar' }}</p>
                                    <p class="hidden sm:block text-[10px] text-brown-500">Tanpa Pengawet</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-2 p-2 sm:p-2.5 rounded-xl bg-white/60 border border-cream-200">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                                    <PackageOpen class="w-4 h-4" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-brown-900 leading-tight">{{ cms.propotition2 || 'Custom Box' }}</p>
                                    <p class="hidden sm:block text-[10px] text-brown-500">Bebas Pilih Kue</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-2 p-2 sm:p-2.5 rounded-xl bg-white/60 border border-cream-200">
                                <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                                    <Truck class="w-4 h-4" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-brown-900 leading-tight">{{ cms.propotition3 || 'Tepat Waktu' }}</p>
                                    <p class="hidden sm:block text-[10px] text-brown-500">Siap Acara Anda</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ─── KANAN: CAROUSEL GAMBAR CURVED BLEED LAYOUT (DESKTOP - DESAIN AWAL) ─── -->
            <div
                class="hidden lg:block lg:absolute lg:inset-y-0 lg:right-0 lg:w-[52%] xl:w-[55%] h-full z-0 cursor-pointer select-none"
                @mouseenter="stopAutoSlide"
                @mouseleave="startAutoSlide"
            >
                <!-- Clip path for the curved bleed effect on Desktop -->
                <div class="absolute inset-0 w-full h-full lg:[clip-path:ellipse(100%_120%_at_100%_50%)] overflow-hidden bg-cream-100 group">
                    <div
                        v-for="(img, idx) in images"
                        :key="idx"
                        class="absolute inset-0 transition-opacity duration-700 ease-out"
                        :class="idx === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
                        @click="handleSlideClick(img.link)"
                    >
                        <img
                            :src="img.src"
                            :alt="img.alt"
                            class="w-full h-full object-cover select-none transition-transform duration-700 ease-out group-hover:scale-105"
                            loading="eager"
                        />
                    </div>

                    <!-- Indikator Slide Elegan Desktop -->
                    <div
                        v-if="images.length > 1"
                        class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2 pointer-events-none"
                    >
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/40 backdrop-blur-md border border-white/25 shadow-lg pointer-events-auto">
                            <button
                                v-for="(_, idx) in images"
                                :key="idx"
                                type="button"
                                @click.stop="goToSlide(idx)"
                                class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                :class="idx === currentSlide ? 'w-8 bg-white shadow-xs' : 'w-2 bg-white/50 hover:bg-white/80'"
                                :aria-label="`Slide ${idx + 1}`"
                            />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

