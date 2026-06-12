<template>
  <div class="flex flex-col min-h-screen bg-gray-50">
    <!-- Sticky Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <Link href="/" class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">SB</div>
            <span class="text-xl font-bold text-gray-900">Snack Box</span>
          </Link>
          <div class="hidden md:flex items-center space-x-8">
            <Link href="/" class="text-gray-700 hover:text-blue-600 transition font-medium">Beranda</Link>
            <Link href="/shop" class="text-gray-700 hover:text-blue-600 transition font-medium">Shop</Link>
          </div>
          <div class="flex items-center space-x-4">
            <button @click="toggleCart" class="relative text-gray-700 hover:text-blue-600 transition p-2">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              <span v-if="count > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ count }}</span>
            </button>
            <Link v-if="isAuthenticated" :href="route('admin.dashboard')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm">Dashboard</Link>
          </div>
        </div>
      </div>
    </nav>

    <main class="flex-1">
      <slot />
    </main>

    <!-- Cart Drawer (always mounted, just hidden) -->
    <Teleport to="body">
      <div v-if="state.isOpen" class="fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="toggleCart"></div>
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col">
          <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-bold">Keranjang Pesanan</h2>
            <button @click="toggleCart" class="text-white hover:text-gray-200 transition p-1">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>

          <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            <div v-if="items.length === 0" class="text-center py-16">
              <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              <p class="text-gray-500 font-medium">Belum ada produk dipilih</p>
              <p class="text-gray-400 text-sm mt-2">Pilih produk dari halaman utama</p>
            </div>

            <div v-for="item in items" :key="item.id" class="bg-gray-50 rounded-xl p-4 flex gap-3">
              <!-- Image -->
              <div class="w-16 h-16 rounded-lg bg-gray-200 flex-shrink-0 overflow-hidden">
                <svg class="w-full h-full text-gray-300 p-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <!-- Info -->
              <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start">
                  <p class="font-semibold text-gray-900 text-sm line-clamp-1">{{ item.name }}</p>
                  <button @click="removeItem(item.id)" class="text-red-500 hover:text-red-700 transition p-0.5 flex-shrink-0 ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                  </button>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">{{ item.type === 'snack_box' ? 'Snack Box' : 'Kue Satuan' }}</p>
                <div class="flex items-center justify-between mt-2">
                  <div class="flex items-center bg-white rounded-lg border border-gray-200">
                    <button @click="updateQty(item.id, item.qty - 1)" class="px-2.5 py-1 hover:bg-gray-100 transition rounded-l-lg">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                    </button>
                    <span class="px-3 py-1 font-semibold text-sm min-w-[28px] text-center">{{ item.qty }}</span>
                    <button @click="updateQty(item.id, item.qty + 1)" class="px-2.5 py-1 hover:bg-gray-100 transition rounded-r-lg">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </button>
                  </div>
                  <p class="font-bold text-blue-600 text-sm">Rp {{ formatNumber(item.price * item.qty) }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div v-if="items.length > 0" class="border-t border-gray-200 px-6 py-4 space-y-3">
            <p class="font-semibold text-gray-700 text-sm">Data Pemesan</p>
            <input v-model="customerName" type="text" placeholder="Nama lengkap" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition text-sm" />
            <input v-model="customerPhone" type="tel" placeholder="No. WhatsApp (contoh: 08123456789)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition text-sm" />
            <input v-model="pickupDate" type="datetime-local" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition text-sm" />
            <textarea v-model="customerLocation" rows="2" placeholder="Lokasi / Alamat pengambilan (contoh: Jl. Raya No. 123, Jakarta)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition text-sm resize-none"></textarea>
            <textarea v-model="notes" rows="2" placeholder="Catatan (opsional)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition text-sm resize-none"></textarea>
            <div class="pt-1 space-y-2">
              <div class="flex justify-between text-sm"><span class="text-gray-600">Total</span><span class="font-bold text-gray-900">Rp {{ formatNumber(totalPrice) }}</span></div>
              <div class="flex justify-between text-sm"><span class="text-orange-600 font-semibold">DP ({{ dpPercentage }}%)</span><span class="font-bold text-orange-600">Rp {{ formatNumber(dpPrice) }}</span></div>
              <div class="bg-orange-50 rounded-lg p-3 text-xs text-orange-800">Sisa bayar <strong>Rp {{ formatNumber(totalPrice - dpPrice) }}</strong> dibayar saat pengambilan</div>
            </div>
            <button @click="sendToWhatsApp" class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition font-bold flex items-center justify-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" />
              </svg>
              Kirim Pesanan ke WhatsApp
            </button>
            <button @click="clearCart" class="w-full text-center text-gray-500 hover:text-gray-700 transition text-sm py-1">Kosongkan Keranjang</button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useCartStore } from '@/Stores/CartStore.js'

