<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    showDeleteModal: {
        type: Boolean,
        default: false
    },
    selectedBrandId: {
        type: Number,
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
        let res = await axios.delete(`/api/admin/brands/${props.selectedBrandId}`);
        if (res.data['success'] === true) {
            successToast("Request completed");
            await getListEvent();
            closeDeleteModal();
            hideLoader();
        } else {
            hideLoader();
            errorToast("Request fail!");
        }
    } catch {
        closeDeleteModal();
        hideLoader();
        errorToast("Request fail!");
    }
}
</script>

<template>
    <div v-if="showDeleteModal" class="modal animated zoomIn" id="delete-modal" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3 class="text-warning mt-3">Delete ! {{ selectedBrandId }}</h3>
                    <p class="mb-3">Once delete, you can't get it back.</p>
                    <input class="d-none" id="deleteID" />
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