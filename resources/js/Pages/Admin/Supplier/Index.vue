<template>
  <AdminLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Suppliers</h2>
          <p class="text-gray-500 text-sm mt-1">Manage suppliers</p>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Name</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Phone</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Address</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Daily Capacity</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="supplier in suppliers" :key="supplier.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-medium text-gray-800">{{ supplier.name }}</td>
                <td class="px-6 py-4 text-gray-500 text-sm">{{ supplier.phone || '-' }}</td>
                <td class="px-6 py-4 text-gray-500 text-sm">{{ supplier.address || '-' }}</td>
                <td class="px-6 py-4 text-gray-600 text-sm">{{ supplier.daily_capacity || '-' }}</td>
              </tr>
              <tr v-if="!suppliers || suppliers.length === 0">
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">No suppliers yet</td>
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
  suppliers: { type: Array, default: () => [] },
  pagination: { type: Object, default: () => ({}) }
})
</script>