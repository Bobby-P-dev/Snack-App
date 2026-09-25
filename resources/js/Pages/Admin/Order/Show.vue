<template>
  <AdminLayout>
    <div class="p-6 max-w-7xl mx-auto space-y-6">
      <!-- Top Navigation / Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <Link
            href="/admin/orders"
            class="p-2 bg-white rounded-xl border border-gray-200 text-gray-600 hover:text-brand-600 hover:border-brand-300 transition"
            aria-label="Kembali ke daftar pesanan"
          >
            <ArrowLeft class="w-5 h-5" />
          </Link>
          <div>
            <div class="flex items-center gap-2 flex-wrap">
              <h1 class="text-2xl font-bold text-gray-900 font-mono">{{ order.order_number }}</h1>
              <span :class="['px-2.5 py-1 rounded-full text-xs font-semibold', statusBadge(order.status)]">
                {{ statusLabel(order.status) }}
              </span>
              <span
                v-if="order.package_type === 'snack_box'"
                class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80 shadow-2xs"
              >
                Paket Snack Box
              </span>
              <span
                v-else-if="order.package_type === 'campuran'"
                class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80 shadow-2xs"
              >
                Campuran (Box + Satuan)
              </span>
              <span
                v-else
                class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs"
              >
                Kue Satuan
              </span>
            </div>
            <p class="text-xs text-gray-500 mt-0.5">Dipesan pada {{ formatDate(order.created_at) }}</p>
          </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
          <Link
            :href="`/admin/orders/${order.id}/edit`"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition shadow-sm"
          >
            <Pencil class="w-4 h-4" />
            Edit Pesanan
          </Link>

          <a
            :href="`/pdf/invoice/${order.order_number}`"
            target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm"
          >
            <Download class="w-4 h-4 text-red-500" />
            Download Invoice PDF
          </a>

          <a
            v-if="order.customer_phone"
            :href="`https://wa.me/${formatWaNumber(order.customer_phone)}`"
            target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-semibold transition shadow-sm"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12c0 1.76.46 3.42 1.25 4.86L2 22l5.35-1.21A9.95 9.95 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm.05 18.06c-1.46 0-2.88-.38-4.14-1.12l-.3-.17-3.07.7.72-2.95-.19-.31A7.95 7.95 0 014.05 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8zm4.42-5.44c-.24-.12-1.44-.71-1.66-.79-.23-.08-.39-.12-.56.12-.16.24-.62.79-.77.95-.14.16-.3.18-.54.06-.24-.12-1.02-.38-1.95-1.21-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.42.06-.64.3s-.84.82-.84 2.01c0 1.19.86 2.34.98 2.5.12.16 1.7 2.6 4.12 3.65.57.25 1.02.39 1.37.5.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.23-.16-.47-.28z"/></svg>
            Hubungi Customer
          </a>
        </div>
      </div>

      <!-- Main Layout Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Items & Notes (2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Order Items Card -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <h2 class="font-bold text-gray-900 text-base">Rincian Produk Pesanan</h2>
              <span class="text-xs text-gray-500 font-medium">{{ orderItems.length }} Item</span>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm">
                <thead>
                  <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-4 py-3">Suplier</th>
                    <th class="px-4 py-3 text-center">Qty</th>
                    <th class="px-4 py-3 text-right">Harga</th>
                    <th class="px-6 py-3 text-right">Subtotal</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr v-for="item in orderItems" :key="item.id" class="hover:bg-gray-50/60">
                    <td class="px-6 py-4">
                      <div class="flex items-center gap-3">
                        <img
                          v-if="item.product?.image_url"
                          :src="item.product.image_url"
                          :alt="item.product?.name"
                          class="w-10 h-10 rounded-xl object-cover border border-gray-100 shrink-0"
                        />
                        <div
                          v-else
                          class="w-10 h-10 rounded-xl bg-cream-100 text-brand-600 flex items-center justify-center shrink-0 border border-cream-200"
                        >
                          <Package class="w-5 h-5" />
                        </div>
                        <div>
                          <p class="font-bold text-gray-900">{{ item.product?.name || 'Produk' }}</p>
                          <span
                            :class="[
                              'inline-block mt-0.5 text-[11px] px-2 py-0.5 rounded font-semibold',
                              item.type === 'kustom_box' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700'
                            ]"
                          >
                            {{ item.type === 'kustom_box' ? 'Snack Box' : 'Kue Satuan' }}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td class="px-4 py-4 text-gray-600 text-xs">
                      {{ item.product?.supplier?.name || '-' }}
                    </td>
                    <td class="px-4 py-4 text-center font-bold text-gray-800">
                      {{ item.quantity }}
                    </td>
                    <td class="px-4 py-4 text-right text-gray-600 font-mono text-xs">
                      Rp {{ formatNumber(item.price_at_order) }}
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900 font-mono">
                      Rp {{ formatNumber(item.price_at_order * item.quantity) }}
                    </td>
                  </tr>

                  <tr v-if="orderItems.length === 0">
                    <td colspan="5" class="py-10 text-center text-gray-400 text-sm">
                      Belum ada rincian produk pesanan.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Customer & Location Card -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-gray-900 text-base">Informasi Pengiriman & Pemesan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
              <div class="bg-gray-50 p-4 rounded-xl">
                <span class="text-xs text-gray-500 uppercase tracking-wider block font-medium">Nama Pemesan</span>
                <p class="text-gray-900 font-semibold mt-1">{{ order.customer_name }}</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-xl">
                <span class="text-xs text-gray-500 uppercase tracking-wider block font-medium">No. Telepon / WA</span>
                <p class="text-gray-900 font-semibold mt-1 font-mono">{{ order.customer_phone || '-' }}</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-xl sm:col-span-2">
                <span class="text-xs text-gray-500 uppercase tracking-wider block font-medium">Jadwal Pengambilan</span>
                <p class="text-gray-900 font-semibold mt-1">
                  {{ formatDate(order.pickup_date) }}
                </p>
              </div>
              <div class="bg-gray-50 p-4 rounded-xl sm:col-span-2">
                <span class="text-xs text-gray-500 uppercase tracking-wider block font-medium">Lokasi / Alamat</span>
                <p class="text-gray-800 mt-1 whitespace-pre-line">{{ order.location || '-' }}</p>
              </div>
              <div v-if="order.notes" class="bg-amber-50/70 border border-amber-200/80 p-4 rounded-xl sm:col-span-2">
                <span class="text-xs text-amber-800 uppercase tracking-wider block font-bold">Catatan Khusus dari Pemesan</span>
                <p class="text-gray-800 mt-1 font-medium text-sm whitespace-pre-line leading-relaxed">
                  "{{ order.notes }}"
                </p>
              </div>
              <div v-else class="bg-gray-50 p-4 rounded-xl sm:col-span-2">
                <span class="text-xs text-gray-500 uppercase tracking-wider block font-medium">Catatan Pesanan</span>
                <p class="text-gray-400 mt-1 text-sm italic">- Tidak ada catatan khusus -</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Status Update & Summary (1 Column) -->
        <div class="space-y-6">
          <!-- Update Status Card -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-gray-900 text-base">Kelola Status Pesanan</h2>
            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-2">Ubah Status</label>
              <CustomSelect
                v-model="selectedStatus"
                :options="statusOptions"
                variant="admin"
                size="md"
                :full-width="true"
              />
            </div>

            <button
              @click="submitStatusUpdate"
              :disabled="updatingStatus || selectedStatus === order.status"
              class="w-full bg-brand-500 hover:bg-brand-600 disabled:bg-gray-300 text-white font-bold py-2.5 px-4 rounded-xl transition text-sm flex items-center justify-center shadow-sm"
            >
              <span v-if="!updatingStatus">Simpan Perubahan Status</span>
              <span v-else>Menyimpan...</span>
            </button>
          </div>

          <!-- Payment Summary Card -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-gray-900 text-base">Ringkasan Pembayaran</h2>
            <div class="space-y-2.5 text-sm">
              <div class="flex justify-between items-center text-gray-600">
                <span>Metode Pembayaran</span>
                <span :class="['px-2.5 py-0.5 rounded-full text-xs font-bold', order.payment_type === 'full' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700']">
                  {{ order.payment_type === 'full' ? 'Bayar Penuh (100%)' : 'Uang Muka (DP 70%)' }}
                </span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Total Tagihan</span>
                <span class="font-semibold text-gray-900 font-mono">Rp {{ formatNumber(order.total_amount) }}</span>
              </div>
              <div class="flex justify-between text-orange-600">
                <span>DP (Uang Muka)</span>
                <span class="font-semibold font-mono">Rp {{ formatNumber(order.dp_amount) }}</span>
              </div>
              <div class="flex justify-between text-gray-600 border-t border-dashed border-gray-200 pt-2 font-medium">
                <span>Sisa Pelunasan</span>
                <span class="font-bold text-gray-900 font-mono">
                  Rp {{ formatNumber(Math.max(0, order.total_amount - order.dp_amount)) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Download, Package, Pencil } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CustomSelect from '@/Components/UI/CustomSelect.vue';

const props = defineProps({
  order: {
    type: Object,
    required: true,
  },
  title: {
    type: String,
    default: 'Detail Pesanan',
  },
});

const orderItems = computed(() => {
  if (!props.order) return [];
  if (Array.isArray(props.order.items)) return props.order.items;
  if (Array.isArray(props.order.items?.data)) return props.order.items.data;
  return [];
});

const selectedStatus = ref(props.order.status || 'pending');
const updatingStatus = ref(false);

const statusOptions = [
  { value: 'pending', label: 'Pending', description: 'Menunggu DP', dotClass: 'bg-amber-400' },
  { value: 'diterima', label: 'Diterima', description: 'DP Terverifikasi', dotClass: 'bg-blue-400' },
  { value: 'diproses', label: 'Diproses', description: 'Sedang Dibuat', dotClass: 'bg-indigo-400' },
  { value: 'dikemas', label: 'Dikemas', description: 'Packaging', dotClass: 'bg-purple-400' },
  { value: 'dikirim', label: 'Dikirim', description: 'Siap Ambil / Kirim', dotClass: 'bg-teal-400' },
  { value: 'selesai', label: 'Selesai', description: 'Lunas', dotClass: 'bg-emerald-400' },
  { value: 'batal', label: 'Batal', description: 'Pesanan Dibatalkan', dotClass: 'bg-rose-400' },
];

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }) + ' WIB';
};

const formatWaNumber = (phone) => {
  let cleaned = String(phone).replace(/\D/g, '');
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.slice(1);
  }
  return cleaned;
};

const statusLabel = (status) => {
  const map = {
    pending: 'Pending',
    diterima: 'Diterima',
    diproses: 'Diproses',
    dikemas: 'Dikemas',
    dikirim: 'Dikirim',
    selesai: 'Selesai',
    batal: 'Batal',
  };
  return map[status] || status;
};

const statusBadge = (status) => {
  const map = {
    pending: 'bg-yellow-100 text-yellow-800',
    diterima: 'bg-blue-100 text-blue-800',
    diproses: 'bg-purple-100 text-purple-800',
    dikemas: 'bg-indigo-100 text-indigo-800',
    dikirim: 'bg-cyan-100 text-cyan-800',
    selesai: 'bg-green-100 text-green-800',
    batal: 'bg-red-100 text-red-800',
  };
  return map[status] || 'bg-gray-100 text-gray-800';
};

const submitStatusUpdate = () => {
  updatingStatus.value = true;
  router.post(
    `/admin/orders/${props.order.id}/update-status`,
    { status: selectedStatus.value },
    {
      onFinish: () => {
        updatingStatus.value = false;
      },
    }
  );
};
</script>
