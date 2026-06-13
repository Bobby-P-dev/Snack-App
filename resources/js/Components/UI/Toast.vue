<template>
  <TransitionGroup
    name="toast"
    tag="div"
    class="fixed bottom-4 right-4 z-[110] flex flex-col gap-2 pointer-events-none"
  >
    <div
      v-for="toast in toasts"
      :key="toast.id"
      :class="[
        'pointer-events-auto flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border bg-white transition-all duration-300 max-w-sm w-full',
        getVariantClass(toast.variant)
      ]"
    >
      <!-- Icon -->
      <svg v-if="toast.variant === 'success'" class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
      </svg>
      <svg v-else-if="toast.variant === 'error'" class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
      </svg>
      <svg v-else-if="toast.variant === 'warning'" class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
      <svg v-else class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>

      <div class="flex-1 min-w-0">
        <p v-if="toast.title" class="text-sm font-bold text-gray-900">{{ toast.title }}</p>
        <p class="text-sm text-gray-600 truncate">{{ toast.message }}</p>
      </div>

      <button @click="remove(toast.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-700 transition p-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </TransitionGroup>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const toasts = ref([])
let toastId = 0

// Watch individual flash values (not the object reference)
const flashSuccess = () => page.props.flash?.success || null
const flashError = () => page.props.flash?.error || null
const flashWarning = () => page.props.flash?.warning || null
const flashInfo = () => page.props.flash?.info || null

watch(flashSuccess, (val) => { if (val) addToast('success', val, 'Berhasil') })
watch(flashError, (val) => { if (val) addToast('error', val, 'Gagal') })
watch(flashWarning, (val) => { if (val) addToast('warning', val, 'Peringatan') })
watch(flashInfo, (val) => { if (val) addToast('info', val, 'Informasi') })

const addToast = (variant, message, title = '') => {
  const id = toastId++
  toasts.value.push({ id, variant, message, title })

  // Auto remove after 4 seconds
  setTimeout(() => {
    remove(id)
  }, 4000)
}

const remove = (id) => {
  const index = toasts.value.findIndex(t => t.id === id)
  if (index > -1) {
    toasts.value.splice(index, 1)
  }
}

const getVariantClass = (variant) => {
  const map = {
    success: 'border-green-200 border-l-4 border-l-green-500',
    error: 'border-red-200 border-l-4 border-l-red-500',
    warning: 'border-amber-200 border-l-4 border-l-amber-500',
    info: 'border-blue-200 border-l-4 border-l-blue-500',
  }
  return map[variant] || map.info
}
</script>

<style scoped>
.toast-enter-active {
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.toast-leave-active {
  transition: all 0.2s ease-out;
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(100%) scale(0.9);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>