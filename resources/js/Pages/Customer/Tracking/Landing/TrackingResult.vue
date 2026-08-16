<template>
    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-gray-100">
        <!-- Header: Back & Title -->
        <div class="mb-8">
            <button @click="$emit('reset')" class="flex items-center text-gray-500 hover:text-gray-900 transition text-sm mb-6">
                <ArrowLeft class="w-4 h-4 mr-2" />
                Kembali ke pencarian
            </button>
            <h2 class="text-2xl font-bold text-[#4A3B32] mb-2">Detail Pesanan</h2>
            <p class="text-gray-600 mb-1">Order Number: <span class="font-semibold">{{ order.order_number }}</span></p>
            <p class="text-gray-600">Tanggal Order: {{ order.date }}</p>
        </div>

        <!-- Download Invoice button (Optional, as seen in image) -->
        <!-- <div class="absolute top-10 right-10 hidden md:block">
            <button class="flex items-center gap-2 px-4 py-2 border border-[#CF5C70] text-[#CF5C70] rounded-xl hover:bg-pink-50 transition font-medium text-sm">
                <Download class="w-4 h-4" />
                Download Invoice
            </button>
        </div> -->

        <!-- Timeline -->
        <div class="mb-12 border-b border-gray-100 pb-12">
            <TrackingTimeline :current-step="order.current_step" />
        </div>

        <!-- Details Grid (3 columns) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Col 1: Informasi Pemesan -->
            <div class="bg-[#FCF9F5] p-6 rounded-2xl">
                <h3 class="font-bold text-[#4A3B32] mb-5">Informasi Pemesan</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <User class="w-5 h-5 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Nama</p>
                            <p class="text-sm font-medium text-gray-800">{{ order.customer_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <Phone class="w-5 h-5 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">No. WhatsApp</p>
                            <p class="text-sm font-medium text-gray-800">{{ order.customer_phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <MapPin class="w-5 h-5 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Alamat Pengiriman</p>
                            <p class="text-sm font-medium text-gray-800 leading-relaxed">{{ order.location }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 2: Ringkasan Pesanan -->
            <div class="bg-[#FCF9F5] p-6 rounded-2xl">
                <h3 class="font-bold text-[#4A3B32] mb-5">Ringkasan Pesanan</h3>
                <div class="space-y-4 mb-6 max-h-48 overflow-y-auto pr-2">
                    <div v-for="(item, index) in order.items" :key="index" class="flex gap-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden shrink-0">
                            <img v-if="item.image_url" :src="`/storage/${item.image_url}`" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center bg-gray-100"><Package class="w-5 h-5 text-gray-400" /></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-800 truncate">{{ item.name }}</h4>
                            <p class="text-xs text-gray-500">x {{ item.quantity }} box</p>
                        </div>
                        <div class="text-sm font-bold text-gray-800 text-right">
                            Rp {{ formatNumber(item.price * item.quantity) }}
                        </div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-200 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium text-gray-800">Rp {{ formatNumber(order.subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ongkir</span>
                        <span class="font-medium text-gray-800">Rp {{ formatNumber(order.ongkir) }}</span>
                    </div>
                    <div class="flex justify-between mt-3 pt-3 border-t border-gray-200">
                        <span class="font-bold text-[#4A3B32]">Total</span>
                        <span class="font-bold text-[#CF5C70]">Rp {{ formatNumber(order.total) }}</span>
                    </div>
                </div>
            </div>

            <!-- Col 3: Catatan & Bantuan -->
            <div class="flex flex-col gap-4">
                <div class="bg-[#FCF9F5] p-6 rounded-2xl flex-1">
                    <h3 class="font-bold text-[#4A3B32] mb-4">Catatan</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Terima kasih telah memesan di Snack Box! 💕<br><br>
                        Kami akan menginformasikan jika pesanan kamu sudah masuk ke tahap pengiriman.
                    </p>
                </div>
                
                <div class="bg-[#FADCE0] bg-opacity-40 p-6 rounded-2xl border border-pink-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-[#4A3B32] text-sm">Butuh bantuan?</h4>
                        <p class="text-xs text-gray-600 mt-1">Hubungi kami via WhatsApp</p>
                    </div>
                    <a href="https://wa.me/" target="_blank" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#CF5C70] shadow-sm hover:scale-105 transition">
                        <MessageCircle class="w-5 h-5" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ArrowLeft, User, Phone, MapPin, Package, Download, MessageCircle } from 'lucide-vue-next';
import TrackingTimeline from './TrackingTimeline.vue';

defineProps({
    order: {
        type: Object,
        required: true
    }
});

defineEmits(['reset']);

const formatNumber = (num) => {
    return Number(num).toLocaleString('id-ID');
};
</script>
