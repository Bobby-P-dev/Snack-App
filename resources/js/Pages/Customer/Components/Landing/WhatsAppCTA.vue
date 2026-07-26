<script setup>
const props = defineProps({
    cms: { type: Object, default: () => ({}) }
});
</script>

<template>
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-blue-50/80 border border-blue-100 rounded-3xl p-6 md:p-8 lg:p-10 flex flex-col md:flex-row items-center justify-between gap-8 shadow-sm hover:shadow-md transition-shadow">
                
                <div class="flex items-center gap-6 text-center md:text-left flex-col md:flex-row w-full md:w-auto">
                    <!-- WhatsApp Icon Circle -->
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-green-500 rounded-full flex items-center justify-center shrink-0 shadow-lg shadow-green-200">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1.5 md:mb-2">Mau order atau tanya-tanya dulu?</h2>
                        <p class="text-gray-600 text-sm md:text-base">Hubungi kami via WhatsApp, admin kami siap membantu!</p>
                    </div>
                </div>

                <a :href="`https://wa.me/${cms.contact_phone}?text=Halo%20${cms.company_name}%2C%20saya%20ingin%20konsultasi%20tentang%20pemesanan`" 
                   target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 md:py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl md:rounded-2xl transition-all shadow-md hover:shadow-xl hover:-translate-y-1 whitespace-nowrap w-full md:w-auto text-base">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z" />
                    </svg>
                    Chat WhatsApp
                </a>

            </div>
        </div>
    </section>
</template>
