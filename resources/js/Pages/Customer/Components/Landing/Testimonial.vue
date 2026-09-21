<script setup>
import { Star } from 'lucide-vue-next';

const testimonials = [
    { name: "Dewi Lestari", role: "Event Organizer Jakarta", text: "Snack boxnya enak banget, kue sus dan lempernya juara! Pengiriman tepat waktu dan kemasan boxnya sangat rapi berkelas.", rating: 5 },
    { name: "Rizky Pratama", role: "HR & General Affair", text: "Paling suka fitur custom snack box-nya, praktis bisa sesuaikan budget meeting kantor tanpa ribet login.", rating: 5 },
    { name: "Siti Nurhaliza", role: "Panitia Seminar Kampus", text: "Rasa kuenya fresh dan gurih manisnya pas. Respon admin WhatsApp sangat cepat dan ramah!", rating: 5 },
    { name: "Budi Santoso", role: "Corporate Secretary", text: "Selalu jadi andalan rapat direksi bulanan. Kualitas kue premium dan higienitas terjamin.", rating: 5 },
    { name: "Anita Wijaya", role: "Acara Arisan Keluarga", text: "Brownies dan croissant-nya ludes dalam sekejap. Semua keluarga memuji rasanya yang lembut.", rating: 5 }
];
</script>

<template>
    <section class="py-16 md:py-24 bg-cream-50/60 overflow-hidden border-b border-cream-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 text-center">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-widest bg-brand-50 px-3.5 py-1 rounded-full border border-brand-200/60 inline-block mb-3">
                Ulasan Pelanggan
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-brown-900 mb-3 tracking-tight">Cerita Momen Bersama Padu Kue</h2>
            <p class="text-brown-600 md:text-lg max-w-2xl mx-auto">Kepuasan rasa dan layanan yang telah dipercaya oleh ribuan acara dan keluarga</p>
        </div>

        <div class="relative w-full flex overflow-hidden group py-4">
            <!-- Fade overlays for soft visual edge -->
            <div class="absolute left-0 top-0 bottom-0 w-16 md:w-36 bg-gradient-to-r from-cream-50/90 to-transparent z-10 pointer-events-none"></div>
            <div class="absolute right-0 top-0 bottom-0 w-16 md:w-36 bg-gradient-to-l from-cream-50/90 to-transparent z-10 pointer-events-none"></div>
            
            <div class="flex animate-marquee hover:[animation-play-state:paused] gap-6 pr-6">
                <div v-for="(item, idx) in testimonials" :key="'a'+idx" class="w-[320px] md:w-[400px] shrink-0 bg-white border border-cream-200/80 p-6 md:p-8 rounded-3xl shadow-2xs hover:shadow-xl hover:border-brand-200 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="grid grid-cols-[auto_1fr] gap-x-5 gap-y-6">
                        <!-- Top Row: Quote & Text -->
                        <div class="w-11 h-11 rounded-2xl border border-brand-200/80 flex items-center justify-center shrink-0 bg-brand-50/70">
                            <span class="text-3xl font-serif font-black text-brand-500 leading-none pt-2">&ldquo;</span>
                        </div>
                        <div class="flex items-center">
                            <p class="text-brown-800 text-sm md:text-base leading-relaxed font-medium">
                                "{{ item.text }}"
                            </p>
                        </div>
                        
                        <!-- Bottom Row: Avatar & Details -->
                        <div class="w-11 h-11 rounded-2xl bg-cream-100 flex items-center justify-center text-brand-600 font-bold text-sm shrink-0 border border-cream-200">
                            {{ item.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="flex gap-1 mb-1.5">
                                <Star v-for="i in item.rating" :key="i" class="w-4 h-4 text-amber-400 fill-amber-400" />
                            </div>
                            <h4 class="font-bold text-brown-900 text-sm md:text-base">{{ item.name }}</h4>
                            <p class="text-xs text-brown-500 mt-0.5">{{ item.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Duplicated container for seamless loop -->
            <div class="flex animate-marquee hover:[animation-play-state:paused] gap-6 pr-6" aria-hidden="true">
                <div v-for="(item, idx) in testimonials" :key="'b'+idx" class="w-[320px] md:w-[400px] shrink-0 bg-white border border-cream-200/80 p-6 md:p-8 rounded-3xl shadow-2xs hover:shadow-xl hover:border-brand-200 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="grid grid-cols-[auto_1fr] gap-x-5 gap-y-6">
                        <div class="w-11 h-11 rounded-2xl border border-brand-200/80 flex items-center justify-center shrink-0 bg-brand-50/70">
                            <span class="text-3xl font-serif font-black text-brand-500 leading-none pt-2">&ldquo;</span>
                        </div>
                        <div class="flex items-center">
                            <p class="text-brown-800 text-sm md:text-base leading-relaxed font-medium">
                                "{{ item.text }}"
                            </p>
                        </div>
                        
                        <div class="w-11 h-11 rounded-2xl bg-cream-100 flex items-center justify-center text-brand-600 font-bold text-sm shrink-0 border border-cream-200">
                            {{ item.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="flex gap-1 mb-1.5">
                                <Star v-for="i in item.rating" :key="i" class="w-4 h-4 text-amber-400 fill-amber-400" />
                            </div>
                            <h4 class="font-bold text-brown-900 text-sm md:text-base">{{ item.name }}</h4>
                            <p class="text-xs text-brown-500 mt-0.5">{{ item.role }}</p>
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
    animation: marquee 35s linear infinite;
    flex-shrink: 0;
}
</style>
