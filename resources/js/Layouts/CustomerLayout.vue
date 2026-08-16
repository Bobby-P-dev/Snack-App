<template>
    <div class="flex flex-col min-h-screen bg-cream-100">
        <!-- Sticky Navbar -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <Link href="/" class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-brand-500 rounded-lg flex items-center justify-center text-white font-bold text-lg"
                        >
                            SB
                        </div>
                        <span class="text-xl font-bold text-gray-900"
                            >Snack Box</span
                        >
                    </Link>
                    <div class="hidden md:flex items-center space-x-8">
                        <Link
                            href="/"
                            class="text-gray-700 hover:text-brand-600 transition font-medium"
                            >Beranda</Link
                        >
                        <Link
                            href="/about"
                            class="text-gray-700 hover:text-brand-600 transition font-medium"
                            >Tentang Kami</Link
                        >
                        <Link
                            href="/shop"
                            class="text-gray-700 hover:text-brand-600 transition font-medium"
                            >Produk</Link
                        >
                        <Link
                            href="/tracking"
                            class="text-gray-700 hover:text-brand-600 transition font-medium"
                            >Lacak Pesanan</Link
                        >
                    </div>
                    <div class="flex items-center space-x-4">
                        <button
                            @click="toggleCart"
                            class="relative text-gray-700 hover:text-brand-600 transition p-2"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                />
                            </svg>
                            <span
                                v-if="count > 0"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"
                                >{{ count }}</span
                            >
                        </button>
                        <!-- Mobile Menu Toggle -->
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="md:hidden p-2 text-gray-600 hover:text-brand-600 focus:outline-none transition-colors rounded-lg hover:bg-gray-100">
                            <svg v-if="!isMobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Dropdown -->
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-show="isMobileMenuOpen" class="md:hidden border-t border-gray-100 bg-white absolute w-full shadow-lg">
                    <div class="px-4 pt-2 pb-4 space-y-2">
                        <Link href="/" class="block px-3 py-2.5 rounded-lg text-base font-medium text-gray-700 hover:text-brand-600 hover:bg-brand-50 transition" @click="isMobileMenuOpen = false">Beranda</Link>
                        <Link href="/about" class="block px-3 py-2.5 rounded-lg text-base font-medium text-gray-700 hover:text-brand-600 hover:bg-brand-50 transition" @click="isMobileMenuOpen = false">Tentang Kami</Link>
                        <Link href="/shop" class="block px-3 py-2.5 rounded-lg text-base font-medium text-gray-700 hover:text-brand-600 hover:bg-brand-50 transition" @click="isMobileMenuOpen = false">Produk</Link>
                        <Link href="/tracking" class="block px-3 py-2.5 rounded-lg text-base font-medium text-gray-700 hover:text-brand-600 hover:bg-brand-50 transition" @click="isMobileMenuOpen = false">Lacak Pesanan</Link>
                    </div>
                </div>
            </transition>
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
                <svg
                    v-if="clientToast.variant === 'success'"
                    class="w-5 h-5 text-green-500 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
                <svg
                    v-else-if="clientToast.variant === 'error'"
                    class="w-5 h-5 text-red-500 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
                <svg
                    v-else
                    class="w-5 h-5 text-brand-500 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                <span class="text-sm text-gray-700">{{
                    clientToast.message
                }}</span>
                <button
                    @click="clientToast.show = false"
                    class="flex-shrink-0 text-gray-400 hover:text-gray-700 transition p-0.5 ml-auto"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </TransitionGroup>

        <main class="flex-1">
            <slot />
        </main>

        <!-- Cart Drawer (always mounted, just hidden) -->
        <Teleport to="body">
            <div v-if="state.isOpen" class="fixed inset-0 z-50">
                <div
                    class="absolute inset-0 bg-black bg-opacity-50"
                    @click="toggleCart"
                ></div>
                <div
                    class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
                >
                    <div
                        class="bg-brand-500 text-white px-6 py-4 flex items-center justify-between"
                    >
                        <h2 class="text-lg font-bold">Keranjang Pesanan</h2>
                        <button
                            @click="toggleCart"
                            class="text-white hover:text-gray-200 transition p-1"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                        <div
                            v-if="items.length === 0"
                            class="text-center py-16"
                        >
                            <svg
                                class="w-16 h-16 mx-auto text-gray-300 mb-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                />
                            </svg>
                            <p class="text-gray-500 font-medium">
                                Belum ada produk dipilih
                            </p>
                            <p class="text-gray-400 text-sm mt-2">
                                Pilih produk dari halaman utama
                            </p>
                        </div>

                        <div
                            v-for="item in items"
                            :key="item.id"
                            class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex gap-3"
                        >
                            <!-- Image -->
                            <div
                                class="w-12 h-12 rounded-md bg-gray-200 flex-shrink-0 overflow-hidden"
                            >
                                <img
                                    v-if="item.image"
                                    :src="getImageUrl(item.image)"
                                    :alt="item.name"
                                    class="w-full h-full object-cover"
                                />
                                <svg
                                    v-else
                                    class="w-full h-full text-gray-300 p-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <!-- Info -->
                            <div
                                class="flex-1 min-w-0 flex flex-col justify-between"
                            >
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p
                                            class="font-semibold text-gray-900 text-sm line-clamp-1 leading-tight"
                                        >
                                            {{ item.name }}
                                        </p>
                                        <p
                                            class="text-[10px] text-gray-500 mt-0.5"
                                        >
                                            {{
                                                item.type === "kustom_box"
                                                    ? "Snack Box"
                                                    : "Kue Satuan"
                                            }}
                                        </p>
                                    </div>
                                    <button
                                        @click="removeItem(item.id)"
                                        class="text-red-400 hover:text-red-600 transition p-1 flex-shrink-0 ml-2"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <div
                                    class="flex items-center justify-between mt-1.5"
                                >
                                    <div
                                        class="flex items-center bg-gray-50 rounded-md border border-gray-200"
                                    >
                                        <button
                                            @click="
                                                updateQty(item.id, item.qty - 1)
                                            "
                                            class="px-2 py-0.5 hover:bg-gray-200 transition rounded-l-md text-gray-600"
                                        >
                                            <svg
                                                class="w-3 h-3"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M20 12H4"
                                                />
                                            </svg>
                                        </button>
                                        <span
                                            class="px-2 py-0.5 font-semibold text-xs min-w-[24px] text-center text-gray-700"
                                            >{{ item.qty }}</span
                                        >
                                        <button
                                            @click="
                                                updateQty(item.id, item.qty + 1)
                                            "
                                            class="px-2 py-0.5 hover:bg-gray-200 transition rounded-r-md text-gray-600"
                                        >
                                            <svg
                                                class="w-3 h-3"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 4v16m8-8H4"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="font-bold text-brand-600 text-xs">
                                        Rp
                                        {{
                                            formatNumber(item.price * item.qty)
                                        }}
                                    </p>
                                </div>
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
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                    >Nama Lengkap</label
                                >
                                <input
                                    v-model="customerName"
                                    type="text"
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-brand-500 transition text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                    >No. WhatsApp / Telepon</label
                                >
                                <input
                                    v-model="customerPhone"
                                    type="tel"
                                    placeholder="Contoh: 08123456789"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-brand-500 transition text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                    >Tanggal & Jam Pengambilan</label
                                >
                                <input
                                    v-model="pickupDate"
                                    type="datetime-local"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-brand-500 transition text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                    >Lokasi / Alamat Pengiriman</label
                                >
                                <textarea
                                    v-model="customerLocation"
                                    rows="2"
                                    placeholder="Contoh: Jl. Raya No. 123, Jakarta"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-brand-500 transition text-sm resize-none"
                                ></textarea>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-600 mb-1"
                                    >Catatan (opsional)</label
                                >
                                <textarea
                                    v-model="notes"
                                    rows="2"
                                    placeholder="Misal: tambahan pesan atau permintaan khusus"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-brand-500 transition text-sm resize-none"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Fixed Footer (Total & Checkout Buttons only) -->
                    <div
                        v-if="items.length > 0"
                        class="border-t border-gray-200 px-6 py-4 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-10 relative space-y-3"
                    >
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total</span
                                ><span class="font-bold text-gray-900"
                                    >Rp {{ formatNumber(totalPrice) }}</span
                                >
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-orange-600 font-semibold"
                                    >DP ({{ dpPercentage }}%)</span
                                ><span class="font-bold text-orange-600"
                                    >Rp {{ formatNumber(dpPrice) }}</span
                                >
                            </div>
                            <div
                                class="flex justify-between text-sm mt-1 border-t border-dashed border-gray-200 pt-1"
                            >
                                <span class="text-gray-500 text-xs"
                                    >Sisa Bayar</span
                                >
                                <span class="font-bold text-gray-700 text-xs"
                                    >Rp
                                    {{
                                        formatNumber(totalPrice - dpPrice)
                                    }}</span
                                >
                            </div>
                        </div>
                        <button
                            @click="sendToWhatsApp"
                            class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition font-bold flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"
                                />
                            </svg>
                            Kirim Pesanan
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
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const isMobileMenuOpen = ref(false);
import { usePage, Link, router } from "@inertiajs/vue3";
import { useCartStore } from "@/Stores/CartStore.js";
import Toast from "@/Components/UI/Toast.vue";
import { getImageUrl } from "@/helpers.js";
import axios from "axios";

const page = usePage();
const isAuthenticated = ref(!!page.props.auth?.user);
const cms = computed(() => page.props.cms?.settings || {});
const {
    state,
    items,
    totalPrice,
    count,
    addToCart,
    removeItem,
    updateQty,
    clearCart,
    toggleCart,
} = useCartStore();

// Dynamic DP & Minimum Order from CMS
const dpPercentage = computed(() => parseInt(cms.value.dp_percentage) || 70);
const dpPrice = computed(() =>
    Math.round(totalPrice.value * (dpPercentage.value / 100)),
);

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
        items: items.value.map((i) => ({
            product_id: i.id,
            quantity: i.qty,
            type: i.type,
            box_group_id: null,
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
