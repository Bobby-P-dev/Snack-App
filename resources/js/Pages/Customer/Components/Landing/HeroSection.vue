<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Leaf, PackageOpen, Truck, ArrowRight } from 'lucide-vue-next';
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

// Helper for formatting value propositions naturally
const formatProp = (rawVal, defaultTitle, defaultSub = '') => {
    if (!rawVal) return { title: defaultTitle, sub: defaultSub };
    const str = String(rawVal).trim();
    if (str.includes('&')) {
        const parts = str.split('&').map(s => s.trim());
        return {
            title: parts[0],
            sub: parts[1] ? `Alami & ${parts[1]}` : defaultSub
        };
    }
    if (str.toLowerCase().includes('custom box')) {
        const extra = str.replace(/custom box/i, '').trim();
        return {
            title: 'Custom Box',
            sub: extra ? `${extra} & Praktis` : 'Bebas Pilih Kue'
        };
    }
    if (str.toLowerCase().includes('pengiriman tepat waktu') || str.toLowerCase().includes('tepat waktu')) {
        return {
            title: 'Tepat Waktu',
            sub: 'Siap untuk Acara'
        };
    }
    const words = str.split(' ');
    if (words.length >= 3) {
        return {
            title: words.slice(0, 2).join(' '),
            sub: words.slice(2).join(' ')
        };
    }
    return { title: str, sub: defaultSub };
};

const trustItems = computed(() => [
    {
        icon: Leaf,
        ...formatProp(props.cms?.propotition1, 'Bahan Segar', 'Alami & Higienis'),
    },
    {
        icon: PackageOpen,
        ...formatProp(props.cms?.propotition2, 'Custom Box', 'Bebas Pilih Kue'),
    },
    {
        icon: Truck,
        ...formatProp(props.cms?.propotition3, 'Tepat Waktu', 'Siap untuk Acara'),
    },
]);
</script>

<template>
    <section class="relative bg-gradient-to-b from-cream-50 via-cream-100/70 to-cream-200/50 overflow-hidden border-b border-cream-200/80">
        <!-- Background Ambient Warm Glow -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-brand-200/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:block min-h-[500px] lg:min-h-[580px] xl:min-h-[660px]">
            
            <!-- ─── KIRI: KONTEN TEKS & AKSI ─── -->
            <div class="w-full lg:w-[50%] flex items-center pt-8 pb-10 lg:py-16 xl:py-20 px-4 sm:px-6 lg:pl-10 lg:pr-6 xl:pl-16 xl:pr-10 2xl:pl-24 z-10 relative lg:min-h-[580px] xl:min-h-[660px]">
                <div class="max-w-xl w-full mx-auto lg:mx-0">
                    
                    <!-- Eyebrow Tag (Consistent with OrderSteps, FaqSection & Product Showcase) -->
                    <span class="text-[10px] sm:text-xs font-bold text-brand-600 uppercase tracking-wider sm:tracking-widest bg-brand-50 px-3.5 py-1 rounded-full border border-brand-200/60 inline-block mb-3.5 shadow-2xs">
                        {{ cms.badge || 'Spesialis Custom Snack Box & Aneka Kue' }}
                    </span>

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

                    <!-- Artisan Micro-Trust Bar (Harmonious with OrderSteps) -->
                    <div class="pt-6 border-t border-cream-300/70">
                        <div class="grid grid-cols-3 gap-2.5 sm:gap-4">
                            <div 
                                v-for="(item, idx) in trustItems" 
                                :key="idx"
                                class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-2 sm:gap-3 p-2.5 sm:p-3.5 rounded-2xl bg-white border border-cream-200/90 shadow-2xs transition-all duration-300 hover:border-brand-200 hover:shadow-xs group"
                            >
                                <!-- Icon Squircle (Same DNA as Step Cards in OrderSteps) -->
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-cream-50/90 border border-cream-200/80 flex items-center justify-center text-brown-800 shrink-0 transition-all duration-300 group-hover:scale-105 group-hover:bg-brand-50/50 group-hover:border-brand-300 shadow-2xs">
                                    <component :is="item.icon" class="w-4 h-4 sm:w-5 sm:h-5 text-brown-800 transition-colors duration-300 group-hover:text-brand-600" stroke-width="1.6" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] sm:text-xs md:text-sm font-bold text-brown-900 leading-tight group-hover:text-brand-700 transition-colors">
                                        {{ item.title }}
                                    </p>
                                    <p class="text-[9px] sm:text-[10px] md:text-xs text-brown-500 mt-0.5 leading-tight font-medium">
                                        {{ item.sub }}
                                    </p>
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

