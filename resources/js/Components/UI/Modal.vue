<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 z-[200] flex items-center justify-center" @keydown.esc="$emit('update:modelValue', false)">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="$emit('update:modelValue', false)"></div>

        <!-- Modal Content -->
        <div
          :class="[
            'relative mx-4 w-full max-w-md p-0 rounded-2xl shadow-2xl z-10 overflow-hidden',
            variant === 'success' ? 'bg-white' :
            variant === 'error' ? 'bg-white' :
            variant === 'warning' ? 'bg-white' :
            'bg-white'
          ]"
        >
          <!-- Top Accent Bar -->
          <div
            :class="[
              'h-1.5 w-full',
              variant === 'success' ? 'bg-green-500' :
              variant === 'error' ? 'bg-red-500' :
              variant === 'warning' ? 'bg-amber-500' :
              'bg-blue-500'
            ]"
          ></div>

          <div class="p-6 md:p-8 text-center">
            <!-- Icon with animated circle -->
            <div class="relative mb-5 flex items-center justify-center">
              <div
                :class="[
                  'w-16 h-16 rounded-full flex items-center justify-center',
                  variant === 'success' ? 'bg-green-100' :
                  variant === 'error' ? 'bg-red-100' :
                  variant === 'warning' ? 'bg-amber-100' :
                  'bg-blue-100'
                ]"
              >
                <!-- Success Check -->
                <CheckCircle2 v-if="variant === 'success'" class="w-8 h-8 text-green-600" />
                <!-- Error Alert -->
                <AlertCircle v-else-if="variant === 'error'" class="w-8 h-8 text-red-600" />
                <!-- Warning Triangle -->
                <AlertTriangle v-else-if="variant === 'warning'" class="w-8 h-8 text-amber-600" />
                <!-- Info -->
                <Info v-else class="w-8 h-8 text-blue-600" />
              </div>
            </div>

            <!-- Title -->
            <h3
              v-if="title"
              :class="[
                'text-xl font-bold mb-2',
                variant === 'success' ? 'text-green-800' :
                variant === 'error' ? 'text-red-800' :
                variant === 'warning' ? 'text-amber-800' :
                'text-blue-800'
              ]"
            >
              {{ title }}
            </h3>

            <!-- Message -->
            <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-1">
              {{ message }}
            </p>

            <!-- Optional Extra Content Slot -->
            <div v-if="$slots.default" class="mt-3 text-left">
              <slot />
            </div>

            <!-- Actions -->
            <div class="flex justify-center gap-3 mt-6">
              <button
                v-if="showCancel"
                @click="$emit('update:modelValue', false)"
                class="px-6 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition"
              >
                {{ cancelText }}
              </button>
              <button
                @click="$emit('confirm')"
                :class="[
                  'px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-md',
                  variant === 'success' ? 'bg-green-600 hover:bg-green-700 text-white shadow-green-200' :
                  variant === 'error' ? 'bg-red-600 hover:bg-red-700 text-white shadow-red-200' :
                  variant === 'warning' ? 'bg-amber-600 hover:bg-amber-700 text-white shadow-amber-200' :
                  'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-200'
                ]"
              >
                {{ confirmText }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { CheckCircle2, AlertCircle, AlertTriangle, Info } from 'lucide-vue-next'

defineProps({
  modelValue: { type: Boolean, default: false },
  variant: {
    type: String,
    default: 'info',
    validator: (v) => ['success', 'error', 'warning', 'info'].includes(v),
  },
  title: { type: String, default: '' },
  message: { type: String, default: '' },
  confirmText: { type: String, default: 'OK' },
  cancelText: { type: String, default: 'Batal' },
  showCancel: { type: Boolean, default: false },
})

defineEmits(['update:modelValue', 'confirm'])
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.modal-enter-from {
  opacity: 0;
}
.modal-enter-from > div:last-child {
  transform: scale(0.85) translateY(30px);
}
.modal-leave-to {
  opacity: 0;
}
.modal-leave-to > div:last-child {
  transform: scale(0.85) translateY(30px);
}
</style>
