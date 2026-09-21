<template>
  <div ref="selectRef" :class="['relative', fullWidth ? 'w-full' : 'inline-block']">
    <!-- Optional Floating/Top Label -->
    <label v-if="label" class="block text-xs font-semibold text-gray-600 mb-1.5">
      {{ label }}
    </label>

    <!-- Trigger Button -->
    <button
      type="button"
      @click="toggleOpen"
      :disabled="disabled"
      :class="[
        'w-full flex items-center justify-between gap-2.5 transition-all text-left outline-none cursor-pointer',
        triggerClasses,
        disabled ? 'opacity-60 cursor-not-allowed bg-gray-50' : '',
        isOpen ? openRingClasses : ''
      ]"
      :aria-expanded="isOpen"
      aria-haspopup="listbox"
    >
      <div class="flex items-center gap-2 min-w-0 truncate">
        <!-- Optional Prefix Icon / Custom Icon -->
        <component
          :is="selectedOption?.icon || icon"
          v-if="selectedOption?.icon || icon"
          :class="['w-4 h-4 shrink-0', iconClass]"
        />

        <!-- Status Dot if available -->
        <span
          v-else-if="selectedOption?.dotClass"
          :class="['w-2 h-2 rounded-full shrink-0', selectedOption.dotClass]"
        ></span>

        <!-- Optional Prefix text (e.g. 'Urutkan:') -->
        <span v-if="prefix" class="text-xs font-medium text-brown-400 shrink-0">
          {{ prefix }}
        </span>

        <!-- Selected Label or Placeholder -->
        <span :class="['truncate', selectedOption ? selectedTextClasses : 'text-gray-400']">
          {{ selectedOption ? selectedOption.label : placeholder }}
        </span>
      </div>

      <!-- Animated Chevron -->
      <ChevronDown
        :class="[
          'w-4 h-4 shrink-0 transition-transform duration-200',
          chevronClass,
          isOpen ? 'rotate-180 text-brand-500' : ''
        ]"
      />
    </button>

    <!-- Floating Dropdown Popover -->
    <Transition
      enter-active-class="transition ease-out duration-150"
      enter-from-class="opacity-0 translate-y-1.5 scale-98"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-1 scale-98"
    >
      <div
        v-if="isOpen"
        :class="[
          'absolute z-50 overflow-hidden focus:outline-none',
          placementClass,
          align === 'right' ? 'right-0' : 'left-0',
          menuWidthClass,
          menuCardClasses
        ]"
        role="listbox"
      >
        <div class="max-h-64 overflow-y-auto p-1.5 space-y-0.5 scrollbar-thin">
          <div
            v-for="option in normalizedOptions"
            :key="option.value"
            @click="selectOption(option)"
            :class="[
              'group flex items-center justify-between gap-3 px-3 py-2 rounded-xl text-xs sm:text-sm cursor-pointer transition-all duration-150 select-none',
              isSelected(option) ? activeOptionClasses : inactiveOptionClasses
            ]"
            role="option"
            :aria-selected="isSelected(option)"
          >
            <div class="flex items-center gap-2.5 min-w-0">
              <!-- Option Icon -->
              <component
                :is="option.icon"
                v-if="option.icon"
                :class="[
                  'w-4 h-4 shrink-0 transition-colors',
                  isSelected(option) ? activeIconClass : 'text-gray-400 group-hover:text-brown-600'
                ]"
              />

              <!-- Option Dot -->
              <span
                v-else-if="option.dotClass"
                :class="['w-2 h-2 rounded-full shrink-0', option.dotClass]"
              ></span>

              <div class="truncate">
                <p :class="['truncate', isSelected(option) ? 'font-bold' : 'font-medium']">
                  {{ option.label }}
                </p>
                <p v-if="option.description" class="text-[11px] text-gray-400 truncate mt-0.5 font-normal">
                  {{ option.description }}
                </p>
              </div>
            </div>

            <!-- Active Checkmark Indicator -->
            <Check
              v-if="isSelected(option)"
              :class="['w-4 h-4 shrink-0', activeCheckClass]"
            />
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { ChevronDown, Check } from 'lucide-vue-next';

