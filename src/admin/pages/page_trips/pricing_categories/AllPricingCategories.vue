<template>
    <div class="tm_destinations_wrapper">
        <AppModal
            :title="'Add New Pricing Categories'"
            :width="800"
            :showFooter="false"
            ref="add_pricing_categories_modal">
            <template #body>
                <AddPricingCategories @updateDataAfterNewAdd="updateDataAfterNewAdd" />
            </template>
        </AppModal>

        <AppTable :tableData="pricing_categories" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Pricing Categories</h1>
                <el-button @click="openPricingCategoriesAddModal" size="large" type="primary" icon="Plus">Add New Pricing Categories</el-button>
            </template>
            <template #filter>
                <el-input @change="getPricingCategories" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
            <template #columns>
                <el-table-column prop="id" label="ID" width="60" />
                <el-table-column prop="pricing_categories_name" label=" Name" width="auto" />
                <el-table-column prop="pricing_categories_slug" label="Slug" width="auto" />
                <el-table-column prop="pricing_categories_desc" label="Description" width="420" />
                <el-table-column label="Image" width="auto">
                    <template #default="{ row }">
                        <img v-if="row.images?.url" :src="row.images?.url" alt="image" style="width: 60px; height: 60px; object-fit: cover;">
                        <span v-else>No Image</span>
                    </template>
                </el-table-column>
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to edit pricing categories" placement="top-start">
                            <el-button @click="openUpdatePricingCategoriesModal(row)" link type="primary" size="small">
                                <Icon icon="tm-edit" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete pricing categories" placement="top-start">
                            <el-button @click="openDeletePricingCategoriesModal(row)" link type="primary" size="small">
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
                    :disabled="total_pricing_categories <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_pricing_categories"
                    @size-change="getPricingCategories"
                    @current-change="getPricingCategories"
                />
            </template>
        </AppTable>
        
        <AppModal
            :title="'Update Pricing Categories'"
            :width="800"
            :showFooter="false"
            ref="update_pricing_categories_modal">
            <template #body>
                <AddPricingCategories :pricing_categories_data="pricing_category" />
            </template>
        </AppModal>

        <AppModal
            :title="'Delete Pricing Categories'"
            :width="400"
            :showFooter="false"
            ref="delete_pricing_categories_modal">
            <template #body>
                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this pricing categories</p>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="$refs.delete_pricing_categories_modal.handleClose()" type="default" size="medium">Cancel</el-button>
                    <el-button @click="deletePricingCategories" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal>
      
    </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import AddPricingCategories from "@/pages/page_trips/pricing_categories/AddPricingCategories.vue"
import Icon from "@/components/Icons/AppIcon.vue"

export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddPricingCategories,
        Icon
    },
    data() {
        return {
            search: '',
            pricing_categories: [],
            pricing_category: {},
            total_pricing_categories: 0,
            loading: false,
            add_pricing_categories_modal: false,
            currentPage: 1,
            pageSize: 10,
            active_id: null
        }
    },

    methods: {
        getPricingCategories() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_pricing_categories",
                    route: "get_pricing_categories",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.pricing_categories = response?.data?.data?.pricing_categories;
                    that.total_pricing_categories = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },
        deletePricingCategories() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_pricing_categories",
                    route: "delete_pricing_categories",
                    id: that.active_id,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.getPricingCategories();
                    that.$refs.delete_pricing_categories_modal.handleClose();
                }).fail((error) => {
                    console.log(error);
                })
        },

        openPricingCategoriesAddModal() {
            this.$refs.add_pricing_categories_modal.openModel();
        },
        openDeletePricingCategoriesModal(row) {
            this.active_id = row.id;
            this.$refs.delete_pricing_categories_modal.openModel();
        },
        openUpdatePricingCategoriesModal(row) {
            this.pricing_category = row;
            this.$refs.update_pricing_categories_modal.openModel();
        },
        updateDataAfterNewAdd(new_pricing_categories) {
            this.$refs.add_pricing_categories_modal.handleClose();
            this.pricing_category.unshift(new_pricing_categories);
        },
       
    },
    created() {
        this.getPricingCategories();
    },
   
}
</script>