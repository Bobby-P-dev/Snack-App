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

                            <!-- Grouped by Box Package (Atomic Package Unit) -->
                            <div
                                v-for="box in groupedSnackBoxes"
                                :key="box.box_group_id || 'default'"
                                class="bg-amber-50/50 border border-amber-200/80 rounded-2xl p-3.5 space-y-3 transition hover:border-amber-300 shadow-2xs"
                            >
                                <!-- Box Header: Title, Badge, Subtotal, Delete Whole Box Button -->
                                <div class="flex items-start justify-between gap-2 border-b border-amber-200/60 pb-2.5">
                                    <div>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="font-bold text-brown-900 text-xs sm:text-sm">
                                                Paket Snack Box
                                            </span>
                                            <span
                                                :class="[
                                                    'text-[9px] font-bold px-1.5 py-0.5 rounded',
                                                    [3, 4, 5].includes(box.items.length)
                                                        ? 'bg-amber-200/80 text-amber-900'
                                                        : 'bg-red-100 text-red-700 font-extrabold'
                                                ]"
                                            >
                                                {{ [3, 4, 5].includes(box.items.length) ? `Paket ${box.items.length} Kue` : `⚠️ Tidak Lengkap (${box.items.length} Kue)` }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-brown-600 mt-0.5 font-medium">
                                            {{ box.boxQty }} box &bull; Rp {{ formatNumber(box.pricePerBox) }} / box
                                        </p>
                                    </div>
                                    <button
                                        @click="removeBoxGroup(box.box_group_id)"
                                        class="text-red-400 hover:text-red-600 transition p-1.5 rounded-lg hover:bg-red-50 flex-shrink-0 cursor-pointer"
                                        aria-label="Hapus seluruh paket snack box"
                                        title="Hapus paket snack box ini"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>

                                <!-- Incomplete package warning alert -->
                                <div
                                    v-if="![3, 4, 5].includes(box.items.length)"
                                    class="bg-red-50 border border-red-200 rounded-xl p-2.5 flex items-center gap-2 text-red-700 text-xs"
                                >
                                    <AlertCircle class="w-4 h-4 shrink-0 text-red-600" />
                                    <span class="text-[11px] leading-tight">
                                        Paket ini tidak lengkap (baru {{ box.items.length }} kue). Paket harus berisi 3, 4, atau 5 pilihan kue.
                                    </span>
                                </div>

                                <!-- Pastries list inside this box -->
                                <div class="space-y-1.5 bg-white/70 rounded-xl p-2 border border-amber-200/40">
                                    <div
                                        v-for="item in box.items"
                                        :key="item.id"
                                        class="flex items-center justify-between text-xs py-1 px-1.5"
                                    >
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-cream-100 overflow-hidden shrink-0 border border-cream-200 flex items-center justify-center">
                                                <img v-if="item.image" :src="getImageUrl(item.image)" :alt="item.name" class="w-full h-full object-cover" />
                                                <Image v-else class="w-3.5 h-3.5 text-cream-400" />
                                            </div>
                                            <span class="font-medium text-brown-800 truncate text-[11px] sm:text-xs">
                                                {{ item.name }}
                                            </span>
                                        </div>
                                        <span class="text-brown-500 font-mono text-[11px] shrink-0">
                                            Rp {{ formatNumber(item.price) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Box Quantity Controls & Box Subtotal -->
                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex items-center bg-white rounded-lg border border-cream-200 shadow-2xs p-0.5">
                                        <button
                                            :disabled="box.boxQty <= minOrderBox"
                                            @click="updateBoxGroupQty(box.box_group_id, box.boxQty - 1)"
                                            :class="[
                                                'px-2 py-1 transition rounded-md text-brown-600',
                                                box.boxQty <= minOrderBox
                                                    ? 'opacity-30 cursor-not-allowed'
                                                    : 'hover:bg-cream-100 cursor-pointer active:scale-95'
                                            ]"
                                            :title="`Minimal ${minOrderBox} box`"
                                        >
                                            <Minus class="w-3.5 h-3.5" />
                                        </button>
                                        <div class="flex items-center px-1">
                                            <input
                                                type="number"
                                                inputmode="numeric"
                                                pattern="[0-9]*"
                                                :min="minOrderBox"
                                                max="5000"
                                                :value="box.boxQty"
                                                @focus="$event.target.select()"
                                                @change="(e) => handleBoxQtyInput(box.box_group_id, e)"
                                                @blur="(e) => handleBoxQtyInput(box.box_group_id, e)"
                                                @keydown.enter="$event.target.blur()"
                                                class="w-11 sm:w-12 text-center font-bold text-xs text-brown-900 font-mono bg-cream-50/60 border border-cream-200 rounded focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-500 py-0.5 px-0.5 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                aria-label="Ketik jumlah box"
                                            />
                                            <span class="text-[11px] text-brown-600 font-medium ml-1 select-none">box</span>
                                        </div>
                                        <button
                                            @click="updateBoxGroupQty(box.box_group_id, box.boxQty + 1)"
                                            class="px-2 py-1 hover:bg-cream-100 transition rounded-md text-brown-600 cursor-pointer active:scale-95"
                                            aria-label="Tambah jumlah box"
                                        >
                                            <Plus class="w-3.5 h-3.5" />
                                        </button>
                                    </div>

                                    <div class="text-right">
                                        <span class="text-[10px] text-brown-400 block">Subtotal Paket:</span>
                                        <span class="font-extrabold text-brand-600 text-xs sm:text-sm font-mono">
                                            Rp {{ formatNumber(box.subtotal) }}
                                        </span>
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
                                            <input
                                                type="number"
                                                inputmode="numeric"
                                                pattern="[0-9]*"
                                                :min="minOrderSatuan"
                                                max="5000"
                                                :value="item.qty"
                                                @focus="$event.target.select()"
                                                @change="(e) => handleSatuanQtyInput(item.id, e)"
                                                @blur="(e) => handleSatuanQtyInput(item.id, e)"
                                                @keydown.enter="$event.target.blur()"
                                                class="w-12 text-center font-bold text-xs sm:text-sm text-brown-900 font-mono bg-transparent border-0 focus:outline-none focus:ring-1 focus:ring-brand-500 py-0.5 px-0.5 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                aria-label="Ketik jumlah satuan"
                                            />
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
                            <!-- Delivery / Pickup Method -->
                            <div>
                                <label class="block text-xs font-semibold text-brown-800 mb-1.5">
                                    Metode Pengambilan / Pengiriman
                                </label>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <button
                                        type="button"
                                        @click="deliveryMethod = 'pickup'"
                                        :class="[
                                            'p-2.5 rounded-xl border text-left transition flex items-center gap-2 cursor-pointer',
                                            deliveryMethod === 'pickup'
                                                ? 'border-brand-500 bg-brand-50/70 text-brand-900 ring-2 ring-brand-500/15'
                                                : 'border-cream-300 bg-white hover:bg-cream-50 text-brown-700'
                                        ]"
                                    >
                                        <div :class="['w-7 h-7 rounded-lg flex items-center justify-center shrink-0', deliveryMethod === 'pickup' ? 'bg-brand-500 text-white' : 'bg-cream-100 text-brown-600']">
                                            <Store class="w-4 h-4" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold leading-tight">Ambil di Toko</p>
                                            <p class="text-[10px] text-brown-500 leading-tight truncate">Ambil langsung</p>
                                        </div>
                                    </button>

                                    <button
                                        type="button"
                                        @click="deliveryMethod = 'delivery'"
                                        :class="[
                                            'p-2.5 rounded-xl border text-left transition flex items-center gap-2 cursor-pointer',
                                            deliveryMethod === 'delivery'
                                                ? 'border-brand-500 bg-brand-50/70 text-brand-900 ring-2 ring-brand-500/15'
                                                : 'border-cream-300 bg-white hover:bg-cream-50 text-brown-700'
                                        ]"
                                    >
                                        <div :class="['w-7 h-7 rounded-lg flex items-center justify-center shrink-0', deliveryMethod === 'delivery' ? 'bg-brand-500 text-white' : 'bg-cream-100 text-brown-600']">
                                            <Truck class="w-4 h-4" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold leading-tight">Diantar ke Lokasi</p>
                                            <p class="text-[10px] text-brown-500 leading-tight truncate">Kirim ke alamat</p>
                                        </div>
                                    </button>
                                </div>

                                <!-- If pickup: show store address info banner -->
                                <div
                                    v-if="deliveryMethod === 'pickup'"
                                    class="bg-amber-50/90 border border-amber-200/90 rounded-xl p-3 text-xs space-y-1"
                                >
                                    <div class="flex items-center gap-1.5 font-bold text-amber-900">
                                        <MapPin class="w-3.5 h-3.5 text-amber-700 shrink-0" />
                                        <span>Lokasi Tempat Usaha:</span>
                                    </div>
                                    <p class="text-brown-700 font-medium pl-5 leading-relaxed">
                                        {{ storeAddress }}
                                    </p>
                                    <p class="text-[11px] text-amber-800/80 pl-5 pt-0.5">
                                        💡 Pesanan disiapkan dan dapat diambil langsung sesuai jadwal di atas.
                                    </p>
                                </div>

                                <!-- If delivery: show custom location textarea -->
                                <div v-else>
                                    <textarea
                                        v-model="customLocation"
                                        rows="2"
                                        placeholder="Masukkan alamat lengkap pengiriman atau lokasi acara Anda..."
                                        class="w-full px-4 py-3 border border-cream-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition text-base sm:text-sm bg-cream-50/40 resize-none"
                                    ></textarea>
                                    <p class="text-[11px] text-brown-500 mt-1">
                                        🛵 Pesanan akan diantar ke alamat yang Anda tuliskan.
                                    </p>
                                </div>
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
                        <!-- Alert if cart contains incomplete snack box -->
                        <div
                            v-if="hasIncompleteBox"
                            class="mb-3 bg-red-50 border border-red-200 rounded-xl p-3 flex items-start gap-2.5 text-red-700 text-xs"
                        >
                            <AlertCircle class="w-4 h-4 shrink-0 text-red-600 mt-0.5" />
                            <p class="leading-relaxed">
                                <strong>Pesanan Belum Lengkap:</strong> Ada paket snack box yang jumlah kuenya tidak sesuai kapasitas paket (harus 3, 4, atau 5 kue).
                            </p>
                        </div>
                        <button
                            @click="sendToWhatsApp"
                            :disabled="hasIncompleteBox"
                            :class="[
                                'w-full py-3.5 sm:py-4 rounded-xl transition font-bold flex items-center justify-center gap-2 text-sm sm:text-base cursor-pointer',
                                hasIncompleteBox
                                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed shadow-none'
                                    : 'bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white shadow-md shadow-emerald-600/20'
                            ]"
                        >
                            <svg
                                class="w-5 h-5 mr-1"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z"
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
    Store,
    MapPin,
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
    removeBoxGroup,
    updateQty,
    updateBoxGroupQty,
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

