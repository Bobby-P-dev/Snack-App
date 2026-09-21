<template>
    <div class="w-full">
        <!-- If order is cancelled -->
        <div v-if="currentStep === 0 || status === 'batal'" class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 sm:p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                <AlertCircle class="w-5 h-5" />
            </div>
            <div>
                <h4 class="font-bold text-brown-900 text-base mb-1">Status: Pesanan Dibatalkan</h4>
                <p class="text-sm text-brown-600 leading-relaxed">
                    Pesanan ini telah berstatus batal. Jika Anda merasa ini adalah kekeliruan atau ingin melakukan pemesanan ulang, silakan hubungi admin kami melalui WhatsApp.
                </p>
            </div>
        </div>

        <!-- Normal Active Lifecycle Timeline -->
        <div v-else class="relative w-full py-2">
            <!-- Desktop / Tablet Timeline (>= 640px) -->
            <div class="hidden sm:block relative">
                <!-- Background progress line -->
                <div class="absolute top-7 left-6 right-6 h-1.5 bg-cream-200 rounded-full z-0"></div>
                <!-- Active filled progress line -->
                <div 
                    class="absolute top-7 left-6 h-1.5 bg-gradient-to-r from-brand-500 to-brand-600 rounded-full z-0 transition-all duration-700 ease-out shadow-xs"
                    :style="{ width: progressWidth }"
                ></div>

                <!-- Steps Container -->
                <div class="relative z-10 flex justify-between">
                    <div 
                        v-for="step in steps" 
                        :key="step.id" 
                        class="flex flex-col items-center group relative w-20 md:w-24 text-center"
                    >
                        <!-- Node Circle -->
                        <div 
                            class="w-13 h-13 md:w-14 md:h-14 rounded-2xl flex items-center justify-center mb-2.5 transition-all duration-300 border-3 border-white"
                            :class="[
                                currentStep > step.id 
                                    ? 'bg-brand-500 text-white shadow-sm' 
                                    : (currentStep === step.id 
                                        ? 'bg-gradient-to-tr from-brand-600 to-brand-400 text-white shadow-md ring-4 ring-brand-200/80 scale-105' 
                                        : 'bg-cream-100 text-brown-300 shadow-2xs')
                            ]"
                        >
                            <!-- Completed Step Checkmark -->
                            <Check v-if="currentStep > step.id" class="w-5 h-5 stroke-[2.5]" />
                            <component v-else :is="step.icon" class="w-5 h-5 md:w-5.5 md:h-5.5" stroke-width="1.8" />
                        </div>
                        
                        <!-- Label -->
                        <p 
                            class="text-xs md:text-sm font-bold tracking-tight transition-colors duration-300"
                            :class="currentStep >= step.id ? 'text-brown-900' : 'text-brown-400'"
                        >
                            {{ step.label }}
                        </p>

                        <!-- Status text pill -->
                        <span 
                            v-if="currentStep === step.id" 
                            class="mt-1 px-2 py-0.5 rounded-md bg-brand-50 text-brand-600 text-[10px] font-extrabold tracking-wide border border-brand-200/60 uppercase"
                        >
                            Sedang Berjalan
                        </span>
                        <span 
                            v-else-if="currentStep > step.id" 
                            class="mt-1 text-[10px] font-medium text-emerald-600"
                        >
                            Selesai
                        </span>
                    </div>
                </div>
            </div>

            <!-- Mobile Stepper (< 640px) -->
            <div class="sm:hidden space-y-3">
                <div class="flex items-center justify-between bg-cream-50/80 p-3.5 rounded-2xl border border-cream-200 mb-2">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-brown-700">Tahap {{ currentStep }} dari 6:</span>
                    </div>
                    <span class="text-xs font-extrabold text-brand-600 bg-white px-2.5 py-1 rounded-lg border border-cream-200 shadow-2xs">
                        {{ currentStepObj?.label || 'Memproses' }}
                    </span>
                </div>

                <div class="grid grid-cols-6 gap-1.5 px-1">
                    <div 
                        v-for="step in steps" 
                        :key="step.id" 
                        class="h-2 rounded-full transition-all duration-300"
                        :class="[
                            currentStep > step.id ? 'bg-brand-500' : (currentStep === step.id ? 'bg-brand-500 ring-2 ring-brand-200' : 'bg-cream-200')
                        ]"
                    ></div>
                </div>

                <!-- Active Step Highlight Card for Mobile -->
                <div class="bg-white p-4 rounded-2xl border border-cream-200 shadow-2xs flex items-center gap-3.5 mt-3">
                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 border border-brand-200/80 flex items-center justify-center shrink-0">
                        <component :is="currentStepObj?.icon || Package" class="w-6 h-6" stroke-width="1.8" />
                    </div>
                    <div>
                        <p class="text-xs text-brand-600 font-bold uppercase tracking-wider">Tahap Aktif Saat Ini</p>
                        <h4 class="text-sm font-extrabold text-brown-900">{{ currentStepObj?.label }}</h4>
                        <p class="text-xs text-brown-600 mt-0.5">{{ currentStepObj?.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { ClipboardList, CheckCircle2, ChefHat, Package, Truck, Gift, Check, AlertCircle } from 'lucide-vue-next';

const props = defineProps({
    currentStep: {
        type: Number,
        default: 1
    },
    status: {
        type: String,
        default: 'pending'
    }
});

const steps = [
    { id: 1, label: 'Pending', icon: ClipboardList, description: 'Pesanan masuk ke sistem dan menunggu konfirmasi admin.' },
    { id: 2, label: 'Diterima', icon: CheckCircle2, description: 'Pesanan telah diverifikasi dan masuk jadwal produksi dapur.' },
    { id: 3, label: 'Diproses', icon: ChefHat, description: 'Koki kami sedang mengolah dan memanggang kue pilihan Anda.' },
    { id: 4, label: 'Dikemas', icon: Package, description: 'Kue dimasukkan ke dalam snack box higienis dan rapi.' },
    { id: 5, label: 'Dikirim', icon: Truck, description: 'Paket siap diambil atau sedang dalam perjalanan kurir.' },
    { id: 6, label: 'Selesai', icon: Gift, description: 'Pesanan telah diterima dan selesai dengan selamat.' },
];

const currentStepObj = computed(() => {
    return steps.find(s => s.id === props.currentStep) || steps[0];
});

const progressWidth = computed(() => {
    if (steps.length <= 1) return '0%';
    const stepPercentage = 100 / (steps.length - 1);
    const current = Math.min(Math.max(1, props.currentStep), steps.length);
    return `${(current - 1) * stepPercentage}%`;
});
</script>