const props = defineProps({
  modelValue: {
    type: [String, Number, Boolean],
    default: ''
  },
  options: {
    type: Array,
    required: true,
    // Array of objects [{ value, label, icon?, dotClass?, description? }] or primitives
  },
  placeholder: {
    type: String,
    default: 'Pilih opsi'
  },
  label: {
    type: String,
    default: ''
  },
  prefix: {
    type: String,
    default: ''
  },
  icon: {
    type: [Object, Function],
    default: null
  },
  variant: {
    type: String,
    default: 'bakery', // 'bakery', 'pill', 'admin', 'subtle'
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
  },
  align: {
    type: String,
    default: 'left', // 'left', 'right'
  },
  menuWidth: {
    type: String,
    default: 'auto', // 'w-full', 'min-w-[180px]', 'w-56', 'auto'
  },
  fullWidth: {
    type: Boolean,
    default: false
  },
  placement: {
    type: String,
    default: 'bottom', // 'bottom', 'top'
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

const placementClass = computed(() => {
  return props.placement === 'top' ? 'bottom-full mb-1.5' : 'top-full mt-1.5';
});

const emit = defineEmits(['update:modelValue', 'change']);

const selectRef = ref(null);
const isOpen = ref(false);

const toggleOpen = () => {
  if (props.disabled) return;
  isOpen.value = !isOpen.value;
};

const close = () => {
  isOpen.value = false;
};

// Normalize options
const normalizedOptions = computed(() => {
  return props.options.map(opt => {
    if (typeof opt === 'object' && opt !== null) {
      return {
        value: opt.value,
        label: opt.label !== undefined ? opt.label : String(opt.value),
        icon: opt.icon || null,
        dotClass: opt.dotClass || null,
        description: opt.description || null
      };
    }
    return {
      value: opt,
      label: String(opt),
      icon: null,
      dotClass: null,
      description: null
    };
  });
});

const selectedOption = computed(() => {
  return normalizedOptions.value.find(opt => String(opt.value) === String(props.modelValue)) || null;
});

const isSelected = (option) => {
  return String(option.value) === String(props.modelValue);
};

const selectOption = (option) => {
  emit('update:modelValue', option.value);
  emit('change', option.value, option);
  close();
};

// Styling Computeds
const triggerClasses = computed(() => {
  const sizeMap = {
    sm: 'py-1.5 px-3 text-xs',
    md: props.variant === 'pill' ? 'py-2 px-4 text-xs sm:text-sm' : 'py-2 px-3.5 text-xs sm:text-sm',
    lg: 'py-3 px-5 text-sm sm:text-base'
  };

  const variantMap = {
    pill: 'rounded-full border border-cream-200 bg-white font-semibold text-brown-800 shadow-2xs hover:border-brand-300 hover:bg-cream-50/40',
    bakery: 'rounded-xl border border-cream-200 bg-white font-semibold text-brown-800 shadow-2xs hover:border-brand-300 hover:bg-cream-50/30',
    admin: 'rounded-xl border border-gray-200 bg-white font-medium text-gray-700 hover:border-gray-300 shadow-2xs hover:bg-gray-50/50',
    subtle: 'rounded-lg border border-transparent bg-transparent hover:bg-cream-100/50 font-medium text-brown-700'
  };

  return `${sizeMap[props.size] || sizeMap.md} ${variantMap[props.variant] || variantMap.bakery}`;
});

const openRingClasses = computed(() => {
  if (props.variant === 'admin') {
    return 'ring-2 ring-blue-500/20 border-blue-500';
  }
  return 'ring-3 ring-brand-500/15 border-brand-500 shadow-md shadow-brand-500/10';
});

const selectedTextClasses = computed(() => {
  if (props.variant === 'admin') return 'text-gray-900 font-medium';
  return 'text-brown-900 font-semibold';
});

const chevronClass = computed(() => {
  if (props.variant === 'admin') return 'text-gray-400';
  return 'text-brown-400';
});

const iconClass = computed(() => {
  if (props.variant === 'admin') return 'text-gray-500';
  return 'text-brand-500';
});

const menuWidthClass = computed(() => {
  if (!props.menuWidth || props.menuWidth === 'auto') {
    return 'w-full min-w-[190px] sm:min-w-[210px]';
  }
  return props.menuWidth;
});

const menuCardClasses = computed(() => {
  if (props.variant === 'admin') {
    return 'bg-white rounded-xl border border-gray-200 shadow-xl shadow-gray-400/15';
  }
  return 'bg-white/98 backdrop-blur-md rounded-2xl border border-cream-200 shadow-xl shadow-brown-900/15';
});

const activeOptionClasses = computed(() => {
  if (props.variant === 'admin') {
    return 'bg-blue-50 text-blue-700 font-bold';
  }
  return 'bg-brand-50 text-brand-600 font-bold';
});

const inactiveOptionClasses = computed(() => {
  if (props.variant === 'admin') {
    return 'text-gray-700 hover:bg-gray-50 hover:text-gray-900';
  }
  return 'text-brown-700 hover:bg-cream-50 hover:text-brown-900';
});

const activeIconClass = computed(() => {
  if (props.variant === 'admin') return 'text-blue-600';
  return 'text-brand-500';
});

const activeCheckClass = computed(() => {
  if (props.variant === 'admin') return 'text-blue-600';
  return 'text-brand-500';
});

// Click Outside & Keyboard Handlers
const handleClickOutside = (e) => {
  if (selectRef.value && !selectRef.value.contains(e.target)) {
    close();
  }
};

const handleKeydown = (e) => {
  if (isOpen.value && e.key === 'Escape') {
    close();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
  document.removeEventListener('keydown', handleKeydown);
});
</script>
