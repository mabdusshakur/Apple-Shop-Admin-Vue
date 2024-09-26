<script setup>
import { ref, onMounted } from 'vue';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';


DataTable.use(DataTablesCore);
const products = ref([]);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const showUpdateModal = ref(false);
const selectedProductId = ref(null);
const selectedProductDetailId = ref(null);

const columns = ref([
    {
        data: null,
        render: (data, type, row, meta) => meta.row + 1
    },
    {
        data: 'image',
        render: (data) => `<img class="w-15 h-auto" alt="" src="${data}">`
    },
    { data: 'title' },
    {
        data: 'is_discount',
        render: (data, type, row) => data == 1 ? `Yes - $${row.discount_price}` : 'No'
    },
    { data: 'price' },
    { data: 'stock' },
    {
        data: 'brand',
        render: (data) => data.name
    },
    {
        data: 'category',
        render: (data) => data.name
    },
    { data: 'remark' },
    {
        data: 'id',
        render: (data, type, row) => `
            <button data-detail="${row.product_detail != null ? row.product_detail.id : row.product_detail}" data-id="${data}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
            <button data-detail="${row.product_detail != null ? row.product_detail.id : row.product_detail}" data-id="${data}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
        `
    }
]);

function editProduct(id, detail_id) {
    openUpdateModal();
    selectedProductId.value = id;
    selectedProductDetailId.value = detail_id;
}

function deleteProduct(id, detail_id) {
    openDeleteModal();
    selectedProductId.value = id;
    selectedProductDetailId.value = detail_id;
}

async function getList() {
    const res = await axios.get("/api/admin/products");
    products.value = res.data[0];
}

// Create Modal
function openCreateModal() {
    showCreateModal.value = true;
}

function closeCreateModal() {
    showCreateModal.value = false;
}

// Delete Modal
function openDeleteModal() {
    showDeleteModal.value = true;
}

function closeDeleteModal() {
    showDeleteModal.value = false;
}

// Update Modal
function openUpdateModal() {
    showUpdateModal.value = true;
}

function closeUpdateModal() {
    showUpdateModal.value = false;
}

onMounted(async () => {
    await getList();
    const table = document.querySelector('.table-responsive');

    table.addEventListener('click', (event) => {
        const target = event.target;
        if (target.classList.contains('editBtn')) {
            const id = target.getAttribute('data-id');
            const detail_id = target.getAttribute('data-detail');
            editProduct(id, detail_id);
        } else if (target.classList.contains('deleteBtn')) {
            const id = target.getAttribute('data-id');
            const detail_id = target.getAttribute('data-detail');
            deleteProduct(id, detail_id);
        }
    });
});
</script>

<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card px-5 py-5">
                    <div class="row justify-content-between">
                        <div class="align-items-center col">
                            <h4>Product</h4>
                        </div>
                        <div class="align-items-center col">
                            <button class="float-end btn bg-gradient-primary m-0" data-bs-toggle="modal"
                                v-on:click="openCreateModal">Create</button>
                        </div>
                    </div>
                    <hr class="bg-secondary" />
                    <div class="table-responsive">
                        <DataTable :columns="columns" :data="products" class="display">
                            <thead>
                                <tr class="bg-light">
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Discount ? Price</th>
                                    <th>Normal Price</th>
                                    <th>Stock</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>