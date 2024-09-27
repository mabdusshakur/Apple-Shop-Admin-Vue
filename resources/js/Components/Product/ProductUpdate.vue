<script setup>
import { watch, ref, defineProps, defineEmits } from 'vue';

const categories = ref([]);
const brands = ref([]);
const product_detail_id = ref(null);

const props = defineProps({
    showUpdateModal: {
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
    closeUpdateModal: null,
    getList: null,
});

function closeUpdateModal() {
    emit('closeUpdateModal');
    document.getElementById("update-form").reset();
}

async function getListEvent() {
    emit('getList');
}

const toggleDiscountPriceTab = () => {
    let isDiscountCb = document.getElementById('isDiscountCbUpdate');
    let discountPriceTab = document.getElementById('discountPriceTabUpdate');
    if (isDiscountCb.checked) {
        discountPriceTab.classList.remove('d-none');;
    } else {
        discountPriceTab.classList.add('d-none');
    }
}
async function fillUpCategoryDropDown() {
    let res = await axios.get("/api/admin/categories")
    categories.value = res.data[0];
}

async function fillUpBrandDropDown() {
    let res = await axios.get("/api/admin/brands")
    brands.value = res.data[0];
}
async function fillUpUpdateModal() {
    let id = props.selectedProductId;
    let detail = product_detail_id.value === null ? null : product_detail_id.value;
    showLoader();

    let res = await axios.get(`/api/admin/products/${id}`);
    res = res.data[0];
    hideLoader();
    document.getElementById('productCategoryUpdate').value = res['category_id'];
    document.getElementById('productBrandUpdate').value = res['brand_id'];
    document.getElementById('productRemarkUpdate').value = res['remark'];
    document.getElementById('productTitleUpdate').value = res['title'];
    document.getElementById('productPriceUpdate').value = res['price'];
    document.getElementById('productStockUpdate').value = res['stock'];
    document.getElementById('productShortDescriptionUpdate').value = res['short_des'];
    document.getElementById('oldImg').src = res['image'];

    if (res['is_discount']) {
        document.getElementById('isDiscountCbUpdate').checked = true;
        document.getElementById('discountPriceTabUpdate').classList.remove('d-none');
        document.getElementById('productDiscountPriceUpdate').value = res['discount_price'];
    } else {
        document.getElementById('isDiscountCbUpdate').checked = false;
        document.getElementById('discountPriceTabUpdate').classList.add('d-none');
        document.getElementById('productDiscountPriceUpdate').value = '';
    }

    if (!isNaN(detail)) {
        document.getElementById('productSize').value = res['product_detail']['size'];
        document.getElementById('productColor').value = res['product_detail']['color'];
        document.getElementById('productDescription').value = res['product_detail']['description'];
        document.getElementById('img1').src = res['product_detail']['img1'];
        document.getElementById('img2').src = res['product_detail']['img2'];
        document.getElementById('img3').src = res['product_detail']['img3'];
        document.getElementById('img4').src = res['product_detail']['img4'];
    }
}

async function Save() {
    let category_id = document.getElementById('productCategoryUpdate').value;
    let brand_id = document.getElementById('productBrandUpdate').value;
    let remark = document.getElementById('productRemarkUpdate').value;
    let title = document.getElementById('productTitleUpdate').value;
    let is_discount = document.getElementById('isDiscountCbUpdate');
    let discount_price = document.getElementById('productDiscountPriceUpdate').value;
    let price = document.getElementById('productPriceUpdate').value;
    let stock = document.getElementById('productStockUpdate').value;
    let short_des = document.getElementById('productShortDescriptionUpdate').value;
    let image = document.getElementById('productImgUpdate').files[0];

    let productSize = document.getElementById('productSize').value;
    let productColor = document.getElementById('productColor').value;
    let productDescription = document.getElementById('productDescription').value;
    let productImg1 = document.getElementById('productImg1').files[0];
    let productImg2 = document.getElementById('productImg2').files[0];
    let productImg3 = document.getElementById('productImg3').files[0];
    let productImg4 = document.getElementById('productImg4').files[0];

    let product_id = props.selectedProductId;
    let detail_id = product_detail_id.value === null ? null : product_detail_id.value;

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
    } else if (is_discount.checked && discount_price.length === 0) {
        errorToast("Discount Price Required !");
    } else if (productSize.length === 0) {
        errorToast("Product Size Required !");
    } else if (productColor.length === 0) {
        errorToast("Product Color Required !");
    } else if (productDescription.length === 0) {
        errorToast("Product Description Required !");
    } else if (!productImg1 && detail_id === 'new') {
        errorToast("Product Image 1 Required !");
    } else if (!productImg2 && detail_id === 'new') {
        errorToast("Product Image 2 Required !");
    } else if (!productImg3 && detail_id === 'new') {
        errorToast("Product Image 3 Required !");
    } else if (!productImg4 && detail_id === 'new') {
        errorToast("Product Image 4 Required !");
    } else {
        let formData = new FormData();
        if (image) {
            formData.append('image', image);
        }
        formData.append('title', title);
        formData.append('price', price);
        formData.append('stock', stock);
        formData.append('category_id', category_id);
        formData.append('brand_id', brand_id);
        formData.append('remark', remark);
        formData.append('short_des', short_des);
        formData.append('is_discount', is_discount.checked ? 1 : 0);
        formData.append('in_stock', 1);
        formData.append('star', 0);
        if (is_discount.checked) {
            formData.append('discount_price', discount_price);
        }

        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }

        showLoader();
        let res = await axios.post(`/api/admin/products/${product_id}`, formData, config)

        let detailsFormData = new FormData();
        detailsFormData.append('size', productSize);
        detailsFormData.append('color', productColor);
        detailsFormData.append('description', productDescription);
        detailsFormData.append('product_id', product_id);
        if (productImg1)
            detailsFormData.append('img1', productImg1);
        if (productImg2)
            detailsFormData.append('img2', productImg2);
        if (productImg3)
            detailsFormData.append('img3', productImg3);
        if (productImg4)
            detailsFormData.append('img4', productImg4);

        if (!isNaN(detail_id)) {
            await axios.post(`/api/admin/product-details/${detail_id}`, detailsFormData, config)
        }
        else {
            await axios.post(`/api/admin/product-details`, detailsFormData, config)
        }

        hideLoader();

        if (res.status === 200 && res.data['success'] === true) {
            successToast(res.data['message']);
            await getListEvent();
            closeUpdateModal();
        } else {
            errorToast(res.data['message'])
        }
    }
}

