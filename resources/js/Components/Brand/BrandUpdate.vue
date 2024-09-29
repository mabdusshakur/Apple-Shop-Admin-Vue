<script setup>
import { watch, ref, defineProps, defineEmits, reactive } from 'vue';

const brandImg = ref(null);

const brand = reactive({
    name: '',
    image: null
});

const props = defineProps({
    showUpdateModal: {
        type: Boolean,
        default: false
    },
    selectedBrandId: {
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
    let res = await axios.get(`/api/admin/brands/${props.selectedBrandId}`);
    res = res.data[0];
    brand.name = res['name'];
    brandImg.value = res['image'];
    hideLoader();
}

function handleImage(e) {
    brand.image = e.target.files[0];
    brandImg.value = URL.createObjectURL(brand.image);
}


async function update() {
    let brandName = brand.name;
    let brandImg = brand.image;

    if (brandName.length === 0) {
        errorToast("brand Required !")
    } else {
        let formData = new FormData();
        formData.append('name', brandName)
        if (brandImg) {
            formData.append('image', brandImg)
        }

        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }

        showLoader();
        let res = await axios.post(`/api/admin/brands/${props.selectedBrandId}`, formData, config)
        console.log(res);
        hideLoader();

        if (res.status === 200 && res.data['success'] === true) {
            successToast(res.data['message']);
            await getListEvent();
            closeUpdateModal();
            document.getElementById("update-form").reset();
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
                    <h6 class="modal-title" id="exampleModalLabel">Update Brand</h6>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Brand Name *</label>
                                    <input class="form-control" id="brandNameUpdate" type="text" v-model="brand.name">
                                    <br />
                                    <img class="w-15" id="oldImg" :src="brandImg" />
                                    <br />
                                    <label class="form-label">Image</label>
                                    <input class="form-control" id="brandImgUpdate" type="file" @change="handleImage">
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