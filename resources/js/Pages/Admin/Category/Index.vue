<template>
  <AdminLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Categories</h2>
          <p class="text-gray-500 text-sm mt-1">Manage product categories</p>
        </div>
        <button @click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition font-medium text-sm shadow-lg shadow-blue-200">
          <Plus class="w-4 h-4" />
          Tambah Kategori
        </button>
      </div>

      <!-- Search -->
      <div class="mb-6">
        <div class="relative max-w-md">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input v-model="searchQuery" @input="search" type="text" placeholder="Cari kategori..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">#</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Name</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Created</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="(category, idx) in categories" :key="category.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-400">{{ idx + 1 }}</td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ category.name }}</td>
                <td class="px-6 py-4">
                  <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">{{ category.slug }}</code>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(category.created_at) }}</td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button @click="openEditModal(category)" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-all text-xs font-medium" title="Edit" aria-label="Edit kategori">
                      <Pencil class="w-4 h-4" />
                    </button>
                    <button @click="confirmDelete(category)" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-all text-xs font-medium" title="Delete" aria-label="Hapus kategori">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="!categories || categories.length === 0">
                <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                  <Tags class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                  <p class="text-gray-500 font-medium">Belum ada kategori</p>
                  <p class="text-sm mt-1">Klik "Tambah Kategori" untuk membuat baru.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Page {{ pagination.current_page }} of {{ pagination.last_page }} ({{ pagination.total }} items)</p>
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
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-black/50" @click="closeModal"></div>
      <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ editingCategory ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>

        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
            <input v-model="form.name" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="contoh: Kue Tradisional" required>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug (auto jika dikosongkan)</label>
            <input v-model="form.slug" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="kue-tradisional">
          </div>

          <div class="flex justify-end gap-3 mt-6">
            <button type="button" @click="closeModal" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition disabled:opacity-50">
              {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-black/50" @click="showDeleteModal = false"></div>
      <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10 text-center">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <AlertTriangle class="w-6 h-6 text-red-600" />
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Kategori</h3>
        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus <strong>{{ deletingCategory?.name }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
          <button @click="showDeleteModal = false" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
          <button @click="deleteCategory" :disabled="form.processing" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition disabled:opacity-50">
            {{ form.processing ? 'Menghapus...' : 'Hapus' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { Plus, Search, Pencil, Trash2, Tags, ChevronLeft, ChevronRight, AlertTriangle } from 'lucide-vue-next'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  categories: { type: Array, default: () => [] },
  pagination: { type: Object, default: () => ({ current_page: 1, last_page: 1, total: 0 }) },
  filters: { type: Object, default: () => ({ search: '' }) }
})

const searchQuery = ref(props.filters?.search || '')
let searchTimeout = null
const search = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get('/admin/categories', { search: searchQuery.value }, { preserveState: true, replace: true })
  }, 500)
}

const goToPage = (page) => {
  router.get('/admin/categories', { page, search: searchQuery.value }, { preserveState: true, replace: true })
}

const showModal = ref(false)
const showDeleteModal = ref(false)
const editingCategory = ref(null)
const deletingCategory = ref(null)

const form = useForm({ name: '', slug: '' })

const openCreateModal = () => {
  editingCategory.value = null
  form.name = ''
  form.slug = ''
  form.clearErrors()
  showModal.value = true
}

const openEditModal = (category) => {
  editingCategory.value = category
  form.name = category.name
  form.slug = category.slug
  form.clearErrors()
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingCategory.value = null
  form.reset()
}

const submitForm = () => {
  if (editingCategory.value) {
    form.put(`/admin/categories/${editingCategory.value.id}`, {
      onSuccess: () => closeModal()
    })
  } else {
    form.post('/admin/categories', {
      onSuccess: () => closeModal()
    })
  }
}

const confirmDelete = (category) => {
  deletingCategory.value = category
  showDeleteModal.value = true
}

const deleteCategory = () => {
  form.delete(`/admin/categories/${deletingCategory.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false
      deletingCategory.value = null
    }
  })
}

const formatDate = (date) => date ? new Date(date).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' }) : '-'
</script>