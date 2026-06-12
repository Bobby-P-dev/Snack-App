<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg">
      <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800">Snack Box</h1>
        <p class="text-sm text-gray-500">Admin Dashboard</p>
      </div>

      <nav class="mt-6">
        <Link
          href="/admin/dashboard"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.dashboard') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 5h4" />
          </svg>
          Dashboard
        </Link>

        <Link
          href="/admin/suppliers"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.suppliers*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
          </svg>
          Suppliers
        </Link>

        <Link
          href="/admin/categories"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.categories*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
          Categories
        </Link>

        <Link
          href="/admin/products"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.products*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          Products
        </Link>

        <Link
          href="/admin/orders"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.orders*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Orders
        </Link>

        <Link
          href="/admin/users"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.users*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M9 9H3v10a6 6 0 0016.996.5" />
          </svg>
          Users
        </Link>

        <Link
          href="/admin/reports"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.reports*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          Reports
        </Link>

        <div class="px-6 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</div>

        <Link
          href="/admin/cms/settings"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.cms.settings*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Web Settings
        </Link>

        <Link
          href="/admin/cms/carousels"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.cms.carousels*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          Carousels
        </Link>

        <Link
          href="/admin/cms/social-media"
          :class="[
            'flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition',
            route().current('admin.cms.social-media*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-600' : ''
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
          </svg>
          Social Media
        </Link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Header -->
      <header class="bg-white shadow">
        <div class="flex items-center justify-between px-6 py-4">
          <div>
            <h2 class="text-lg font-semibold text-gray-800">{{ pageTitle }}</h2>
            <p class="text-sm text-gray-500">{{ pageSubtitle }}</p>
          </div>

          <div class="flex items-center space-x-4">
            <!-- Notification Bell -->
            <button class="relative text-gray-600 hover:text-gray-900 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">3</span>
            </button>

            <!-- User Dropdown -->
            <div class="relative">
              <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                  {{ userInitial }}
                </div>
                <span class="text-sm">{{ userName }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
              </button>

              <!-- Dropdown Menu -->
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
        <div class="p-6">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()
const userMenuOpen = ref(false)

const pageTitle = ref('Dashboard')
const pageSubtitle = ref('Welcome back!')

const userName = computed(() => page.props.auth.user?.name || 'Admin')
const userInitial = computed(() => userName.value.charAt(0).toUpperCase())

const logout = () => {
  router.post('/logout')
}
</script>

<style scoped>
/* Smooth transitions */
:deep(a) {
  transition: all 0.3s ease;
}
</style>
