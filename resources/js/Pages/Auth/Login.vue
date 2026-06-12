<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

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
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-white/5 rounded-full"></div>
            <div class="absolute top-1/3 -left-10 w-48 h-48 bg-blue-500/20 rounded-full blur-2xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-indigo-500/20 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col justify-center items-center w-full p-12">
                <!-- Logo -->
                <div class="mb-8 flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-xl">
                        <span class="text-3xl font-extrabold text-blue-600">SB</span>
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-white mb-4 text-center">Snack Box Admin</h1>
                <p class="text-blue-200 text-lg text-center max-w-md">
                    Kelola pesanan, produk, supplier, dan konten website Anda dalam satu dashboard terintegrasi.
                </p>

                <!-- Feature List -->
                <div class="mt-12 space-y-4 w-full max-w-sm">
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <div class="w-10 h-10 bg-blue-500/30 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-white font-medium">Manajemen Pesanan</span>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <div class="w-10 h-10 bg-blue-500/30 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <span class="text-white font-medium">Produk & Kategori</span>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <div class="w-10 h-10 bg-blue-500/30 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-white font-medium">Pengaturan Website (CMS)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Login Form -->
        <div class="flex-1 flex items-center justify-center bg-gray-50 px-6 py-12">
            <div class="w-full max-w-md">
                <!-- Header -->
                <div class="text-center mb-10">
                    <div class="lg:hidden flex items-center justify-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-extrabold text-white">SB</span>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
                    <p class="text-gray-500 mt-2">Silakan masuk ke dashboard admin</p>
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
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition placeholder:text-gray-400"
                                placeholder="admin@snackbox.id"
                            />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition placeholder:text-gray-400"
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
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                            />
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline"
                        >
                            Lupa password?
                        </Link>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-blue-200"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                        <span v-else>Masuk ke Dashboard</span>
                    </button>
                </form>

                <!-- Footer -->
                <p class="text-center text-xs text-gray-400 mt-8">
                    &copy; 2026 Snack Box. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>
