<template>
    <footer class="bg-gradient-to-b from-gray-900 to-gray-950 text-white pt-16 pb-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 mb-12">
                <!-- Left: Logo, Description & Contact -->
                <div class="lg:col-span-5 space-y-8">
                    <!-- Brand -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20">
                                <span class="text-xl tracking-wider">{{ getInitials(cms.company_name || 'Snack Box') }}</span>
                            </div>
                            <span class="text-2xl font-bold tracking-tight">{{ cms.company_name || 'Snack Box' }}</span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                            {{ cms.company_description || 'Mitra terpercaya untuk pemesanan snack box custom dan aneka kue satuan untuk menyukseskan berbagai acara spesial Anda.' }}
                        </p>
                    </div>

                    <!-- Contact Details -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Hubungi Kami</h4>
                        <ul class="space-y-3">
                            <li v-if="cms.contact_phone_display" class="flex items-start gap-3 text-gray-400 group">
                                <div class="mt-0.5 p-1.5 rounded-md bg-gray-800/50 text-green-400 group-hover:bg-green-500/10 group-hover:text-green-300 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" /></svg>
                                </div>
                                <span class="text-sm font-medium group-hover:text-white transition-colors">{{ cms.contact_phone_display }}</span>
                            </li>
                            <li v-if="cms.contact_email" class="flex items-start gap-3 text-gray-400 group">
                                <div class="mt-0.5 p-1.5 rounded-md bg-gray-800/50 text-blue-400 group-hover:bg-blue-500/10 group-hover:text-blue-300 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <span class="text-sm group-hover:text-white transition-colors">{{ cms.contact_email }}</span>
                            </li>
                            <li v-if="cms.contact_address" class="flex items-start gap-3 text-gray-400 group">
                                <div class="mt-0.5 p-1.5 rounded-md bg-gray-800/50 text-red-400 group-hover:bg-red-500/10 group-hover:text-red-300 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" /></svg>
                                </div>
                                <span class="text-sm leading-relaxed group-hover:text-white transition-colors">{{ cms.contact_address }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Middle: Operating Hours & Socials -->
                <div class="lg:col-span-3 space-y-8">
                    <div v-if="operatingHours">
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Jam Operasional</h4>
                        <div class="bg-gray-800/30 rounded-xl p-5 border border-gray-800">
                            <ul class="space-y-3">
                                <li v-for="(hours, day) in operatingHours" :key="day" class="flex justify-between items-center text-sm border-b border-gray-800/50 pb-2 last:border-0 last:pb-0">
                                    <span class="text-gray-400">{{ day }}</span>
                                    <span class="font-medium text-gray-200">{{ hours }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div v-if="socials && socials.length > 0">
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Ikuti Kami</h4>
                        <div class="flex flex-wrap gap-3">
                            <a v-for="social in socials" :key="social.id" :href="social.url" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 bg-gray-800 hover:bg-blue-500 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/20 group text-gray-400 hover:text-white">
                                <span class="text-sm font-medium">{{ social.platform.charAt(0).toUpperCase() }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Interactive Map -->
                <div class="lg:col-span-4" v-if="cms.maps_embed_url">
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Lokasi Kami</h4>
                    <div class="relative group rounded-2xl overflow-hidden border border-gray-700/50 shadow-2xl bg-gray-800 h-[280px]">
                        <!-- Google Maps Iframe -->
                        <iframe
                            :src="cms.maps_embed_url"
                            class="absolute inset-0 w-full h-full border-0 filter grayscale opacity-75 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700"
                            loading="lazy"
                            allowfullscreen=""
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>

                        <!-- Overlay gradient (bottom) -->
                        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-900 to-transparent pointer-events-none"></div>

                        <!-- Location pill overlay -->
                        <div class="absolute bottom-4 left-4 right-4 bg-gray-900/90 backdrop-blur-md px-4 py-3 rounded-xl border border-gray-700/50 flex items-center gap-3 transform translate-y-2 opacity-90 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 shadow-lg">
                            <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 flex-shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            </div>
                            <p class="text-xs text-gray-300 font-medium line-clamp-2">{{ cms.contact_address || 'Kunjungi toko kami' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer: Copyright & Links -->
            <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm text-center md:text-left">
                    &copy; {{ new Date().getFullYear() }} <span class="text-gray-300">{{ cms.company_name || 'Snack Box' }}</span>. All rights reserved.
                </p>
                <div class="flex items-center gap-6 text-sm">
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <span class="w-1 h-1 rounded-full bg-gray-700"></span>
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

// Get CMS data from global Inertia props
const cms = computed(() => page.props.cms?.settings || {});
const socials = computed(() => page.props.cms?.socials || []);

// Parse JSON operating hours
const operatingHours = computed(() => {
    try {
        if (!cms.value.operating_hours) return null;
        return typeof cms.value.operating_hours === 'string'
            ? JSON.parse(cms.value.operating_hours)
            : cms.value.operating_hours;
    } catch (e) {
        return null;
    }
});

// Helper for Initials (Snack Box -> SB)
const getInitials = (name) => {
    if (!name) return 'SB';
    const words = name.split(' ');
    if (words.length >= 2) {
        return (words[0][0] + words[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};
</script>

<style scoped>
/* Smooth transitions */
a {
    transition: color 0.3s ease;
}
</style>
