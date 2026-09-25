<template>
    <section class="bg-cream-100 py-8 border-b border-cream-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
                <Link href="/" class="hover:text-brand-600 flex items-center transition font-medium">
                    <ArrowLeft class="w-4 h-4 mr-1.5" />
                    Beranda
                </Link>
                <span class="text-gray-400">&rsaquo;</span>
                <span class="text-brown-800 font-semibold">Kue Satuan</span>
            </nav>

            <!-- Title & Description -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-brown-800 mb-3 tracking-tight">
                    Kue Satuan & Jajanan Pasar
                </h1>
                <p class="text-brown-600 text-base md:text-lg max-w-3xl">
                    Pilih aneka kue basah, pastry, bolu, donat, dan jajanan pasar favorit dengan cita rasa lezat berkualitas untuk berbagai kebutuhan acara Anda.
                </p>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-4 gap-2 sm:flex sm:flex-wrap sm:gap-4 md:gap-8 mt-6">
                <div class="flex flex-col sm:flex-row items-center gap-1.5 sm:gap-2.5 text-[10px] sm:text-sm font-semibold text-brown-800 text-center sm:text-left">
                    <Leaf class="h-6 w-6 sm:h-5 sm:w-5 text-brand-500 sm:text-brand-500 flex-shrink-0 mb-1 sm:mb-0" stroke-width="1.5" />
                    <span class="hidden sm:inline">Fresh Setiap Hari</span>
                    <span class="sm:hidden">Fresh</span>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-1.5 sm:gap-2.5 text-[10px] sm:text-sm font-semibold text-brown-800 text-center sm:text-left">
                    <Sparkles class="h-6 w-6 sm:h-5 sm:w-5 text-brand-500 sm:text-brand-500 flex-shrink-0 mb-1 sm:mb-0" stroke-width="1.5" />
                    <span class="hidden sm:inline">Pilihan Lengkap</span>
                    <span class="sm:hidden">Lengkap</span>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-1.5 sm:gap-2.5 text-[10px] sm:text-sm font-semibold text-brown-800 text-center sm:text-left">
                   <Truck class="h-6 w-6 sm:h-5 sm:w-5 text-brand-500 sm:text-brand-500 flex-shrink-0 mb-1 sm:mb-0" stroke-width="1.5" />
                    <span class="hidden sm:inline">Pengiriman Tepat Waktu</span>
                    <span class="sm:hidden">Tepat Waktu</span>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-1.5 sm:gap-2.5 text-[10px] sm:text-sm font-semibold text-brown-800 text-center sm:text-left">
                    <ShieldCheck class="h-6 w-6 sm:h-5 sm:w-5 text-brand-500 sm:text-brand-500 flex-shrink-0 mb-1 sm:mb-0" stroke-width="1.5" />
                    <span class="hidden sm:inline">100% Halal</span>
                    <span class="sm:hidden">Halal</span>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { Leaf, Sparkles, ShieldCheck, Truck, ArrowLeft } from 'lucide-vue-next';
</script>
