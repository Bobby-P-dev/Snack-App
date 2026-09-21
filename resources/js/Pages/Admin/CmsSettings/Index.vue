<template>
  <Head title="Web Settings" />
  <AdminLayout>
    <div class="p-6">
      <div class="mb-6 flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Web Settings</h2>
          <p class="text-gray-600 text-sm mt-1">Kelola konten teks dan pengaturan website</p>
        </div>
        <button @click="showCreateModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
          <Plus class="w-4 h-4" />
          Tambah Setting Baru
        </button>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Key</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Value</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="setting in settings" :key="setting.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <span class="inline-block bg-gray-100 text-gray-800 font-mono text-xs px-2 py-1 rounded">{{ setting.key }}</span>
                  <div class="text-xs text-gray-400 mt-1">Type: {{ setting.type }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="max-w-md truncate text-sm text-gray-800 font-medium" :title="setting.value">{{ setting.value }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ setting.description || '-' }}</td>
                <td class="px-6 py-4 text-right">
                  <button @click="editSetting(setting)" class="text-blue-600 hover:text-blue-900 font-medium text-sm transition mr-3">Edit</button>
                </td>
              </tr>
              <tr v-if="settings.length === 0">
                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada setting ditemukan.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showCreateModal || editingSetting" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">{{ editingSetting ? 'Edit Setting' : 'Tambah Setting Baru' }}</h3>

            <form @submit.prevent="submitForm">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Key <span class="text-red-500">*</span></label>
                  <input v-model="form.key" type="text" :disabled="editingSetting" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500" placeholder="contoh: company_name" required>
                  <p v-if="form.errors.key" class="text-red-500 text-xs mt-1">{{ form.errors.key }}</p>
                  <p v-if="editingSetting" class="text-gray-400 text-xs mt-1">Key tidak dapat diubah setelah dibuat.</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                  <select v-model="form.type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="text">Text (Singkat)</option>
                    <option value="textarea">Textarea (Panjang)</option>
                    <option value="json">JSON</option>
                    <option value="email">Email</option>
                    <option value="phone">Phone</option>
                    <option value="url">URL</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Value <span class="text-red-500">*</span></label>
                  <textarea v-if="['textarea', 'json'].includes(form.type)" v-model="form.value" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 font-mono text-sm" required></textarea>
                  <input v-else v-model="form.value" :type="form.type === 'email' ? 'email' : (form.type === 'url' ? 'url' : 'text')" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                  <p v-if="form.errors.value" class="text-red-500 text-xs mt-1">{{ form.errors.value }}</p>

                  <!-- Dynamic WhatsApp Variables Helper -->
                  <div v-if="getAvailableTags(form.key).length > 0" class="mt-2.5 p-3 bg-amber-50/70 rounded-xl border border-amber-200/80">
                    <p class="text-xs font-bold text-amber-900 mb-1.5 flex items-center gap-1.5">
                      <span>💡 Variabel Dinamis (Klik untuk menyisipkan ke teks):</span>
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                      <button
                        v-for="item in getAvailableTags(form.key)"
                        :key="item.tag"
                        type="button"
                        @click="insertTag(item.tag)"
                        class="px-2 py-1 bg-white hover:bg-amber-100 text-amber-900 border border-amber-200 rounded text-xs font-mono transition-colors shadow-2xs cursor-pointer"
                        :title="item.desc"
                      >
                        {{ item.tag }}
                      </button>
                    </div>
                    <p class="text-[11px] text-amber-800/80 mt-2 leading-relaxed">
                      Sistem akan secara otomatis mengganti variabel di atas dengan data riil saat pesan digenerate.
                    </p>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                  <input v-model="form.description" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan fungsi setting ini">
                  <p v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</p>
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
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus } from 'lucide-vue-next';

const props = defineProps({
    settings: {
        type: Array,
        required: true
    }
});

const showCreateModal = ref(false);
const editingSetting = ref(null);

const form = useForm({
    key: '',
    value: '',
    type: 'text',
    description: ''
});

const getAvailableTags = (key) => {
    if (!key) return [];
    if (key === 'wa_checkout_template') {
        return [
            { tag: '{company_name}', desc: 'Nama brand / toko' },
            { tag: '{order_number}', desc: 'No pesanan unik (PK-XXXXXXXX)' },
            { tag: '{customer_name}', desc: 'Nama customer' },
            { tag: '{customer_phone}', desc: 'No WhatsApp customer' },
            { tag: '{pickup_date}', desc: 'Jadwal pengambilan' },
            { tag: '{customer_location}', desc: 'Lokasi customer' },
            { tag: '{notes}', desc: 'Catatan tambahan' },
            { tag: '{order_list}', desc: 'Rincian produk & custom box' },
            { tag: '{total_amount}', desc: 'Total tagihan (Rp)' },
            { tag: '{dp_percentage}', desc: 'Persentase DP (%)' },
            { tag: '{dp_amount}', desc: 'Nominal DP (Rp)' },
            { tag: '{remaining_amount}', desc: 'Sisa pelunasan (Rp)' },
            { tag: '{tracking_url}', desc: 'Link langsung tracking status pesanan' },
            { tag: '{invoice_url}', desc: 'Link resmi unduh PDF invoice' },
        ];
    }
    if (key === 'wa_tracking_help_message') {
        return [
            { tag: '{company_name}', desc: 'Nama brand / toko' },
            { tag: '{order_number}', desc: 'No pesanan (PK-XXXXXXXX)' },
        ];
    }
    if (key === 'wa_consultation_message' || key === 'wa_tracking_not_found_message') {
        return [
            { tag: '{company_name}', desc: 'Nama brand / toko' },
        ];
    }
    return [];
};

const insertTag = (tag) => {
    if (!form.value) {
        form.value = tag;
    } else {
        form.value += ' ' + tag;
    }
};

const editSetting = (setting) => {
    editingSetting.value = setting;
    form.key = setting.key;
    form.value = setting.value;
    form.type = setting.type || 'text';
    form.description = setting.description;

    // UI mapping for large text
    if (setting.type === 'text' && setting.value && setting.value.length > 100) {
        form.type = 'textarea';
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    editingSetting.value = null;
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    if (editingSetting.value) {
        form.put(route('admin.cms.settings.update', editingSetting.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.cms.settings.store'), {
            onSuccess: () => closeModal(),
        });
    }
};
</script>