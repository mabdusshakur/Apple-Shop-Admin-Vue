<script setup>
import { watch, ref, defineProps, defineEmits } from 'vue';

const product_detail_id = ref(null);

const props = defineProps({
    showDeleteModal: {
        type: Boolean,
        default: false
    },
    selectedProductId: {
        type: String,
        default: null
    },
    selectedProductDetailId: {
        type: [Number, String, null],
        default: null
    }
});

const emit = defineEmits({
    closeDeleteModal: null,
    getList: null,
});

function closeDeleteModal() {
    emit('closeDeleteModal');
}

async function getListEvent() {
    emit('getList');
}

async function itemDelete() {
    showLoader();
    try {

        let detail_id = product_detail_id.value === null ? null : product_detail_id.value;

        if (!isNaN(detail_id)) {
            await axios.delete(`/api/admin/product-details/${detail_id}`)
        }

        let res = await axios.delete(`/api/admin/products/${props.selectedProductId}`).catch(err => {
            hideLoader();
            errorToast("Invoice exist for this product!")
        })

        hideLoader();

        if (res.data['success'] === true) {
            closeDeleteModal();
            successToast("Request completed")
            await getListEvent();
        } else {
            closeDeleteModal();
            errorToast("Request fail!")
        }
    } catch {
        closeDeleteModal();
        hideLoader();
        errorToast("Request fail!");
    }
}

watch(() => props.showDeleteModal, (newVar) => {
    if (newVar) {
        product_detail_id.value = props.selectedProductDetailId;
    }
})

</script>

<template>
    <div v-if="showDeleteModal" class="modal animated zoomIn" id="delete-modal" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3 class="text-warning mt-3">Delete !</h3>
                    <p class="mb-3">Once delete, you can't get it back.</p>
                    <input class="d-none" id="deleteProductId" />
                    <input class="d-none" id="deleteDetailId" />
                </div>
                <div class="modal-footer justify-content-end">
                    <div>
                        <button class="btn bg-gradient-success mx-2" id="delete-modal-close" data-bs-dismiss="modal"
                            type="button" v-on:click="closeDeleteModal">Cancel</button>
                        <button class="btn bg-gradient-danger" id="confirmDelete" type="button"
                            v-on:click="itemDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>