<script setup>
import { watch, ref, defineProps, defineEmits, onMounted, reactive } from 'vue';

const categoryImg = ref('');

const category = reactive({
    name: '',
    image: null
});

const props = defineProps({
    showUpdateModal: {
        type: Boolean,
        default: false
    },
    selectedCategoryId: {
        type: Number,
        default: null
    }
});

const emit = defineEmits({
    closeUpdateModal: null,
    getList: null,
});


async function getListEvent() {
    emit('getList');
}

function closeUpdateModal() {
    emit('closeUpdateModal');
}

async function fillUpUpdateForm() {
    showLoader();
    let res = await axios.get(`/api/admin/categories/${props.selectedCategoryId}`);
    res = res.data[0];
    category.name = res['name'];
    categoryImg.value = res['image'];
    hideLoader();
}

function handleImage(e) {
    category.image = e.target.files[0];
    categoryImg.value = URL.createObjectURL(category.image);
}

async function update() {
    let categoryName = category.name;
    let categoryImg = category.image;
    let id = document.getElementById('updateID').value;

    if (categoryName.length === 0) {
        errorToast("category Required !")
    } else {
        let formData = new FormData();
        formData.append('name', categoryName)
        if (categoryImg) {
            formData.append('image', categoryImg)
        }

        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }

        showLoader();
        let res = await axios.post(`/api/admin/categories/${props.selectedCategoryId}`, formData, config)
        console.log(res);
        hideLoader();

        if (res.status === 200 && res.data['success'] === true) {
            successToast(res.data['message']);
            await getListEvent();
            document.getElementById("update-form").reset();
            closeUpdateModal();
        } else {
            errorToast(res.data['message'])
        }
    }
}


// Watcher
watch(() => props.showUpdateModal, (newVar) => {
    if (newVar) {
        fillUpUpdateForm();
    }
});

</script>

<template>
    <div v-if="showUpdateModal" class="modal animated zoomIn" id="update-modal" aria-labelledby="exampleModalLabel"
        aria-hidden="true" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Update Category</h6>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Category Name *</label>
                                    <input class="form-control" id="categoryNameUpdate" type="text"
                                        v-model="category.name">
                                    <br />
                                    <img class="w-15" id="oldImg" :src="categoryImg ?? categoryImg" />
                                    <br />
                                    <label class="form-label">Image</label>
                                    <input class="form-control" id="categoryImgUpdate" type="file"
                                        @change="handleImage">
                                    <input class="d-none" id="updateID" type="text">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn bg-gradient-primary" id="modal-close" data-bs-dismiss="modal" aria-label="Close"
                        v-on:click="closeUpdateModal">Close</button>
                    <button class="btn bg-gradient-success" id="save-btn" v-on:click="update">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>