<template>
  <Head title="Manage Social Media" />
  <AdminLayout>
    <div class="p-6">
      <div class="mb-6 flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Social Media</h2>
          <p class="text-gray-600 text-sm mt-1">Kelola tautan sosial media di footer</p>
        </div>
        <button @click="showCreateModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
          <Plus class="w-4 h-4" />
          Tambah Sosial Media
        </button>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16 text-center">Urutan</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Platform</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">URL Link</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="social in socials" :key="social.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-center">
                  <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-600 font-bold text-xs">{{ social.order }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                      {{ social.icon_name || social.platform.charAt(0) }}
                    </div>
                    <span class="font-bold text-gray-800">{{ social.platform }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <a :href="social.url" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                    {{ social.url }}
                    <ExternalLink class="w-3.5 h-3.5" />
                  </a>
                </td>
                <td class="px-6 py-4 text-center">
                  <span v-if="social.is_active" class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-green-200">Aktif</span>
                  <span v-else class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-gray-200">Nonaktif</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <button @click="editSocial(social)" class="text-blue-600 hover:text-blue-900 font-medium text-sm transition mr-3">Edit</button>
                  <button @click="deleteSocial(social)" class="text-red-600 hover:text-red-900 font-medium text-sm transition">Hapus</button>
                </td>
              </tr>
              <tr v-if="socials.length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada sosial media ditemukan.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showCreateModal || editingSocial" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">{{ editingSocial ? 'Edit Sosial Media' : 'Tambah Sosial Media' }}</h3>

            <form @submit.prevent="submitForm">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Platform <span class="text-red-500">*</span></label>
                  <input v-model="form.platform" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="contoh: Instagram" required>
                  <p v-if="form.errors.platform" class="text-red-500 text-xs mt-1">{{ form.errors.platform }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">URL Profil <span class="text-red-500">*</span></label>
                  <input v-model="form.url" type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="https://instagram.com/..." required>
                  <p v-if="form.errors.url" class="text-red-500 text-xs mt-1">{{ form.errors.url }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Icon Text (1-2 Huruf)</label>
                  <input v-model="form.icon_name" type="text" maxlength="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="IG">
                  <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan huruf pertama platform otomatis.</p>
                  <p v-if="form.errors.icon_name" class="text-red-500 text-xs mt-1">{{ form.errors.icon_name }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tampil</label>
                    <input v-model="form.order" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Aktif</label>
                    <div class="flex items-center h-10">
                      <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">{{ form.is_active ? 'Aktif' : 'Nonaktif' }}</span>
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
import { Plus, ExternalLink } from 'lucide-vue-next';

const props = defineProps({
    socials: {
        type: Array,
        required: true
    }
});

const showCreateModal = ref(false);
const editingSocial = ref(null);

const form = useForm({
    platform: '',
    url: '',
    icon_name: '',
    order: 0,
    is_active: true
});

const editSocial = (social) => {
    editingSocial.value = social;
    form.platform = social.platform;
    form.url = social.url;
    form.icon_name = social.icon_name || '';
    form.order = social.order;
    form.is_active = social.is_active;
    showCreateModal.value = true;
};

const deleteSocial = (social) => {
    if (confirm(`Apakah Anda yakin ingin menghapus social media ${social.platform}?`)) {
        router.delete(route('admin.cms.social-media.destroy', social.id));
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    editingSocial.value = null;
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    if (editingSocial.value) {
        form.put(route('admin.cms.social-media.update', editingSocial.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.cms.social-media.store'), {
            onSuccess: () => closeModal(),
        });
    }
};
</script>