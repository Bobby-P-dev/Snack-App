import { reactive } from 'vue';

const state = reactive({
    isOpen: false,
    product: null,
});

export function useProductModalStore() {
    function openModal(product) {
        state.product = product;
        state.isOpen = true;
    }

    function closeModal() {
        state.isOpen = false;
        state.product = null;
    }

    return {
        state,
        openModal,
        closeModal,
    };
}
