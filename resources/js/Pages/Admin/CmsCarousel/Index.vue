<template>
  <Head title="Manage Carousels" />
  <AdminLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Carousels</h2>
          <p class="text-gray-500 text-sm mt-1">Kelola banner gambar di homepage — drag & drop untuk urutkan</p>
        </div>
        <button @click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition font-medium text-sm shadow-lg shadow-blue-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Tambah Banner
        </button>
      </div>

      <!-- Info Bar -->
      <div class="mb-3 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <strong>{{ localCarousels.length }}</strong> banner — Seret baris untuk mengubah urutan</p>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">#</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Image</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Link URL</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Order</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Durasi</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <draggable
              tag="tbody"
              :list="localCarousels"
              item-key="id"
              handle=".drag-handle"
              class="divide-y divide-gray-100"
              @end="onDragEnd"
            >
              <template #item="{ element: carousel, index: idx }">
                <tr class="hover:bg-blue-50/30 transition-colors">
                  <td class="px-6 py-4 text-sm text-gray-400">
                    <span class="drag-handle cursor-grab active:cursor-grabbing inline-flex items-center gap-1">
                      <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h.01M12 6h.01M16 6h.01M8 12h.01M12 12h.01M16 12h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
                      <span class="font-mono">{{ idx + 1 }}</span>
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="w-20 h-12 rounded-lg bg-gray-100 overflow-hidden">
                      <img v-if="carousel.image_url" :src="getImageUrl(carousel.image_url)" :alt="carousel.title" class="w-full h-full object-cover" />
                      <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <p class="font-semibold text-gray-800">{{ carousel.title || '-' }}</p>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-500 max-w-[180px] truncate">{{ carousel.description || '-' }}</td>
                  <td class="px-6 py-4 text-sm text-blue-600 max-w-[160px] truncate">
                    <a v-if="carousel.link_url" :href="carousel.link_url" target="_blank" class="hover:underline">{{ carousel.link_url }}</a>
                    <span v-else class="text-gray-400">-</span>
                  </td>
                  <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-sm font-bold text-gray-700">{{ carousel.order }}</span>
                  </td>
                  <td class="px-6 py-4 text-center">
                    <span class="text-sm text-gray-600">{{ carousel.duration || 5 }}s</span>
                  </td>
                  <td class="px-6 py-4 text-center">
                    <span :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold', carousel.is_active ? 'bg-green-50 text-green-700 ring-1 ring-green-200' : 'bg-gray-50 text-gray-500 ring-1 ring-gray-200']">
                      <span :class="['w-1.5 h-1.5 rounded-full', carousel.is_active ? 'bg-green-500' : 'bg-gray-400']"></span>
                      {{ carousel.is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                      <button @click="editCarousel(carousel)" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-all text-xs font-medium" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                      </button>
                      <button @click="confirmDelete(carousel)" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-all text-xs font-medium" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </template>
            </draggable>
            <tbody v-if="!localCarousels || localCarousels.length === 0">
              <tr>
                <td colspan="9">
                  <div class="py-16 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-lg font-medium text-gray-500 mb-1">Belum ada banner</p>
                    <p class="text-sm text-gray-400">Klik "Tambah Banner" untuk membuat baru.</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal (Create/Edit) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.esc="closeModal">
      <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10 max-h-[90vh] overflow-y-auto animate-modal-in">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-lg font-bold text-gray-900">{{ editingCarousel ? 'Edit Banner' : 'Tambah Banner' }}</h3>
          <button @click="closeModal" class="p-1.5 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Banner <span class="text-red-500">*</span></label>
              <input v-model="form.title" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition" placeholder="contoh: Promo Kemerdekaan" required>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Gambar Banner</label>
              <div class="flex items-center gap-4">
                <div v-if="imagePreview" class="w-20 h-14 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0">
                  <img :src="imagePreview" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                  <label class="flex items-center justify-center gap-2 w-full px-4 py-3 border border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 transition bg-white">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-sm text-gray-600">{{ imagePreview ? 'Ganti gambar' : 'Pilih gambar' }}</span>
                    <input type="file" @change="handleFile" accept="image/*" class="hidden">
                  </label>
                  <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 5MB.</p>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi (opsional)</label>
              <textarea v-model="form.description" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition resize-none" placeholder="Deskripsi singkat banner"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">URL Tujuan / Link CTA (opsional)</label>
              <input v-model="form.link_url" type="url" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition" placeholder="https://...">
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Tampil (detik)</label>
                <input v-model="form.duration" type="number" min="1" max="30" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition">
                <p class="text-xs text-gray-400 mt-1">Berapa detik banner ini ditampilkan (1-30s)</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Aktif</label>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mt-1">
                  <label for="isActive" class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="form.is_active" id="isActive" class="sr-only peer">
                    <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                  </label>
                  <label for="isActive" class="text-sm font-medium text-gray-700 cursor-pointer">{{ form.is_active ? 'Aktif tampil' : 'Disembunyikan' }}</label>
                </div>
              </div>
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
        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Banner</h3>
        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus banner <strong class="text-gray-700">{{ deletingCarousel?.title }}</strong>?</p>
        <div class="flex justify-center gap-3">
          <button @click="showDeleteModal = false" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
          <button @click="deleteCarousel" :disabled="form.processing" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition disabled:opacity-50 flex items-center gap-2 shadow-lg shadow-red-200">
            {{ form.processing ? 'Menghapus...' : 'Ya, Hapus' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { getImageUrl } from '@/helpers.js';
import draggable from 'vuedraggable'

const props = defineProps({
    carousels: { type: Array, default: () => [] }
});

const localCarousels = ref([...props.carousels]);
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingCarousel = ref(null);
const deletingCarousel = ref(null);
const imagePreview = ref(null);

const form = useForm({
    title: '',
    image: null,
    description: '',
    link_url: '',
    order: 0,
    duration: 5,
    is_active: true
});

const openCreateModal = () => {
    editingCarousel.value = null;
    imagePreview.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const editCarousel = (carousel) => {
    editingCarousel.value = carousel;
    form.title = carousel.title;
    form.description = carousel.description || '';
    form.link_url = carousel.link_url || '';
    form.order = carousel.order;
    form.duration = carousel.duration || 5;
    form.is_active = carousel.is_active;
    imagePreview.value = getImageUrl(carousel.image_url);
    form.clearErrors();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    showDeleteModal.value = false;
    editingCarousel.value = null;
    deletingCarousel.value = null;
    imagePreview.value = null;
    form.reset();
    form.clearErrors();
};

const handleFile = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
    e.target.value = '';
};

const submitForm = () => {
    if (editingCarousel.value) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.cms.carousels.update', editingCarousel.value.id), {
            forceFormData: true,
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('admin.cms.carousels.store'), {
            onSuccess: () => closeModal()
        });
    }
};

const confirmDelete = (carousel) => {
    deletingCarousel.value = carousel;
    showDeleteModal.value = true;
};

const deleteCarousel = () => {
    form.delete(route('admin.cms.carousels.destroy', deletingCarousel.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            deletingCarousel.value = null;
        }
    });
};

const onDragEnd = () => {
    const updates = localCarousels.value.map((item, index) => ({
        id: item.id,
        order: index + 1
    }));
    router.post('/admin/cms/carousels/reorder', { items: updates }, {
        preserveScroll: true,
        preserveState: true,
    });
};
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