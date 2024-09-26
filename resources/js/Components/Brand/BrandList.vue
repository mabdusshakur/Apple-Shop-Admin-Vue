<script setup>
import { ref, onMounted } from 'vue';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import BrandCreate from './BrandCreate.vue';
import BrandDelete from './BrandDelete.vue';
import BrandUpdate from './BrandUpdate.vue';


DataTable.use(DataTablesCore);
const brands = ref([]);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const showUpdateModal = ref(false);
const selectedBrandId = ref(null);

const columns = ref([
    {
        data: null,
        render: (data, type, row, meta) => meta.row + 1
    },
    {
        data: 'image',
        render: (data) => `<img src="${data}" alt="Brand Image" style="width: 50px; height: auto;" />`
    },
    { data: 'name' },
    {
        data: 'id',
        render: (data) => `
            <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${data}">Edit</button>
            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${data}">Delete</button>
        `
    }
]);

function editBrand(id) {
    openUpdateModal();
    selectedBrandId.value = id;
}

function deleteBrand(id) {
    openDeleteModal();
    selectedBrandId.value = id;
}

async function getList() {
    const res = await axios.get("/api/admin/brands");
    brands.value = res.data[0];
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

        if (target.classList.contains('edit-btn')) {
            const id = target.getAttribute('data-id');
            editBrand(id);
        } else if (target.classList.contains('delete-btn')) {
            const id = target.getAttribute('data-id');
            deleteBrand(id);
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
                            <h4>Brand</h4>
                        </div>
                        <div class="align-items-center col">
                            <button class="float-end btn bg-gradient-primary m-0" data-bs-toggle="modal"
                                v-on:click="openCreateModal">Create</button>
                        </div>
                    </div>
                    <hr class="bg-secondary" />
                    <div class="table-responsive">
                        <DataTable :columns="columns" :data="brands" class="display">
                            <thead>
                                <tr class="bg-light">
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Brand</th>
                                </tr>
                            </thead>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <BrandCreate :showCreateModal="showCreateModal" @closeCreateModal="closeCreateModal" @getList="getList" />
    <BrandDelete :showDeleteModal="showDeleteModal" :selectedBrandId="selectedBrandId"
        @closeDeleteModal="closeDeleteModal" @getList="getList" />

    <BrandUpdate :showUpdateModal="showUpdateModal" :selectedBrandId="selectedBrandId"
        @closeUpdateModal="closeUpdateModal" @getList="getList" />
</template>

<style scoped></style>