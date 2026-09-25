<template>
    <div class="flex flex-col min-h-screen bg-cream-100">
        <!-- Sticky Navbar with Soft Bakery Styling -->
        <nav class="bg-white/95 backdrop-blur-md border-b border-cream-200/80 sticky top-0 z-50 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20">
                    <Link href="/" class="flex items-center group py-1">
                        <img
                            v-if="!cms.company_name || cms.company_name === 'Padu Kue'"
                            src="/images/padukue-horizontal.png"
                            alt="Padu Kue"
                            class="h-11 sm:h-13 md:h-14 w-auto object-contain group-hover:scale-105 transition-transform"
                        />
                        <div v-else class="flex items-center gap-3">
                            <img
                                src="/images/padukue-icon.png"
                                :alt="cms.company_name"
                                class="w-12 h-12 sm:w-14 sm:h-14 object-contain group-hover:scale-105 transition-transform"
                            />
                            <div class="flex flex-col">
                                <span class="text-xl sm:text-2xl font-black text-brown-800 tracking-tight group-hover:text-brand-600 transition-colors leading-none">
                                    {{ cms.company_name }}
                                </span>
                                <span class="text-[11px] font-semibold text-brand-600 tracking-wider uppercase mt-0.5">
                                    Bakery & Snack Box
                                </span>
                            </div>
                        </div>
                    </Link>
                    <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                        <Link
                            href="/"
                            class="px-3.5 py-2 rounded-lg text-brown-800 hover:text-brand-600 hover:bg-brand-50/60 transition font-semibold text-sm"
                            >Beranda</Link
                        >
                        <Link
                            href="/snack-box"
                            class="px-3.5 py-2 rounded-lg text-brown-800 hover:text-brand-600 hover:bg-brand-50/60 transition font-semibold text-sm"
                            >Custom Snack Box</Link
                        >
                        <Link
                            href="/shop"
                            class="px-3.5 py-2 rounded-lg text-brown-800 hover:text-brand-600 hover:bg-brand-50/60 transition font-semibold text-sm"
                            >Kue Satuan</Link
                        >
                        <Link
                            href="/tracking"
                            class="px-3.5 py-2 rounded-lg text-brown-800 hover:text-brand-600 hover:bg-brand-50/60 transition font-semibold text-sm"
                            >Lacak Pesanan</Link
                        >
                    </div>
                    <div class="flex items-center space-x-3">
                        <button
                            @click="toggleCart"
                            class="relative text-brown-800 hover:text-brand-600 hover:bg-brand-50/60 p-2.5 rounded-xl transition-all cursor-pointer"
                            aria-label="Buka Keranjang"
                        >
                            <ShoppingCart class="w-6 h-6" />
                            <span
                                v-if="count > 0"
                                class="absolute -top-0.5 -right-0.5 bg-brand-500 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-sm"
                                >{{ count }}</span
                            >
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Client-side Toast for non-Inertia actions -->
        <TransitionGroup
            name="toast"
            tag="div"
            class="fixed bottom-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none"
        >
            <div
                v-if="clientToast.show"
                key="client-toast"
                :class="[
                    'pointer-events-auto flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border bg-white transition-all duration-300 max-w-sm',
                    clientToast.variant === 'success'
                        ? 'border-green-200 border-l-4 border-l-green-500'
                        : clientToast.variant === 'error'
                          ? 'border-red-200 border-l-4 border-l-red-500'
                          : 'border-brand-200 border-l-4 border-l-brand-500',
                ]"
            >
                <CheckCircle2
                    v-if="clientToast.variant === 'success'"
                    class="w-5 h-5 text-green-500 flex-shrink-0"
                />
                <AlertCircle
                    v-else-if="clientToast.variant === 'error'"
                    class="w-5 h-5 text-red-500 flex-shrink-0"
                />
                <Info
                    v-else
                    class="w-5 h-5 text-brand-500 flex-shrink-0"
                />
                <span class="text-sm text-gray-700">{{
                    clientToast.message
                }}</span>
                <button
                    @click="clientToast.show = false"
                    class="flex-shrink-0 text-gray-400 hover:text-gray-700 transition p-0.5 ml-auto"
                    aria-label="Tutup notifikasi"
                >
                    <X class="w-4 h-4" />
                </button>
            </div>
        </TransitionGroup>

        <main class="flex-1 pb-20 md:pb-0">
            <slot />
        </main>

        <!-- ─── MOBILE BOTTOM NAVIGATION BAR ─── -->
        <nav
            class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-cream-200/90 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] px-2 py-1.5 flex items-center justify-around"
            aria-label="Mobile Bottom Navigation"
        >
            <!-- Beranda -->
            <Link
                href="/"
                :class="[
                    'flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all duration-200',
                    isCurrentRoute('/')
                        ? 'text-brand-600 font-bold'
                        : 'text-brown-600 hover:text-brand-600 font-medium'
                ]"
            >
                <div :class="['p-1 rounded-xl transition-colors', isCurrentRoute('/') ? 'bg-brand-50' : '']">
                    <Home class="w-5 h-5" />
                </div>
                <span class="text-[10px] mt-0.5">Beranda</span>
            </Link>

            <!-- Custom Snack Box -->
            <Link
                href="/snack-box"
                :class="[
                    'flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all duration-200',
                    isCurrentRoute('/snack-box')
                        ? 'text-brand-600 font-bold'
                        : 'text-brown-600 hover:text-brand-600 font-medium'
                ]"
            >
                <div :class="['p-1 rounded-xl transition-colors', isCurrentRoute('/snack-box') ? 'bg-brand-50' : '']">
                    <Package class="w-5 h-5" />
                </div>
                <span class="text-[10px] mt-0.5">Snack Box</span>
            </Link>

            <!-- Katalog Kue Satuan -->
            <Link
                href="/shop"
                :class="[
                    'flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all duration-200',
                    isCurrentRoute('/shop')
                        ? 'text-brand-600 font-bold'
                        : 'text-brown-600 hover:text-brand-600 font-medium'
                ]"
            >
                <div :class="['p-1 rounded-xl transition-colors', isCurrentRoute('/shop') ? 'bg-brand-50' : '']">
                    <CakeSlice class="w-5 h-5" />
                </div>
                <span class="text-[10px] mt-0.5">Katalog</span>
            </Link>

            <!-- Lacak Pesanan -->
            <Link
                href="/tracking"
                :class="[
                    'flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all duration-200',
                    isCurrentRoute('/tracking')
                        ? 'text-brand-600 font-bold'
                        : 'text-brown-600 hover:text-brand-600 font-medium'
                ]"
            >
                <div :class="['p-1 rounded-xl transition-colors', isCurrentRoute('/tracking') ? 'bg-brand-50' : '']">
                    <Truck class="w-5 h-5" />
                </div>
                <span class="text-[10px] mt-0.5">Lacak</span>
            </Link>

            <!-- Keranjang Drawer Trigger Button -->
            <button
                @click="toggleCart"
                class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl text-brown-600 hover:text-brand-600 transition-all relative cursor-pointer"
                aria-label="Keranjang Belanja"
            >
                <div class="p-1 rounded-xl relative">
                    <ShoppingCart class="w-5 h-5" />
                    <span
                        v-if="count > 0"
                        class="absolute -top-1 -right-1 bg-brand-500 text-white text-[9px] rounded-full w-4 h-4 flex items-center justify-center font-extrabold shadow-sm animate-pulse"
                    >
                        {{ count }}
                    </span>
                </div>
                <span class="text-[10px] mt-0.5 font-medium">Keranjang</span>
            </button>
        </nav>

        <!-- Cart Drawer (always mounted, just hidden) -->
        <Teleport to="body">
            <div v-if="state.isOpen" class="fixed inset-0 z-50 flex justify-end">
                <div
                    class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"
                    @click="toggleCart"
                ></div>
                <div
                    class="relative w-full sm:max-w-md bg-white h-full flex flex-col shadow-2xl z-10 overflow-hidden"
                >
                    <div
                        class="bg-gradient-to-r from-brand-600 to-brand-500 text-white px-5 py-4 flex items-center justify-between shadow-xs"
                    >
                        <h2 class="text-lg font-bold">Keranjang Pesanan</h2>
                        <button
                            @click="toggleCart"
                            class="text-white hover:text-gray-200 transition p-1"
                            aria-label="Tutup Keranjang"
                        >
                            <X class="w-6 h-6" />
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                        <div
                            v-if="items.length === 0"
                            class="text-center py-16"
                        >
                            <ShoppingCart
                                class="w-16 h-16 mx-auto text-gray-300 mb-4"
                            />
                            <p class="text-gray-500 font-medium">
                                Belum ada produk dipilih
                            </p>
                            <p class="text-gray-400 text-sm mt-2">
                                Pilih produk dari halaman utama
                            </p>
                        </div>

                        <!-- Kategori 1: Paket Snack Box (Custom) -->
                        <div v-if="snackBoxItems.length > 0" class="space-y-3">
                            <div class="flex items-center justify-between pb-2 border-b border-cream-200">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                                        <Package class="w-3.5 h-3.5 text-amber-800" />
                                    </div>
                                    <div>
                                        <h3 class="text-xs sm:text-sm font-bold text-brown-900 leading-tight">Paket Snack Box</h3>
                                        <p class="text-[10px] text-brown-500 font-medium">Custom box (min. {{ minOrderBox }} box)</p>
                                    </div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-bold text-brand-700 bg-brand-50 border border-brand-200/80 px-2 py-0.5 rounded-full font-mono">
                                    Rp {{ formatNumber(snackBoxSubtotal) }}
                                </span>
                            </div>

                            <div
                                v-for="item in snackBoxItems"
                                :key="item.box_group_id ? `${item.box_group_id}-${item.id}` : item.id"
                                class="bg-amber-50/40 border border-amber-200/70 rounded-xl p-3 flex gap-3 transition hover:border-amber-300"
                            >
                                <!-- Image -->
                                <div
                                    class="w-12 h-12 rounded-lg bg-cream-100 flex-shrink-0 overflow-hidden flex items-center justify-center border border-cream-200"
                                >
                                    <img
                                        v-if="item.image"
                                        :src="getImageUrl(item.image)"
                                        :alt="item.name"
                                        class="w-full h-full object-cover"
                                    />
                                    <Image
                                        v-else
                                        class="w-6 h-6 text-cream-400"
                                    />
                                </div>
                                <!-- Info -->
                                <div
                                    class="flex-1 min-w-0 flex flex-col justify-between"
                                >
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p
                                                class="font-semibold text-brown-900 text-xs sm:text-sm line-clamp-1 leading-tight"
                                            >
                                                {{ item.name }}
                                            </p>
                                            <span class="inline-flex items-center gap-1 text-[9px] font-bold text-amber-800 bg-amber-100/90 px-1.5 py-0.5 rounded mt-0.5">
                                                <Package class="w-2.5 h-2.5" /> Snack Box
                                            </span>
                                        </div>
                                        <button
                                            @click="removeItem(item.id, 'kustom_box', item.box_group_id)"
                                            class="text-red-400 hover:text-red-600 transition p-1 flex-shrink-0 ml-2 cursor-pointer"
                                            aria-label="Hapus item"
                                            title="Hapus dari box"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>

                                    <div
                                        class="flex items-center justify-between mt-1.5"
                                    >
                                        <div
                                            class="flex items-center bg-white rounded-md border border-cream-200 shadow-2xs"
                                        >
                                            <button
                                                :disabled="item.qty <= minOrderBox"
                                                @click="
                                                    updateQty(item.id, item.qty - 1, 'kustom_box', item.box_group_id)
                                                "
                                                :class="[
                                                    'px-2 py-0.5 transition rounded-l-md text-brown-600',
                                                    item.qty <= minOrderBox
                                                        ? 'opacity-30 cursor-not-allowed'
                                                        : 'hover:bg-cream-100 cursor-pointer'
                                                ]"
                                                :title="`Minimal ${minOrderBox} box`"
                                            >
                                                <Minus class="w-3 h-3" />
                                            </button>
                                            <span
                                                class="px-2 py-0.5 font-bold text-xs min-w-[24px] text-center text-brown-900 font-mono"
                                                >{{ item.qty }}</span
                                            >
                                            <button
                                                @click="
                                                    updateQty(item.id, item.qty + 1, 'kustom_box', item.box_group_id)
                                                "
                                                class="px-2 py-0.5 hover:bg-cream-100 transition rounded-r-md text-brown-600 cursor-pointer"
                                                aria-label="Tambah jumlah"
                                            >
                                                <Plus class="w-3 h-3" />
                                            </button>
                                        </div>
                                        <p class="font-bold text-brand-600 text-xs font-mono">
                                            Rp
                                            {{
                                                formatNumber(item.price * item.qty)
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori 2: Kue Satuan & Jajanan Pasar -->
                        <div v-if="satuanItems.length > 0" :class="['space-y-3', snackBoxItems.length > 0 ? 'pt-2' : '']">
                            <div class="flex items-center justify-between pb-2 border-b border-cream-200">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center">
                                        <CakeSlice class="w-3.5 h-3.5 text-rose-800" />
                                    </div>
                                    <div>
                                        <h3 class="text-xs sm:text-sm font-bold text-brown-900 leading-tight">Kue Satuan & Eceran</h3>
                                        <p class="text-[10px] text-brown-500 font-medium">Kue basah & pastry (min. {{ minOrderSatuan }} pcs)</p>
                                    </div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-bold text-brand-700 bg-brand-50 border border-brand-200/80 px-2 py-0.5 rounded-full font-mono">
                                    Rp {{ formatNumber(satuanSubtotal) }}
                                </span>
                            </div>

                            <div
                                v-for="item in satuanItems"
                                :key="item.id"
                                class="bg-cream-50/50 border border-cream-200 rounded-xl p-3 flex gap-3 transition hover:border-brand-200"
                            >
                                <!-- Image -->
                                <div
                                    class="w-12 h-12 rounded-lg bg-cream-100 flex-shrink-0 overflow-hidden flex items-center justify-center border border-cream-200"
                                >
                                    <img
                                        v-if="item.image"
                                        :src="getImageUrl(item.image)"
                                        :alt="item.name"
                                        class="w-full h-full object-cover"
                                    />
                                    <Image
                                        v-else
                                        class="w-6 h-6 text-cream-400"
                                    />
                                </div>
                                <!-- Info -->
                                <div
                                    class="flex-1 min-w-0 flex flex-col justify-between"
                                >
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p
                                                class="font-semibold text-brown-900 text-xs sm:text-sm line-clamp-1 leading-tight"
                                            >
                                                {{ item.name }}
                                            </p>
                                            <span class="inline-flex items-center gap-1 text-[9px] font-bold text-rose-800 bg-rose-100/90 px-1.5 py-0.5 rounded mt-0.5">
                                                <CakeSlice class="w-2.5 h-2.5" /> Kue Satuan
                                            </span>
                                        </div>
                                        <button
                                            @click="removeItem(item.id, 'satuan')"
                                            class="text-red-400 hover:text-red-600 transition p-1 flex-shrink-0 ml-2 cursor-pointer"
                                            aria-label="Hapus item"
                                            title="Hapus item"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>

                                    <div
                                        class="flex items-center justify-between mt-1.5"
                                    >
                                        <div
                                            class="flex items-center bg-white rounded-md border border-cream-200 shadow-2xs"
                                        >
                                            <button
                                                :disabled="item.qty <= minOrderSatuan"
                                                @click="
                                                    updateQty(item.id, item.qty - 1, 'satuan')
                                                "
                                                :class="[
                                                    'px-2 py-0.5 transition rounded-l-md text-brown-600',
                                                    item.qty <= minOrderSatuan
                                                        ? 'opacity-30 cursor-not-allowed'
                                                        : 'hover:bg-cream-100 cursor-pointer'
                                                ]"
                                                :title="`Minimal ${minOrderSatuan} pcs`"
                                            >
                                                <Minus class="w-3 h-3" />
                                            </button>
                                            <span
                                                class="px-2 py-0.5 font-bold text-xs min-w-[24px] text-center text-brown-900 font-mono"
                                                >{{ item.qty }}</span
                                            >
                                            <button
                                                @click="
                                                    updateQty(item.id, item.qty + 1, 'satuan')
                                                "
                                                class="px-2 py-0.5 hover:bg-cream-100 transition rounded-r-md text-brown-600 cursor-pointer"
                                                aria-label="Tambah jumlah"
                                            >
                                                <Plus class="w-3 h-3" />
                                            </button>
                                        </div>
                                        <p class="font-bold text-brand-600 text-xs font-mono">
                                            Rp
                                            {{
                                                formatNumber(item.price * item.qty)
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pilihan Metode Pembayaran (DP vs Full) -->
                        <div
                            v-if="items.length > 0"
                            class="border-t border-cream-200/80 pt-4 mt-2 space-y-2.5"
                        >
                            <div class="flex items-center justify-between">
                                <p class="font-bold text-brown-900 text-sm">Opsi Pembayaran</p>
                                <span class="text-[11px] font-semibold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-full">
                                    {{ paymentType === 'full' ? 'Bayar Penuh (100%)' : `DP (${dpPercentage}%)` }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <!-- Opsi DP -->
                                <button
                                    type="button"
                                    @click="setPaymentType('dp')"
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
                                    <p class="text-xs font-black text-brand-600 font-mono">Rp {{ formatNumber(dpPrice) }}</p>
                                    <p class="text-[10px] text-brown-500 mt-1 leading-tight">Sisa saat ambil</p>
                                </button>

                                <!-- Opsi Bayar Full -->
                                <button
                                    type="button"
                                    @click="setPaymentType('full')"
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
                                    <p class="text-xs font-black text-emerald-600 font-mono">Rp {{ formatNumber(totalPrice) }}</p>
                                    <p class="text-[10px] text-emerald-600 font-medium mt-1 leading-tight">Lunas langsung</p>
                                </button>
                            </div>
                        </div>

                        <!-- Formulir Pemesan (Moved into the scrollable area) -->
                        <div
                            v-if="items.length > 0"
                            class="border-t border-gray-200 pt-5 mt-2 space-y-3"
                        >
                            <p class="font-semibold text-gray-700 text-sm">
                                Data Pemesan
                            </p>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-brown-800 mb-1"
                                    >Nama Lengkap</label
                                >
                                <input
                                    v-model="customerName"
                                    type="text"
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full px-4 py-3 border border-cream-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition text-base sm:text-sm bg-cream-50/40"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-brown-800 mb-1"
                                    >No. WhatsApp / Telepon</label
                                >
                                <input
                                    v-model="customerPhone"
                                    type="tel"
                                    inputmode="tel"
                                    autocomplete="tel"
                                    placeholder="Contoh: 08123456789"
                                    class="w-full px-4 py-3 border border-cream-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition text-base sm:text-sm bg-cream-50/40"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-brown-800 mb-1"
                                    >Tanggal & Jam Pengambilan</label
                                >
                                <input
                                    v-model="pickupDate"
                                    type="datetime-local"
                                    class="w-full px-4 py-3 border border-cream-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition text-base sm:text-sm bg-cream-50/40"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-brown-800 mb-1"
                                    >Lokasi / Alamat Pengiriman</label
                                >
                                <textarea
                                    v-model="customerLocation"
                                    rows="2"
                                    placeholder="Contoh: Jl. Raya No. 123, Jakarta"
                                    class="w-full px-4 py-3 border border-cream-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition text-base sm:text-sm bg-cream-50/40 resize-none"
                                ></textarea>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-brown-800 mb-1"
                                    >Catatan (opsional)</label
                                >
                                <textarea
                                    v-model="notes"
                                    rows="2"
                                    placeholder="Misal: pita tulisan atau permintaan khusus"
                                    class="w-full px-4 py-3 border border-cream-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition text-base sm:text-sm bg-cream-50/40 resize-none"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Fixed Footer (Total & Checkout Buttons only) -->
                    <div
                        v-if="items.length > 0"
                        class="border-t border-cream-200 px-5 sm:px-6 py-4 bg-white shadow-[0_-4px_12px_rgba(0,0,0,0.05)] z-10 relative space-y-3"
                    >
                        <div class="space-y-1.5">
                            <!-- Category subtotals if both exist -->
                            <div v-if="hasBothCategories" class="space-y-1 pb-2 border-b border-cream-200/70 text-xs">
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
                                <span class="text-brown-600 font-medium">Total Pesanan</span>
                                <span class="font-bold text-brown-900 font-mono"
                                    >Rp {{ formatNumber(totalPrice) }}</span
                                >
                            </div>
                            <template v-if="paymentType === 'dp'">
                                <div class="flex justify-between text-sm">
                                    <span class="text-brand-700 font-semibold"
                                        >DP ({{ dpPercentage }}%)</span
                                    ><span class="font-bold text-brand-700 font-mono"
                                        >Rp {{ formatNumber(dpPrice) }}</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between text-xs border-t border-dashed border-cream-200 pt-1.5"
                                >
                                    <span class="text-brown-500"
                                        >Sisa Pelunasan Saat Ambil</span
                                    >
                                    <span class="font-bold text-brown-700 font-mono"
                                        >Rp
                                        {{
                                            formatNumber(Math.max(0, totalPrice - dpPrice))
                                        }}</span
                                    >
                                </div>
                            </template>
                            <template v-else>
                                <div class="flex justify-between text-sm">
                                    <span class="text-emerald-700 font-semibold"
                                        >Pembayaran Awal (100%)</span
                                    ><span class="font-bold text-emerald-700 font-mono"
                                        >Rp {{ formatNumber(totalPrice) }}</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between text-xs border-t border-dashed border-cream-200 pt-1.5"
                                >
                                    <span class="text-brown-500"
                                        >Sisa Pelunasan Saat Ambil</span
                                    >
                                    <span class="font-bold text-emerald-600 font-mono"
                                        >Rp 0 (LUNAS)</span
                                    >
                                </div>
                            </template>
                        </div>
                        <button
                            @click="sendToWhatsApp"
                            class="w-full bg-emerald-600 text-white py-3.5 sm:py-4 rounded-xl hover:bg-emerald-700 active:bg-emerald-800 transition font-bold flex items-center justify-center gap-2 shadow-md shadow-emerald-600/20 text-sm sm:text-base cursor-pointer"
                        >
                            <svg
                                class="w-5 h-5 mr-1"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"
                                />
                            </svg>
                            Kirim Pesanan ke WhatsApp
                        </button>
                        <button
                            @click="clearCart"
                            class="w-full text-center text-gray-500 hover:text-gray-700 transition text-sm py-1"
                        >
                            Kosongkan Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Product Detail Modal (Quick View & Custom Quantity) -->
        <ProductDetailModal />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import {
    ShoppingCart,
    X,
    CheckCircle2,
    AlertCircle,
    Info,
    Home,
    Package,
    CakeSlice,
    Truck,
    Trash2,
    Minus,
    Plus,
    Image,
    Check,
} from "lucide-vue-next";

import { usePage, Link, router } from "@inertiajs/vue3";
import { useCartStore } from "@/Stores/CartStore.js";
import Toast from "@/Components/UI/Toast.vue";
import ProductDetailModal from "@/Pages/Customer/Components/ProductDetailModal.vue";
import { getImageUrl } from "@/helpers.js";
import axios from "axios";

const page = usePage();
const isAuthenticated = ref(!!page.props.auth?.user);
const cms = computed(() => page.props.cms?.settings || {});

const isCurrentRoute = (path) => {
    if (path === '/') {
        return page.url === '/' || page.url === '';
    }
    return page.url.startsWith(path);
};

const {
    state,
    items,
    totalPrice,
    paymentType,
    setPaymentType,
    count,
    initFromSession,
    fetchCart,
    addToCart,
    removeItem,
    updateQty,
    clearCart,
    toggleCart,
} = useCartStore();

onMounted(() => {
    if (page.props.cart && Array.isArray(page.props.cart) && page.props.cart.length > 0) {
        initFromSession(page.props.cart);
    } else {
        fetchCart();
    }
});

// Dynamic DP & Minimum Order from CMS
const dpPercentage = computed(() => parseInt(cms.value.dp_percentage) || 70);
const dpPrice = computed(() =>
    Math.round(totalPrice.value * (dpPercentage.value / 100)),
);
const minOrderBox = computed(() => parseInt(cms.value.min_order_box) || 10);
const minOrderSatuan = computed(() => parseInt(cms.value.min_order_satuan) || 10);

// Categorized Items in Cart Drawer
const snackBoxItems = computed(() => items.value.filter(i => i.type === 'kustom_box'));
const satuanItems = computed(() => items.value.filter(i => i.type !== 'kustom_box'));
const hasBothCategories = computed(() => snackBoxItems.value.length > 0 && satuanItems.value.length > 0);

const snackBoxSubtotal = computed(() => snackBoxItems.value.reduce((sum, item) => sum + (item.price * item.qty), 0));
const satuanSubtotal = computed(() => satuanItems.value.reduce((sum, item) => sum + (item.price * item.qty), 0));

const customerName = ref("");
const customerPhone = ref("");
const pickupDate = ref("");
const customerLocation = ref("");
const notes = ref("");

const formatNumber = (num) => new Intl.NumberFormat("id-ID").format(num);

// Simple reactive toast notification (for non-Inertia actions)
const clientToast = ref({ show: false, variant: "info", message: "" });
const showToast = (variant, message) => {
    clientToast.value = { show: true, variant, message };
    setTimeout(() => {
        clientToast.value.show = false;
    }, 4000);
};

const sendToWhatsApp = () => {
    if (items.value.length === 0) {
        showToast("error", "Pilih produk dulu!");
        return;
    }

    const invalidBox = items.value.find(i => i.type === 'kustom_box' && i.qty < minOrderBox.value);
    if (invalidBox) {
        showToast("error", `Minimal pemesanan untuk Snack Box adalah ${minOrderBox.value} box`);
        return;
    }

    const invalidSatuan = items.value.find(i => i.type === 'satuan' && i.qty < minOrderSatuan.value);
    if (invalidSatuan) {
        showToast("error", `Minimal pemesanan untuk kue satuan adalah ${minOrderSatuan.value} pcs`);
        return;
    }

    if (!customerName.value.trim()) {
        showToast("error", "Silakan masukkan nama lengkap");
        return;
    }
    if (!customerPhone.value.trim()) {
        showToast("error", "Silakan masukkan nomor WhatsApp");
        return;
    }

    const phoneRegex = /^(\+62|0)[0-9]{9,12}$/;
    if (!phoneRegex.test(customerPhone.value.trim())) {
        showToast(
            "error",
            "Format nomor telepon tidak valid (gunakan format: 0812xxx atau +62812xxx)",
        );
        return;
    }

    if (!pickupDate.value) {
        showToast("error", "Silakan pilih tanggal pengambilan");
        return;
    }
    if (!customerLocation.value.trim()) {
        showToast("error", "Silakan masukkan lokasi/alamat pengambilan");
        return;
    }

    const payload = {
        customer_name: customerName.value.trim(),
        customer_phone: customerPhone.value.trim(),
        pickup_date: pickupDate.value,
        location: customerLocation.value.trim(),
        payment_type: paymentType.value,
        items: items.value.map((i) => ({
            product_id: i.id,
            quantity: i.qty,
            type: i.type,
            box_group_id: i.box_group_id || null,
        })),
        terms_agreed: true,
    };

    axios
        .post("/checkout", payload, {
            headers: {
                Accept: "application/json",
            },
        })
        .then((response) => {
            // API trigger succeeded, backend has prepared the WhatsApp URL based on OrderService.php!
            if (response.data.whatsappUrl) {
                window.open(response.data.whatsappUrl, "_blank");
            } else {
                showToast(
                    "error",
                    "Gagal mendapatkan link WhatsApp dari server",
                );
            }
            clearCart();
            toggleCart();
        })
        .catch((error) => {
            console.error("Checkout Error:", error);
            if (error.response) {
                if (
                    error.response.status === 422 &&
                    error.response.data?.errors
                ) {
                    // Tampilkan pesan error validasi dari server (misal: nomor telepon tidak valid)
                    const pesan = Object.values(error.response.data.errors)
                        .flat()
                        .join(". ");
                    showToast("error", pesan);
                } else {
                    showToast(
                        "error",
                        "Terjadi kesalahan di server, silakan coba lagi",
                    );
                }
            } else if (error.request) {
                showToast(
                    "error",
                    "Gagal terhubung ke server, periksa koneksi Anda",
                );
            } else {
                showToast("error", "Terjadi kesalahan tak terduga");
            }
        });
};
</script>
