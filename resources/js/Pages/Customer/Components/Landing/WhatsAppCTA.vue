<script setup>
import { computed } from 'vue';

const props = defineProps({
    cms: { type: Object, default: () => ({}) }
});

const waConsultationUrl = computed(() => {
    const phone = (props.cms.contact_phone || '6281234567890').replace(/[^0-9]/g, '');
    const company = props.cms.company_name || 'Padu Kue';
    let message = props.cms.wa_consultation_message || 'Halo {company_name}, saya ingin konsultasi mengenai pemesanan snack box dan aneka kue.';
    message = message.replace(/\{company_name\}/g, company);
    return `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
});
</script>

<template>
    <section class="py-14 md:py-20 bg-cream-50/50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-cream-50 to-brand-50/40 border border-cream-200/90 rounded-3xl p-6 sm:p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 md:gap-8 shadow-sm hover:shadow-md transition-shadow">
                
                <div class="flex items-center gap-5 text-center md:text-left flex-col md:flex-row w-full md:w-auto">
                    <!-- WhatsApp Emblem Circle -->
                    <div class="w-16 h-16 md:w-18 md:h-18 bg-emerald-500 rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/25 text-white">
                        <svg class="w-8 h-8 md:w-9 md:h-9" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-extrabold text-brown-900 mb-1.5">Butuh Konsultasi atau Paket Khusus?</h2>
                        <p class="text-brown-600 text-sm md:text-base">Tim admin Padu Kue siap melayani rekomendasi menu, jumlah porsi, dan jadwal acara Anda.</p>
                    </div>
                </div>

                <a :href="waConsultationUrl" 
                   target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 md:py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-600/25 hover:shadow-lg hover:-translate-y-0.5 whitespace-nowrap w-full md:w-auto text-sm sm:text-base">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z" />
                    </svg>
                    <span>Hubungi via WhatsApp</span>
                </a>

            </div>
        </div>
    </section>
</template>
