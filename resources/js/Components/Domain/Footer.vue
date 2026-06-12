<template>
    <!-- ─── FOOTER ─── -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Left: Logo & Description -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                        {{ getInitials(cms.company_name || 'Snack Box') }}
                    </div>
                    <span class="text-xl font-bold">{{ cms.company_name || 'Snack Box' }}</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">{{ cms.company_description || 'Mitra terpercaya untuk snack box custom dan kue satuan.' }}</p>

                <!-- Contact Info -->
                <div class="mb-6">
                    <h4 class="font-semibold text-lg mb-3">Kontak</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li v-if="cms.contact_email" class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>{{ cms.contact_email }}</span>
                        </li>
                        <li v-if="cms.contact_phone_display" class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" /></svg>
                            <span>{{ cms.contact_phone_display }}</span>
                        </li>
                        <li v-if="cms.contact_address" class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" /></svg>
                            <span>{{ cms.contact_address }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div v-if="socials.length > 0" class="mb-6">
                    <h4 class="font-semibold text-lg mb-3">Sosial Media</h4>
                    <div class="flex gap-3">
                        <a v-for="social in socials" :key="social.id" :href="social.url" target="_blank" rel="noopener noreferrer" class="w-8 h-8 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center transition-colors">
                            <span class="text-xs">{{ social.platform.charAt(0) }}</span>
                        </a>
                    </div>
                </div>

                <!-- Operating Hours -->
                <div v-if="operatingHours">
                    <h4 class="font-semibold text-lg mb-3">Jam Operasional</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li v-for="(hours, day) in operatingHours" :key="day">📅 {{ day }}: {{ hours }}</li>
                    </ul>
                </div>
            </div>

            <!-- Right: Maps Embed (smaller & elegant) -->
            <div v-if="cms.maps_embed_url" class="rounded-xl overflow-hidden shadow-lg border border-gray-800">
                <div class="h-48 md:h-56">
                    <iframe
                        width="100%"
                        height="100%"
                        style="border:0"
                        loading="lazy"
                        allowfullscreen=""
                        referrerpolicy="no-referrer-when-downgrade"
                        :src="cms.maps_embed_url"
                        class="grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-500"
                    ></iframe>
                </div>
                <div class="px-4 py-3 bg-gray-800/80 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    <span class="text-gray-300 text-xs font-medium">{{ cms.contact_address || 'Jakarta, Indonesia' }}</span>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-800 mt-6 pt-6"></div>

        <!-- Bottom: Copyright & Quick Links -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ new Date().getFullYear() }} {{ cms.company_name || 'Snack Box' }}. All rights reserved.</p>
                <div class="flex gap-4 text-gray-400 text-sm">
                    <a href="#" class="hover:text-blue-400 transition">Kebijakan Privasi</a>
                    <span class="text-gray-700">•</span>
                    <a href="#" class="hover:text-blue-400 transition">Syarat & Ketentuan</a>
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
