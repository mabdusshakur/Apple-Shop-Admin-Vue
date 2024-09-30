<script setup>
import { watch, ref, defineProps, defineEmits, reactive } from 'vue';

const categories = ref([]);
const brands = ref([]);
const product_detail_id = ref(null);
const productImageUrl = ref(null);

const product_detail_img1 = ref(null);
const product_detail_img2 = ref(null);
const product_detail_img3 = ref(null);
const product_detail_img4 = ref(null);

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
    discount_price: '',

    // product detail
    product_detail: {
        size: '',
        color: '',
        description: '',
        img1: '',
        img2: '',
        img3: '',
        img4: ''
    }
});

const resetProduct = () => {
    product.title = '';
    product.category_id = '';
    product.brand_id = '';
    product.price = '';
    product.stock = '';
    product.short_des = '';
    product.image = null;
    product.remark = '';
    product.is_discount = false;
    product.discount_price = '';

    // product detail
    product.product_detail.size = '';
    product.product_detail.color = '';
    product.product_detail.description = '';
    product.product_detail.img1 = '';
    product.product_detail.img2 = '';
    product.product_detail.img3 = '';
    product.product_detail.img4 = '';

    product_detail_img4.value = null;
    product_detail_img3.value = null;
    product_detail_img2.value = null;
    product_detail_img1.value = null;
};

function handleImage(e) {
    const target = e.target;
    const siblingId = target.nextElementSibling.id;
    product.product_detail[siblingId] = e.target.files[0];

    const imageMap = {
        img1: product_detail_img1,
        img2: product_detail_img2,
        img3: product_detail_img3,
        img4: product_detail_img4
    };

    if (imageMap[siblingId]) {
        imageMap[siblingId].value = URL.createObjectURL(product.product_detail[siblingId]);
    }


    // main product image
    if (siblingId === 'oldImg') {
        product.image = e.target.files[0];
        productImageUrl.value = URL.createObjectURL(product.image);
    }
}


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
    resetProduct();
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
async function fillUpUpdateModal() {
    let id = props.selectedProductId;
    let detail = product_detail_id.value === null ? null : product_detail_id.value;
    showLoader();

    let res = await axios.get(`/api/admin/products/${id}`);
    res = res.data[0];
    hideLoader();
    product.category_id = res['category_id'];
    product.brand_id = res['brand_id'];
    product.remark = res['remark'];
    product.title = res['title'];
    product.price = res['price'];
    product.stock = res['stock'];
    product.short_des = res['short_des'];
    productImageUrl.value = res['image'];
    product.is_discount = res['is_discount'] == 1 ? true : false;
    product.discount_price = product.is_discount ? res['discount_price'] : '';

    if (!isNaN(detail)) {
        product.product_detail.size = res['product_detail']['size'];
        product.product_detail.color = res['product_detail']['color'];
        product.product_detail.description = res['product_detail']['description'];
        product_detail_img1.value = res['product_detail']['img1'];
        product_detail_img2.value = res['product_detail']['img2'];
        product_detail_img3.value = res['product_detail']['img3'];
        product_detail_img4.value = res['product_detail']['img4'];
    }
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

    let productSize = product.product_detail.size;
    let productColor = product.product_detail.color;
    let productDescription = product.product_detail.description;
    let productImg1 = product.product_detail.img1;
    let productImg2 = product.product_detail.img2;
    let productImg3 = product.product_detail.img3;
    let productImg4 = product.product_detail.img4;

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
    } else if (is_discount && discount_price.length === 0) {
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
                                    <select class="form-control form-select" id="productCategoryUpdate"
                                        v-model="product.category_id">
                                        <option v-for="(item, index) in categories" :key="index" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>

                                    <label class="form-label">Brand</label>
                                    <select class="form-control form-select" id="productBrandUpdate"
                                        v-model="product.brand_id">
                                        <option v-for="(item, index) in brands" :key="index" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>

                                    <label class="form-label">Remark</label>
                                    <select class="form-control form-select" id="productRemarkUpdate"
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
                                    <input class="form-control" id="productTitleUpdate" type="text"
                                        v-model="product.title">

                                    <label class="form-label mt-2">Price</label>
                                    <input class="form-control" id="productPriceUpdate" type="text"
                                        v-model="product.price">

                                    <br />
                                    <label class="form-label">is Discount ?</label>
                                    <input id="isDiscountCbUpdate" type="checkbox"
                                        style="background-color: rgb(0, 110, 255);" v-model="product.is_discount">
                                    <br />

                                    <div :class="product.is_discount ? '' : 'd-none'" id="discountPriceTabUpdate">
                                        <label class="form-label mt-2">Discount Price</label>
                                        <input class="form-control" id="productDiscountPriceUpdate" type="text"
                                            v-model="product.discount_price">
                                    </div>

                                    <label class="form-label mt-2">Stock</label>
                                    <input class="form-control" id="productStockUpdate" type="text"
                                        v-model="product.stock">

                                    <label class="form-label mt-2">Short Description</label>
                                    <textarea class="form-control" id="productShortDescriptionUpdate" name="" rows="3"
                                        v-model="product.short_des"></textarea>

                                    <br />

                                    <label class="form-label">Image</label>
                                    <input class="form-control" id="productImgUpdate" type="file" @change="handleImage">

                                    <img class="w-15" id="oldImg"
                                        :src="productImageUrl ? productImageUrl : '/admin/images/default.jpg'" />

                                    <br />

                                    <hr />
                                    <h4>Product Detail Form</h4>

                                    <label class="form-label mt-2">Size</label>
                                    <input class="form-control" id="productSize" type="text"
                                        v-model="product.product_detail.size">

                                    <label class="form-label mt-2">Color</label>
                                    <input class="form-control" id="productColor" type="text"
                                        v-model="product.product_detail.color">

                                    <label class="form-label mt-2">Description</label>
                                    <textarea class="form-control" id="productDescription" name="" rows="3"
                                        v-model="product.product_detail.description"></textarea>

                                    <br />

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label mt-2">Image 1</label>
                                            <input class="form-control" id="productImg1" type="file"
                                                @change="handleImage">
                                            <img class="w-15" id="img1"
                                                :src="product_detail_img1 ? product_detail_img1 : '/admin/images/default.jpg'" />
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mt-2">Image 2</label>
                                            <input class="form-control" id="productImg2" type="file"
                                                @change="handleImage">
                                            <img class=" w-15" id="img2"
                                                :src="product_detail_img2 ? product_detail_img2 : '/admin/images/default.jpg'" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label mt-2">Image 3</label>
                                            <input class="form-control" id="productImg3" type="file"
                                                @change="handleImage">
                                            <img class="w-15" id="img3"
                                                :src="product_detail_img3 ? product_detail_img3 : '/admin/images/default.jpg'" />
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mt-2">Image 4</label>
                                            <input class="form-control" id="productImg4" type="file"
                                                @change="handleImage">
                                            <img class="w-15" id="img4"
                                                :src="product_detail_img4 ? product_detail_img4 : '/admin/images/default.jpg'" />
                                        </div>
                                    </div>
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