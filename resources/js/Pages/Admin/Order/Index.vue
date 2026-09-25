<template>
  <AdminLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Orders</h2>
          <p class="text-gray-500 text-sm mt-1">Manage all orders</p>
        </div>
      </div>

      <!-- Filters -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 mb-6">
        <div class="relative">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input v-model="searchQuery" @input="search" type="text" placeholder="Cari no. pesanan, nama, telepon..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
        <CustomSelect
          v-model="filterStatus"
          :options="statusOptions"
          variant="admin"
          size="md"
          :full-width="true"
          placeholder="Semua Status"
          @change="applyFilters"
        />
        <CustomSelect
          v-model="filterPackageType"
          :options="packageTypeOptions"
          variant="admin"
          size="md"
          :full-width="true"
          placeholder="Semua Tipe"
          @change="applyFilters"
        />
        <div>
          <input v-model="filterStartDate" @change="applyFilters" type="date" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition" placeholder="Start Date">
        </div>
        <div>
          <input v-model="filterEndDate" @change="applyFilters" type="date" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition" placeholder="End Date">
        </div>
        <div class="flex gap-2">
          <button @click="resetFilters" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition flex-1">Reset</button>
        </div>
      </div>

      <!-- Info Bar -->
      <div class="mb-3 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <strong>{{ pagination.total || orders.length }}</strong> pesanan</p>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Order No</th>
                <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Tipe</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pickup Date</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Amount</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="(order, idx) in orders" :key="order.id" class="hover:bg-blue-50/30 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-400 font-mono">{{ idx + 1 + ((pagination.current_page - 1) * pagination.per_page || 0) }}</td>
                <td class="px-6 py-4 font-mono text-sm font-semibold">
                  <button
                    type="button"
                    @click="openOrderDetail(order)"
                    class="font-mono font-bold text-brand-600 hover:text-brand-800 hover:underline transition text-left cursor-pointer"
                    title="Klik untuk melihat detail rincian"
                  >
                    {{ order.order_number }}
                  </button>
                </td>
                <td class="px-4 py-4 text-center">
                  <span
                    v-if="order.package_type === 'snack_box'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80 shadow-2xs"
                    title="Pesanan Snack Box"
                  >
                    <span>Box</span>
                  </span>
                  <span
                    v-else-if="order.package_type === 'campuran'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80 shadow-2xs"
                    title="Pesanan Campuran (Snack Box + Kue Satuan)"
                  >
                    <span>Campuran</span>
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs"
                    title="Pesanan Kue Satuan"
                  >
                    <span>Satuan</span>
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <p class="font-semibold text-gray-800">{{ order.customer_name }}</p>
                    <p v-if="order.location" class="text-xs text-gray-400 truncate max-w-[200px]">{{ order.location }}</p>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ order.customer_phone || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ formatDate(order.pickup_date) }}</td>
                <td class="px-6 py-4 text-right font-semibold text-brand-600 font-mono">Rp {{ formatNumber(order.total_amount) }}</td>
                <td class="px-6 py-4 text-center">
                  <span :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold', statusClass(order.status)]">
                    <span :class="['w-1.5 h-1.5 rounded-full', statusDot(order.status)]"></span>
                    {{ statusLabel(order.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <!-- Modern Status CustomSelect -->
                    <CustomSelect
                      :model-value="order.status"
                      :options="rowStatusOptions"
                      variant="admin"
                      size="sm"
                      menu-width="w-36"
                      align="right"
                      :placement="idx >= orders.length - 2 ? 'top' : 'bottom'"
                      @update:modelValue="(val) => updateStatus(order, val)"
                    />

                    <!-- Interactive Detail Modal Trigger -->
                    <button
                      type="button"
                      @click="openOrderDetail(order)"
                      class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition shadow-xs cursor-pointer"
                      title="Lihat Detail Pesanan"
                    >
                      <Eye class="w-3.5 h-3.5" />
                      <span>Detail</span>
                    </button>

                    <!-- Edit Order Action Button -->
                    <Link
                      :href="`/admin/orders/${order.id}/edit`"
                      class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-xl text-xs font-bold transition shadow-xs"
                      title="Edit Pesanan"
                    >
                      <Pencil class="w-3.5 h-3.5 text-amber-600" />
                      <span>Edit</span>
                    </Link>
                  </div>
                </td>
              </tr>
              <tr v-if="!orders || orders.length === 0">
                <td colspan="8">
                  <div class="py-16 text-center">
                    <ShoppingBag class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                    <p class="text-lg font-medium text-gray-500 mb-1">Belum ada pesanan</p>
                    <p class="text-sm text-gray-400">Pesanan akan muncul setelah customer melakukan checkout.</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Halaman {{ pagination.current_page }} dari {{ pagination.last_page }} ({{ pagination.total }} pesanan)</p>
        <div class="flex gap-2">
          <button :disabled="pagination.current_page <= 1" @click="goToPage(pagination.current_page - 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
            <ChevronLeft class="w-4 h-4" />
            Prev
          </button>
          <button :disabled="pagination.current_page >= pagination.last_page" @click="goToPage(pagination.current_page + 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
            Next
            <ChevronRight class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Order Detail Modal -->
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
          v-if="isDetailModalOpen"
          class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-6"
          @click="closeDetailModal"
        >
          <div
            class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden my-auto max-h-[92vh] flex flex-col"
            @click.stop
          >
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between shrink-0">
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                  <span class="font-mono text-lg font-black text-gray-900 tracking-tight">
                    {{ selectedOrder?.order_number }}
                  </span>
                  <button
                    type="button"
                    @click="copyOrderNumber(selectedOrder?.order_number)"
                    class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-200/60 rounded-lg transition cursor-pointer"
                    title="Salin No. Pesanan"
                  >
                    <Check v-if="isCopied" class="w-4 h-4 text-emerald-600" />
                    <Copy v-else class="w-4 h-4" />
                  </button>
                </div>
                <span
                  v-if="selectedOrder?.status"
                  :class="['inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold', statusClass(selectedOrder.status)]"
                >
                  <span :class="['w-1.5 h-1.5 rounded-full', statusDot(selectedOrder.status)]"></span>
                  {{ statusLabel(selectedOrder.status) }}
                </span>
              </div>

              <button
                type="button"
                @click="closeDetailModal"
                class="p-1.5 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-200/60 transition cursor-pointer"
                aria-label="Tutup"
              >
                <X class="w-5 h-5" />
              </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto space-y-6 flex-1 divide-y divide-gray-100">
              <!-- Loading Indicator -->
              <div v-if="isLoadingDetail" class="flex items-center justify-center py-8 gap-2 text-brand-600">
                <Loader2 class="w-5 h-5 animate-spin" />
                <span class="text-sm font-medium">Memuat rincian pesanan...</span>
              </div>

              <!-- Error Alert -->
              <div
                v-if="detailError"
                class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm flex items-center gap-2"
              >
                <AlertCircle class="w-5 h-5 shrink-0 text-rose-500" />
                <span>{{ detailError }}</span>
              </div>

              <!-- Customer & Logistics Section -->
              <div v-if="selectedOrder" class="space-y-3 pt-1 first:pt-0">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Informasi Pemesan & Pengambilan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                  <div class="bg-gray-50 p-3.5 rounded-xl flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                      <User class="w-4 h-4" />
                    </div>
                    <div>
                      <p class="text-xs text-gray-400 font-medium">Nama Pemesan</p>
                      <p class="font-bold text-gray-800 mt-0.5">{{ selectedOrder.customer_name }}</p>
                    </div>
                  </div>

                  <div class="bg-gray-50 p-3.5 rounded-xl flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                      <Phone class="w-4 h-4" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-xs text-gray-400 font-medium">No. Telepon / WhatsApp</p>
                      <div class="flex items-center gap-2 mt-0.5">
                        <span class="font-bold font-mono text-gray-800 truncate">{{ selectedOrder.customer_phone || '-' }}</span>
                        <a
                          v-if="selectedOrder.customer_phone"
                          :href="`https://wa.me/${formatWaNumber(selectedOrder.customer_phone)}`"
                          target="_blank"
                          class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded-md transition"
                        >
                          WA <ExternalLink class="w-3 h-3" />
                        </a>
                      </div>
                    </div>
                  </div>

                  <div class="bg-gray-50 p-3.5 rounded-xl flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mt-0.5">
                      <Calendar class="w-4 h-4" />
                    </div>
                    <div>
                      <p class="text-xs text-gray-400 font-medium">Jadwal Pengambilan</p>
                      <p class="font-bold text-gray-800 mt-0.5">{{ formatDate(selectedOrder.pickup_date) }}</p>
                    </div>
                  </div>

                  <div class="bg-gray-50 p-3.5 rounded-xl flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                      <MapPin class="w-4 h-4" />
                    </div>
                    <div class="min-w-0">
                      <p class="text-xs text-gray-400 font-medium">Lokasi / Alamat</p>
                      <p class="font-semibold text-gray-800 mt-0.5 whitespace-pre-line text-xs">
                        {{ selectedOrder.location || '-' }}
                      </p>
                    </div>
                  </div>

                  <div class="bg-gray-50 p-3.5 rounded-xl flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mt-0.5">
                      <Package class="w-4 h-4" />
                    </div>
                    <div class="min-w-0">
                      <p class="text-xs text-gray-400 font-medium">Kategori Pesanan</p>
                      <div class="mt-1">
                        <span
                          v-if="selectedOrder.package_type === 'snack_box'"
                          class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800"
                        >
                          Paket Snack Box
                        </span>
                        <span
                          v-else-if="selectedOrder.package_type === 'campuran'"
                          class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800"
                        >
                          Campuran (Snack Box & Kue Satuan)
                        </span>
                        <span
                          v-else
                          class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800"
                        >
                          Kue Satuan
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="bg-amber-50/70 border border-amber-200/80 p-3.5 rounded-xl sm:col-span-2 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                      <FileText class="w-4 h-4" />
                    </div>
                    <div class="min-w-0 flex-1">
                      <p class="text-xs text-amber-800 font-bold uppercase tracking-wider">Catatan Khusus dari Pemesan</p>
                      <p v-if="selectedOrder.notes" class="font-medium text-gray-800 mt-0.5 text-xs whitespace-pre-line leading-relaxed">
                        "{{ selectedOrder.notes }}"
                      </p>
                      <p v-else class="text-gray-400 mt-0.5 text-xs italic">
                        - Tidak ada catatan khusus -
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Order Items Section -->
              <div v-if="selectedOrder" class="pt-6 space-y-3">
                <div class="flex items-center justify-between">
                  <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                    Rincian Produk ({{ modalOrderItems.length }})
                  </h3>
                </div>

                <div class="border border-gray-200/80 rounded-xl overflow-hidden">
                  <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                      <tr class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200/80">
                        <th class="px-4 py-2.5">Produk</th>
                        <th class="px-3 py-2.5">Tipe</th>
                        <th class="px-3 py-2.5 text-center">Qty</th>
                        <th class="px-3 py-2.5 text-right">Harga</th>
                        <th class="px-4 py-2.5 text-right">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                      <tr v-for="item in modalOrderItems" :key="item.id" class="hover:bg-gray-50/50">
                        <td class="px-4 py-3">
                          <div class="flex items-center gap-2.5">
                            <img
                              v-if="item.product?.image_url"
                              :src="item.product.image_url"
                              :alt="item.product?.name"
                              class="w-9 h-9 rounded-lg object-cover border border-gray-100 shrink-0"
                            />
                            <div
                              v-else
                              class="w-9 h-9 rounded-lg bg-cream-100 text-brand-600 flex items-center justify-center shrink-0"
                            >
                              <Package class="w-4 h-4" />
                            </div>
                            <div>
                              <p class="font-bold text-gray-800">{{ item.product?.name || 'Produk' }}</p>
                              <p v-if="item.product?.supplier?.name" class="text-[11px] text-gray-400">
                                Suplier: {{ item.product.supplier.name }}
                              </p>
                            </div>
                          </div>
                        </td>
                        <td class="px-3 py-3">
                          <span
                            :class="[
                              'inline-block px-2 py-0.5 rounded text-[11px] font-semibold',
                              item.type === 'kustom_box' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700'
                            ]"
                          >
                            {{ item.type === 'kustom_box' ? 'Snack Box' : 'Kue Satuan' }}
                          </span>
                        </td>
                        <td class="px-3 py-3 text-center font-bold text-gray-800">
                          {{ item.quantity }}
                        </td>
                        <td class="px-3 py-3 text-right font-mono text-gray-600 text-xs">
                          Rp {{ formatNumber(item.price_at_order) }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900 font-mono">
                          Rp {{ formatNumber(item.price_at_order * item.quantity) }}
                        </td>
                      </tr>

                      <tr v-if="modalOrderItems.length === 0">
                        <td colspan="5" class="py-6 text-center text-gray-400 text-xs">
                          {{ isLoadingDetail ? 'Sedang mengambil rincian produk...' : 'Rincian produk tidak tersedia.' }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Payment & Status Section -->
              <div v-if="selectedOrder" class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Status Updater -->
                <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200/80 space-y-3">
                  <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kelola Status Pesanan</h4>
                    <p class="text-xs text-gray-400 mt-0.5">Ubah status pesanan secara langsung</p>
                  </div>
                  <CustomSelect
                    :model-value="selectedOrder.status"
                    :options="rowStatusOptions"
                    variant="admin"
                    size="md"
                    :full-width="true"
                    :disabled="updatingModalStatus"
                    @update:modelValue="updateModalStatus"
                  />
                  <div v-if="updatingModalStatus" class="flex items-center gap-2 text-xs text-brand-600 font-medium">
                    <Loader2 class="w-3.5 h-3.5 animate-spin" />
                    <span>Menyimpan perubahan status...</span>
                  </div>
                </div>

                <!-- Payment Breakdown Card -->
                <div class="bg-amber-50/40 p-4 rounded-xl border border-amber-200/60 space-y-2 text-xs sm:text-sm">
                  <div class="flex items-center justify-between pb-2 border-b border-amber-200/60">
                    <span class="text-gray-600 font-medium">Metode Pembayaran</span>
                    <span
                      :class="[
                        'px-2.5 py-0.5 rounded-full text-xs font-bold',
                        selectedOrder.payment_type === 'full' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'
                      ]"
                    >
                      {{ selectedOrder.payment_type === 'full' ? 'Bayar Penuh (100%)' : 'Uang Muka (DP 70%)' }}
                    </span>
                  </div>
                  <div class="flex justify-between text-gray-600">
                    <span>Total Tagihan</span>
                    <span class="font-bold text-gray-900 font-mono">Rp {{ formatNumber(selectedOrder.total_amount) }}</span>
                  </div>
                  <div class="flex justify-between text-amber-700">
                    <span>Uang Muka (DP)</span>
                    <span class="font-bold font-mono">Rp {{ formatNumber(selectedOrder.dp_amount) }}</span>
                  </div>
                  <div class="flex justify-between items-center text-gray-700 border-t border-dashed border-amber-200/80 pt-2 font-semibold">
                    <span>Sisa Pelunasan</span>
                    <div class="text-right">
                      <span class="font-bold text-gray-900 font-mono">
                        Rp {{ formatNumber(Math.max(0, (selectedOrder.total_amount || 0) - (selectedOrder.dp_amount || 0))) }}
                      </span>
                      <span
                        v-if="Math.max(0, (selectedOrder.total_amount || 0) - (selectedOrder.dp_amount || 0)) === 0"
                        class="block text-[10px] text-emerald-600 font-bold"
                      >
                        Lunas
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 shrink-0">
              <div class="flex items-center gap-2">
                <a
                  v-if="selectedOrder?.order_number"
                  :href="`/pdf/invoice/${selectedOrder.order_number}`"
                  target="_blank"
                  class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-xs"
                >
                  <Download class="w-3.5 h-3.5 text-red-500" />
                  <span>Download Invoice PDF</span>
                </a>

                <Link
                  v-if="selectedOrder?.id"
                  :href="`/admin/orders/${selectedOrder.id}`"
                  class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-xs"
                >
                  <ExternalLink class="w-3.5 h-3.5 text-blue-500" />
                  <span>Buka Halaman Penuh</span>
                </Link>

                <Link
                  v-if="selectedOrder?.id"
                  :href="`/admin/orders/${selectedOrder.id}/edit`"
                  class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 border border-amber-200 rounded-xl text-xs font-bold text-amber-800 hover:bg-amber-100 transition shadow-xs"
                >
                  <Pencil class="w-3.5 h-3.5 text-amber-600" />
                  <span>Edit Pesanan</span>
                </Link>
              </div>

              <button
                type="button"
                @click="closeDetailModal"
                class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold text-xs rounded-xl transition cursor-pointer"
              >
                Tutup
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import {
  Search,
  ShoppingBag,
  ChevronLeft,
  ChevronRight,
  Eye,
  Pencil,
  Copy,
  Check,
  X,
  Download,
  ExternalLink,
  Phone,
  Calendar,
  MapPin,
  User,
  Package,
  FileText,
  AlertCircle,
  Loader2,
} from 'lucide-vue-next'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CustomSelect from '@/Components/UI/CustomSelect.vue'

const props = defineProps({
  orders: { type: Array, default: () => [] },
  pagination: { type: Object, default: () => ({ current_page: 1, last_page: 1, total: 0, per_page: 10 }) },
  filters: { type: Object, default: () => ({ search: '', status: '', package_type: '', start_date: '', end_date: '' }) },
})

const statusOptions = [
  { value: '', label: 'Semua Status' },
  { value: 'pending', label: 'Pending', dotClass: 'bg-amber-400' },
  { value: 'diterima', label: 'Diterima', dotClass: 'bg-blue-400' },
  { value: 'diproses', label: 'Diproses', dotClass: 'bg-indigo-400' },
  { value: 'dikemas', label: 'Dikemas', dotClass: 'bg-purple-400' },
  { value: 'dikirim', label: 'Dikirim', dotClass: 'bg-teal-400' },
  { value: 'selesai', label: 'Selesai', dotClass: 'bg-emerald-400' },
  { value: 'batal', label: 'Batal', dotClass: 'bg-rose-400' },
]

const packageTypeOptions = [
  { value: '', label: 'Semua Tipe' },
  { value: 'snack_box', label: 'Snack Box' },
  { value: 'satuan', label: 'Kue Satuan' },
  { value: 'campuran', label: 'Campuran' },
]

const rowStatusOptions = [
  { value: 'pending', label: 'Pending', dotClass: 'bg-amber-400' },
  { value: 'diterima', label: 'Diterima', dotClass: 'bg-blue-400' },
  { value: 'diproses', label: 'Diproses', dotClass: 'bg-indigo-400' },
  { value: 'dikemas', label: 'Dikemas', dotClass: 'bg-purple-400' },
  { value: 'dikirim', label: 'Dikirim', dotClass: 'bg-teal-400' },
  { value: 'selesai', label: 'Selesai', dotClass: 'bg-emerald-400' },
  { value: 'batal', label: 'Batal', dotClass: 'bg-rose-400' },
]

// Filters
const searchQuery = ref(props.filters?.search || '')
const filterStatus = ref(props.filters?.status || '')
const filterPackageType = ref(props.filters?.package_type || '')
const filterStartDate = ref(props.filters?.start_date || '')
const filterEndDate = ref(props.filters?.end_date || '')

let searchTimeout = null
const search = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => applyFilters(), 400)
}

const applyFilters = () => {
  router.get(
    '/admin/orders',
    {
      search: searchQuery.value,
      status: filterStatus.value,
      package_type: filterPackageType.value,
      start_date: filterStartDate.value,
      end_date: filterEndDate.value,
    },
    { preserveState: true, replace: true }
  )
}

const resetFilters = () => {
  searchQuery.value = ''
  filterStatus.value = ''
  filterPackageType.value = ''
  filterStartDate.value = ''
  filterEndDate.value = ''
  applyFilters()
}

watch(
  () => props.filters,
  (newFilters) => {
    searchQuery.value = newFilters?.search || ''
    filterStatus.value = newFilters?.status || ''
    filterPackageType.value = newFilters?.package_type || ''
    filterStartDate.value = newFilters?.start_date || ''
    filterEndDate.value = newFilters?.end_date || ''
  },
  { deep: true }
)

const goToPage = (page) => {
  router.get(
    '/admin/orders',
    {
      page,
      search: searchQuery.value,
      status: filterStatus.value,
      start_date: filterStartDate.value,
      end_date: filterEndDate.value,
    },
    { preserveState: true, replace: true }
  )
}

// Update status directly from table row
const updateStatus = (order, newStatus) => {
  if (order.status === newStatus) return
  router.post(
    `/admin/orders/${order.id}/update-status`,
    { status: newStatus },
    {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        order.status = newStatus
        if (selectedOrder.value && selectedOrder.value.id === order.id) {
          selectedOrder.value.status = newStatus
        }
      },
    }
  )
}

