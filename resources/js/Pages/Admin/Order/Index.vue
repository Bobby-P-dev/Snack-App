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
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input v-model="searchQuery" @input="search" type="text" placeholder="Cari no. pesanan, nama, telepon..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
        <select v-model="filterStatus" @change="applyFilters" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="diterima">Diterima</option>
          <option value="diproses">Diproses</option>
          <option value="dikemas">Dikemas</option>
          <option value="dikirim">Dikirim</option>
          <option value="selesai">Selesai</option>
        </select>
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
                <td class="px-6 py-4 font-mono text-sm font-semibold text-gray-800">{{ order.order_number }}</td>
                <td class="px-6 py-4">
                  <div>
                    <p class="font-semibold text-gray-800">{{ order.customer_name }}</p>
                    <p v-if="order.location" class="text-xs text-gray-400 truncate max-w-[200px]">{{ order.location }}</p>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ order.customer_phone || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ formatDate(order.pickup_date) }}</td>
                <td class="px-6 py-4 text-right font-semibold text-blue-600 font-mono">Rp {{ formatNumber(order.total_amount) }}</td>
                <td class="px-6 py-4 text-center">
                  <span :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold', statusClass(order.status)]">
                    <span :class="['w-1.5 h-1.5 rounded-full', statusDot(order.status)]"></span>
                    {{ statusLabel(order.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <select
                      :value="order.status"
                      @change="updateStatus(order, $event.target.value)"
                      class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                    >
                      <option value="pending">Pending</option>
                      <option value="diterima">Diterima</option>
                      <option value="diproses">Diproses</option>
                      <option value="dikemas">Dikemas</option>
                      <option value="dikirim">Dikirim</option>
                      <option value="selesai">Selesai</option>
                    </select>
                  </div>
                </td>
              </tr>
              <tr v-if="!orders || orders.length === 0">
                <td colspan="8">
                  <div class="py-16 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Prev
          </button>
          <button :disabled="pagination.current_page >= pagination.last_page" @click="goToPage(pagination.current_page + 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
            Next
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  orders: { type: Array, default: () => [] },
  pagination: { type: Object, default: () => ({ current_page: 1, last_page: 1, total: 0, per_page: 10 }) },
  filters: { type: Object, default: () => ({ search: '', status: '', start_date: '', end_date: '' }) },
})

// Filters
const searchQuery = ref(props.filters?.search || '')
const filterStatus = ref(props.filters?.status || '')
const filterStartDate = ref(props.filters?.start_date || '')
const filterEndDate = ref(props.filters?.end_date || '')

let searchTimeout = null
const search = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => applyFilters(), 500)
}

const applyFilters = () => {
  router.get('/admin/orders', {
    search: searchQuery.value,
    status: filterStatus.value,
    start_date: filterStartDate.value,
    end_date: filterEndDate.value,
  }, { preserveState: true, replace: true })
}

const resetFilters = () => {
  searchQuery.value = ''
  filterStatus.value = ''
  filterStartDate.value = ''
  filterEndDate.value = ''
  applyFilters()
}

const goToPage = (page) => {
  router.get('/admin/orders', {
    page,
    search: searchQuery.value,
    status: filterStatus.value,
    start_date: filterStartDate.value,
    end_date: filterEndDate.value,
  }, { preserveState: true, replace: true })
}

// Update status
const updateStatus = (order, newStatus) => {
  router.post(`/admin/orders/${order.id}/update-status`, { status: newStatus }, { preserveScroll: true })
}

// Helpers
const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num || 0)

const formatDate = (date) => {
  if (!date) return '-'
  const d = new Date(date)
  return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
}

const statusClass = (status) => ({
  pending: 'bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200',
  diterima: 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
  diproses: 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200',
  dikemas: 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
  dikirim: 'bg-orange-50 text-orange-700 ring-1 ring-orange-200',
  selesai: 'bg-green-50 text-green-700 ring-1 ring-green-200',
}[status] || 'bg-gray-50 text-gray-500 ring-1 ring-gray-200')

const statusDot = (status) => ({
  pending: 'bg-yellow-500',
  diterima: 'bg-blue-500',
  diproses: 'bg-indigo-500',
  dikemas: 'bg-amber-500',
  dikirim: 'bg-orange-500',
  selesai: 'bg-green-500',
}[status] || 'bg-gray-400')

const statusLabel = (status) => ({
  pending: 'Pending',
  diterima: 'Diterima',
  diproses: 'Diproses',
  dikemas: 'Dikemas',
  dikirim: 'Dikirim',
  selesai: 'Selesai',
}[status] || status)
</script>