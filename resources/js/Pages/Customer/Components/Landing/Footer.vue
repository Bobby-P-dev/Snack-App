<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { Instagram, Facebook, Phone, Mail, MapPin } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});
</script>

<template>
    <footer class="bg-cream-100 pt-16 pb-10 border-t border-cream-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8 lg:gap-10 mb-14">
                <div class="lg:col-span-2">
                    <div class="mb-5">
                        <Link href="/" class="inline-block group">
                            <img src="/images/padukue-logo.png" :alt="cms.company_name || 'Padu Kue'" class="h-24 sm:h-28 w-auto object-contain group-hover:scale-105 transition-transform" />
                        </Link>
                    </div>
                    <p class="text-brown-600 text-sm mb-6 max-w-sm leading-relaxed">
                        {{ cms.company_description || 'Mitra terpercaya untuk custom snack box dan aneka kue berkualitas. Kami merakit hidangan terbaik untuk melengkapi setiap momen spesial Anda.' }}
                    </p>
                    <div class="flex items-center gap-4">
                        <a v-if="cms.social_instagram" :href="cms.social_instagram" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white border border-cream-200 flex items-center justify-center text-brown-700 hover:text-brand-600 hover:border-brand-300 transition-colors shadow-2xs" aria-label="Instagram">
                            <Instagram class="w-4 h-4" />
                        </a>
                        <a v-if="cms.social_facebook" :href="cms.social_facebook" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white border border-cream-200 flex items-center justify-center text-brown-700 hover:text-brand-600 hover:border-brand-300 transition-colors shadow-2xs" aria-label="Facebook">
                            <Facebook class="w-4 h-4" />
                        </a>
                        <a :href="`https://wa.me/${cms.contact_phone}`" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white border border-cream-200 flex items-center justify-center text-brown-700 hover:text-emerald-600 hover:border-emerald-300 transition-colors shadow-2xs" aria-label="WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" /></svg>
                        </a>
                        <a v-if="cms.social_tiktok" :href="cms.social_tiktok" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white border border-cream-200 flex items-center justify-center text-brown-700 hover:text-brand-600 hover:border-brand-300 transition-colors shadow-2xs" aria-label="TikTok">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 15.68a6.34 6.34 0 0 0 6.26 6.36 6.33 6.33 0 0 0 6.34-6.36v-6.23a8.16 8.16 0 0 0 4.7 1.5v-3.4a4.85 4.85 0 0 1-2.71-.86z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Menu Toko -->
                <div>
                    <h4 class="font-bold text-brown-900 text-sm mb-4 tracking-tight">Menu Kami</h4>
                    <ul class="space-y-2.5">
                        <li><Link href="/snack-box" class="text-brown-600 hover:text-brand-600 transition text-sm">Custom Snack Box</Link></li>
                        <li><Link href="/shop" class="text-brown-600 hover:text-brand-600 transition text-sm">Kue Satuan & Donat</Link></li>
                        <li><Link href="/shop" class="text-brown-600 hover:text-brand-600 transition text-sm">Paket Rapat & Acara</Link></li>
                        <li><Link href="/tracking" class="text-brown-600 hover:text-brand-600 transition text-sm">Lacak Status Pesanan</Link></li>
                    </ul>
                </div>

                <!-- Column 3: Layanan -->
                <div>
                    <h4 class="font-bold text-brown-900 text-sm mb-4 tracking-tight">Layanan</h4>
                    <ul class="space-y-2.5">
                        <li><Link href="/" class="text-brown-600 hover:text-brand-600 transition text-sm">Beranda</Link></li>
                        <li><Link href="/snack-box" class="text-brown-600 hover:text-brand-600 transition text-sm">Rakit Snack Box</Link></li>
                        <li><Link href="/cart" class="text-brown-600 hover:text-brand-600 transition text-sm">Keranjang Pesanan</Link></li>
                        <li><Link href="/checkout" class="text-brown-600 hover:text-brand-600 transition text-sm">Checkout Cepat</Link></li>
                    </ul>
                </div>

                <!-- Column 4: Dukungan Pelanggan -->
                <div>
                    <h4 class="font-bold text-brown-900 text-sm mb-4 tracking-tight">Bantuan</h4>
                    <ul class="space-y-2.5">
                        <li><a :href="`https://wa.me/${cms.contact_phone}`" target="_blank" class="text-brown-600 hover:text-brand-600 transition text-sm">Konsultasi Acara</a></li>
                        <li><Link href="/tracking" class="text-brown-600 hover:text-brand-600 transition text-sm">Konfirmasi DP</Link></li>
                        <li><span class="text-brown-500 text-xs italic">Senin - Sabtu: 07.30 - 17.30</span></li>
                    </ul>
                </div>

                <!-- Column 5: Kontak -->
                <div>
                    <h4 class="font-bold text-brown-900 text-sm mb-4 tracking-tight whitespace-nowrap">Kontak Kami</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2.5">
                            <Phone class="w-4 h-4 text-brand-500 shrink-0 mt-0.5" />
                            <span class="text-brown-600 text-xs sm:text-sm">{{ cms.contact_phone_display || cms.contact_phone || '0812-3456-7890' }}</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <Mail class="w-4 h-4 text-brand-500 shrink-0 mt-0.5" />
                            <span class="text-brown-600 text-xs sm:text-sm">{{ cms.contact_email || 'halo@padukue.store' }}</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <MapPin class="w-4 h-4 text-brand-500 shrink-0 mt-0.5" />
                            <span class="text-brown-600 text-xs sm:text-sm leading-relaxed">{{ cms.company_address || cms.contact_address || 'Bekasi, Jawa Barat' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-cream-200 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <p class="text-brown-500 text-xs">
                    &copy; {{ new Date().getFullYear() }} <span class="font-semibold text-brown-700">{{ cms.company_name || 'Padu Kue' }}</span>. All rights reserved.
                </p>
                <p class="text-brown-500 text-xs">
                    Sajian kue, donat, dan snack box custom untuk momen berharga Anda.
                </p>
            </div>
        </div>
    </footer>
</template>
