<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ShoppingBag, Package, Settings, Mail, Lock, Loader2 } from 'lucide-vue-next';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login - Snack Box Admin" />

    <!-- Background Split Layout -->
    <div class="min-h-screen flex">
        <!-- Left: Brand/Info Panel -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-brown-800 via-brand-700 to-amber-800 relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-white/5 rounded-full"></div>
            <div class="absolute top-1/3 -left-10 w-48 h-48 bg-brand-400/20 rounded-full blur-2xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-amber-400/20 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col justify-center items-center w-full p-12">
                <!-- Logo -->
                <div class="mb-8 flex items-center justify-center">
                    <img src="/images/padukue-logo.png" alt="Padu Kue" class="h-36 w-auto object-contain bg-white/95 rounded-2xl p-4 shadow-xl backdrop-blur-xs" />
                </div>

                <h1 class="text-3xl font-black text-white mb-3 text-center tracking-tight">Padu Kue Admin</h1>
                <p class="text-cream-200 text-base text-center max-w-md">
                    Kelola pesanan, produk, supplier, dan konten website Anda dalam satu dashboard terintegrasi.
                </p>

                <!-- Feature List -->
                <div class="mt-10 space-y-3.5 w-full max-w-sm">
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/10">
                        <div class="w-10 h-10 bg-brand-500/30 rounded-lg flex items-center justify-center text-white">
                            <ShoppingBag class="w-5 h-5" />
                        </div>
                        <span class="text-white font-medium text-sm">Manajemen Pesanan</span>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/10">
                        <div class="w-10 h-10 bg-brand-500/30 rounded-lg flex items-center justify-center text-white">
                            <Package class="w-5 h-5" />
                        </div>
                        <span class="text-white font-medium text-sm">Produk & Kategori</span>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/10">
                        <div class="w-10 h-10 bg-brand-500/30 rounded-lg flex items-center justify-center text-white">
                            <Settings class="w-5 h-5" />
                        </div>
                        <span class="text-white font-medium text-sm">Pengaturan Website (CMS)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Login Form -->
        <div class="flex-1 flex items-center justify-center bg-cream-50/50 px-6 py-12">
            <div class="w-full max-w-md">
                <!-- Header -->
                <div class="text-center mb-10">
                    <div class="lg:hidden flex items-center justify-center mb-6">
                        <img src="/images/padukue-logo.png" alt="Padu Kue" class="h-20 sm:h-24 w-auto object-contain" />
                    </div>
                    <h2 class="text-3xl font-bold text-brown-900">Welcome Back</h2>
                    <p class="text-brown-500 mt-2 text-sm">Silakan masuk ke dashboard admin</p>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg text-sm font-medium text-green-700">
                    {{ status }}
                </div>

                <!-- Error Messages -->
                <div v-if="form.errors.email || form.errors.password" class="mb-6 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600">Email atau password salah. Silakan coba lagi.</p>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <Mail class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 border border-cream-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition placeholder:text-brown-300 bg-white"
                                placeholder="admin@padukue.store"
                            />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-brown-800 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <Lock class="w-5 h-5 text-brown-400" />
                            </div>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                class="w-full pl-10 pr-4 py-3 border border-cream-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition placeholder:text-brown-300 bg-white"
                                placeholder="Masukkan password"
                            />
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="w-4 h-4 text-brand-600 border-cream-300 rounded focus:ring-brand-500 cursor-pointer"
                            />
                            <span class="text-sm text-brown-600">Ingat saya</span>
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm font-medium text-brand-600 hover:text-brand-800 hover:underline"
                        >
                            Lupa password?
                        </Link>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3 px-4 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold rounded-xl hover:from-brand-700 hover:to-brand-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-brand-500/20 cursor-pointer"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center gap-2">
                            <Loader2 class="animate-spin h-5 w-5 text-white" />
                            Memproses...
                        </span>
                        <span v-else>Masuk ke Dashboard</span>
                    </button>
                </form>

                <!-- Footer -->
                <p class="text-center text-xs text-brown-400 mt-8">
                    &copy; 2026 Padu Kue. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>
