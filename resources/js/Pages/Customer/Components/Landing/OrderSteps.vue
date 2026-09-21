<script setup>
import { ShoppingBag, ClipboardList, MessageCircle, Truck } from 'lucide-vue-next';

const steps = [
    {
        title: 'Pilih Produk',
        description: 'Pilih snack box atau kue satuan sesuai kebutuhan dan selera acara Anda.',
        icon: ShoppingBag,
    },
    {
        title: 'Custom & Checkout',
        description: 'Sesuaikan komposisi isi box, tentukan jumlah, lalu checkout tanpa perlu login.',
        icon: ClipboardList,
    },
    {
        title: 'Konfirmasi WhatsApp',
        description: 'Pesan diteruskan ke WhatsApp admin untuk invoice otomatis dan pengamanan slot.',
        icon: MessageCircle,
    },
    {
        title: 'Pesanan Tiba Segar',
        description: 'Paket dirakit higienis dan dikirim tepat waktu, siap disajikan untuk tamu Anda.',
        icon: Truck,
    }
];
</script>

<template>
    <section class="py-16 md:py-24 bg-cream-50/70 border-b border-cream-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest bg-brand-50 px-3.5 py-1 rounded-full border border-brand-200/60 inline-block mb-3">
                    Langkah Pemesanan
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-brown-900 mb-3 tracking-tight">Cara Memesan di Padu Kue</h2>
                <p class="text-brown-600 md:text-lg max-w-2xl mx-auto">Sangat mudah, cepat, dan praktis tanpa harus repot mendaftar akun</p>
            </div>

            <!-- Steps Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8 relative">
                
                <div v-for="(step, index) in steps" :key="index" class="relative z-10 flex flex-col items-center text-center group">
                    
                    <!-- Icon Container -->
                    <div class="relative mb-6">
                        <!-- Step Number Badge -->
                        <div class="absolute -top-1 -left-2 w-8 h-8 rounded-full bg-gradient-to-tr from-brand-600 to-brand-400 text-white flex items-center justify-center font-bold text-sm z-20 shadow-md border-2 border-white transition-transform group-hover:scale-110">
                            {{ index + 1 }}
                        </div>
                        
                        <!-- Main Icon Circle -->
                        <div class="w-24 h-24 rounded-2xl bg-white border border-cream-200 shadow-sm flex items-center justify-center z-10 relative transition-all duration-300 group-hover:border-brand-300 group-hover:bg-brand-50/50 group-hover:shadow-md">
                            <component :is="step.icon" class="w-10 h-10 text-brown-800 transition-all duration-300 group-hover:scale-110 group-hover:text-brand-600" stroke-width="1.6" />
                        </div>
                    </div>

                    <!-- Text Content -->
                    <h3 class="text-lg font-bold text-brown-900 mb-2">{{ step.title }}</h3>
                    <p class="text-sm text-brown-600 leading-relaxed max-w-[240px] mx-auto">{{ step.description }}</p>

                    <!-- Arrow connecting to next step (Desktop Only) -->
                    <div v-if="index < steps.length - 1" class="hidden lg:flex absolute top-12 left-[calc(50%+3.5rem)] w-[calc(100%-7rem+2rem)] items-center pointer-events-none">
                        <svg class="w-full h-4 text-brand-200/80" fill="none" viewBox="0 0 100 10" preserveAspectRatio="none">
                            <line x1="0" y1="5" x2="95" y2="5" stroke="currentColor" stroke-width="2" stroke-dasharray="6 6" />
                            <path d="M90 1 L96 5 L90 9" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>