// Interactive Order Detail Modal State & AJAX Fetch
const isDetailModalOpen = ref(false)
const selectedOrder = ref(null)
const isLoadingDetail = ref(false)
const detailError = ref('')
const isCopied = ref(false)
const updatingModalStatus = ref(false)

const modalOrderItems = computed(() => {
  if (!selectedOrder.value) return []
  if (Array.isArray(selectedOrder.value.items)) return selectedOrder.value.items
  if (Array.isArray(selectedOrder.value.items?.data)) return selectedOrder.value.items.data
  return []
})

const openOrderDetail = async (order) => {
  // Prepopulate with existing row order data for instant visual feedback
  selectedOrder.value = { ...order }
  isDetailModalOpen.value = true
  isLoadingDetail.value = true
  detailError.value = ''

  try {
    const res = await fetch(`/admin/orders/${order.id}?format=json`, {
      headers: {
        Accept: 'application/json',
      },
    })

    if (!res.ok) throw new Error(`HTTP error ${res.status}`)

    const json = await res.json()
    if (json.success && json.data) {
      selectedOrder.value = json.data
    } else {
      throw new Error(json.message || 'Format data pesanan tidak valid')
    }
  } catch (err) {
    console.error('Error fetching order detail:', err)
    detailError.value = 'Gagal memuat rincian lengkap pesanan dari server.'
  } finally {
    isLoadingDetail.value = false
  }
}

