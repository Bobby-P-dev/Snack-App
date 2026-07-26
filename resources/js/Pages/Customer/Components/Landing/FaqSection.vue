<script setup>
import { ref } from 'vue';
import { ChevronDown } from 'lucide-vue-next';

const faqs = [
    {
        question: "Berapa minimal pemesanan?",
        answer: "Minimal pemesanan untuk Snack Box adalah 10 box, sedangkan untuk kue satuan minimal 10 pcs per varian/jenisnya."
    },
    {
        question: "Apakah produk 100% halal?",
        answer: "Ya, seluruh produk kami dibuat menggunakan bahan-bahan berkualitas tinggi yang 100% halal dan higienis."
    },
    {
        question: "Apakah bisa custom isi snack box?",
        answer: "Tentu bisa! Anda bebas memilih varian kue manis maupun asin untuk dimasukkan ke dalam snack box sesuai selera dan budget acara Anda."
    },
    {
        question: "Bagaimana cara pembayaran?",
        answer: "Pembayaran dapat dilakukan melalui transfer bank. Anda cukup membayar DP sebesar 70% di awal untuk mengamankan slot pesanan, dan sisa pelunasan dapat dibayarkan saat pesanan diambil atau dikirim."
    },
    {
        question: "Berapa lama proses pengiriman?",
        answer: "Pengiriman dilakukan sesuai dengan jadwal acara Anda (tepat waktu). Kami menyarankan pemesanan dilakukan minimal H-2 untuk memastikan ketersediaan."
    },
    {
        question: "Bisakah pesan mendadak (hari H)?",
        answer: "Untuk pesanan mendadak di hari H, silakan hubungi tim CS/Admin kami via WhatsApp terlebih dahulu untuk mengecek ketersediaan stok kue pada hari tersebut."
    }
];

const openIndex = ref(null);

const toggleFaq = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <section class="py-16 md:py-24 bg-slate-50/30 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-gray-500 md:text-lg max-w-2xl mx-auto">Temukan jawaban untuk pertanyaan yang paling sering ditanyakan oleh pelanggan kami.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 max-w-6xl mx-auto items-start">
                <div v-for="(faq, index) in faqs" :key="index" 
                     class="border rounded-2xl overflow-hidden transition-all duration-300"
                     :class="openIndex === index ? 'border-blue-200 bg-blue-50/50 shadow-md ring-4 ring-blue-50' : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm'">
                    <button @click="toggleFaq(index)" 
                            class="w-full px-6 py-5 flex justify-between items-center text-left focus:outline-none gap-4">
                        <span class="font-bold text-gray-900 md:text-lg" :class="openIndex === index ? 'text-blue-700' : ''">{{ faq.question }}</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-colors duration-300"
                             :class="openIndex === index ? 'bg-blue-600' : 'bg-gray-100'">
                            <ChevronDown class="w-5 h-5 transition-transform duration-300" 
                                         :class="openIndex === index ? 'rotate-180 text-white' : 'text-gray-500'" />
                        </div>
                    </button>
                    
                    <div class="transition-all duration-300 ease-in-out overflow-hidden"
                         :style="{ maxHeight: openIndex === index ? '300px' : '0', opacity: openIndex === index ? 1 : 0 }">
                        <div class="px-6 pb-6 text-gray-600 leading-relaxed md:text-base border-t border-transparent"
                             :class="openIndex === index ? 'border-blue-100/50 pt-4' : ''">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
