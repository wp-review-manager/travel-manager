<template>
    <div class="tm_destinations_wrapper">
        <AppModal
            :title="'Add New Category'"
            :width="800"
            :showFooter="false"
            ref="add_category_modal">
            <template #body>
                <AddCategories @updateDataAfterNewAdd="updateDataAfterNewAdd" />
            </template>
        </AppModal>

        <AppTable :tableData="categories" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Travel Categories</h1>
                <el-button @click="openCategoryAddModal" size="large" type="primary" icon="Plus">Add New Category</el-button>
            </template>

            <template #filter>
                <el-input @change="getCategories" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
            <template #columns>
                <el-table-column prop="id" label="ID" width="40" />
                <el-table-column prop="trip_category_name" label="Category Name" width="auto" />
                <el-table-column prop="trip_category_slug" label="Slug" width="auto" />
                <el-table-column prop="trip_category_desc" label="Description" width="420" />
                <el-table-column label="Image" width="auto">
                    <template #default="{ row }">
                        <img v-if="row.images?.url" :src="row.images?.url" alt="image" style="width: 60px; height: 60px; object-fit: cover;">
                        <span v-else>No Image</span>
                    </template>
                </el-table-column>
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to edit categories" placement="top-start">
                            <el-button @click="openUpdateCategoriesModal(row)" link type="primary" size="small">
                                <Icon icon="tm-edit" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete categories" placement="top-start">
                            <el-button @click="openDeleteCategoriesModal(row)" link type="primary" size="small">
                                <Icon icon="tm-delete" />
                            </el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
            </template>
            <template #footer>
                <el-pagination
                    v-model:current-page="currentPage"
                    v-model:page-size="pageSize"
                    :page-sizes="[10, 20, 30, 40]"
                    large
                    :disabled="total_categories <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_categories"
                    @size-change="getCategories"
                    @current-change="getCategories"
                />
            </template>
           
        </AppTable>

        <AppModal
            :title="'Update Categories'"
            :width="800"
            :showFooter="false"
            ref="update_category_modal">
            <template #body>
                <AddCategories :categories_data="category" />
            </template>
        </AppModal>

        <AppModal
            :title="'Delete Categories'"
            :width="400"
            :showFooter="false"
            ref="delete_categories_modal">
            <template #body>
                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this categories</p>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="$refs.delete_categories_modal.handleClose()" type="default" size="medium">Cancel</el-button>
                    <el-button @click="deleteCategories" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal>
        
      
    </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import AddCategories from "@/pages/page_trips/categories/AddCategories.vue"
import Icon from "@/components/Icons/AppIcon.vue"

export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddCategories,
        Icon
    },
    data() {
        return {
            search: '',
            categories: [],
            category: {},
            total_categories: 0,
            loading: false,
            add_category_modal: false,
            currentPage: 1,
            pageSize: 10,
            active_id: null
        }
    },
    methods: {
        getCategories() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_categories",
                    route: "get_categories",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.categories = response?.data?.data?.categories;
                    that.total_categories = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },
        deleteCategories() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_categories",
                    route: "delete_categories",
                    id: that.active_id,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.getCategories();
                    that.$refs.delete_categories_modal.handleClose();
                }).fail((error) => {
                    console.log(error);
                })
        },
      
        openCategoryAddModal() {
            this.$refs.add_category_modal.openModel();
        },
        openDeleteCategoriesModal(row) {
            this.active_id = row.id;
            this.$refs.delete_categories_modal.openModel();
        },
        openUpdateCategoriesModal(row) {
            this.category = row;
            this.$refs.update_category_modal.openModel();
        },
        updateDataAfterNewAdd(new_category) {
            this.$refs.add_category_modal.handleClose();
            this.category.unshift(new_category);
        },

    },
    created() {
        this.getCategories();
    },

}
</script>