watch(() => props.showUpdateModal, (newVar) => {
    if (newVar) {
        product_detail_id.value = props.selectedProductDetailId;
        fillUpCategoryDropDown();
        fillUpBrandDropDown();
        fillUpUpdateModal();
    }
})

</script>

<template>
    <div v-if="showUpdateModal" class="modal animated zoomIn" id="create-modal" aria-labelledby="exampleModalLabel"
        aria-hidden="true" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Create Product</h6>
                </div>
                <div class="modal-body">

                    <form id="update-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">

                                    <label class="form-label">Category</label>
                                    <select class="form-control form-select" id="productCategoryUpdate">
                                        <option v-for="(item, index) in categories" :key="index" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>

                                    <label class="form-label">Brand</label>
                                    <select class="form-control form-select" id="productBrandUpdate">
                                        <option v-for="(item, index) in brands" :key="index" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>

                                    <label class="form-label">Remark</label>
                                    <select class="form-control form-select" id="productRemarkUpdate">
                                        <option value="">Select Remark</option>
                                        <option value="popular">Popular</option>
                                        <option value="new">New</option>
                                        <option value="top">Top</option>
                                        <option value="special">Special</option>
                                        <option value="trending">Trending</option>
                                        <option value="regular">Regular</option>
                                    </select>

                                    <label class="form-label mt-2">Title</label>
                                    <input class="form-control" id="productTitleUpdate" type="text">

                                    <label class="form-label mt-2">Price</label>
                                    <input class="form-control" id="productPriceUpdate" type="text">

                                    <br />
                                    <label class="form-label">is Discount ?</label>
                                    <input id="isDiscountCbUpdate" type="checkbox"
                                        style="background-color: rgb(0, 110, 255);" v-on:click="toggleDiscountPriceTab">
                                    <br />

                                    <div class="d-none" id="discountPriceTabUpdate">
                                        <label class="form-label mt-2">Discount Price</label>
                                        <input class="form-control" id="productDiscountPriceUpdate" type="text">
                                    </div>

                                    <label class="form-label mt-2">Stock</label>
                                    <input class="form-control" id="productStockUpdate" type="text">

                                    <label class="form-label mt-2">Short Description</label>
                                    <textarea class="form-control" id="productShortDescriptionUpdate" name=""
                                        rows="3"></textarea>

                                    <br />
                                    <img class="w-15" id="oldImg" src="/public/admin/images/default.jpg" />
                                    <br />

                                    <label class="form-label">Image</label>
                                    <input class="form-control" id="productImgUpdate" type="file"
                                        oninput="oldImg.src=window.URL.createObjectURL(this.files[0])">

                                    <hr />
                                    <h4>Product Detail Form</h4>

                                    <label class="form-label mt-2">Size</label>
                                    <input class="form-control" id="productSize" type="text">

                                    <label class="form-label mt-2">Color</label>
                                    <input class="form-control" id="productColor" type="text">

                                    <label class="form-label mt-2">Description</label>
                                    <textarea class="form-control" id="productDescription" name="" rows="3"></textarea>

                                    <br />

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label mt-2">Image 1</label>
                                            <input class="form-control" id="productImg1" type="file"
                                                oninput="img1.src=window.URL.createObjectURL(this.files[0])">
                                            <img class="w-15" id="img1" src="/public/admin/images/default.jpg" />
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mt-2">Image 2</label>
                                            <input class="form-control" id="productImg2" type="file"
                                                oninput="img2.src=window.URL.createObjectURL(this.files[0])">
                                            <img class="w-15" id="img2" src="/public/admin/images/default.jpg" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label mt-2">Image 3</label>
                                            <input class="form-control" id="productImg3" type="file"
                                                oninput="img3.src=window.URL.createObjectURL(this.files[0])">
                                            <img class="w-15" id="img3" src="/public/admin/images/default.jpg" />
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mt-2">Image 4</label>
                                            <input class="form-control" id="productImg4" type="file"
                                                oninput="img4.src=window.URL.createObjectURL(this.files[0])">
                                            <img class="w-15" id="img4" src="/public/admin/images/default.jpg" />
                                        </div>
                                    </div>

                                    <input class="d-none" id="updateID" type="text">
                                    <input class="d-none" id="detailID" type="text">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn bg-gradient-primary" id="modal-close" data-bs-dismiss="modal" aria-label="Close"
                        v-on:click="closeUpdateModal">Close</button>
                    <button class="btn bg-gradient-success" id="save-btn" v-on:click="Save">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>