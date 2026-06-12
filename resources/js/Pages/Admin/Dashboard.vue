<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Total Orders</p>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.totalOrders }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4">📈 +12% from last month</p>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Pending Orders</p>
              <p class="text-3xl font-bold text-yellow-600 mt-2">{{ stats.pendingOrders }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-lg">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4">Need attention</p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Total Revenue</p>
              <p class="text-3xl font-bold text-green-600 mt-2">{{ formatCurrency(stats.totalRevenue) }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4">📈 +8% from last month</p>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Total Products</p>
              <p class="text-3xl font-bold text-purple-600 mt-2">{{ stats.totalProducts }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-4">{{ stats.activeProducts }} active</p>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
            <Link href="/admin/orders" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All →</Link>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600">Order No</th>
                  <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600">Customer</th>
                  <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600">Amount</th>
                  <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600">Status</th>
                  <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600">Date</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in recentOrders" :key="order.id" class="border-b border-gray-100 hover:bg-gray-50">
                  <td class="py-3 px-4">
                    <span class="font-medium text-gray-800">{{ order.order_number }}</span>
                  </td>
                  <td class="py-3 px-4 text-gray-600">{{ order.customer_name }}</td>
                  <td class="py-3 px-4 font-semibold text-gray-800">{{ formatCurrency(order.total_amount) }}</td>
                  <td class="py-3 px-4">
                    <span
                      :class="[
                        'px-3 py-1 rounded-full text-xs font-semibold',
                        order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '',
                        order.status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '',
                        order.status === 'completed' ? 'bg-green-100 text-green-800' : ''
                      ]"
                    >
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-gray-600 text-sm">{{ formatDate(order.created_at) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>

          <div class="space-y-3">
            <Link
              href="/admin/orders/create"
              class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-600 transition"
            >
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span class="text-sm font-medium">Create Order</span>
            </Link>

            <Link
              href="/admin/products/create"
              class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg text-green-600 transition"
            >
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span class="text-sm font-medium">Add Product</span>
            </Link>

            <Link
              href="/admin/suppliers/create"
              class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg text-purple-600 transition"
            >
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span class="text-sm font-medium">Add Supplier</span>
            </Link>

            <Link
              href="/admin/reports"
              class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg text-orange-600 transition"
            >
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              <span class="text-sm font-medium">View Reports</span>
            </Link>
          </div>
        </div>
      </div>

      <!-- Order Status Overview -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status Overview</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="text-center">
            <div class="text-4xl font-bold text-yellow-600">{{ stats.pendingOrders }}</div>
            <p class="text-gray-600 text-sm mt-2">Pending Orders</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
              <div class="bg-yellow-600 h-2 rounded-full" :style="{ width: pendingPercentage + '%' }"></div>
            </div>
          </div>

          <div class="text-center">
            <div class="text-4xl font-bold text-blue-600">{{ stats.confirmedOrders }}</div>
            <p class="text-gray-600 text-sm mt-2">Confirmed Orders</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
              <div class="bg-blue-600 h-2 rounded-full" :style="{ width: confirmedPercentage + '%' }"></div>
            </div>
          </div>

          <div class="text-center">
            <div class="text-4xl font-bold text-green-600">{{ stats.completedOrders }}</div>
            <p class="text-gray-600 text-sm mt-2">Completed Orders</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
              <div class="bg-green-600 h-2 rounded-full" :style="{ width: completedPercentage + '%' }"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      totalOrders: 0,
      pendingOrders: 0,
      confirmedOrders: 0,
      completedOrders: 0,
      totalRevenue: 0,
      totalProducts: 0,
      activeProducts: 0,
    })
  },
  recentOrders: {
    type: Array,
    default: () => []
  }
})

// Calculate percentages
const totalOrders = computed(() => props.stats.totalOrders || 1)
const pendingPercentage = computed(() => (props.stats.pendingOrders / totalOrders.value) * 100)
const confirmedPercentage = computed(() => (props.stats.confirmedOrders / totalOrders.value) * 100)
const completedPercentage = computed(() => (props.stats.completedOrders / totalOrders.value) * 100)

// Helper functions
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount)
}

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
