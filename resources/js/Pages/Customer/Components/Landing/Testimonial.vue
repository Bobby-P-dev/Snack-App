<script setup>
import { Star } from 'lucide-vue-next';

const testimonials = [
    { name: "Dewi Lestari", role: "Event Organizer", text: "Snack boxnya enak, pengiriman tepat waktu, dan semua tamu puas. Highly recommended!", rating: 5 },
    { name: "Rizky Pratama", role: "HR Manager", text: "Custom isinya fleksibel banget. Sesuai kebutuhan acara kantor kami.", rating: 5 },
    { name: "Siti Nurhaliza", role: "Mahasiswa", text: "Rasa enak, kemasan rapi, harga bersahabat. Pasti pesan lagi!", rating: 5 },
    { name: "Budi Santoso", role: "Corporate Secretary", text: "Selalu jadi andalan untuk setiap meeting direksi. Pelayanan sangat profesional.", rating: 5 },
    { name: "Anita Wijaya", role: "Ibu Rumah Tangga", text: "Kue-kuenya fresh dan rasanya premium banget. Anak-anak suka sekali.", rating: 5 }
];
</script>

<template>
    <section class="py-16 md:py-24 bg-white overflow-hidden border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Apa Kata Mereka?</h2>
            <p class="text-gray-500 md:text-lg">Ribuan pelanggan telah mempercayakan momen spesialnya pada kami</p>
        </div>

        <div class="relative w-full flex overflow-hidden group py-4">
            <!-- Fade overlays for nice visual effect -->
            <div class="absolute left-0 top-0 bottom-0 w-16 md:w-40 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
            <div class="absolute right-0 top-0 bottom-0 w-16 md:w-40 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
            
            <div class="flex animate-marquee hover:[animation-play-state:paused] gap-6 pr-6">
                <div v-for="(item, idx) in testimonials" :key="'a'+idx" class="w-[320px] md:w-[400px] shrink-0 bg-white border border-gray-100 p-6 md:p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="grid grid-cols-[auto_1fr] gap-x-5 gap-y-8">
                        <!-- Top Row: Quote & Text -->
                        <div class="w-12 h-12 rounded-full border-2 border-blue-50 flex items-center justify-center shrink-0 bg-blue-50/50">
                            <span class="text-4xl font-serif font-black text-blue-600 leading-none pt-3">&ldquo;</span>
                        </div>
                        <div class="flex items-center">
                            <p class="text-gray-700 text-sm md:text-base leading-relaxed font-medium">
                                "{{ item.text }}"
                            </p>
                        </div>
                        
                        <!-- Bottom Row: Avatar & Details -->
                        <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden shrink-0">
                            <svg class="w-full h-full text-gray-400 mt-2" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <div>
                            <div class="flex gap-1 mb-2">
                                <Star v-for="i in item.rating" :key="i" class="w-4 h-4 text-amber-400 fill-amber-400" />
                            </div>
                            <h4 class="font-bold text-gray-900 text-sm md:text-base">{{ item.name }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">{{ item.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Duplicated container for seamless loop -->
            <div class="flex animate-marquee hover:[animation-play-state:paused] gap-6 pr-6" aria-hidden="true">
                <div v-for="(item, idx) in testimonials" :key="'b'+idx" class="w-[320px] md:w-[400px] shrink-0 bg-white border border-gray-100 p-6 md:p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="grid grid-cols-[auto_1fr] gap-x-5 gap-y-8">
                        <div class="w-12 h-12 rounded-full border-2 border-blue-50 flex items-center justify-center shrink-0 bg-blue-50/50">
                            <span class="text-4xl font-serif font-black text-blue-600 leading-none pt-3">&ldquo;</span>
                        </div>
                        <div class="flex items-center">
                            <p class="text-gray-700 text-sm md:text-base leading-relaxed font-medium">
                                "{{ item.text }}"
                            </p>
                        </div>
                        
                        <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden shrink-0">
                            <svg class="w-full h-full text-gray-400 mt-2" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <div>
                            <div class="flex gap-1 mb-2">
                                <Star v-for="i in item.rating" :key="i" class="w-4 h-4 text-amber-400 fill-amber-400" />
                            </div>
                            <h4 class="font-bold text-gray-900 text-sm md:text-base">{{ item.name }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">{{ item.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes marquee {
    0% { transform: translateX(0%); }
    100% { transform: translateX(-100%); }
}
.animate-marquee {
    animation: marquee 30s linear infinite;
    flex-shrink: 0;
}
</style>