const page = usePage()
const isAuthenticated = ref(!!page.props.auth?.user)
const cms = computed(() => page.props.cms?.settings || {})
const { state, items, totalPrice, count, addToCart, removeItem, updateQty, clearCart, toggleCart } = useCartStore()

// Dynamic DP & Minimum Order from CMS
const dpPercentage = computed(() => parseInt(cms.value.dp_percentage) || 70)
const dpPrice = computed(() => Math.round(totalPrice.value * (dpPercentage.value / 100)))

const customerName = ref('')
const customerPhone = ref('')
const pickupDate = ref('')
const customerLocation = ref('')
const notes = ref('')

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num)

const sendToWhatsApp = () => {
  if (items.length === 0) { alert('Pilih produk dulu!'); return }
  if (!customerName.value.trim()) { alert('Silakan masukkan nama lengkap'); return }
  if (!customerPhone.value.trim()) { alert('Silakan masukkan nomor WhatsApp'); return }
  if (!pickupDate.value) { alert('Silakan pilih tanggal pengambilan'); return }
  if (!customerLocation.value.trim()) { alert('Silakan masukkan lokasi/alamat pengambilan'); return }

  const now = new Date()
  const d = String(now.getFullYear()) + String(now.getMonth()+1).padStart(2,'0') + String(now.getDate()).padStart(2,'0')
  const r = String(Math.floor(Math.random()*10000)).padStart(4,'0')
  const orderNumber = `ORD-${d}-${r}`
  const dt = new Date(pickupDate.value)
  const tanggal = dt.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })

  // Build dynamic order list
  const itemsArray = items.value || []
  const boxItems = itemsArray.filter(i => i.type === 'snack_box')
  const singleItems = itemsArray.filter(i => i.type === 'satuan')

  let orderList = ''
  if (boxItems.length > 0) {
    orderList += `[1] Pesanan Snack Box\n\n`
    boxItems.forEach(item => {
      orderList += `- ${item.qty}x ${item.name}\n`
      orderList += `  Subtotal: Rp ${formatNumber(item.price * item.qty)}\n`
    })
  }
  if (singleItems.length > 0) {
    orderList += `[2] Pesanan Kue Satuan\n\n`
    singleItems.forEach(item => {
      orderList += `- ${item.qty}x ${item.name}\n`
      orderList += `  Subtotal: Rp ${formatNumber(item.price * item.qty)}\n`
    })
  }
  orderList = orderList.trim()

  // Prepare template variables
  const templateVars = {
    '{company_name}': cms.value.company_name || 'Snack Box',
    '{order_number}': orderNumber,
    '{customer_name}': customerName.value.trim(),
    '{customer_phone}': customerPhone.value.trim(),
    '{pickup_date}': tanggal,
    '{customer_location}': customerLocation.value.trim(),
    '{notes}': notes.value || '-',
    '{order_list}': orderList,
    '{total_amount}': formatNumber(totalPrice.value),
    '{dp_percentage}': dpPercentage.value,
    '{dp_amount}': formatNumber(dpPrice.value),
    '{remaining_amount}': formatNumber(totalPrice.value - dpPrice.value),
  }

  // Use template from CMS, or fallback to default format
  const rawTemplate = cms.value.wa_checkout_template || ''
  let msg = rawTemplate

  // If no template in CMS, use default hardcoded format
  if (!rawTemplate.trim()) {
    msg = `INVOICE PESANAN - ${templateVars['{company_name}']}\n`
    msg += `No Pesanan: ${orderNumber}\n`
    msg += `Nama: ${customerName.value.trim()}\n`
    msg += `WhatsApp: ${customerPhone.value.trim()}\n`
    msg += `Pengambilan: ${tanggal}\n`
    msg += `Lokasi: ${customerLocation.value.trim()}\n`
    msg += `Catatan: ${notes.value || '-'}\n\n`
    msg += `${orderList}\n\n`
    msg += `TOTAL TAGIHAN: Rp ${formatNumber(totalPrice.value)}\n`
    msg += `DP (${dpPercentage.value}%): Rp ${formatNumber(dpPrice.value)}\n`
    msg += `Sisa bayar: Rp ${formatNumber(totalPrice.value - dpPrice.value)} (dibayar saat pengambilan)\n`
  } else {
    // Replace all placeholders with actual values
    Object.entries(templateVars).forEach(([key, value]) => {
      msg = msg.replaceAll(key, value)
    })
  }

  window.open(`https://wa.me/${cms.value.contact_phone}?text=${encodeURIComponent(msg)}`, '_blank')

  window.open(`https://wa.me/6285155337991?text=${encodeURIComponent(msg)}`, '_blank')
  toggleCart()
}
</script>