// Group snack box items by box_group_id so each box is an atomic unit
const groupedSnackBoxes = computed(() => {
    const groups = {};
    snackBoxItems.value.forEach((item) => {
        const gid = item.box_group_id || 'default';
        if (!groups[gid]) {
            groups[gid] = {
                box_group_id: item.box_group_id,
                boxQty: item.qty || 1,
                items: [],
                subtotal: 0,
            };
        }
        groups[gid].items.push(item);
        groups[gid].subtotal += (item.price || 0) * (item.qty || 1);
    });
    return Object.values(groups).map((g) => ({
        ...g,
        pricePerBox: g.boxQty > 0 ? Math.round(g.subtotal / g.boxQty) : g.subtotal,
    }));
});

const hasIncompleteBox = computed(() => {
    return groupedSnackBoxes.value.some(g => ![3, 4, 5].includes(g.items.length));
});

const snackBoxSubtotal = computed(() => snackBoxItems.value.reduce((sum, item) => sum + (item.price * item.qty), 0));
const satuanSubtotal = computed(() => satuanItems.value.reduce((sum, item) => sum + (item.price * item.qty), 0));

const customerName = ref("");
const customerPhone = ref("");
const pickupDate = ref("");
const deliveryMethod = ref("pickup"); // 'pickup' | 'delivery'
const customLocation = ref("");
const notes = ref("");

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

