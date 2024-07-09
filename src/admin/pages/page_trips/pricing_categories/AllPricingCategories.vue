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

        <AppTable :tableData="destinations" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Pricing Categories</h1>
                <el-button @click="openPricingCategoriesAddModal" size="large" type="primary" icon="Plus">Add New Pricing Categories</el-button>
            </template>
            <template #filter>
                <el-input @change="getDestinations" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
            <template #columns>
                <el-table-column prop="id" label="ID" width="40" />
                <el-table-column prop="place_name" label="Place Name" width="auto" />
                <el-table-column prop="place_slug" label="Slug" width="auto" />
                <el-table-column prop="place_desc" label="Description" width="420" />
                <el-table-column label="Image" width="auto">
                    <template #default="{ row }">
                        <img v-if="row.images?.url" :src="row.images?.url" alt="image" style="width: 60px; height: 60px; object-fit: cover;">
                        <span v-else>No Image</span>
                    </template>
                </el-table-column>
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to edit destinations" placement="top-start">
                            <el-button @click="openUpdateDestinationModal(row)" link type="primary" size="small">
                                <Icon icon="tm-edit" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete destinations" placement="top-start">
                            <el-button @click="openDeleteDestinationModal(row)" link type="primary" size="small">
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
                    :disabled="total_destinations <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_destinations"
                    @size-change="getDestinations"
                    @current-change="getDestinations"
                />
            </template>
        </AppTable>
        
      
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
      
        openPricingCategoriesAddModal() {
            this.$refs.add_pricing_categories_modal.openModel();
        },
       
    },
   
}
</script>