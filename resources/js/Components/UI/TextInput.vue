<template>
  <div class="flex flex-col space-y-2">
    <label v-if="label" :for="id" class="text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      @input="$emit('update:modelValue', $event.target.value)"
      :class="[
        'px-4 py-2 border rounded-lg transition duration-200',
        'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
        'disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-500',
        error ? 'border-red-500 focus:ring-red-500' : 'border-gray-300'
      ]"
    />
    <p v-if="error" class="text-sm text-red-500">{{ error }}</p>
    <p v-if="hint" class="text-xs text-gray-500">{{ hint }}</p>
  </div>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    default: null
  },
  type: {
    type: String,
    default: 'text'
  },
  label: {
    type: String,
    default: null
  },
  placeholder: {
    type: String,
    default: null
  },
  modelValue: {
    type: [String, Number],
    default: ''
  },
  error: {
    type: String,
    default: null
  },
  hint: {
    type: String,
    default: null
  },
  disabled: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  }
})

defineEmits(['update:modelValue'])
</script>
