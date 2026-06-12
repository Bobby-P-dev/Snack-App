<template>
  <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
    <!-- Image -->
    <div class="relative bg-gray-200 h-48 overflow-hidden">
      <img
        v-if="product.image_url"
        :src="`/storage/${product.image_url}`"
        :alt="product.name"
        class="w-full h-full object-cover hover:scale-110 transition duration-300"
      />
      <div v-else class="w-full h-full flex items-center justify-center bg-gray-300">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>

      <!-- Badge -->
      <div v-if="showBadge" class="absolute top-2 right-2">
        <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
          {{ badge }}
        </span>
      </div>
    </div>

    <!-- Content -->
    <div class="p-4">
      <!-- Category Badge -->
      <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">{{ product.category?.name }}</p>

      <!-- Product Name -->
      <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ product.name }}</h3>

      <!-- Supplier -->
      <p class="text-sm text-gray-600 mb-3">{{ product.supplier?.name }}</p>

      <!-- Prices -->
      <div class="flex items-end justify-between mb-4">
        <div>
          <p class="text-xs text-gray-500 line-through">Rp {{ formatNumber(product.base_price) }}</p>
          <p class="text-2xl font-bold text-blue-600">Rp {{ formatNumber(product.sell_price) }}</p>
        </div>
        <div v-if="showProfit" class="text-right">
          <p class="text-xs text-gray-600">Profit</p>
          <p class="text-sm font-semibold text-green-600">{{ profitMargin }}%</p>
        </div>
      </div>

      <!-- Add to Cart / Action -->
      <slot name="actions">
        <button
          @click="$emit('add-to-cart')"
          class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm"
        >
          Add to Cart
        </button>
      </slot>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  showBadge: {
    type: Boolean,
    default: false
  },
  badge: {
    type: String,
    default: 'New'
  },
  showProfit: {
    type: Boolean,
    default: false
  }
})

defineEmits(['add-to-cart'])

const formatNumber = (num) => {
  return new Intl.NumberFormat('id-ID').format(num)
}

const profitMargin = computed(() => {
  if (!props.product.base_price) return 0
  const profit = ((props.product.sell_price - props.product.base_price) / props.product.base_price) * 100
  return profit.toFixed(0)
})
</script>