const closeDetailModal = () => {
  isDetailModalOpen.value = false
}

const updateModalStatus = (newStatus) => {
  if (!selectedOrder.value || selectedOrder.value.status === newStatus || updatingModalStatus.value) return
  updatingModalStatus.value = true

  router.post(
    `/admin/orders/${selectedOrder.value.id}/update-status`,
    { status: newStatus },
    {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        selectedOrder.value.status = newStatus
        const found = props.orders.find((o) => o.id === selectedOrder.value.id)
        if (found) found.status = newStatus
      },
      onFinish: () => {
        updatingModalStatus.value = false
      },
    }
  )
}

const copyOrderNumber = (num) => {
  if (!num) return
  navigator.clipboard.writeText(num).then(() => {
    isCopied.value = true
    setTimeout(() => {
      isCopied.value = false
    }, 2000)
  })
}

const formatWaNumber = (phone) => {
  if (!phone) return ''
  let cleaned = String(phone).replace(/\D/g, '')
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.slice(1)
  }
  return cleaned
}

// Keyboard shortcuts for modal
const handleKeyDown = (e) => {
  if (e.key === 'Escape' && isDetailModalOpen.value) {
    closeDetailModal()
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
})

// Formatting Helpers
const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num || 0)

const formatDate = (date) => {
  if (!date) return '-'
  const d = new Date(date)
  return d.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const statusClass = (status) =>
  ({
    pending: 'bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200',
    diterima: 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
    diproses: 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200',
    dikemas: 'bg-purple-50 text-purple-700 ring-1 ring-purple-200',
    dikirim: 'bg-teal-50 text-teal-700 ring-1 ring-teal-200',
    selesai: 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
    batal: 'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
  }[status] || 'bg-gray-50 text-gray-500 ring-1 ring-gray-200')

const statusDot = (status) =>
  ({
    pending: 'bg-yellow-500',
    diterima: 'bg-blue-500',
    diproses: 'bg-indigo-500',
    dikemas: 'bg-purple-500',
    dikirim: 'bg-teal-500',
    selesai: 'bg-emerald-500',
    batal: 'bg-rose-500',
  }[status] || 'bg-gray-400')

const statusLabel = (status) =>
  ({
    pending: 'Pending',
    diterima: 'Diterima',
    diproses: 'Diproses',
    dikemas: 'Dikemas',
    dikirim: 'Dikirim',
    selesai: 'Selesai',
    batal: 'Batal',
  }[status] || status)
</script>