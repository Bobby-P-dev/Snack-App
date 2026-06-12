<template>
  <Transition name="alert">
    <div
      v-if="show"
      :class="[
        'rounded-lg p-4 flex items-start space-x-3 border',
        variantClasses
      ]"
    >
      <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path v-if="variant === 'success'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        <path v-else-if="variant === 'error'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        <path v-else-if="variant === 'warning'" fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        <path v-else fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
      </svg>
      <div class="flex-1">
        <p v-if="title" class="font-semibold">{{ title }}</p>
        <p class="text-sm">{{ message }}</p>
      </div>
      <button
        v-if="dismissible"
        @click="show = false"
        class="text-gray-400 hover:text-gray-600 transition flex-shrink-0"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
  },
  title: {
    type: String,
    default: null
  },
  message: {
    type: String,
    required: true
  },
  dismissible: {
    type: Boolean,
    default: true
  },
  autoClose: {
    type: Number,
    default: null // milliseconds, null = no auto-close
  }
})

const show = ref(true)

const variantClasses = computed(() => {
  const variants = {
    success: 'bg-green-50 border-green-200 text-green-800',
    error: 'bg-red-50 border-red-200 text-red-800',
    warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
    info: 'bg-blue-50 border-blue-200 text-blue-800'
  }
  return variants[props.variant]
})

watch(() => props.autoClose, (value) => {
  if (value) {
    setTimeout(() => {
      show.value = false
    }, value)
  }
}, { immediate: true })
</script>

<style scoped>
.alert-enter-active,
.alert-leave-active {
  transition: all 0.3s ease;
}

.alert-enter-from {
  opacity: 0;
  transform: translateX(-10px);
}

.alert-leave-to {
  opacity: 0;
  transform: translateX(-10px);
}
</style>
