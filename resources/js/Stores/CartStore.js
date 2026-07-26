import { reactive, computed } from 'vue'
import axios from 'axios'

const state = reactive({
    items: [],
    isOpen: false,
})

export function useCartStore() {
    const totalPrice = computed(() => state.items.reduce((sum, item) => sum + (item.price * item.qty), 0))
    const dpPrice = computed(() => Math.round(totalPrice.value * 0.7))
    const count = computed(() => state.items.length)

    function addToCart(product, type = 'satuan') {
        const incomingQty = product.qty || 1

        // Send to backend API
        axios.post('/cart/add', {
            product_id: product.id,
            quantity: incomingQty,
            type: type,
        }, {
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => {
            const data = response.data;
            console.log('Add to cart response:', data);
            if (data.success) {
                // Update local store for UI feedback
                const existing = state.items.find(item => item.id === product.id && item.type === type)
                if (existing) {
                    existing.qty += incomingQty
                } else {
                    state.items.push({
                        id: product.id,
                        name: product.name,
                        price: product.sell_price || product.price,
                        image: product.image_url || product.image || null,
                        qty: incomingQty,
                        type,
                    })
                }
                state.isOpen = true
            }
        })
        .catch(error => console.error('Error adding to cart:', error))
    }

    function removeItem(productId) {
        state.items = state.items.filter(item => item.id !== productId)
        axios.post('/cart/remove', { product_id: productId }, {
            headers: { 'Accept': 'application/json' }
        }).catch(e => console.error(e))
    }

    function updateQty(productId, newQty) {
        if (newQty <= 0) { removeItem(productId); return }
        const item = state.items.find(i => i.id === productId)
        if (item) {
            item.qty = newQty
            axios.post('/cart/update', { product_id: productId, quantity: newQty }, {
                headers: { 'Accept': 'application/json' }
            }).catch(e => console.error(e))
        }
    }

    function clearCart() {
        state.items = []
        axios.post('/cart/clear', {}, {
            headers: { 'Accept': 'application/json' }
        }).catch(e => console.error(e))
    }

    function openCart() { state.isOpen = true }
    function closeCart() { state.isOpen = false }
    function toggleCart() { state.isOpen = !state.isOpen }

    return {
        state,
        items: computed(() => state.items),
        totalPrice,
        dpPrice,
        count,
        addToCart,
        removeItem,
        updateQty,
        clearCart,
        openCart,
        closeCart,
        toggleCart,
    }
}
