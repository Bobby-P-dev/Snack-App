<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Toast Notifications -->
    <Toast />

    <!-- Sidebar Toggle Button (mobile) -->
    <button
      @click="sidebarOpen = !sidebarOpen"
      class="lg:hidden fixed top-4 left-4 z-50 w-10 h-10 bg-white rounded-xl shadow-md flex items-center justify-center text-gray-700 hover:bg-gray-50 transition"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path v-if="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Sidebar -->
    <aside
      :class="[
        'bg-white shadow-lg flex-shrink-0 transition-all duration-300 overflow-hidden',
        sidebarOpen ? 'w-64' : 'w-0 lg:w-16'
      ]"
    >
      <div class="w-64 h-full flex flex-col">
        <!-- Logo -->
        <div class="p-6 flex items-center justify-between">
          <div :class="sidebarOpen ? '' : 'lg:hidden'">
            <h1 class="text-2xl font-bold text-gray-800">Snack Box</h1>
            <p class="text-sm text-gray-500">Admin Dashboard</p>
          </div>
          <!-- Desktop toggle -->
          <button
            @click="sidebarOpen = !sidebarOpen"
            class="hidden lg:flex w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 items-center justify-center text-gray-500 transition flex-shrink-0"
          >
            <svg class="w-4 h-4 transition-transform" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
          </button>
        </div>

        <nav class="flex-1 overflow-y-auto pb-4 space-y-1 px-3">
          <Link
            href="/admin/dashboard"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 5h4" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Dashboard</span>
          </Link>

          <Link
            href="/admin/suppliers"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.suppliers*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Suppliers</span>
          </Link>

          <Link
            href="/admin/categories"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.categories*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Categories</span>
          </Link>

          <Link
            href="/admin/products"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.products*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Products</span>
          </Link>

          <Link
            href="/admin/orders"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.orders*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Orders</span>
          </Link>

          <Link
            href="/admin/users"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.users*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M9 9H3v10a6 6 0 0016.996.5" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Users</span>
          </Link>

          <Link
            href="/admin/reports"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.reports*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Reports</span>
          </Link>

          <div :class="['px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider', sidebarOpen ? '' : 'lg:hidden']">Settings</div>

          <Link
            href="/admin/cms/settings"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.cms.settings*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Web Settings</span>
          </Link>

          <Link
            href="/admin/cms/carousels"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.cms.carousels*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Carousels</span>
          </Link>

          <Link
            href="/admin/cms/social-media"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.cms.social-media*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Social Media</span>
          </Link>
        </nav>

        <!-- Logout -->
        <div class="p-3 border-t border-gray-100">
          <button
            @click="logout"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition w-full"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Logout</span>
          </button>
        </div>
      </div>
    </aside>

    <!-- Mobile overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/30 z-30"></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Header -->
      <header class="bg-white shadow-sm">
        <div class="flex items-center justify-between px-4 md:px-6 py-3 md:py-4">
          <div class="flex items-center gap-3">
            <div class="lg:hidden w-10"></div>
            <div>
              <h2 class="text-lg font-semibold text-gray-800">{{ pageTitle }}</h2>
              <p class="text-sm text-gray-500">{{ pageSubtitle }}</p>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <div class="relative">
              <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                  {{ userInitial }}
                </div>
                <span class="text-sm hidden sm:inline">{{ userName }}</span>
                <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
              </button>

              <div
                v-if="userMenuOpen"
                @click.away="userMenuOpen = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50"
              >
                <Link href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition border-b">
                  Profile Settings
                </Link>
                <button
                  @click="logout"
                  class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 transition"
                >
                  Logout
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-auto">
        <div class="p-4 md:p-6">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Toast from '@/Components/UI/Toast.vue'

const page = usePage()
const userMenuOpen = ref(false)
const sidebarOpen = ref(true)

const pageTitle = ref('Dashboard')
const pageSubtitle = ref('Welcome back!')

const userName = computed(() => page.props.auth.user?.name || 'Admin')
const userInitial = computed(() => userName.value.charAt(0).toUpperCase())

const logout = () => {
  router.post('/logout')
}
</script>

<style scoped>
:deep(a) {
  transition: all 0.3s ease;
}
</style>