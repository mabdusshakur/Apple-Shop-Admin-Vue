<script setup>
import { watch, ref, defineProps, defineEmits, reactive } from 'vue';

const categories = ref([]);
const brands = ref([]);
const productImageUrl = ref(null);

const product = reactive({
    title: '',
    category_id: '',
    brand_id: '',
    price: '',
    stock: '',
    short_des: '',
    image: null,
    remark: '',
    is_discount: false,
    discount_price: ''
});

const props = defineProps({
    showCreateModal: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits({
    closeCreateModal: null,
    getList: null,
});

function closeCreateModal() {
    emit('closeCreateModal');
}

async function getListEvent() {
    emit('getList');
}


async function fillUpCategoryDropDown() {
    let res = await axios.get("/api/admin/categories")
    categories.value = res.data[0];
}

async function fillUpBrandDropDown() {
    let res = await axios.get("/api/admin/brands")
    brands.value = res.data[0];
}

function handleImage(e) {
    product.image = e.target.files[0];
    productImageUrl.value = URL.createObjectURL(product.image);
}

async function Save() {
    let category_id = product.category_id;
    let brand_id = product.brand_id;
    let remark = product.remark;
    let title = product.title;
    let is_discount = product.is_discount
    let discount_price = product.discount_price;
    let price = product.price;
    let stock = product.stock;
    let short_des = product.short_des
    let image = product.image;

    if (category_id.length === 0) {
        errorToast("Product Category Required !");
    } else if (brand_id.length === 0) {
        errorToast("Product Brand Required !");
    } else if (remark.length === 0) {
        errorToast("Product Remark Required !");
    } else if (title.length === 0) {
        errorToast("Product Title Required !");
    } else if (price.length === 0) {
        errorToast("Product Price Required !");
    } else if (short_des.length === 0) {
        errorToast("Product Short Description Required !");
    } else if (is_discount && discount_price.length === 0) {
        errorToast("Discount Price Required !");
    } else if (!image) {
        errorToast("Product Image Required !");
    } else {

        let formData = new FormData();
        formData.append('image', image);
        formData.append('title', title);
        formData.append('price', price);
        formData.append('stock', stock);
        formData.append('category_id', category_id);
        formData.append('brand_id', brand_id);
        formData.append('remark', remark);
        formData.append('short_des', short_des);
        formData.append('is_discount', is_discount ? 1 : 0);
        formData.append('in_stock', 1);
        formData.append('star', 0);
        if (is_discount) {
            formData.append('discount_price', discount_price);
        }

        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }

        showLoader();
        let res = await axios.post("/api/admin/products", formData, config)
        hideLoader();

        if (res.status === 201) {
            successToast(res.data['message']);
            await getListEvent();
            document.getElementById("save-form").reset();
            closeCreateModal();
        } else {
            closeCreateModal()
            errorToast(res.data['message'])
        }
    }
}

watch(() => props.showCreateModal, (newVar) => {
    if (newVar) {
        fillUpCategoryDropDown();
        fillUpBrandDropDown();
    }
})

</script>

<template>
    <div v-if="showCreateModal" class="modal animated zoomIn" id="create-modal" aria-labelledby="exampleModalLabel"
        aria-hidden="true" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Create Product</h6>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">

                                    <label class="form-label">Category</label>
                                    <select class="form-control form-select" id="productCategory"
                                        v-model="product.category_id">
                                        <option v-for="(item, index) in categories" :key="index" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>

                                    <label class="form-label">Brand</label>
                                    <select class="form-control form-select" id="productBrand"
                                        v-model="product.brand_id">
                                        <option v-for="(item, index) in brands" :key="index" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>

                                    <label class="form-label">Remark</label>
                                    <select class="form-control form-select" id="productRemark"
                                        v-model="product.remark">
                                        <option value="">Select Remark</option>
                                        <option value="popular">Popular</option>
                                        <option value="new">New</option>
                                        <option value="top">Top</option>
                                        <option value="special">Special</option>
                                        <option value="trending">Trending</option>
                                        <option value="regular">Regular</option>
                                    </select>

                                    <label class="form-label mt-2">Title</label>
                                    <input class="form-control" id="productTitle" type="text" v-model="product.title">

                                    <label class="form-label mt-2">Price</label>
                                    <input class="form-control" id="productPrice" type="text" v-model="product.price">

                                    <br />
                                    <label class="form-label">is Discount ?</label>
                                    <input id="isDiscountCb" type="checkbox" style="background-color: rgb(0, 110, 255);"
                                        v-on:click="toggleDiscountPriceTab" v-model="product.is_discount">
                                    <br />

                                    <div :class="product.is_discount ? '' : 'd-none'" id="discountPriceTab">
                                        <label class="form-label mt-2">Discount Price</label>
                                        <input class="form-control" id="productDiscountPrice" type="text"
                                            v-model="product.discount_price">
                                    </div>

                                    <label class="form-label mt-2">Stock</label>
                                    <input class="form-control" id="productStock" type="text" v-model="product.stock">

                                    <label class="form-label mt-2">Short Description</label>
                                    <textarea class="form-control" id="productShortDescription" name="" rows="3"
                                        v-model="product.short_des"></textarea>

                                    <br />
                                    <img class="w-15" id="newImg"
                                        :src="product.image ? productImageUrl : '/admin/images/default.jpg'" />
                                    <br />

                                    <label class="form-label">Image</label>
                                    <input class="form-control" id="productImg" type="file" @change="handleImage">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn bg-gradient-primary" id="modal-close" data-bs-dismiss="modal" aria-label="Close"
                        v-on:click="closeCreateModal">Close</button>
                    <button class="btn bg-gradient-success" id="save-btn" v-on:click="Save">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>