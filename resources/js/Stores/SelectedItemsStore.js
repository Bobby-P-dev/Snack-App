import { reactive, computed } from 'vue'

// Shared state — persists across page navigation within the same session
const state = reactive({
    items: [],
})

export function useSelectedItemsStore() {
    const count = computed(() => state.items.length)
    const items = computed(() => state.items)

    function addItem(product, qty, type = 'box') {
        const existing = state.items.find(i => i.id === product.id)
        if (existing) {
            existing.qty += qty
        } else {
            state.items.push({
                id: product.id,
                name: product.name,
                price: product.sell_price || product.price,
                qty,
                type,
                image: product.image_url || null,
            })
        }
    }

    function removeItem(productId) {
        state.items = state.items.filter(i => i.id !== productId)
    }

    function clearItems() {
        state.items = []
    }

    return {
        items,
        count,
        addItem,
        removeItem,
        clearItems,
    }
}
