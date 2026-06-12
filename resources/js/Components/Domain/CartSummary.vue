<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>

    <!-- Items List -->
    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
      <div v-for="item in items" :key="item.id" class="flex items-center justify-between pb-3 border-b border-gray-100">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-800">{{ item.product.name }}</p>
          <p class="text-xs text-gray-500">{{ item.quantity }}x @ Rp {{ formatNumber(item.price_at_order) }}</p>
        </div>
        <button
          @click="$emit('remove-item', item.id)"
          class="text-red-500 hover:text-red-700 transition ml-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>

      <div v-if="items.length === 0" class="text-center py-6 text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <p class="text-sm">Keranjang kosong</p>
      </div>
    </div>

    <!-- Summary -->
    <div v-if="items.length > 0" class="space-y-2 mb-4 pt-4 border-t border-gray-200">
      <div class="flex justify-between text-sm text-gray-600">
        <span>Subtotal:</span>
        <span>Rp {{ formatNumber(subtotal) }}</span>
      </div>

      <div v-if="discount > 0" class="flex justify-between text-sm text-green-600 font-medium">
        <span>Diskon:</span>
        <span>-Rp {{ formatNumber(discount) }}</span>
      </div>

      <div class="flex justify-between text-sm text-gray-600">
        <span>Pajak (10%):</span>
        <span>Rp {{ formatNumber(tax) }}</span>
      </div>

      <div class="flex justify-between text-lg font-bold text-gray-800 pt-2 border-t border-gray-200 mt-2">
        <span>Total:</span>
        <span class="text-blue-600">Rp {{ formatNumber(total) }}</span>
      </div>

      <div class="flex justify-between text-sm text-orange-600 font-semibold pt-2">
        <span>DP ({{ dpPercentage }}%):</span>
        <span>Rp {{ formatNumber(dp) }}</span>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-2">
      <button
        v-if="items.length > 0"
        @click="$emit('checkout')"
        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-medium text-sm"
      >
        Lanjut ke Checkout
      </button>

      <button
        @click="$emit('continue-shopping')"
        class="w-full bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition font-medium text-sm"
      >
        Lanjut Belanja
      </button>
    </div>

    <!-- Info -->
    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
      <p class="text-xs text-blue-800">
        <span class="font-semibold">💡 Tip:</span> Pembayaran DP dilakukan via WhatsApp. Saldo akhir dibayarkan saat pengambilan.
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed, inject } from 'vue'

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  },
  discount: {
    type: Number,
    default: 0
  },
  dpPercentage: {
    type: Number,
    default: 70
  }
})

defineEmits(['remove-item', 'checkout', 'continue-shopping'])

const formatNumber = (num) => {
  return new Intl.NumberFormat('id-ID').format(num)
}

const subtotal = computed(() => {
  return props.items.reduce((sum, item) => sum + (item.price_at_order * item.quantity), 0)
})

const tax = computed(() => {
  return Math.round((subtotal.value - props.discount) * 0.1)
})

const total = computed(() => {
  return subtotal.value - props.discount + tax.value
})

const dp = computed(() => {
  return Math.round(total.value * (props.dpPercentage / 100))
})
</script>
