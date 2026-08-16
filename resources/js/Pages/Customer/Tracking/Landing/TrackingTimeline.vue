<template>
    <div class="relative w-full">
        <!-- Progress bar line -->
        <div class="absolute top-6 sm:top-8 left-4 right-4 h-1 bg-gray-200 rounded-full z-0"></div>
        <div 
            class="absolute top-6 sm:top-8 left-4 h-1 bg-[#CF5C70] rounded-full z-0 transition-all duration-700 ease-in-out"
            :style="{ width: progressWidth }"
        ></div>

        <!-- Steps -->
        <div class="relative z-10 flex justify-between">
            <div v-for="(step, index) in steps" :key="index" class="flex flex-col items-center group relative w-16 sm:w-24">
                
                <!-- Icon Circle -->
                <div 
                    :class="[
                        'w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mb-3 transition-colors duration-500 shadow-sm border-4',
                        currentStep >= step.id 
                            ? 'bg-[#CF5C70] border-white text-white' 
                            : 'bg-gray-100 border-white text-gray-400'
                    ]"
                >
                    <component :is="step.icon" class="w-5 h-5 sm:w-6 sm:h-6" />
                </div>
                
                <!-- Label -->
                <p 
                    :class="[
                        'text-[10px] sm:text-xs font-bold text-center leading-tight transition-colors duration-500',
                        currentStep >= step.id ? 'text-[#4A3B32]' : 'text-gray-400'
                    ]"
                >
                    {{ step.label }}
                </p>
                
                <!-- Date (just for active/current step as mock, normally from DB) -->
                <p v-if="currentStep >= step.id" class="text-[9px] sm:text-[10px] text-gray-500 text-center mt-1">
                    {{ step.id === 1 ? 'Pesanan dibuat' : (currentStep === step.id ? 'Sedang tahap ini' : 'Selesai') }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { ClipboardList, CheckCircle2, ChefHat, Package, Truck, Gift } from 'lucide-vue-next';

const props = defineProps({
    currentStep: {
        type: Number,
        default: 1
    }
});

const steps = [
    { id: 1, label: 'Pending', icon: ClipboardList },
    { id: 2, label: 'Di Terima', icon: CheckCircle2 },
    { id: 3, label: 'Di Proses', icon: ChefHat },
    { id: 4, label: 'Di Kemas', icon: Package },
    { id: 5, label: 'Dikirim', icon: Truck },
    { id: 6, label: 'Selesai', icon: Gift },
];

const progressWidth = computed(() => {
    // Formula for progress bar width between centers of circles
    if (steps.length <= 1) return '0%';
    const stepPercentage = 100 / (steps.length - 1);
    const current = Math.min(Math.max(1, props.currentStep), steps.length);
    
    // We adjust by slightly pulling it in so it doesn't poke out of the last circle perfectly
    // but a standard percentage works fine since we padded the absolute line with left/right 4.
    return `${(current - 1) * stepPercentage}%`;
});
</script>
