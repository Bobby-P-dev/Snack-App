<template>
    <Head title="Checkout - Snack Box" />
    <CustomerLayout>
        <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
            <!-- Header -->
            <section class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Checkout Pesanan</h1>
                </div>
            </section>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Form -->
                    <div class="lg:col-span-2">
                        <form @submit.prevent="submitOrder" class="space-y-6">
                            <!-- Personal Info Card -->
                            <div class="bg-white rounded-2xl shadow-sm border border-cream-200/80 overflow-hidden">
                                <div class="bg-gradient-to-r from-brand-50 to-cream-100 px-4 md:px-6 py-4 border-b border-cream-200/80">
                                    <h2 class="font-bold text-brown-900 text-base md:text-lg flex items-center">
                                        <span class="w-6 h-6 bg-brand-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 shadow-2xs">1</span>
                                        Informasi Pemesan
                                    </h2>
                                </div>

                                <div class="px-4 md:px-6 py-6 space-y-4">
                                    <!-- Name -->
                                    <div>
                                        <label class="block text-xs font-bold text-brown-800 mb-1.5">Nama Lengkap</label>
                                        <input
                                            v-model="form.customer_name"
                                            type="text"
                                            required
                                            :class="['w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 transition text-base sm:text-sm bg-cream-50/30', fieldError('customer_name') ? 'border-red-400 bg-red-50' : 'border-cream-300']"
                                            placeholder="Masukkan nama Anda"
                                        />
                                        <p v-if="fieldError('customer_name')" class="text-red-500 text-xs md:text-sm mt-1">{{ fieldError('customer_name') }}</p>
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="block text-xs font-bold text-brown-800 mb-1.5">Nomor WhatsApp</label>
                                        <input
                                            v-model="form.customer_phone"
                                            type="tel"
                                            inputmode="tel"
                                            autocomplete="tel"
                                            required
                                            :class="['w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 transition text-base sm:text-sm bg-cream-50/30', fieldError('customer_phone') ? 'border-red-400 bg-red-50' : 'border-cream-300']"
                                            placeholder="Contoh: 08123456789"
                                        />
                                        <p v-if="fieldError('customer_phone')" class="text-red-500 text-xs md:text-sm mt-1">{{ fieldError('customer_phone') }}</p>
                                        <p class="text-brown-500 text-xs mt-1.5">💬 Konfirmasi pesanan & invoice akan dikirim ke nomor ini</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pickup Info Card -->
                            <div class="bg-white rounded-2xl shadow-sm border border-cream-200/80 overflow-hidden">
                                <div class="bg-gradient-to-r from-brand-50 to-cream-100 px-4 md:px-6 py-4 border-b border-cream-200/80">
                                    <h2 class="font-bold text-brown-900 text-base md:text-lg flex items-center">
                                        <span class="w-6 h-6 bg-brand-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 shadow-2xs">2</span>
                                        Pengambilan / Pengiriman
                                    </h2>
                                </div>

                                <div class="px-4 md:px-6 py-6 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-brown-800 mb-1.5">Pilih Tanggal & Jam</label>
                                        <input
                                            v-model="form.pickup_date"
                                            type="datetime-local"
                                            required
                                            :class="['w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 transition text-base sm:text-sm bg-cream-50/30', fieldError('pickup_date') ? 'border-red-400 bg-red-50' : 'border-cream-300']"
                                        />
                                        <p v-if="fieldError('pickup_date')" class="text-red-500 text-xs md:text-sm mt-1">{{ fieldError('pickup_date') }}</p>
                                        <p class="text-brown-500 text-xs mt-1.5">
                                            ⏰ Pesanan disiapkan segar dari dapur sesuai jadwal yang Anda tentukan
                                        </p>
                                    </div>
                                    
                                    <!-- Metode Pengambilan / Pengiriman -->
                                    <div>
                                        <label class="block text-xs font-bold text-brown-800 mb-1.5">
                                            Metode Pengambilan / Pengiriman
                                        </label>
                                        <div class="grid grid-cols-2 gap-2.5 mb-2.5">
                                            <button
                                                type="button"
                                                @click="deliveryMethod = 'pickup'"
                                                :class="[
                                                    'p-3 rounded-xl border text-left transition flex items-center gap-3 cursor-pointer',
                                                    deliveryMethod === 'pickup'
                                                        ? 'border-brand-500 bg-brand-50/70 text-brand-900 ring-2 ring-brand-500/15'
                                                        : 'border-cream-300 bg-white hover:bg-cream-50 text-brown-700'
                                                ]"
                                            >
                                                <div :class="['w-8 h-8 rounded-lg flex items-center justify-center shrink-0', deliveryMethod === 'pickup' ? 'bg-brand-500 text-white' : 'bg-cream-100 text-brown-600']">
                                                    <Store class="w-4 h-4" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs sm:text-sm font-bold leading-tight">Ambil di Toko</p>
                                                    <p class="text-[11px] text-brown-500 leading-tight truncate">Ambil langsung ke lokasi kami</p>
                                                </div>
                                            </button>

                                            <button
                                                type="button"
                                                @click="deliveryMethod = 'delivery'"
                                                :class="[
                                                    'p-3 rounded-xl border text-left transition flex items-center gap-3 cursor-pointer',
                                                    deliveryMethod === 'delivery'
                                                        ? 'border-brand-500 bg-brand-50/70 text-brand-900 ring-2 ring-brand-500/15'
                                                        : 'border-cream-300 bg-white hover:bg-cream-50 text-brown-700'
                                                ]"
                                            >
                                                <div :class="['w-8 h-8 rounded-lg flex items-center justify-center shrink-0', deliveryMethod === 'delivery' ? 'bg-brand-500 text-white' : 'bg-cream-100 text-brown-600']">
                                                    <Truck class="w-4 h-4" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs sm:text-sm font-bold leading-tight">Diantar ke Lokasi</p>
                                                    <p class="text-[11px] text-brown-500 leading-tight truncate">Kirim ke alamat acara Anda</p>
                                                </div>
                                            </button>
                                        </div>

                                        <!-- If pickup: show store address info banner -->
                                        <div
                                            v-if="deliveryMethod === 'pickup'"
                                            class="bg-amber-50/90 border border-amber-200 rounded-xl p-3.5 text-xs sm:text-sm space-y-1.5"
                                        >
                                            <div class="flex items-center gap-2 font-bold text-amber-900">
                                                <MapPin class="w-4 h-4 text-amber-700 shrink-0" />
                                                <span>Lokasi Tempat Usaha:</span>
                                            </div>
                                            <p class="text-brown-700 font-medium pl-6 leading-relaxed">
                                                {{ storeAddress }}
                                            </p>
                                            <p class="text-[11px] text-amber-800/80 pl-6 pt-0.5">
                                                💡 Pesanan disiapkan dan dapat diambil langsung sesuai jadwal yang Anda tentukan di atas.
                                            </p>
                                        </div>

                                        <!-- If delivery: show custom location textarea -->
                                        <div v-else>
                                            <textarea
                                                v-model="customLocation"
                                                rows="3"
                                                :class="['w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 transition text-base sm:text-sm bg-cream-50/30 resize-none', fieldError('location') ? 'border-red-400 bg-red-50' : 'border-cream-300']"
                                                placeholder="Masukkan alamat lengkap pengiriman atau lokasi acara Anda..."
                                            ></textarea>
                                            <p v-if="fieldError('location')" class="text-red-500 text-xs md:text-sm mt-1">{{ fieldError('location') }}</p>
                                            <p class="text-brown-500 text-xs mt-1">
                                                🛵 Pesanan akan diantar langsung oleh kurir ke alamat yang Anda tuliskan.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Notes (Optional) -->
                                    <div>
                                        <label class="block text-xs font-bold text-brown-800 mb-1.5">Catatan Pesanan (Opsional)</label>
                                        <textarea
                                            v-model="form.notes"
                                            rows="2"
                                            :class="['w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 transition text-base sm:text-sm bg-cream-50/30 resize-none', fieldError('notes') ? 'border-red-400 bg-red-50' : 'border-cream-300']"
                                            placeholder="Misal: pita tulisan ucapan atau permintaan khusus lainnya"
                                        ></textarea>
                                        <p v-if="fieldError('notes')" class="text-red-500 text-xs md:text-sm mt-1">{{ fieldError('notes') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Card -->
                            <div class="bg-white rounded-2xl shadow-sm border border-cream-200/80 overflow-hidden">
                                <div class="bg-gradient-to-r from-brand-50 to-cream-100 px-4 md:px-6 py-4 border-b border-cream-200/80">
                                    <h2 class="font-bold text-brown-900 text-base md:text-lg flex items-center">
                                        <span class="w-6 h-6 bg-brand-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 shadow-2xs">3</span>
                                        Rincian Pesanan ({{ cart.length }} Item)
                                    </h2>
                                </div>

                                <div class="px-4 md:px-6 py-4 space-y-4 max-h-80 overflow-y-auto">
                                    <!-- Kategori 1: Paket Snack Box -->
                                    <div v-if="snackBoxItems.length > 0" class="space-y-2">
                                        <div class="flex items-center justify-between pb-1.5 border-b border-cream-200">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-md bg-amber-100 text-amber-800 flex items-center justify-center">
                                                    <Package class="w-3.5 h-3.5 text-amber-800" />
                                                </div>
                                                <span class="text-xs sm:text-sm font-bold text-brown-900">Paket Snack Box (Custom)</span>
                                            </div>
                                            <span class="text-xs font-bold text-brand-700 bg-brand-50 border border-brand-200 px-2 py-0.5 rounded-full font-mono">
                                                Rp {{ formatNumber(snackBoxSubtotal) }}
                                            </span>
                                        </div>

                                        <div class="space-y-3">
                                            <div
                                                v-for="box in groupedSnackBoxes"
                                                :key="box.box_group_id || 'default'"
                                                class="p-3 bg-amber-50/50 border border-amber-200/70 rounded-xl space-y-2"
                                            >
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-bold text-brown-900">Paket Snack Box ({{ box.boxQty }} Box)</span>
                                                        <span
                                                            :class="[
                                                                'text-[10px] font-bold px-1.5 py-0.5 rounded',
                                                                [3, 4, 5].includes(box.items.length)
                                                                    ? 'bg-amber-100 text-amber-900'
                                                                    : 'bg-rose-100 text-rose-700 font-extrabold'
                                                            ]"
                                                        >
                                                            {{ [3, 4, 5].includes(box.items.length) ? `${box.items.length} Kue/Box` : `⚠️ Tidak Lengkap (${box.items.length} Kue)` }}
                                                        </span>
                                                    </div>
                                                    <span class="text-xs font-bold text-brand-600 font-mono">Rp {{ formatNumber(box.subtotal) }}</span>
                                                </div>
                                                <div class="pl-2 border-l-2 border-amber-200 space-y-1">
                                                    <p v-for="it in box.items" :key="it.product_id" class="text-xs text-brown-600 flex justify-between">
                                                        <span>• {{ it.product?.name || 'Kue' }}</span>
                                                        <span class="text-brown-400 font-mono text-[11px]">@ Rp {{ formatNumber(it.product?.sell_price || 0) }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kategori 2: Kue Satuan -->
                                    <div v-if="satuanItems.length > 0" :class="['space-y-2', snackBoxItems.length > 0 ? 'pt-3 border-t border-cream-200' : '']">
                                        <div class="flex items-center justify-between pb-1.5 border-b border-cream-200">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-md bg-rose-100 text-rose-800 flex items-center justify-center">
                                                    <CakeSlice class="w-3.5 h-3.5 text-rose-800" />
                                                </div>
                                                <span class="text-xs sm:text-sm font-bold text-brown-900">Kue Satuan & Eceran</span>
                                            </div>
                                            <span class="text-xs font-bold text-brand-700 bg-brand-50 border border-brand-200 px-2 py-0.5 rounded-full font-mono">
                                                Rp {{ formatNumber(satuanSubtotal) }}
                                            </span>
                                        </div>

                                        <div class="divide-y divide-cream-100">
                                            <div
                                                v-for="item in satuanItems"
                                                :key="item.product_id"
                                                class="py-2.5 flex justify-between items-center text-xs sm:text-sm"
                                            >
                                                <div class="min-w-0 pr-2">
                                                    <div class="flex items-center gap-1.5">
                                                        <p class="font-bold text-brown-900 truncate">{{ item.product?.name || 'Produk' }}</p>
                                                        <span class="inline-flex text-[9px] font-semibold text-rose-800 bg-rose-100 px-1.5 py-0.5 rounded">Satuan</span>
                                                    </div>
                                                    <p class="text-brown-500 text-xs mt-0.5">{{ item.quantity }} pcs @ Rp {{ formatNumber(item.product?.sell_price || 0) }}</p>
                                                </div>
                                                <p class="font-bold text-brand-600 font-mono flex-shrink-0">Rp {{ formatNumber(item.quantity * (item.product?.sell_price || 0)) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="bg-cream-50 border border-cream-200 rounded-2xl p-4 md:p-6">
                                <label class="flex items-start space-x-3 cursor-pointer">
                                    <input
                                        v-model="form.terms_agreed"
                                        type="checkbox"
                                        required
                                        class="mt-1 w-4 h-4 accent-brand-600 rounded cursor-pointer"
                                    />
                                    <span class="text-xs sm:text-sm text-brown-700 leading-relaxed">
                                        Saya setuju untuk membayar DP sesuai instruksi WhatsApp dan saldo akhir saat pengambilan/pengiriman.
                                    </span>
                                </label>
                            </div>

                            <!-- Incomplete Snack Box Warning Banner -->
                            <div v-if="hasIncompleteBox" class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs flex items-start gap-2.5">
                                <AlertCircle class="w-4 h-4 shrink-0 mt-0.5 text-rose-500" />
                                <div>
                                    <p class="font-bold text-rose-900">Paket Snack Box Belum Sesuai</p>
                                    <p class="mt-0.5 text-rose-600 leading-relaxed">Ada paket snack box yang belum sesuai kapasitas paket (wajib 3, 4, atau 5 macam kue). Silakan kembali ke keranjang untuk menyesuaikan isian box.</p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="loading || hasIncompleteBox"
                                :class="[
                                    'w-full py-4 px-6 rounded-2xl font-bold text-base md:text-lg transition flex items-center justify-center gap-2',
                                    hasIncompleteBox
                                        ? 'bg-slate-200 text-slate-400 cursor-not-allowed shadow-none'
                                        : 'bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white shadow-lg shadow-emerald-600/20 cursor-pointer disabled:bg-gray-400'
                                ]"
                            >
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z" />
                                </svg>
                                <span v-if="!loading">Lanjutkan ke WhatsApp</span>
                                <span v-else>Memproses Pesanan...</span>
                            </button>

                            <!-- Back to Cart -->
                            <Link
                                href="/cart"
                                class="inline-flex items-center justify-center gap-1.5 w-full text-center text-brown-600 hover:text-brand-600 font-semibold text-sm transition"
                            >
                                <ArrowLeft class="w-4 h-4" />
                                Kembali ke Keranjang
                            </Link>
                        </form>
                    </div>

                    <!-- Summary Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm border border-cream-200/80 overflow-hidden sticky top-24">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-brand-50 to-cream-100 px-4 md:px-6 py-4 border-b border-cream-200/80">
                                <h2 class="font-bold text-brown-900 text-base md:text-lg">Total Pembayaran</h2>
                            </div>

                            <!-- Summary Content -->
                            <div class="px-4 md:px-6 py-6 space-y-4">
                                <!-- Breakdown -->
                                <div class="space-y-2.5 pb-4 border-b border-cream-100">
                                    <!-- Category Subtotals if both exist -->
                                    <div v-if="hasBothCategories" class="space-y-1.5 pb-2.5 border-b border-cream-200/70 text-xs">
                                        <div class="flex justify-between text-brown-600">
                                            <span class="flex items-center gap-1.5 font-medium"><Package class="w-3.5 h-3.5 text-amber-700" /> Subtotal Snack Box</span>
                                            <span class="font-bold text-brown-800 font-mono">Rp {{ formatNumber(snackBoxSubtotal) }}</span>
                                        </div>
                                        <div class="flex justify-between text-brown-600">
                                            <span class="flex items-center gap-1.5 font-medium"><CakeSlice class="w-3.5 h-3.5 text-rose-700" /> Subtotal Kue Satuan</span>
                                            <span class="font-bold text-brown-800 font-mono">Rp {{ formatNumber(satuanSubtotal) }}</span>
                                        </div>
                                    </div>

                                    <div class="flex justify-between text-sm">
                                        <span class="text-brown-600">Total Subtotal</span>
                                        <span class="font-bold text-brown-900 font-mono">Rp {{ formatNumber(subtotal) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-brown-600">Pajak / Biaya</span>
                                        <span class="font-bold text-emerald-600 font-mono">Gratis</span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div>
                                    <p class="text-brown-600 text-xs mb-1">Total Tagihan</p>
                                    <p class="text-2xl md:text-3xl font-black text-brand-600 font-mono">
                                        Rp {{ formatNumber(total) }}
                                    </p>
                                </div>

                                <!-- Opsi Pembayaran (DP vs Full) -->
                                <div class="border-t border-cream-200 pt-4 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <label class="text-xs font-bold text-brown-800 uppercase tracking-wider">Opsi Pembayaran</label>
                                        <span class="text-[11px] font-semibold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-full">
                                            {{ paymentType === 'full' ? 'Bayar Penuh (100%)' : `DP (${dpPercentage}%)` }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2.5">
                                        <!-- Opsi DP 70% -->
                                        <button
                                            type="button"
                                            @click="setPaymentOption('dp')"
                                            :class="[
                                                'p-3 rounded-2xl border text-left transition-all cursor-pointer relative',
                                                paymentType === 'dp'
                                                    ? 'border-brand-500 bg-brand-50/70 shadow-xs ring-2 ring-brand-500/15'
                                                    : 'border-cream-200 bg-white hover:bg-cream-50/50 text-brown-700'
                                            ]"
                                        >
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-bold text-brown-900">DP {{ dpPercentage }}%</span>
                                                <span v-if="paymentType === 'dp'" class="w-4 h-4 rounded-full bg-brand-500 text-white flex items-center justify-center">
                                                    <Check class="w-2.5 h-2.5" />
                                                </span>
                                            </div>
                                            <p class="text-xs font-black text-brand-600 font-mono">Rp {{ formatNumber(dp) }}</p>
                                            <p class="text-[10px] text-brown-500 mt-1 leading-tight">Sisa saat ambil</p>
                                        </button>

                                        <!-- Opsi Bayar Full (100%) -->
                                        <button
                                            type="button"
                                            @click="setPaymentOption('full')"
                                            :class="[
                                                'p-3 rounded-2xl border text-left transition-all cursor-pointer relative',
                                                paymentType === 'full'
                                                    ? 'border-emerald-500 bg-emerald-50/70 shadow-xs ring-2 ring-emerald-500/15'
                                                    : 'border-cream-200 bg-white hover:bg-cream-50/50 text-brown-700'
                                            ]"
                                        >
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-bold text-brown-900">Bayar Full</span>
                                                <span v-if="paymentType === 'full'" class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center">
                                                    <Check class="w-2.5 h-2.5" />
                                                </span>
                                            </div>
                                            <p class="text-xs font-black text-emerald-600 font-mono">Rp {{ formatNumber(total) }}</p>
                                            <p class="text-[10px] text-emerald-600 font-medium mt-1 leading-tight">Lunas langsung (100%)</p>
                                        </button>
                                    </div>
                                </div>

                                <!-- Payment Breakdown Details -->
                                <div 
                                    v-if="paymentType === 'dp'"
                                    class="bg-amber-50/80 border border-amber-200 rounded-xl p-4 space-y-1.5"
                                >
                                    <div class="flex justify-between text-xs sm:text-sm text-brown-900 font-semibold">
                                        <span>Uang Muka / DP ({{ dpPercentage }}%):</span>
                                        <span class="text-brand-600 font-bold font-mono">Rp {{ formatNumber(dp) }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs text-brown-600 border-t border-dashed border-amber-200/80 pt-1.5 font-medium">
                                        <span>Sisa Pelunasan Saat Ambil:</span>
                                        <span class="font-bold font-mono text-brown-800">Rp {{ formatNumber(remainingPayment) }}</span>
                                    </div>
                                    <p class="text-[11px] text-brown-500 mt-1 leading-relaxed">
                                        💡 Transfer DP via konfirmasi WhatsApp, sisa pelunasan saat kue diambil/dikirim.
                                    </p>
                                </div>
                                <div 
                                    v-else
                                    class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-4 space-y-1.5"
                                >
                                    <div class="flex justify-between text-xs sm:text-sm text-emerald-900 font-semibold">
                                        <span>Pembayaran Penuh (100%):</span>
                                        <span class="text-emerald-700 font-bold font-mono">Rp {{ formatNumber(total) }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs text-emerald-700 border-t border-dashed border-emerald-200/80 pt-1.5 font-medium">
                                        <span>Sisa Tagihan Saat Ambil:</span>
                                        <span class="font-bold font-mono text-emerald-800">Rp 0 (LUNAS)</span>
                                    </div>
                                    <p class="text-[11px] text-emerald-600 mt-1 leading-relaxed">
                                        ✨ Bebas repot! Langsung bayar penuh saat konfirmasi, tanpa tanggungan pelunasan lagi.
                                    </p>
                                </div>

                                <!-- Payment Method -->
                                <div class="bg-emerald-50/60 border border-emerald-200/70 rounded-xl p-4">
                                    <p class="text-xs font-bold text-emerald-900 mb-1.5">Metode Konfirmasi & Pembayaran</p>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z" />
                                        </svg>
                                        <span class="text-xs sm:text-sm font-bold text-emerald-900">Direct WhatsApp Admin</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>

    <!-- Validation Error Modal -->
    <Modal
        v-model="validationModal"
        variant="warning"
        title="Lengkapi Data Berikut"
        confirmText="Mengerti"
    >
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-2">
            <ul class="space-y-2">
                <li
                    v-for="(err, idx) in validationErrors"
                    :key="idx"
                    class="flex items-start gap-2 text-sm text-amber-800"
                >
                    <AlertTriangle class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" />
                    <span>{{ err }}</span>
                </li>
            </ul>
        </div>
    </Modal>
</template>

<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Modal from '@/Components/UI/Modal.vue';
import { ArrowLeft, AlertTriangle, AlertCircle, Check, Package, CakeSlice, Store, MapPin, Truck } from 'lucide-vue-next';
import { useCartStore } from '@/Stores/CartStore.js';

const props = defineProps({
    cart: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const cms = computed(() => page.props.cms?.settings || {});
const dpPercentage = computed(() => parseInt(cms.value.dp_percentage) || 70);

const deliveryMethod = ref('pickup'); // 'pickup' | 'delivery'
const customLocation = ref('');

const storeAddress = computed(() => {
    return cms.value.company_address || cms.value.contact_address || 'Jl. Boulevard Raya No. 88, Bekasi, Jawa Barat 17144';
});

const storeName = computed(() => {
    return cms.value.company_name || 'Padu Kue';
});

const resolvedLocation = computed(() => {
    if (deliveryMethod.value === 'pickup') {
        return `Ambil di Tempat (${storeName.value}: ${storeAddress.value})`;
    }
    return customLocation.value.trim();
});

const { paymentType: storePaymentType, setPaymentType: setStorePaymentType } = useCartStore();

// Read payment_type from URL query param if present, or fallback to store, or default 'dp'
const getInitialPaymentType = () => {
    if (typeof window !== 'undefined') {
        const urlParams = new URLSearchParams(window.location.search);
        const queryType = urlParams.get('payment_type');
        if (queryType === 'full' || queryType === 'dp') {
            return queryType;
        }
    }
    return storePaymentType.value || 'dp';
};

const paymentType = ref(getInitialPaymentType());
setStorePaymentType(paymentType.value);

const setPaymentOption = (type) => {
    paymentType.value = type;
    setStorePaymentType(type);
    form.value.payment_type = type;
};

const cart = ref(props.cart);
const loading = ref(false);
const errors = ref({});
const validationModal = ref(false);
const validationErrors = ref([]);

const form = ref({
    customer_name: '',
    customer_phone: '',
    pickup_date: '',
    location: '',
    notes: '',
    items: props.cart,
    payment_type: paymentType.value,
    terms_agreed: false,
});

// Category separation
const snackBoxItems = computed(() => (cart.value || []).filter(item => item.type === 'kustom_box'));
const satuanItems = computed(() => (cart.value || []).filter(item => (item.type || 'satuan') !== 'kustom_box'));
const hasBothCategories = computed(() => snackBoxItems.value.length > 0 && satuanItems.value.length > 0);

// Group snack box items by box_group_id so each box is an atomic package
const groupedSnackBoxes = computed(() => {
    const groups = {};
    snackBoxItems.value.forEach((item) => {
        const gid = item.box_group_id || 'default';
        if (!groups[gid]) {
            groups[gid] = {
                box_group_id: item.box_group_id,
                boxQty: item.quantity || 1,
                items: [],
                subtotal: 0,
            };
        }
        groups[gid].items.push(item);
        const itemPrice = item?.product?.sell_price || 0;
        const itemQty = item?.quantity || 1;
        groups[gid].subtotal += itemPrice * itemQty;
    });
    return Object.values(groups).map((g) => ({
        ...g,
        pricePerBox: g.boxQty > 0 ? Math.round(g.subtotal / g.boxQty) : g.subtotal,
    }));
});

const hasIncompleteBox = computed(() => {
    return groupedSnackBoxes.value.some(g => ![3, 4, 5].includes(g.items.length));
});

const snackBoxSubtotal = computed(() => {
    return snackBoxItems.value.reduce((sum, item) => sum + (item.quantity * (item.product?.sell_price || 0)), 0);
});

const satuanSubtotal = computed(() => {
    return satuanItems.value.reduce((sum, item) => sum + (item.quantity * (item.product?.sell_price || 0)), 0);
});

// Calculate totals
const subtotal = computed(() => {
    return cart.value.reduce((sum, item) => sum + (item.quantity * (item.product?.sell_price || 0)), 0);
});

const total = computed(() => {
    return subtotal.value;
});

const dp = computed(() => {
    return Math.round(total.value * (dpPercentage.value / 100));
});

const remainingPayment = computed(() => {
    if (paymentType.value === 'full') {
        return 0;
    }
    return Math.max(0, total.value - dp.value);
});

// Check if a field has an error
const fieldError = (field) => errors.value[field] ? errors.value[field][0] : null

// Format number to IDR
const formatNumber = (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
};

// Submit order
const submitOrder = () => {
    // Client-side validation check
    validationErrors.value = [];
    if (hasIncompleteBox.value) {
        validationErrors.value.push("Ada paket snack box yang isian kuenya tidak lengkap (wajib 3, 4, atau 5 macam kue). Silakan sesuaikan di keranjang.");
    }
    if (!form.value.customer_name.trim()) validationErrors.value.push("Nama Lengkap belum diisi.");
    if (!form.value.customer_phone.trim()) validationErrors.value.push("Nomor WhatsApp belum diisi.");
    if (!form.value.pickup_date) validationErrors.value.push("Tanggal & Jam pengambilan belum dipilih.");

    if (deliveryMethod.value === 'delivery' && !customLocation.value.trim()) {
        validationErrors.value.push("Alamat / Lokasi pengiriman belum diisi.");
    }

    form.value.location = resolvedLocation.value;
    if (!form.value.location.trim()) {
        validationErrors.value.push("Lokasi pengambilan / pengiriman belum ditentukan.");
    }

    if (!form.value.terms_agreed) validationErrors.value.push("Anda harus menyetujui syarat & ketentuan.");

    if (validationErrors.value.length > 0) {
        validationModal.value = true;
        return;
    }

    loading.value = true;
    errors.value = {};

    router.post('/checkout', form.value, {
        onError: (errs) => {
            errors.value = errs;
            validationErrors.value = Object.values(errs).flat();
            validationModal.value = true;
            loading.value = false;
        },
        onSuccess: () => {
            loading.value = false;
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};
</script>

<style scoped>
/* Smooth transitions */
button, input, label {
    transition: all 0.3s ease;
}
</style>
