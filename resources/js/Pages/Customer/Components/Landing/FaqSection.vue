<script setup>
import { ref } from 'vue';
import { ChevronDown } from 'lucide-vue-next';

const faqs = [
    {
        question: "Berapa minimal pemesanan?",
        answer: "Minimal pemesanan untuk Custom Snack Box adalah 10 box, sedangkan untuk kue satuan minimal 10 pcs per jenis variasinya."
    },
    {
        question: "Apakah produk Padu Kue 100% halal dan higienis?",
        answer: "Tentu saja! Seluruh sajian kue dan pastry kami diproduksi menggunakan bahan-bahan halal bermutu tinggi dengan standar dapur yang bersih dan higienis."
    },
    {
        question: "Apakah bisa custom isi kue di snack box sesuka hati?",
        answer: "Bisa sekali! Anda dapat bebas merakit kombinasi kue asin gurih dan manis favorit Anda langsung melalui menu Custom Snack Box kami."
    },
    {
        question: "Bagaimana alur pembayaran di Padu Kue?",
        answer: "Pembayaran dapat dilakukan melalui transfer bank. Cukup bayar DP sebesar 70% di awal untuk mengamankan slot produksi, dan sisa pelunasan dapat diselesaikan saat pesanan diterima."
    },
    {
        question: "Kapan sebaiknya melakukan pemesanan?",
        answer: "Kami menyarankan pemesanan dilakukan minimal H-2 untuk menjamin ketersediaan bahan baku segar. Untuk kebutuhan mendadak hari H, silakan konsultasi langsung ke admin via WhatsApp."
    },
    {
        question: "Apakah perlu membuat akun atau login terlebih dahulu?",
        answer: "Tidak perlu! Di Padu Kue, Anda bisa langsung memilih menu, merakit snack box, dan menyelesaikan checkout tanpa hambatan registrasi atau login."
    }
];

const openIndex = ref(null); // Tutup semua secara default saat awal dimuat

const toggleFaq = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <section class="py-16 md:py-24 bg-white border-b border-cream-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest bg-brand-50 px-3.5 py-1 rounded-full border border-brand-200/60 inline-block mb-3">
                    Bantuan & Informasi
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-brown-900 mb-3 tracking-tight">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-brown-600 md:text-lg max-w-2xl mx-auto">Jawaban ringkas seputar pemesanan, pengiriman, dan layanan kustom snack box kami</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5 max-w-6xl mx-auto items-start">
                <div v-for="(faq, index) in faqs" :key="index" 
                     class="border rounded-2xl overflow-hidden transition-all duration-300"
                     :class="openIndex === index 
                        ? 'border-brand-200 bg-brand-50/40 shadow-sm ring-2 ring-brand-100/60' 
                        : 'border-cream-200 bg-cream-50/30 hover:border-cream-300 hover:bg-white'">
                    <button @click="toggleFaq(index)" 
                            class="w-full px-5 sm:px-6 py-4 sm:py-5 flex justify-between items-center text-left focus:outline-none gap-4">
                        <span class="font-bold text-sm sm:text-base md:text-lg transition-colors" 
                              :class="openIndex === index ? 'text-brand-700' : 'text-brown-900'">
                            {{ faq.question }}
                        </span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-colors duration-200"
                             :class="openIndex === index ? 'bg-brand-500 text-white shadow-2xs' : 'bg-cream-200 text-brown-600'">
                            <ChevronDown class="w-4 h-4 transition-transform duration-300" 
                                         :class="openIndex === index ? 'rotate-180 text-white' : 'text-brown-600'" />
                        </div>
                    </button>
                    
                    <div class="transition-all duration-300 ease-in-out overflow-hidden"
                         :style="{ maxHeight: openIndex === index ? '300px' : '0', opacity: openIndex === index ? 1 : 0 }">
                        <div class="px-5 sm:px-6 pb-5 sm:pb-6 text-brown-700/90 leading-relaxed text-sm sm:text-base border-t border-transparent"
                             :class="openIndex === index ? 'border-brand-100/50 pt-2 sm:pt-3' : ''">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
