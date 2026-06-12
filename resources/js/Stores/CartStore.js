import { reactive, computed } from 'vue'

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
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({
                product_id: product.id,
                quantity: incomingQty,
                type: type,
            }),
        })
        .then(response => response.json())
        .then(data => {
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
                        image: product.image_url || null,
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
    }

    function updateQty(productId, newQty) {
        if (newQty <= 0) { removeItem(productId); return }
        const item = state.items.find(i => i.id === productId)
        if (item) item.qty = newQty
    }

    function clearCart() {
        state.items = []
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
