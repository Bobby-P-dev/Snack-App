<script setup>
import { ShoppingCart, ClipboardList, Truck, CheckCircle } from 'lucide-vue-next';

const steps = [
    {
        title: 'Pilih Produk',
        description: 'Pilih snack box atau kue sesuai kebutuhan Anda.',
        icon: ShoppingCart,
    },
    {
        title: 'Custom & Checkout',
        description: 'Sesuaikan isi (jika perlu) kemudian checkout pesanan.',
        icon: ClipboardList,
    },
    {
        title: 'Pengiriman',
        description: 'Kami proses pesanan dan kirim tepat waktu.',
        icon: Truck,
    },
    {
        title: 'Pesanan Sampai',
        description: 'Pesanan diterima dengan aman dan siap dinikmati.',
        icon: CheckCircle,
    }
];
</script>

<template>
    <section class="py-16 md:py-24 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Cara Memesan di Padukue</h2>
                <p class="text-gray-600 md:text-lg max-w-2xl mx-auto">Mudah, cepat, dan praktis dalam 4 langkah</p>
            </div>

            <!-- Steps Grid -->
            <!-- We use gap-4 or gap-8 on smaller screens, and gap-8 on large screens -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 relative">
                
                <div v-for="(step, index) in steps" :key="index" class="relative z-10 flex flex-col items-center text-center group">
                    
                    <!-- Icon Container -->
                    <div class="relative mb-6">
                        <!-- Step Number Badge -->
                        <div class="absolute -top-1 -left-2 w-7 h-7 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-sm z-20 shadow-sm border-2 border-white transition-transform group-hover:scale-110">
                            {{ index + 1 }}
                        </div>
                        
                        <!-- Main Icon Circle -->
                        <div class="w-24 h-24 rounded-full bg-blue-50 flex items-center justify-center z-10 relative transition-colors duration-300 group-hover:bg-blue-100">
                            <component :is="step.icon" class="w-10 h-10 text-gray-800 transition-transform duration-300 group-hover:scale-110 group-hover:text-blue-700" stroke-width="1.5" />
                        </div>
                    </div>

                    <!-- Text Content -->
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ step.title }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-[220px] mx-auto">{{ step.description }}</p>

                    <!-- Arrow connecting to next step (Desktop Only) -->
                    <!-- gap is 2rem (gap-8). column center to center is 100% + 2rem. -->
                    <div v-if="index < steps.length - 1" class="hidden lg:flex absolute top-12 left-[calc(50%+3.5rem)] w-[calc(100%-7rem+2rem)] items-center pointer-events-none">
                        <svg class="w-full h-4 text-gray-300" fill="none" viewBox="0 0 100 10" preserveAspectRatio="none">
                            <line x1="0" y1="5" x2="95" y2="5" stroke="currentColor" stroke-width="2" stroke-dasharray="6 6" />
                            <path d="M90 1 L96 5 L90 9" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>
