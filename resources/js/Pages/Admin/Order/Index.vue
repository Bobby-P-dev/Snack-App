<template>
  <AdminLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Orders</h2>
          <p class="text-gray-500 text-sm mt-1">Manage all orders</p>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Order No</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-medium text-gray-800">{{ order.order_number }}</td>
                <td class="px-6 py-4 text-gray-600">{{ order.customer_name }}</td>
                <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ formatNumber(order.total_amount) }}</td>
                <td class="px-6 py-4">
                  <span :class="['px-3 py-1 rounded-full text-xs font-semibold', statusClass(order.status)]">{{ order.status }}</span>
                </td>
                <td class="px-6 py-4 text-gray-500 text-sm">{{ formatDate(order.created_at) }}</td>
              </tr>
              <tr v-if="!orders || orders.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No orders yet</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  orders: { type: Array, default: () => [] },
  pagination: { type: Object, default: () => ({}) }
})

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num || 0)
const formatDate = (date) => date ? new Date(date).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' }) : '-'
const statusClass = (status) => ({
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800'
}[status] || 'bg-gray-100 text-gray-800')
</script>