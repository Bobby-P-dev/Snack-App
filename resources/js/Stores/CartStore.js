import { reactive, computed } from 'vue'
import axios from 'axios'

const state = reactive({
    items: [],
    isOpen: false,
    paymentType: 'dp', // 'dp' | 'full'
})

export function useCartStore() {
    const totalPrice = computed(() => state.items.reduce((sum, item) => sum + (item.price * item.qty), 0))
    const dpPrice = computed(() => Math.round(totalPrice.value * 0.5))
    const count = computed(() => state.items.length)

    function initFromSession(sessionItems) {
        if (Array.isArray(sessionItems) && sessionItems.length > 0) {
            state.items = sessionItems.map(item => ({
                id: item.product_id || item.id,
                name: item.product?.name || item.name,
                price: item.product?.sell_price || item.price,
                image: item.product?.image_url || item.image || null,
                qty: item.quantity || item.qty || 1,
                type: item.type || 'satuan',
                box_group_id: item.box_group_id || null,
            }))
        }
    }

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

    function removeItem(productId, type = null, boxGroupId = null) {
        if (type === 'kustom_box' && boxGroupId) {
            removeBoxGroup(boxGroupId);
            return;
        }
        state.items = state.items.filter(item => {
            if (item.id !== productId) return true;
            if (type && item.type !== type) return true;
            if (boxGroupId && item.box_group_id !== boxGroupId) return true;
            return false;
        });
        axios.post('/cart/remove', { 
            product_id: productId,
            type: type,
            box_group_id: boxGroupId,
        }, {
            headers: { 'Accept': 'application/json' }
        }).catch(e => console.error(e));
    }

    function removeBoxGroup(boxGroupId) {
        state.items = state.items.filter(item => item.box_group_id !== boxGroupId);
        axios.post('/cart/remove-box-group', { box_group_id: boxGroupId }, {
            headers: { 'Accept': 'application/json' }
        }).catch(e => console.error(e));
    }

    function updateBoxGroupQty(boxGroupId, newQty) {
        if (newQty <= 0) {
            removeBoxGroup(boxGroupId);
            return;
        }
        state.items.forEach(item => {
            if (item.box_group_id === boxGroupId) {
                item.qty = newQty;
            }
        });
        return axios.post('/cart/update-quantity', { 
            product_id: null, 
            quantity: newQty,
            type: 'kustom_box',
            box_group_id: boxGroupId,
        }, {
            headers: { 'Accept': 'application/json' }
        }).catch(e => console.error(e));
    }

    function updateQty(productId, newQty, type = null, boxGroupId = null) {
        if (type === 'kustom_box' && boxGroupId) {
            return updateBoxGroupQty(boxGroupId, newQty);
        }
        if (newQty <= 0) { removeItem(productId, type, boxGroupId); return; }
        const item = state.items.find(i => {
            if (i.id !== productId) return false;
            if (type && i.type !== type) return false;
            if (boxGroupId && i.box_group_id !== boxGroupId) return false;
            return true;
        });
        if (item) {
            item.qty = newQty;
            return axios.post('/cart/update-quantity', { 
                product_id: productId, 
                quantity: newQty,
                type: type,
                box_group_id: boxGroupId,
            }, {
                headers: { 'Accept': 'application/json' }
            }).catch(e => console.error(e));
        }
    }

    function clearCart() {
        state.items = []
        axios.post('/cart/clear', {}, {
            headers: { 'Accept': 'application/json' }
        }).catch(e => console.error(e))
    }

    async function fetchCart() {
        try {
            const response = await axios.get('/cart/items', {
                headers: { 'Accept': 'application/json' }
            });
            if (response.data && Array.isArray(response.data.items)) {
                state.items = response.data.items;
            }
        } catch (error) {
            console.error('Error fetching cart:', error);
        }
    }

    function setItems(newItems) {
        if (Array.isArray(newItems)) {
            state.items = newItems.map(item => ({
                id: item.product_id || item.id,
                name: item.product?.name || item.name,
                price: item.product?.sell_price || item.price,
                image: item.product?.image_url || item.image || null,
                qty: item.quantity || item.qty || 1,
                type: item.type || 'satuan',
                box_group_id: item.box_group_id || null,
            }));
        }
    }

    function openCart() { state.isOpen = true }
    function closeCart() { state.isOpen = false }
    function toggleCart() { state.isOpen = !state.isOpen }
    function setPaymentType(type) { state.paymentType = type === 'full' ? 'full' : 'dp' }

    return {
        state,
        items: computed(() => state.items),
        totalPrice,
        dpPrice,
        paymentType: computed(() => state.paymentType),
        setPaymentType,
        count,
        initFromSession,
        fetchCart,
        setItems,
        addToCart,
        removeItem,
        removeBoxGroup,
        updateQty,
        updateBoxGroupQty,
        clearCart,
        openCart,
        closeCart,
        toggleCart,
    }
}
