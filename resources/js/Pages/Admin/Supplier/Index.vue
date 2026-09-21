<template>
  <AdminLayout>
    <div class="p-6">
      <!-- Header & Search -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Suppliers</h2>
          <p class="text-gray-500 text-sm mt-1">Manage product suppliers</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
          <!-- Search -->
          <div class="relative w-full sm:w-64">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input v-model="searchQuery" @input="search" type="text" placeholder="Cari supplier..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
          
          <!-- Add Button -->
          <button @click="openCreateModal" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition font-medium text-sm shadow-lg shadow-blue-200 whitespace-nowrap">
            <Plus class="w-4 h-4" />
            Tambah Supplier
          </button>
        </div>
      </div>

      <!-- Info Bar -->
      <div class="mb-3 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <strong>{{ pagination.total || suppliers.length }}</strong> supplier</p>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Address</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Daily Capacity</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="(supplier, idx) in suppliers" :key="supplier.id" class="hover:bg-blue-50/30 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-400 font-mono">{{ idx + 1 + ((pagination.current_page - 1) * pagination.per_page || 0) }}</td>
                <td class="px-6 py-4 font-semibold text-gray-800">{{ supplier.name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ supplier.phone || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500 max-w-[200px] truncate">{{ supplier.address || '-' }}</td>
                <td class="px-6 py-4 text-center">
                  <span class="inline-block bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full">{{ supplier.daily_capacity ?? '-' }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openEditModal(supplier)" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-all text-xs font-medium" title="Edit">
                      <Pencil class="w-4 h-4" />
                    </button>
                    <button @click="confirmDelete(supplier)" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-all text-xs font-medium" title="Delete">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="!suppliers || suppliers.length === 0">
                <td colspan="6">
                  <div class="py-16 text-center">
                    <Truck class="w-16 h-16 mx-auto mb-4 text-gray-300 stroke-1" />
                    <p class="text-lg font-medium text-gray-500 mb-1">Belum ada supplier</p>
                    <p class="text-sm text-gray-400">Klik "Tambah Supplier" untuk membuat baru.</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Halaman {{ pagination.current_page }} dari {{ pagination.last_page }} ({{ pagination.total }} supplier)</p>
        <div class="flex gap-2">
          <button :disabled="pagination.current_page <= 1" @click="goToPage(pagination.current_page - 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
            <ChevronLeft class="w-4 h-4" />
            Prev
          </button>
          <button :disabled="pagination.current_page >= pagination.last_page" @click="goToPage(pagination.current_page + 1)" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium disabled:opacity-50 hover:bg-gray-50 transition flex items-center gap-1">
            Next
            <ChevronRight class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Modal (Create/Edit) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.esc="closeModal">
      <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10 max-h-[90vh] overflow-y-auto animate-modal-in">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-lg font-bold text-gray-900">{{ editingSupplier ? 'Edit Supplier' : 'Tambah Supplier' }}</h3>
          <button @click="closeModal" class="p-1.5 rounded-lg hover:bg-gray-100 transition">
            <X class="w-5 h-5 text-gray-500" />
          </button>
        </div>

        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Supplier <span class="text-red-500">*</span></label>
              <input v-model="form.name" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition" placeholder="Nama supplier" required>
              <span v-if="form.errors.name" class="text-red-500 text-xs mt-1 block">{{ form.errors.name }}</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon <span class="text-red-500">*</span></label>
              <input v-model="form.phone" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition" placeholder="Contoh: 08123456789" required>
              <span v-if="form.errors.phone" class="text-red-500 text-xs mt-1 block">{{ form.errors.phone }}</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat <span class="text-red-500">*</span></label>
              <textarea v-model="form.address" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition resize-none" placeholder="Alamat lengkap supplier" required></textarea>
              <span v-if="form.errors.address" class="text-red-500 text-xs mt-1 block">{{ form.errors.address }}</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Kapasitas Harian <span class="text-red-500">*</span></label>
              <input v-model="form.daily_capacity" type="number" min="1" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition" placeholder="Contoh: 100" required>
              <span v-if="form.errors.daily_capacity" class="text-red-500 text-xs mt-1 block">{{ form.errors.daily_capacity }}</span>
            </div>
          </div>

          <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
            <button type="button" @click="closeModal" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl text-sm font-medium hover:from-blue-700 hover:to-blue-800 transition disabled:opacity-50 shadow-lg shadow-blue-200 flex items-center gap-2">
              <span v-if="form.processing" class="flex items-center gap-2">
                <Loader2 class="w-4 h-4 animate-spin" />
                Menyimpan...
              </span>
              <span v-else class="flex items-center gap-1.5">
                <Check class="w-4 h-4" />
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
          <AlertTriangle class="w-7 h-7 text-red-600" />
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Supplier</h3>
        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus <strong class="text-gray-700">{{ deletingSupplier?.name }}</strong>?<br>Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
          <button @click="showDeleteModal = false" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
          <button @click="deleteSupplier" :disabled="form.processing" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition disabled:opacity-50 flex items-center gap-2 shadow-lg shadow-red-200">
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
import {
  Search,
  Plus,
  Pencil,
  Trash2,
  Truck,
  ChevronLeft,
  ChevronRight,
  X,
  Check,
  AlertTriangle,
  Loader2
} from 'lucide-vue-next'

const props = defineProps({
  suppliers: { type: Array, default: () => [] },
  pagination: { type: Object, default: () => ({ current_page: 1, last_page: 1, total: 0, per_page: 10 }) },
  filters: { type: Object, default: () => ({ search: '' }) }
})

// Search
const searchQuery = ref(props.filters?.search || '')
let searchTimeout = null
const search = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get('/admin/suppliers', { search: searchQuery.value }, { preserveState: true, replace: true })
  }, 500)
}

const goToPage = (page) => {
  router.get('/admin/suppliers', { page, search: searchQuery.value }, { preserveState: true, replace: true })
}

// Modal
const showModal = ref(false)
const showDeleteModal = ref(false)
const editingSupplier = ref(null)
const deletingSupplier = ref(null)

const form = useForm({
  name: '', phone: '', address: '', daily_capacity: ''
})

const openCreateModal = () => {
  editingSupplier.value = null
  form.reset()
  form.clearErrors()
  showModal.value = true
}

const openEditModal = (supplier) => {
  editingSupplier.value = supplier
  form.name = supplier.name
  form.phone = supplier.phone || ''
  form.address = supplier.address || ''
  form.daily_capacity = supplier.daily_capacity || ''
  form.clearErrors()
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingSupplier.value = null
  form.reset()
}

const submitForm = () => {
  if (editingSupplier.value) {
    form.put(`/admin/suppliers/${editingSupplier.value.id}`, {
      onSuccess: () => closeModal()
    })
  } else {
    form.post('/admin/suppliers', {
      onSuccess: () => closeModal()
    })
  }
}

const confirmDelete = (supplier) => {
  deletingSupplier.value = supplier
  showDeleteModal.value = true
}

const deleteSupplier = () => {
  form.delete(`/admin/suppliers/${deletingSupplier.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false
      deletingSupplier.value = null
    }
  })
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