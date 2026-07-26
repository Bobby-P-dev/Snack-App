<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { Instagram, Facebook, Phone, Mail, MapPin } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});
</script>

<template>
    <footer class="bg-slate-50 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8 lg:gap-12 mb-16">
                <!-- Column 1: Brand & Desc (Spans 2 columns) -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                            SB
                        </div>
                        <span class="text-xl font-bold text-gray-900">{{ cms.company_name || 'Snack Box' }}</span>
                    </div>
                    <p class="text-gray-500 mb-8 max-w-sm leading-relaxed">
                        Snack box premium untuk meeting, seminar, dan acara spesial Anda. Fresh, halal, dan dikirim tepat waktu.
                    </p>
                    <div class="flex gap-4">
                        <a v-if="cms.social_instagram" :href="cms.social_instagram" target="_blank" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                            <Instagram class="w-5 h-5" />
                        </a>
                        <a v-if="cms.social_facebook" :href="cms.social_facebook" target="_blank" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                            <Facebook class="w-5 h-5" />
                        </a>
                        <!-- Generic WhatsApp icon -->
                        <a :href="`https://wa.me/${cms.contact_phone}`" target="_blank" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-green-50 hover:text-green-600 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" /></svg>
                        </a>
                        <!-- Custom TikTok icon SVG -->
                        <a v-if="cms.social_tiktok" :href="cms.social_tiktok" target="_blank" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 15.68a6.34 6.34 0 0 0 6.26 6.36 6.33 6.33 0 0 0 6.34-6.36v-6.23a8.16 8.16 0 0 0 4.7 1.5v-3.4a4.85 4.85 0 0 1-2.71-.86z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Navigasi -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-6">Navigasi</h4>
                    <ul class="space-y-4">
                        <li><Link href="/" class="text-gray-500 hover:text-blue-600 transition">Beranda</Link></li>
                        <li><Link href="/shop" class="text-gray-500 hover:text-blue-600 transition">Shop</Link></li>
                        <li><Link href="/tracking" class="text-gray-500 hover:text-blue-600 transition">Tracking</Link></li>
                        <li><Link href="/about" class="text-gray-500 hover:text-blue-600 transition">Tentang Kami</Link></li>
                    </ul>
                </div>

                <!-- Column 3: Kategori -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-6">Kategori</h4>
                    <ul class="space-y-4">
                        <li><Link href="/shop?category=snack-box" class="text-gray-500 hover:text-blue-600 transition">Snack Box</Link></li>
                        <li><Link href="/shop?category=kue-satuan" class="text-gray-500 hover:text-blue-600 transition">Kue Satuan</Link></li>
                        <li><Link href="/shop?category=minuman" class="text-gray-500 hover:text-blue-600 transition">Minuman</Link></li>
                        <li><Link href="/shop?category=paket-meeting" class="text-gray-500 hover:text-blue-600 transition">Paket Meeting</Link></li>
                    </ul>
                </div>

                <!-- Column 4: Bantuan -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-6">Bantuan</h4>
                    <ul class="space-y-4">
                        <li><Link href="/faq" class="text-gray-500 hover:text-blue-600 transition">FAQ</Link></li>
                        <li><Link href="/how-to-order" class="text-gray-500 hover:text-blue-600 transition">Cara Order</Link></li>
                        <li><Link href="/privacy" class="text-gray-500 hover:text-blue-600 transition">Kebijakan Privasi</Link></li>
                        <li><Link href="/terms" class="text-gray-500 hover:text-blue-600 transition">Syarat & Ketentuan</Link></li>
                    </ul>
                </div>

                <!-- Column 5: Kontak -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-6 whitespace-nowrap">Kontak Kami</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <Phone class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" />
                            <span class="text-gray-500 text-sm md:text-base">{{ cms.contact_phone || '0812-3456-7890' }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <Mail class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" />
                            <span class="text-gray-500 text-sm md:text-base">{{ cms.contact_email || 'hello@padukue.id' }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <MapPin class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" />
                            <span class="text-gray-500 text-sm md:text-base">{{ cms.company_address || 'Bekasi, Indonesia' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row items-center justify-center text-center">
                <p class="text-gray-500 text-sm">
                    &copy; {{ new Date().getFullYear() }} {{ cms.company_name || 'Padukue Snack Box' }}. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>
