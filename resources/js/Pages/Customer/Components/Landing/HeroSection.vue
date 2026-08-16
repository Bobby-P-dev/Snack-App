<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Leaf, PackageOpen, Truck, Sparkles } from 'lucide-vue-next';
import { getImageUrl } from '@/helpers.js';

const props = defineProps({
    cms: { type: Object, required: true },
    carousels: { type: Array, default: () => [] },
});

const fallbackImages = [
    { src: 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=1200&q=80', alt: 'Koleksi Snack Box Premium', duration: 5 }
];

const images = computed(() => {
    if (props.carousels && props.carousels.length > 0) {
        return props.carousels.map(c => ({
            src: getImageUrl(c.image_url),
            alt: c.title,
            link: c.link_url,
            duration: c.duration || 5
        }));
    }
    return fallbackImages;
});

const currentSlide = ref(0);
let autoSlideTimer = null;

const startAutoSlide = () => {
    stopAutoSlide();
    if (images.value.length <= 1) return;
    const duration = (images.value[currentSlide.value]?.duration || 5) * 1000;
    autoSlideTimer = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % images.value.length;
    }, duration);
};

const stopAutoSlide = () => {
    if (autoSlideTimer) {
        clearInterval(autoSlideTimer);
        autoSlideTimer = null;
    }
};

watch(currentSlide, () => { startAutoSlide(); });
onMounted(() => { startAutoSlide(); });
onUnmounted(() => { stopAutoSlide(); });
</script>

<template>
    <section class="relative bg-cream-100 overflow-hidden border-b border-cream-200">
        <div class="relative flex flex-col-reverse lg:block min-h-[500px] lg:min-h-[600px] xl:min-h-[700px]">
            <!-- Kiri: Konten Teks -->
            <div class="w-full lg:w-[50%] flex items-center pt-8 pb-16 lg:pt-28 lg:pb-24 px-4 sm:px-6 lg:px-12 xl:px-20 z-10 relative lg:min-h-[600px] xl:min-h-[700px]">
                <div class="max-w-2xl w-full mx-auto lg:mx-0 lg:mr-auto">
                    
                    <!-- Badge -->
                    <p class="inline-flex items-center gap-2 px-4 py-1.5 bg-brand-50 text-brand-600 font-bold tracking-widest text-xs md:text-sm uppercase mb-6 rounded-full border border-brand-100 shadow-sm">
                        <Sparkles class="h-4 w-4" />
                        <span>{{ cms.badge }}</span>
                    </p>
                    
                    <!-- Title -->
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-[4rem] font-extrabold text-brown-800 mb-6 leading-[1.15] tracking-tight">
                        {{ cms.hero_title_1 }}
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-base md:text-xl text-brown-600 mb-10 max-w-xl leading-relaxed">
                        {{ cms.hero_subtitle }}
                    </p>
                    
                    <!-- Propositions -->
                    <div class="flex flex-wrap items-center gap-5 md:gap-8 mb-10">
                        <p class="flex items-center gap-2.5 text-brown-800 text-sm md:text-base font-semibold">
                            <Leaf class="h-5 w-5 text-brand-500 flex-shrink-0" />
                            <span>{{ cms.propotition1 }}</span>
                        </p>
                        <p class="flex items-center gap-2.5 text-brown-800 text-sm md:text-base font-semibold">
                            <PackageOpen class="h-5 w-5 text-brand-500 flex-shrink-0" />
                            <span>{{ cms.propotition2 }}</span>
                        </p>
                        <p class="flex items-center gap-2.5 text-brown-800 text-sm md:text-base font-semibold">
                            <Truck class="h-5 w-5 text-brand-500 flex-shrink-0" />
                            <span>{{ cms.propotition3 }}</span>
                        </p>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="inline-flex items-center justify-center px-8 py-3.5 md:py-4 text-base font-bold text-white bg-brand-500 hover:bg-brand-600 rounded-xl shadow-md shadow-brand-500/30 hover:-translate-y-1 transition-all duration-300">
                            Pesan Sekarang
                        </button>
                        <button class="inline-flex items-center justify-center px-8 py-3.5 md:py-4 text-base font-bold text-brand-600 bg-white border-2 border-brand-100 hover:border-brand-500 hover:bg-brand-50 rounded-xl transition-all duration-300 hover:-translate-y-1 shadow-sm">
                            Lihat Menu
                        </button>
                    </div>
                    
                </div>
            </div>

            <!-- Kanan: Carousel Gambar (Curved Bleed Layout) -->
            <div class="w-full lg:absolute lg:inset-y-0 lg:right-0 lg:w-[55%] relative h-[350px] sm:h-[450px] lg:h-full z-0">
                <!-- Clip path for the curved effect on Desktop -->
                <div class="absolute inset-0 w-full h-full lg:[clip-path:ellipse(100%_120%_at_100%_50%)] overflow-hidden bg-gray-100">
                    <div v-for="(img, idx) in images" :key="idx" class="absolute inset-0 transition-all duration-1000 transform" :class="idx === currentSlide ? 'opacity-100 scale-100' : 'opacity-0 scale-105 pointer-events-none'">
                        <img :src="img.src" :alt="img.alt" class="w-full h-full object-cover" />
                        <!-- Subtle gradient to ensure dots are visible -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    </div>
                    
                    <!-- Indikator Slide -->
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-3">
                        <button v-for="(_, idx) in images" :key="idx" @click="currentSlide = idx" class="h-2 rounded-full transition-all duration-300" :class="idx === currentSlide ? 'w-8 bg-white shadow-md' : 'w-2 bg-white/60 hover:bg-white'"></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
