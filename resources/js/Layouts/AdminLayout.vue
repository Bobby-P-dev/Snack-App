<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Toast Notifications -->
    <Toast />

    <!-- Sidebar -->
    <aside
      :class="[
        'bg-white shadow-lg flex-shrink-0 transition-all duration-300 overflow-hidden z-40',
        'fixed inset-y-0 left-0 lg:static lg:inset-auto',
        sidebarOpen ? 'w-64' : 'w-0 lg:w-16'
      ]"
    >
      <div :class="['h-full flex flex-col', sidebarOpen ? 'w-64' : 'w-64 lg:w-16']">
        <!-- Logo Header: Expanded State -->
        <div v-if="sidebarOpen" class="p-4 flex items-center justify-between border-b border-gray-100">
          <div class="flex items-center gap-3 min-w-0">
            <img src="/images/padukue-icon.png" alt="Padu Kue" class="w-10 h-10 object-contain flex-shrink-0" />
            <div class="truncate">
              <h1 class="text-base font-extrabold text-gray-900 leading-tight truncate">Padu Kue</h1>
              <p class="text-xs text-gray-500 font-medium">Admin Dashboard</p>
            </div>
          </div>
          <!-- Close / Collapse Button -->
          <button
            @click="sidebarOpen = false"
            class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-800 transition flex-shrink-0 cursor-pointer"
            title="Tutup Sidebar"
          >
            <ChevronLeft class="w-4 h-4 hidden lg:block" />
            <X class="w-4 h-4 lg:hidden" />
          </button>
        </div>

        <!-- Logo Header: Collapsed State (Clickable icon to expand) -->
        <div v-else class="p-3 w-16 flex items-center justify-center border-b border-gray-100">
          <button
            @click="sidebarOpen = true"
            class="w-10 h-10 rounded-xl hover:bg-gray-100 flex items-center justify-center transition cursor-pointer group"
            title="Klik untuk membuka Sidebar"
          >
            <img src="/images/padukue-icon.png" alt="Padu Kue" class="w-9 h-9 object-contain group-hover:scale-110 transition-transform" />
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
            <LayoutDashboard class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Dashboard</span>
          </Link>

          <Link
            href="/admin/suppliers"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.suppliers*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <Truck class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Suppliers</span>
          </Link>

          <Link
            href="/admin/categories"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.categories*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <Tags class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Categories</span>
          </Link>

          <Link
            href="/admin/products"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.products*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <Package class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Products</span>
          </Link>

          <Link
            href="/admin/orders"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.orders*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <ShoppingBag class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Orders</span>
          </Link>

          <Link
            href="/admin/users"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.users*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <Users class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Users</span>
          </Link>

          <Link
            href="/admin/reports"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.reports*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <BarChart3 class="w-5 h-5 flex-shrink-0" />
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
            <Settings class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Web Settings</span>
          </Link>

          <Link
            href="/admin/cms/carousels"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.cms.carousels*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <SlidersHorizontal class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Carousels</span>
          </Link>

          <Link
            href="/admin/cms/social-media"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
              route().current('admin.cms.social-media*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <Share2 class="w-5 h-5 flex-shrink-0" />
            <span :class="sidebarOpen ? '' : 'lg:hidden'">Social Media</span>
          </Link>
        </nav>

        <!-- Logout -->
        <div class="p-3 border-t border-gray-100">
          <button
            @click="logout"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition w-full cursor-pointer"
          >
            <LogOut class="w-5 h-5 flex-shrink-0" />
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
            <!-- Sidebar Toggle Button (Always visible on all screens) -->
            <button
              @click="sidebarOpen = !sidebarOpen"
              class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition cursor-pointer shadow-2xs active:scale-95 flex-shrink-0"
              :title="sidebarOpen ? 'Ciutkan Sidebar' : 'Buka Sidebar'"
              aria-label="Toggle Sidebar"
            >
              <Menu class="w-5 h-5" />
            </button>

            <div>
              <h2 class="text-lg font-bold text-gray-800 leading-tight">{{ pageTitle }}</h2>
              <p class="text-xs text-gray-500 font-medium">{{ pageSubtitle }}</p>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <div class="relative">
              <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                  {{ userInitial }}
                </div>
                <span class="text-sm hidden sm:inline">{{ userName }}</span>
                <ChevronDown class="w-4 h-4 hidden sm:block text-gray-500" />
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
                  class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 transition cursor-pointer"
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
import {
  LayoutDashboard,
  Truck,
  Tags,
  Package,
  ShoppingBag,
  Users,
  BarChart3,
  Settings,
  SlidersHorizontal,
  Share2,
  LogOut,
  Menu,
  X,
  ChevronLeft,
  ChevronDown,
} from 'lucide-vue-next'

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