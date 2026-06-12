<template>
  <Head title="Manage Carousels" />
  <AdminLayout>
    <div class="p-6">
      <div class="mb-6 flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Carousels</h2>
          <p class="text-gray-600 text-sm mt-1">Kelola banner gambar di homepage</p>
        </div>
        <button @click="showCreateModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
          Tambah Banner
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="carousel in carousels" :key="carousel.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
          <div class="h-48 relative overflow-hidden bg-gray-100">
            <img :src="getImageUrl(carousel.image_url)" :alt="carousel.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            <div class="absolute top-2 right-2">
              <span v-if="carousel.is_active" class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-green-200">Aktif</span>
              <span v-else class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-gray-200">Nonaktif</span>
            </div>
            <div class="absolute top-2 left-2">
              <span class="bg-black/50 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded">Urutan: {{ carousel.order }}</span>
            </div>
          </div>
          <div class="p-4">
            <h3 class="font-bold text-gray-900 mb-1 line-clamp-1">{{ carousel.title }}</h3>
            <p v-if="carousel.description" class="text-sm text-gray-500 line-clamp-2 mb-3">{{ carousel.description }}</p>
            <div v-if="carousel.link_url" class="text-xs text-blue-600 mb-3 truncate flex items-center">
              <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
              {{ carousel.link_url }}
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
              <button @click="editCarousel(carousel)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded transition">Edit</button>
              <button @click="deleteCarousel(carousel)" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded transition">Hapus</button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="carousels.length === 0" class="col-span-full py-12 text-center bg-white rounded-xl border border-gray-100 border-dashed">
          <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
          <p class="text-gray-500 font-medium">Belum ada banner tersimpan</p>
        </div>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showCreateModal || editingCarousel" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">{{ editingCarousel ? 'Edit Banner' : 'Tambah Banner Baru' }}</h3>

            <form @submit.prevent="submitForm">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Judul Banner <span class="text-red-500">*</span></label>
                  <input v-model="form.title" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="contoh: Promo Kemerdekaan" required>
                  <p v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Banner {{ editingCarousel ? '' : '<span class="text-red-500">*</span>' }}</label>
                  <div class="flex items-center gap-4">
                    <div v-if="imagePreview" class="w-24 h-24 rounded-lg overflow-hidden border border-gray-200">
                      <img :src="imagePreview" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                      <input type="file" @input="form.image = $event.target.files[0]" @change="handleImageUpload" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" :required="!editingCarousel">
                      <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 5MB. Rekomendasi rasio 16:9.</p>
                      <p v-if="form.errors.image" class="text-red-500 text-xs mt-1">{{ form.errors.image }}</p>
                    </div>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                  <textarea v-model="form.description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                  <p v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">URL Tujuan / Link CTA (Opsional)</label>
                  <input v-model="form.link_url" type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="https://...">
                  <p v-if="form.errors.link_url" class="text-red-500 text-xs mt-1">{{ form.errors.link_url }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan (Order)</label>
                    <input v-model="form.order" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Makin kecil makin awal tampil (0, 1, 2...)</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Aktif</label>
                    <div class="flex items-center h-10">
                      <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">{{ form.is_active ? 'Aktif tampil' : 'Disembunyikan' }}</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="closeModal" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  Batal
                </button>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                  {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    carousels: {
        type: Array,
        required: true
    }
});

const showCreateModal = ref(false);
const editingCarousel = ref(null);
const imagePreview = ref(null);

const form = useForm({
    _method: 'POST',
    title: '',
    image: null,
    description: '',
    link_url: '',
    order: 0,
    is_active: true
});

const getImageUrl = (url) => {
    if (!url) return '';
    return url.startsWith('http') ? url : `/storage/${url}`;
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        imagePreview.value = URL.createObjectURL(file);
    }
};

const editCarousel = (carousel) => {
    editingCarousel.value = carousel;
    form.title = carousel.title;
    form.description = carousel.description || '';
    form.link_url = carousel.link_url || '';
    form.order = carousel.order;
    form.is_active = carousel.is_active;
    form._method = 'PUT';

    imagePreview.value = getImageUrl(carousel.image_url);
    showCreateModal.value = true;
};

const deleteCarousel = (carousel) => {
    if (confirm(`Apakah Anda yakin ingin menghapus banner "${carousel.title}"?`)) {
        router.delete(route('admin.cms.carousels.destroy', carousel.id));
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    editingCarousel.value = null;
    imagePreview.value = null;
    form.reset();
    form.clearErrors();
    form._method = 'POST';
};

const submitForm = () => {
    if (editingCarousel.value) {
        form.post(route('admin.cms.carousels.update', editingCarousel.value.id), {
            onSuccess: () => closeModal(),
            forceFormData: true // Required for file upload with PUT method in Laravel
        });
    } else {
        form.post(route('admin.cms.carousels.store'), {
            onSuccess: () => closeModal(),
        });
    }
};
</script>