const formatNumber = (num) => new Intl.NumberFormat("id-ID").format(num);

const handleBoxQtyInput = (boxGroupId, event) => {
    let val = parseInt(event.target.value, 10);
    const min = minOrderBox.value || 10;
    if (isNaN(val) || val < min) {
        val = min;
    } else if (val > 5000) {
        val = 5000;
    }
    event.target.value = val;
    updateBoxGroupQty(boxGroupId, val);
};

const handleSatuanQtyInput = (productId, event) => {
    let val = parseInt(event.target.value, 10);
    const min = minOrderSatuan.value || 10;
    if (isNaN(val) || val < min) {
        val = min;
    } else if (val > 5000) {
        val = 5000;
    }
    event.target.value = val;
    updateQty(productId, val, 'satuan');
};

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

    if (hasIncompleteBox.value) {
        showToast("error", "Ada paket snack box yang belum lengkap. Setiap paket harus berisi 3, 4, atau 5 pilihan kue.");
        return;
    }

    for (const box of groupedSnackBoxes.value) {
        if (box.boxQty < minOrderBox.value) {
            showToast("error", `Minimal pemesanan untuk Paket Snack Box adalah ${minOrderBox.value} box`);
            return;
        }
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
        showToast("error", "Silakan pilih tanggal & waktu");
        return;
    }
    if (deliveryMethod.value === 'delivery' && !customLocation.value.trim()) {
        showToast("error", "Silakan masukkan alamat / lokasi pengiriman");
        return;
    }

    const payload = {
        customer_name: customerName.value.trim(),
        customer_phone: customerPhone.value.trim(),
        pickup_date: pickupDate.value,
        location: resolvedLocation.value,
        notes: notes.value && notes.value.trim() ? notes.value.trim() : null,
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
