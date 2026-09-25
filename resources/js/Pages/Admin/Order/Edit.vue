<template>
  <AdminLayout>
    <Head :title="title || 'Edit Pesanan'" />

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">
      <!-- Top Action Bar / Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <Link
            href="/admin/orders"
            class="p-2.5 bg-white rounded-xl border border-gray-200 text-gray-600 hover:text-brand-600 hover:border-brand-300 transition shadow-xs"
            aria-label="Kembali ke daftar pesanan"
          >
            <ArrowLeft class="w-5 h-5" />
          </Link>
          <div>
            <div class="flex items-center gap-2.5 flex-wrap">
              <h1 class="text-xl sm:text-2xl font-black text-gray-900 font-mono tracking-tight">
                Edit: {{ order.order_number }}
              </h1>
              <span :class="['px-2.5 py-1 rounded-full text-xs font-semibold', statusBadge(form.status)]">
                {{ statusLabel(form.status) }}
              </span>
              <span
                v-if="derivedPackageType === 'snack_box'"
                class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80 shadow-2xs"
              >
                Snack Box
              </span>
              <span
                v-else-if="derivedPackageType === 'campuran'"
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
            <p class="text-xs text-gray-500 mt-1">
              Dibuat pada {{ formatDate(order.created_at) }} • Terakhir diperbarui {{ formatDate(order.updated_at) }}
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
          <Link
            :href="`/admin/orders/${order.id}`"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition shadow-xs"
          >
            <Eye class="w-4 h-4 text-gray-500" />
            <span>Lihat Detail</span>
          </Link>

          <button
            type="button"
            :disabled="processing || form.items.length === 0"
            @click="submitForm"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 disabled:opacity-50 text-white rounded-xl text-sm font-bold transition shadow-sm shadow-brand-500/20 cursor-pointer"
          >
            <Save v-if="!processing" class="w-4 h-4" />
            <Loader2 v-else class="w-4 h-4 animate-spin" />
            <span>{{ processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
          </button>
        </div>
      </div>

      <!-- Alert Errors if any -->
      <div v-if="validationErrors.length > 0" class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm space-y-1 shadow-xs">
        <div class="flex items-center gap-2 font-bold text-rose-900">
          <AlertCircle class="w-4 h-4 text-rose-600 shrink-0" />
          <span>Mohon periksa data pesanan berikut:</span>
        </div>
        <ul class="list-disc list-inside pl-6 space-y-0.5 text-xs text-rose-700">
          <li v-for="(err, idx) in validationErrors" :key="idx">{{ err }}</li>
        </ul>
      </div>

      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Left 2 Cols: Items & Logistics -->
          <div class="lg:col-span-2 space-y-6">

            <!-- Card 1: Daftar Produk / Items Pesanan -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
                <div>
                  <h2 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <Package class="w-4 h-4 text-brand-600" />
                    <span>Daftar Produk Pesanan ({{ form.items.length }} Item)</span>
                  </h2>
                  <p class="text-xs text-gray-500 mt-0.5">Admin dapat menambah kue baru, mengubah jumlah, atau mengubah harga satuan</p>
                </div>
                <button
                  type="button"
                  @click="openAddProductModal"
                  class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 rounded-xl text-xs font-bold transition shadow-2xs cursor-pointer"
                >
                  <Plus class="w-4 h-4" />
                  <span>Tambah Produk</span>
                </button>
              </div>

              <!-- Empty State -->
              <div v-if="form.items.length === 0" class="p-10 text-center text-gray-400">
                <ShoppingBag class="w-12 h-12 mx-auto text-gray-300 mb-2" />
                <p class="font-medium text-gray-600 text-sm">Belum ada item dalam pesanan ini.</p>
                <p class="text-xs text-gray-400 mt-1">Klik tombol "Tambah Produk" di atas untuk menambahkan kue.</p>
              </div>

              <!-- Items Table -->
              <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                  <thead>
                    <tr class="bg-gray-50/80 text-[11px] font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                      <th class="px-5 py-3">Produk</th>
                      <th class="px-3 py-3 text-center">Tipe</th>
                      <th class="px-3 py-3 text-center w-36">Jumlah (Qty)</th>
                      <th class="px-4 py-3 text-right w-36">Harga Satuan</th>
                      <th class="px-4 py-3 text-right w-32">Subtotal</th>
                      <th class="px-3 py-3 text-center w-12">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">
                    <tr v-for="(item, idx) in form.items" :key="item.client_id || item.id || idx" class="hover:bg-gray-50/50 transition">
                      <!-- Product Info -->
                      <td class="px-5 py-4">
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
                          <div class="min-w-0">
                            <p class="font-bold text-gray-900 text-xs sm:text-sm line-clamp-1">
                              {{ item.product?.name || 'Produk ID #' + item.product_id }}
                            </p>
                            <p class="text-[11px] text-gray-400 truncate">
                              {{ item.product?.category?.name || 'Kue' }} • {{ item.product?.supplier?.name || 'Mitra' }}
                            </p>
                          </div>
                        </div>
                      </td>

                      <!-- Item Type Badge -->
                      <td class="px-3 py-4 text-center">
                        <span
                          v-if="item.type === 'kustom_box'"
                          class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200"
                        >
                          Snack Box
                        </span>
                        <span
                          v-else
                          class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200"
                        >
                          Kue Satuan
                        </span>
                      </td>

                      <!-- Quantity Controls -->
                      <td class="px-3 py-4 text-center">
                        <div class="inline-flex items-center rounded-lg border border-gray-200 bg-white shadow-2xs">
                          <button
                            type="button"
                            @click="adjustItemQty(idx, -1)"
                            class="px-2 py-1 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-l-lg transition cursor-pointer"
                            aria-label="Kurangi jumlah"
                          >
                            <Minus class="w-3.5 h-3.5" />
                          </button>
                          <input
                            type="number"
                            v-model.number="item.quantity"
                            @input="onItemQtyChange(idx)"
                            min="1"
                            class="w-14 text-center border-0 py-1 text-xs sm:text-sm font-bold text-gray-900 focus:ring-0 focus:outline-none p-0"
                          />
                          <button
                            type="button"
                            @click="adjustItemQty(idx, 1)"
                            class="px-2 py-1 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-r-lg transition cursor-pointer"
                            aria-label="Tambah jumlah"
                          >
                            <Plus class="w-3.5 h-3.5" />
                          </button>
                        </div>
                      </td>

                      <!-- Price Input -->
                      <td class="px-4 py-4 text-right">
                        <div class="relative rounded-lg shadow-2xs">
                          <span class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-xs text-gray-400 font-mono">Rp</span>
                          <input
                            type="number"
                            v-model.number="item.price_at_order"
                            @input="onItemPriceChange"
                            min="0"
                            step="500"
                            class="w-28 pl-7 pr-2 py-1 text-right text-xs sm:text-sm font-mono font-semibold text-gray-900 rounded-lg border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:outline-none"
                          />
                        </div>
                      </td>

                      <!-- Subtotal -->
                      <td class="px-4 py-4 text-right font-mono font-bold text-xs sm:text-sm text-brand-700">
                        Rp {{ formatNumber(item.quantity * item.price_at_order) }}
                      </td>

                      <!-- Remove Action -->
                      <td class="px-3 py-4 text-center">
                        <button
                          type="button"
                          @click="removeItem(idx)"
                          class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                          title="Hapus Item"
                          aria-label="Hapus item"
                        >
                          <Trash2 class="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <!-- Table Footer Subtotal -->
                  <tfoot>
                    <tr class="bg-gray-50/90 font-bold border-t border-gray-200">
                      <td colspan="4" class="px-5 py-3 text-right text-xs uppercase tracking-wider text-gray-600">
                        Total Subtotal Produk:
                      </td>
                      <td class="px-4 py-3 text-right font-mono text-sm text-brand-700">
                        Rp {{ formatNumber(itemsSubtotal) }}
                      </td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <!-- Card 2: Informasi Pemesan & Pengambilan -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 text-base flex items-center gap-2">
                  <User class="w-4 h-4 text-brand-600" />
                  <span>Informasi Pemesan & Pengambilan</span>
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Kontak pelanggan dan jadwal pengambilan/pengantaran pesanan</p>
              </div>

              <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <!-- Customer Name -->
                  <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Pemesan</label>
                    <input
                      type="text"
                      v-model="form.customer_name"
                      required
                      class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition"
                      placeholder="Nama lengkap pemesan"
                    />
                  </div>

                  <!-- Customer Phone -->
                  <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Nomor WhatsApp / Telepon</label>
                    <input
                      type="tel"
                      v-model="form.customer_phone"
                      required
                      class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition font-mono"
                      placeholder="Contoh: 08123456789"
                    />
                  </div>
                </div>

                <!-- Pickup Datetime -->
                <div>
                  <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal & Jam Pengambilan</label>
                  <input
                    type="datetime-local"
                    v-model="form.pickup_date"
                    required
                    class="w-full sm:w-80 px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition"
                  />
                  <p class="text-[11px] text-gray-400 mt-1">Waktu pesanan harus disiapkan dapur dan siap diambil / diantar</p>
                </div>

                <!-- Delivery Method Selector (Ambil di Toko vs Diantar) -->
                <div class="space-y-2">
                  <label class="block text-xs font-bold text-gray-700">Metode & Lokasi Pengambilan / Pengiriman</label>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button
                      type="button"
                      @click="setDeliveryMethod('pickup')"
                      :class="[
                        'p-3 rounded-xl border text-left transition flex items-center gap-3 cursor-pointer',
                        deliveryMethod === 'pickup'
                          ? 'border-brand-500 bg-brand-50/70 text-brand-900 ring-2 ring-brand-500/15'
                          : 'border-gray-200 bg-white hover:bg-gray-50 text-gray-700'
                      ]"
                    >
                      <div :class="['w-8 h-8 rounded-lg flex items-center justify-center shrink-0', deliveryMethod === 'pickup' ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-600']">
                        <Store class="w-4 h-4" />
                      </div>
                      <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-bold leading-tight">Ambil di Toko</p>
                        <p class="text-[11px] text-gray-500 leading-tight truncate">Ambil langsung di tempat usaha</p>
                      </div>
                    </button>

                    <button
                      type="button"
                      @click="setDeliveryMethod('delivery')"
                      :class="[
                        'p-3 rounded-xl border text-left transition flex items-center gap-3 cursor-pointer',
                        deliveryMethod === 'delivery'
                          ? 'border-brand-500 bg-brand-50/70 text-brand-900 ring-2 ring-brand-500/15'
                          : 'border-gray-200 bg-white hover:bg-gray-50 text-gray-700'
                      ]"
                    >
                      <div :class="['w-8 h-8 rounded-lg flex items-center justify-center shrink-0', deliveryMethod === 'delivery' ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-600']">
                        <Truck class="w-4 h-4" />
                      </div>
                      <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-bold leading-tight">Diantar ke Lokasi</p>
                        <p class="text-[11px] text-gray-500 leading-tight truncate">Pengiriman ke alamat kustom</p>
                      </div>
                    </button>
                  </div>

                  <!-- Store Address Banner -->
                  <div
                    v-if="deliveryMethod === 'pickup'"
                    class="bg-amber-50/90 border border-amber-200 rounded-xl p-3 text-xs space-y-1 mt-2"
                  >
                    <div class="flex items-center gap-1.5 font-bold text-amber-900">
                      <MapPin class="w-3.5 h-3.5 text-amber-700 shrink-0" />
                      <span>Alamat Tempat Usaha (Otomatis):</span>
                    </div>
                    <p class="text-gray-700 pl-5 leading-relaxed">{{ storeAddress }}</p>
                  </div>

                  <!-- Delivery Custom Textarea -->
                  <div v-else class="mt-2">
                    <textarea
                      v-model="customLocation"
                      rows="2"
                      required
                      placeholder="Masukkan alamat lengkap pengiriman atau lokasi acara..."
                      class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition resize-none"
                    ></textarea>
                    <p class="text-[11px] text-gray-400 mt-1">Alamat ini akan tertera pada invoice dan tracking kurir</p>
                  </div>
                </div>

                <!-- Notes -->
                <div>
                  <label class="block text-xs font-bold text-gray-700 mb-1.5">Catatan Pesanan (Opsional)</label>
                  <textarea
                    v-model="form.notes"
                    rows="2"
                    placeholder="Contoh: pita ucapan selamat, tanpa kemasan mika, dll."
                    class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition resize-none"
                  ></textarea>
                </div>
              </div>
            </div>

          </div>

          <!-- Right 1 Col: Status & Pricing Card (Sticky) -->
          <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-6">
              <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 text-base flex items-center gap-2">
                  <CreditCard class="w-4 h-4 text-brand-600" />
                  <span>Status & Pembayaran</span>
                </h2>
              </div>

              <div class="p-6 space-y-5">
                <!-- Status Pesanan -->
                <div>
                  <label class="block text-xs font-bold text-gray-700 mb-1.5">Status Pesanan</label>
                  <select
                    v-model="form.status"
                    class="w-full px-3.5 py-2.5 text-sm font-semibold border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition bg-white"
                  >
                    <option v-for="st in statuses" :key="st.value" :value="st.value">
                      {{ st.label }}
                    </option>
                  </select>
                </div>

                <!-- Tipe Pembayaran (DP vs Full) -->
                <div>
                  <label class="block text-xs font-bold text-gray-700 mb-1.5">Metode Pembayaran</label>
                  <div class="grid grid-cols-2 gap-2">
                    <button
                      type="button"
                      @click="setPaymentType('dp')"
                      :class="[
                        'py-2 px-3 rounded-xl border text-xs font-bold text-center transition cursor-pointer',
                        form.payment_type === 'dp'
                          ? 'border-brand-500 bg-brand-50 text-brand-700 ring-2 ring-brand-500/15'
                          : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'
                      ]"
                    >
                      DP ({{ dpPercentage }}%)
                    </button>
                    <button
                      type="button"
                      @click="setPaymentType('full')"
                      :class="[
                        'py-2 px-3 rounded-xl border text-xs font-bold text-center transition cursor-pointer',
                        form.payment_type === 'full'
                          ? 'border-emerald-500 bg-emerald-50 text-emerald-800 ring-2 ring-emerald-500/15'
                          : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'
                      ]"
                    >
                      Bayar Penuh (100%)
                    </button>
                  </div>
                </div>

                <!-- Total Amount & DP Amounts -->
                <div class="pt-3 border-t border-gray-100 space-y-4">
                  <!-- Auto Calculation Toggle -->
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold text-gray-600">Hitung Otomatis dari Item</label>
                    <button
                      type="button"
                      @click="syncTotalFromItems"
                      class="text-xs text-brand-600 hover:text-brand-800 font-bold hover:underline flex items-center gap-1 cursor-pointer"
                    >
                      <RefreshCw class="w-3 h-3" />
                      <span>Hitung Ulang</span>
                    </button>
                  </div>

                  <!-- Total Tagihan -->
                  <div>
                    <div class="flex justify-between items-center mb-1">
                      <label class="text-xs font-bold text-gray-700">Total Tagihan (Rp)</label>
                      <span class="text-[10px] text-gray-400 font-mono">Subtotal item: {{ formatNumber(itemsSubtotal) }}</span>
                    </div>
                    <input
                      type="number"
                      v-model.number="form.total_amount"
                      min="0"
                      class="w-full px-3.5 py-2.5 text-base font-bold font-mono text-brand-700 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition"
                    />
                  </div>

                  <!-- DP Amount -->
                  <div>
                    <div class="flex justify-between items-center mb-1">
                      <label class="text-xs font-bold text-gray-700">Uang Muka / DP (Rp)</label>
                      <span class="text-[10px] text-gray-400 font-mono">
                        {{ form.payment_type === 'full' ? '100% Lunas' : `${dpPercentage}% Target` }}
                      </span>
                    </div>
                    <input
                      type="number"
                      v-model.number="form.dp_amount"
                      min="0"
                      class="w-full px-3.5 py-2.5 text-base font-bold font-mono text-amber-700 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition"
                    />
                  </div>

                  <!-- Remaining Calculation -->
                  <div class="bg-gray-50 rounded-xl p-3.5 border border-gray-100 space-y-1 text-xs">
                    <div class="flex justify-between text-gray-600">
                      <span>Total Tagihan:</span>
                      <span class="font-mono font-bold text-gray-900">Rp {{ formatNumber(form.total_amount) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                      <span>Uang Muka (DP):</span>
                      <span class="font-mono font-bold text-amber-700">Rp {{ formatNumber(form.dp_amount) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-800 font-bold border-t border-dashed border-gray-200 pt-1.5">
                      <span>Sisa Pelunasan:</span>
                      <span class="font-mono text-brand-700">Rp {{ formatNumber(remainingPayment) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-3 border-t border-gray-100">
                  <button
                    type="submit"
                    :disabled="processing || form.items.length === 0"
                    class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 disabled:opacity-50 text-white rounded-xl text-sm font-bold transition shadow-md shadow-brand-500/20 flex items-center justify-center gap-2 cursor-pointer"
                  >
                    <Save v-if="!processing" class="w-4 h-4" />
                    <Loader2 v-else class="w-4 h-4 animate-spin" />
                    <span>{{ processing ? 'Menyimpan...' : 'Simpan Semua Perubahan' }}</span>
                  </button>
                  <Link
                    href="/admin/orders"
                    class="block text-center mt-2.5 text-xs text-gray-500 hover:text-gray-800 transition"
                  >
                    Batal dan Kembali
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Product Picker Modal -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="isAddProductModalOpen"
          class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-6"
          @click="closeAddProductModal"
        >
          <div
            class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden my-auto max-h-[90vh] flex flex-col"
            @click.stop
          >
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between shrink-0">
              <div>
                <h3 class="font-bold text-gray-900 text-base">Tambah Produk ke Pesanan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Pilih produk kue untuk ditambahkan ke daftar pesanan</p>
              </div>
              <button
                type="button"
                @click="closeAddProductModal"
                class="p-1.5 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-200/60 transition cursor-pointer"
                aria-label="Tutup"
              >
                <X class="w-5 h-5" />
              </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto space-y-4 flex-1">
              <!-- Search & Filter -->
              <div class="relative">
                <Search class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input
                  type="text"
                  v-model="productSearch"
                  placeholder="Cari nama kue atau kategori..."
                  class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none transition"
                />
              </div>

              <!-- Product List -->
              <div class="max-h-60 overflow-y-auto divide-y divide-gray-100 border border-gray-100 rounded-xl">
                <div
                  v-for="prod in filteredProducts"
                  :key="prod.id"
                  @click="selectProductToAdd(prod)"
                  :class="[
                    'p-3 flex items-center justify-between gap-3 cursor-pointer transition',
                    selectedProduct?.id === prod.id ? 'bg-brand-50/80 ring-2 ring-brand-500/20' : 'hover:bg-gray-50'
                  ]"
                >
                  <div class="flex items-center gap-3 min-w-0">
                    <img
                      v-if="prod.image_url"
                      :src="prod.image_url"
                      :alt="prod.name"
                      class="w-10 h-10 rounded-lg object-cover border border-gray-200 shrink-0"
                    />
                    <div
                      v-else
                      class="w-10 h-10 rounded-lg bg-cream-100 text-brand-600 flex items-center justify-center shrink-0 border border-cream-200"
                    >
                      <Package class="w-5 h-5" />
                    </div>
                    <div class="min-w-0">
                      <p class="font-bold text-xs sm:text-sm text-gray-900 truncate">{{ prod.name }}</p>
                      <p class="text-[11px] text-gray-400">{{ prod.category?.name || 'Kue' }} • {{ prod.supplier?.name || 'Mitra' }}</p>
                    </div>
                  </div>
                  <div class="text-right shrink-0">
                    <p class="font-mono font-bold text-xs sm:text-sm text-brand-700">Rp {{ formatNumber(prod.sell_price) }}</p>
                    <span
                      v-if="selectedProduct?.id === prod.id"
                      class="inline-flex items-center gap-1 text-[10px] font-bold text-brand-600 bg-brand-100/80 px-2 py-0.5 rounded-full mt-0.5"
                    >
                      <Check class="w-3 h-3" /> Terpilih
                    </span>
                  </div>
                </div>
                <div v-if="filteredProducts.length === 0" class="p-6 text-center text-gray-400 text-xs">
                  Tidak ada produk yang cocok dengan pencarian "{{ productSearch }}"
                </div>
              </div>

              <!-- Options for Selected Product -->
              <div v-if="selectedProduct" class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold text-gray-700">Pengaturan Produk Terpilih:</span>
                  <span class="text-xs font-bold text-brand-700">{{ selectedProduct.name }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <!-- Type -->
                  <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Tipe Item</label>
                    <select
                      v-model="newProductConfig.type"
                      class="w-full px-2.5 py-1.5 text-xs font-semibold border border-gray-200 rounded-lg focus:outline-none bg-white"
                    >
                      <option value="satuan">Kue Satuan</option>
                      <option value="kustom_box">Snack Box</option>
                    </select>
                  </div>

                  <!-- Initial Qty -->
                  <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Jumlah (Qty)</label>
                    <input
                      type="number"
                      v-model.number="newProductConfig.quantity"
                      min="1"
                      class="w-full px-2.5 py-1.5 text-xs font-bold font-mono border border-gray-200 rounded-lg focus:outline-none"
                    />
                  </div>

                  <!-- Unit Price -->
                  <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Harga Satuan (Rp)</label>
                    <input
                      type="number"
                      v-model.number="newProductConfig.price_at_order"
                      min="0"
                      step="500"
                      class="w-full px-2.5 py-1.5 text-xs font-bold font-mono border border-gray-200 rounded-lg focus:outline-none"
                    />
                  </div>
                </div>

                <!-- Subtotal preview -->
                <div class="flex justify-between items-center text-xs pt-1 border-t border-gray-200">
                  <span class="text-gray-500">Subtotal Tambahan:</span>
                  <span class="font-mono font-bold text-brand-700">
                    Rp {{ formatNumber(newProductConfig.quantity * newProductConfig.price_at_order) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex items-center justify-end gap-2.5 shrink-0">
              <button
                type="button"
                @click="closeAddProductModal"
                class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition cursor-pointer"
              >
                Batal
              </button>
              <button
                type="button"
                :disabled="!selectedProduct || newProductConfig.quantity <= 0"
                @click="confirmAddProduct"
                class="px-4 py-2 bg-brand-600 hover:bg-brand-700 disabled:opacity-50 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5"
              >
                <Plus class="w-3.5 h-3.5" />
                <span>Tambahkan ke Pesanan</span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
  ArrowLeft, Eye, Save, Plus, Trash2, Minus, AlertCircle,
  Package, ShoppingBag, User, CreditCard, Store, Truck, MapPin,
  RefreshCw, Loader2, X, Search, Check
} from 'lucide-vue-next';

const props = defineProps({
  order: {
    type: Object,
    required: true,
  },
  availableProducts: {
    type: Array,
    default: () => [],
  },
  cmsSettings: {
    type: Object,
    default: () => ({
      dp_percentage: 70,
      company_address: 'Jl. Boulevard Raya No. 88, Bekasi, Jawa Barat 17144',
      company_name: 'Padu Kue',
    }),
  },
  statuses: {
    type: Array,
    default: () => [
      { value: 'pending', label: 'Pending' },
      { value: 'diterima', label: 'Diterima' },
      { value: 'diproses', label: 'Diproses' },
      { value: 'dikemas', label: 'Dikemas' },
      { value: 'dikirim', label: 'Dikirim' },
      { value: 'selesai', label: 'Selesai' },
    ],
  },
  title: {
    type: String,
    default: 'Edit Pesanan',
  },
});

const dpPercentage = computed(() => props.cmsSettings?.dp_percentage || 70);
const storeAddress = computed(() => props.cmsSettings?.company_address || 'Jl. Boulevard Raya No. 88, Bekasi, Jawa Barat 17144');
const storeName = computed(() => props.cmsSettings?.company_name || 'Padu Kue');

// Initial location detection: is it pickup or delivery?
const isInitialPickup = computed(() => {
  const loc = props.order.location || '';
  return loc.startsWith('Ambil di Tempat');
});

const deliveryMethod = ref(isInitialPickup.value ? 'pickup' : 'delivery');
const customLocation = ref(isInitialPickup.value ? '' : (props.order.location || ''));

const resolvedLocation = computed(() => {
  if (deliveryMethod.value === 'pickup') {
    return `Ambil di Tempat (${storeName.value}: ${storeAddress.value})`;
  }
  return customLocation.value.trim();
});

const setDeliveryMethod = (method) => {
  deliveryMethod.value = method;
  form.value.location = resolvedLocation.value;
};

// Format pickup_date for datetime-local input
const formatPickupDateForInput = (dateStr) => {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return '';
  // Format as YYYY-MM-DDTHH:mm
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  const hours = String(d.getHours()).padStart(2, '0');
  const minutes = String(d.getMinutes()).padStart(2, '0');
  return `${year}-${month}-${day}T${hours}:${minutes}`;
};

// Form state
const form = ref({
  customer_name: props.order.customer_name || '',
  customer_phone: props.order.customer_phone || '',
  pickup_date: formatPickupDateForInput(props.order.pickup_date),
  location: props.order.location || '',
  notes: props.order.notes || '',
  status: props.order.status || 'pending',
  payment_type: props.order.payment_type || 'dp',
  dp_amount: parseFloat(props.order.dp_amount) || 0,
  total_amount: parseFloat(props.order.total_amount) || 0,
  items: (props.order.items || []).map((it, idx) => ({
    client_id: `existing_${it.id}_${idx}`,
    id: it.id,
    product_id: it.product?.id || it.product_id,
    product: it.product,
    quantity: parseInt(it.quantity) || 1,
    price_at_order: parseFloat(it.price_at_order) || 0,
    type: it.type || 'satuan',
    box_group_id: it.box_group_id || null,
  })),
});

// Calculations
const itemsSubtotal = computed(() => {
  return form.value.items.reduce((sum, item) => {
    return sum + ((item.quantity || 0) * (item.price_at_order || 0));
  }, 0);
});

const derivedPackageType = computed(() => {
  const hasBox = form.value.items.some(i => i.type === 'kustom_box');
  const hasSatuan = form.value.items.some(i => i.type === 'satuan');
  if (hasBox && hasSatuan) return 'campuran';
  if (hasBox) return 'snack_box';
  return 'satuan';
});

const remainingPayment = computed(() => {
  return Math.max(0, form.value.total_amount - form.value.dp_amount);
});

const setPaymentType = (type) => {
  form.value.payment_type = type;
  if (type === 'full') {
    form.value.dp_amount = form.value.total_amount;
  } else {
    form.value.dp_amount = Math.round((form.value.total_amount * dpPercentage.value) / 100);
  }
};

const syncTotalFromItems = () => {
  form.value.total_amount = itemsSubtotal.value;
  if (form.value.payment_type === 'full') {
    form.value.dp_amount = itemsSubtotal.value;
  } else {
    form.value.dp_amount = Math.round((itemsSubtotal.value * dpPercentage.value) / 100);
  }
};

const onItemQtyChange = (idx) => {
  if (form.value.items[idx].quantity < 1) {
    form.value.items[idx].quantity = 1;
  }
  syncTotalFromItems();
};

const adjustItemQty = (idx, delta) => {
  const current = form.value.items[idx].quantity || 1;
  const next = Math.max(1, current + delta);
  form.value.items[idx].quantity = next;
  syncTotalFromItems();
};

const onItemPriceChange = () => {
  syncTotalFromItems();
};

const removeItem = (idx) => {
  if (form.value.items.length <= 1) {
    alert('Pesanan harus memiliki minimal 1 item produk.');
    return;
  }
  form.value.items.splice(idx, 1);
  syncTotalFromItems();
};

// Add Product Modal State
const isAddProductModalOpen = ref(false);
const productSearch = ref('');
const selectedProduct = ref(null);
const newProductConfig = ref({
  type: 'satuan',
  quantity: 10,
  price_at_order: 0,
});

const filteredProducts = computed(() => {
  const q = productSearch.value.toLowerCase().trim();
  if (!q) return props.availableProducts;
  return props.availableProducts.filter(p => {
    const nameMatch = p.name?.toLowerCase().includes(q);
    const catMatch = p.category?.name?.toLowerCase().includes(q);
    return nameMatch || catMatch;
  });
});

const openAddProductModal = () => {
  productSearch.value = '';
  selectedProduct.value = null;
  newProductConfig.value = {
    type: 'satuan',
    quantity: 10,
    price_at_order: 0,
  };
  isAddProductModalOpen.value = true;
};

const closeAddProductModal = () => {
  isAddProductModalOpen.value = false;
};

const selectProductToAdd = (prod) => {
  selectedProduct.value = prod;
  newProductConfig.value.price_at_order = parseFloat(prod.sell_price) || 0;
};

const confirmAddProduct = () => {
  if (!selectedProduct.value) return;

  const newItem = {
    client_id: `new_${Date.now()}_${Math.random()}`,
    id: null,
    product_id: selectedProduct.value.id,
    product: selectedProduct.value,
    quantity: Math.max(1, parseInt(newProductConfig.value.quantity) || 1),
    price_at_order: parseFloat(newProductConfig.value.price_at_order) || 0,
    type: newProductConfig.value.type,
    box_group_id: newProductConfig.value.type === 'kustom_box' ? String(Date.now()) : null,
  };

  form.value.items.push(newItem);
  syncTotalFromItems();
  closeAddProductModal();
};

// Form submission
const processing = ref(false);
const validationErrors = ref([]);

const submitForm = () => {
  validationErrors.value = [];

  if (!form.value.customer_name.trim()) {
    validationErrors.value.push('Nama pemesan belum diisi.');
  }
  if (!form.value.customer_phone.trim()) {
    validationErrors.value.push('Nomor WhatsApp belum diisi.');
  }
  if (!form.value.pickup_date) {
    validationErrors.value.push('Tanggal & jam pengambilan belum ditentukan.');
  }

  // Ensure location is updated
  form.value.location = resolvedLocation.value;
  if (!form.value.location.trim()) {
    validationErrors.value.push('Lokasi atau alamat pengiriman belum diisi.');
  }

  if (form.value.items.length === 0) {
    validationErrors.value.push('Pesanan harus memiliki minimal 1 item produk.');
  }

  if (validationErrors.value.length > 0) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    return;
  }

  processing.value = true;

  const payload = {
    customer_name: form.value.customer_name.trim(),
    customer_phone: form.value.customer_phone.trim(),
    pickup_date: form.value.pickup_date,
    location: form.value.location.trim(),
    notes: form.value.notes ? form.value.notes.trim() : null,
    status: form.value.status,
    payment_type: form.value.payment_type,
    dp_amount: form.value.dp_amount,
    total_amount: form.value.total_amount,
    items: form.value.items.map(it => ({
      id: it.id || null,
      product_id: it.product_id,
      quantity: it.quantity,
      price_at_order: it.price_at_order,
      type: it.type,
      box_group_id: it.box_group_id || null,
    })),
  };

  router.put(`/admin/orders/${props.order.id}`, payload, {
    onError: (errs) => {
      validationErrors.value = Object.values(errs).flat();
      processing.value = false;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    onSuccess: () => {
      processing.value = false;
    },
    onFinish: () => {
      processing.value = false;
    },
  });
};

// Utilities
const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(Math.round(num || 0));

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(d);
};

const statusLabel = (st) => {
  const map = {
    pending: 'Pending',
    diterima: 'Diterima',
    diproses: 'Diproses',
    dikemas: 'Dikemas',
    dikirim: 'Dikirim',
    selesai: 'Selesai',
  };
  return map[st] || st;
};

const statusBadge = (st) => {
  const map = {
    pending: 'bg-amber-100 text-amber-800 border border-amber-200',
    diterima: 'bg-blue-100 text-blue-800 border border-blue-200',
    diproses: 'bg-indigo-100 text-indigo-800 border border-indigo-200',
    dikemas: 'bg-purple-100 text-purple-800 border border-purple-200',
    dikirim: 'bg-cyan-100 text-cyan-800 border border-cyan-200',
    selesai: 'bg-emerald-100 text-emerald-800 border border-emerald-200',
  };
  return map[st] || 'bg-gray-100 text-gray-800';
};
</script>
