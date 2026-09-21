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
            <div class="bg-blue-100 p-3 rounded-lg text-blue-600">
              <ShoppingBag class="w-6 h-6" />
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
            <div class="bg-yellow-100 p-3 rounded-lg text-yellow-600">
              <Clock class="w-6 h-6" />
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
            <div class="bg-green-100 p-3 rounded-lg text-green-600">
              <Banknote class="w-6 h-6" />
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
            <div class="bg-purple-100 p-3 rounded-lg text-purple-600">
              <Package class="w-6 h-6" />
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
            <Link href="/admin/orders" class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center gap-1">
              <span>View All</span>
              <ArrowRight class="w-4 h-4" />
            </Link>
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
              <ShoppingBag class="w-5 h-5 mr-3" />
              <span class="text-sm font-medium">Create Order</span>
            </Link>

            <Link
              href="/admin/products/create"
              class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg text-green-600 transition"
            >
              <Package class="w-5 h-5 mr-3" />
              <span class="text-sm font-medium">Add Product</span>
            </Link>

            <Link
              href="/admin/suppliers/create"
              class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg text-purple-600 transition"
            >
              <Truck class="w-5 h-5 mr-3" />
              <span class="text-sm font-medium">Add Supplier</span>
            </Link>

            <Link
              href="/admin/reports"
              class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg text-orange-600 transition"
            >
              <BarChart3 class="w-5 h-5 mr-3" />
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
import { ShoppingBag, Clock, Banknote, Package, ArrowRight, Truck, BarChart3 } from 'lucide-vue-next'

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
