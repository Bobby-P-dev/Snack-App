<template>
  <AdminLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Products</h2>
          <p class="text-gray-500 text-sm mt-1">Manage all products</p>
        </div>
        <button @click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition font-medium text-sm shadow-lg shadow-blue-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Tambah Produk
        </button>
      </div>

      <!-- Search & Filters -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input v-model="searchQuery" @input="search" type="text" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition">
        </div>
        <select v-model="filterCategory" @change="applyFilters" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition">
          <option value="">Semua Kategori</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
        <select v-model="filterSupplier" @change="applyFilters" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition">
          <option value="">Semua Supplier</option>
          <option v-for="sup in suppliers" :key="sup.id" :value="sup.id">{{ sup.name }}</option>
        </select>
      </div>

      <!-- Info Bar -->
      <div class="mb-3 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <strong>{{ pagination.total || products.length }}</strong> produk</p>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Supplier</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Base Price</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sell Price</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="(product, idx) in products" :key="product.id" class="hover:bg-blue-50/30 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-400 font-mono">{{ idx + 1 + ((pagination.current_page - 1) * pagination.per_page || 0) }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                      <img v-if="product.image_url" :src="getImageUrl(product.image_url)" class="w-full h-full object-cover" />
                      <svg v-else class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="font-semibold text-gray-800">{{ product.name }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-block bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full">{{ product.category?.name || '-' }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ product.supplier?.name || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">Rp {{ formatNumber(product.base_price) }}</td>
                <td class="px-6 py-4 font-semibold text-blue-600 font-mono">Rp {{ formatNumber(product.sell_price) }}</td>
                <td class="px-6 py-4 text-center">
                  <span :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold', product.is_active ? 'bg-green-50 text-green-700 ring-1 ring-green-200' : 'bg-gray-50 text-gray-500 ring-1 ring-gray-200']">
                    <span :class="['w-1.5 h-1.5 rounded-full', product.is_active ? 'bg-green-500' : 'bg-gray-400']"></span>
                    {{ product.is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="toggleStatus(product)" :title="product.is_active ? 'Nonaktifkan' : 'Aktifkan'" :class="['p-2 rounded-lg text-xs font-medium transition-all', product.is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700' : 'bg-green-50 text-green-600 hover:bg-green-100 hover:text-green-700']">
                      <svg v-if="product.is_active" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 18a8 8 0 100-16 8 8 0 000 16zM6.343 6.343a4 4 0 015.657 0l-5.657 5.657a4 4 0 010-5.657zm5.657 0a4 4 0 015.657 5.657l-5.657-5.657z"/></svg>
                      <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                    <button @click="openEditModal(product)" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-all text-xs font-medium" title="Edit">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button @click="confirmDelete(product)" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-all text-xs font-medium" title="Delete">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="products.length === 0">
                <td colspan="8">
                  <div class="py-16 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <p class="text-lg font-medium text-gray-500 mb-1">Belum ada produk</p>
                    <p class="text-sm text-gray-400">Klik "Tambah Produk" untuk membuat produk baru.</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Halaman {{ pagination.current_page }} dari {{ pagination.last_page }} ({{ pagination.total }} produk)</p>
        <div class="flex gap-2">
          <button :disabled="pagination.current_page <= 1" @click="goToPage(pagination.current_page - 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Prev
          </button>
          <button :disabled="pagination.current_page >= pagination.last_page" @click="goToPage(pagination.current_page + 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
            Next
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Modal (Create/Edit) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.esc="closeModal">
      <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10 max-h-[90vh] overflow-y-auto animate-modal-in">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-lg font-bold text-gray-900">{{ editingProduct ? 'Edit Produk' : 'Tambah Produk' }}</h3>
          <button @click="closeModal" class="p-1.5 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
              <input v-model="form.name" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition" placeholder="Nama produk" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select v-model="form.category_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition" required>
                  <option value="">Pilih Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Supplier <span class="text-red-500">*</span></label>
                <select v-model="form.supplier_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition" required>
                  <option value="">Pilih Supplier</option>
                  <option v-for="sup in suppliers" :key="sup.id" :value="sup.id">{{ sup.name }}</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Base Price <span class="text-red-500">*</span></label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                  <input v-model="form.base_price" type="number" min="0" class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition" required>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Sell Price <span class="text-red-500">*</span></label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                  <input v-model="form.sell_price" type="number" min="0" class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition" required>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Gambar Produk</label>
              <div class="flex items-center gap-4">
                <div v-if="imagePreview" class="w-20 h-20 rounded-xl overflow-hidden border-2 border-gray-100 flex-shrink-0">
                  <img :src="imagePreview" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                  <label class="flex flex-col items-center justify-center w-full px-4 py-4 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50/30 transition">
                    <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs text-gray-500">Klik untuk upload gambar</span>
                    <input type="file" @input="handleFile" accept="image/*" class="hidden">
                  </label>
                </div>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
              <div class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="form.is_active" id="isActive" class="sr-only peer">
                <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
              </div>
              <label for="isActive" class="text-sm font-medium text-gray-700 cursor-pointer">Produk Aktif</label>
            </div>
          </div>

          <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
            <button type="button" @click="closeModal" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl text-sm font-medium hover:from-blue-700 hover:to-blue-800 transition disabled:opacity-50 shadow-lg shadow-blue-200 flex items-center gap-2">
              <span v-if="form.processing" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Menyimpan...
              </span>
              <span v-else>
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 text-center animate-modal-in">
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 ring-8 ring-red-50">
          <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Produk</h3>
        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus <strong class="text-gray-700">{{ deletingProduct?.name }}</strong>?<br>Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
          <button @click="showDeleteModal = false" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
          <button @click="deleteProduct" :disabled="form.processing" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition disabled:opacity-50 flex items-center gap-2 shadow-lg shadow-red-200">
            {{ form.processing ? 'Menghapus...' : 'Ya, Hapus' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  products: { type: Array, default: () => [] },
  pagination: { type: Object, default: () => ({ current_page: 1, last_page: 1, total: 0, per_page: 15 }) },
  filters: { type: Object, default: () => ({ search: '', category_id: '', supplier_id: '' }) },
  categories: { type: Array, default: () => [] },
  suppliers: { type: Array, default: () => [] }
})

// Search & Filters
const searchQuery = ref(props.filters?.search || '')
const filterCategory = ref(props.filters?.category_id || '')
const filterSupplier = ref(props.filters?.supplier_id || '')

let searchTimeout = null
const search = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => applyFilters(), 500)
}

const applyFilters = () => {
  router.get('/admin/products', {
    search: searchQuery.value,
    category_id: filterCategory.value,
    supplier_id: filterSupplier.value
  }, { preserveState: true, replace: true })
}

const goToPage = (page) => {
  router.get('/admin/products', {
    page,
    search: searchQuery.value,
    category_id: filterCategory.value,
    supplier_id: filterSupplier.value
  }, { preserveState: true, replace: true })
}

const getImageUrl = (url) => {
  if (!url) return ''
  return url.startsWith('http') ? url : `/storage/${url}`
}

// Modal
const showModal = ref(false)
const showDeleteModal = ref(false)
const editingProduct = ref(null)
const deletingProduct = ref(null)
const imagePreview = ref(null)

const form = useForm({
  name: '', category_id: '', supplier_id: '', base_price: '', sell_price: '', image: null, is_active: true
})

const openCreateModal = () => {
  editingProduct.value = null
  form.reset()
  form.is_active = true
  imagePreview.value = null
  form.clearErrors()
  showModal.value = true
}

const openEditModal = (product) => {
  editingProduct.value = product
  form.name = product.name
  form.category_id = product.category?.id || ''
  form.supplier_id = product.supplier?.id || ''
  form.base_price = product.base_price
  form.sell_price = product.sell_price
  form.is_active = product.is_active
  form.image = null
  imagePreview.value = product.image_url ? getImageUrl(product.image_url) : null
  form.clearErrors()
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingProduct.value = null
  form.reset()
  imagePreview.value = null
}

const handleFile = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.image = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const submitForm = () => {
  if (editingProduct.value) {
    form.transform((data) => ({
      ...data,
      _method: 'PUT'
    })).post(`/admin/products/${editingProduct.value.id}`, {
      forceFormData: true,
      onSuccess: () => closeModal()
    })
  } else {
    form.post('/admin/products', {
      onSuccess: () => closeModal()
    })
  }
}

const toggleStatus = (product) => {
  router.post(`/admin/products/${product.id}/toggle-status`, {}, {
    preserveScroll: true
  })
}

const confirmDelete = (product) => {
  deletingProduct.value = product
  showDeleteModal.value = true
}

const deleteProduct = () => {
  form.delete(`/admin/products/${deletingProduct.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false
      deletingProduct.value = null
    }
  })
}

const formatNumber = (num) => {
  if (num === null || num === undefined || num === '') return '0'
  return new Intl.NumberFormat('id-ID').format(num)
}
</script>

<style scoped>
@keyframes modal-in {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modal-in 0.15s ease-out;
}
</style>