<template>
    <Head title="Pilihan Paket Snack Box - Padu Kue" />
    <CustomerLayout>
        <div class="min-h-screen bg-cream-100 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-orange-100/70 px-3.5 py-1.5 rounded-full inline-block mb-3">
                        Custom Snack Box
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Pilih Kapasitas Snack Box Anda
                    </h1>
                    <p class="text-gray-600 text-sm sm:text-base mt-3 leading-relaxed">
                        Pilih kapasitas isi kue yang diinginkan, kemudian pilih sendiri kombinasi kue favorit Anda dari puluhan pilihan kue lezat dan segar.
                    </p>
                </div>

                <!-- Package Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div
                        v-for="(pkg, idx) in packages"
                        :key="pkg.id"
                        :class="[
                            'bg-white rounded-3xl p-8 border shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-lg relative group',
                            idx === 1
                                ? 'border-brand-500 ring-2 ring-brand-500/20 shadow-md'
                                : 'border-orange-100/80 hover:border-brand-300'
                        ]"
                    >
                        <div v-if="idx === 1" class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand-500 text-white text-[11px] font-bold uppercase tracking-wider px-3.5 py-1 rounded-full shadow-sm">
                            Paling Populer
                        </div>

                        <div>
                            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-brand-600 flex items-center justify-center font-bold text-xl mb-6 group-hover:scale-110 transition duration-300">
                                {{ pkg.capacity }}
                            </div>

                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ pkg.name }}</h2>
                            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                                {{ pkg.description || `Pilihan bebas ${pkg.capacity} kue dalam satu box eksklusif.` }}
                            </p>

                            <div class="space-y-3 py-4 border-t border-gray-100 text-sm text-gray-600">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-4 h-4 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold">✓</span>
                                    <span>Pilih bebas <strong>{{ pkg.capacity }} jenis kue</strong></span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span class="w-4 h-4 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold">✓</span>
                                    <span>Termasuk box kemasan & tisu</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span class="w-4 h-4 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold">✓</span>
                                    <span>Kue dibuat segar sesuai pesanan</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span class="w-4 h-4 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold">✓</span>
                                    <span>Harga transparan per item</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <Link
                                :href="`/snack-box/${pkg.slug}`"
                                :class="[
                                    'w-full py-3.5 px-6 rounded-2xl font-bold text-center block transition shadow-sm',
                                    idx === 1
                                        ? 'bg-brand-500 hover:bg-brand-600 text-white'
                                        : 'bg-cream-50 hover:bg-brand-500 hover:text-white text-brand-700 border border-orange-200 hover:border-transparent'
                                ]"
                            >
                                Rakit Box Ini →
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Steps Banner -->
                <div class="bg-white rounded-3xl p-8 sm:p-10 border border-orange-100/80 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-8">Cara Memesan Snack Box Custom di Padu Kue</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-center">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-brand-700 font-extrabold flex items-center justify-center mx-auto mb-3 text-lg">1</div>
                            <h4 class="font-bold text-gray-800 text-sm">Pilih Kapasitas Box</h4>
                            <p class="text-xs text-gray-500 mt-1">Pilih ukuran box 3, 4, atau 5 item.</p>
                        </div>
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-brand-700 font-extrabold flex items-center justify-center mx-auto mb-3 text-lg">2</div>
                            <h4 class="font-bold text-gray-800 text-sm">Pilih Isian Kue</h4>
                            <p class="text-xs text-gray-500 mt-1">Kombinasikan kue manis, gurih, dan snack.</p>
                        </div>
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-brand-700 font-extrabold flex items-center justify-center mx-auto mb-3 text-lg">3</div>
                            <h4 class="font-bold text-gray-800 text-sm">Tentukan Jumlah Box</h4>
                            <p class="text-xs text-gray-500 mt-1">Tentukan kuantitas untuk acara Anda.</p>
                        </div>
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-brand-700 font-extrabold flex items-center justify-center mx-auto mb-3 text-lg">4</div>
                            <h4 class="font-bold text-gray-800 text-sm">Checkout & WhatsApp</h4>
                            <p class="text-xs text-gray-500 mt-1">Konfirmasi pesanan langsung ke admin.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

defineProps({
    packages: {
        type: Array,
        required: true,
    },
});
</script>
