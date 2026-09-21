<template>
  <Transition name="alert">
    <div
      v-if="show"
      :class="[
        'rounded-lg p-4 flex items-start space-x-3 border',
        variantClasses
      ]"
    >
      <CheckCircle2 v-if="variant === 'success'" class="w-5 h-5 mt-0.5 flex-shrink-0" />
      <AlertCircle v-else-if="variant === 'error'" class="w-5 h-5 mt-0.5 flex-shrink-0" />
      <AlertTriangle v-else-if="variant === 'warning'" class="w-5 h-5 mt-0.5 flex-shrink-0" />
      <Info v-else class="w-5 h-5 mt-0.5 flex-shrink-0" />
      <div class="flex-1">
        <p v-if="title" class="font-semibold">{{ title }}</p>
        <p class="text-sm">{{ message }}</p>
      </div>
      <button
        v-if="dismissible"
        @click="show = false"
        class="text-gray-400 hover:text-gray-600 transition flex-shrink-0"
      >
        <X class="w-5 h-5" />
      </button>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { CheckCircle2, AlertCircle, AlertTriangle, Info, X } from 'lucide-vue-next'

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
