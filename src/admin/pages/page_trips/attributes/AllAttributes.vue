<template>
     <div class="tm_destinations_wrapper">
        <AppModal
            :title="'Add New Attributes'"
            :width="800"
            :showFooter="false"
            ref="add_attributes_modal">
            <template #body>
                <AddAttributions @updateDataAfterNewAddAttribute="updateDataAfterNewAddAttribute" />
            </template>
        </AppModal>

        <AppTable :tableData="attributions" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Attributes</h1>
                <el-button @click="openAttributesAddModal" size="large" type="primary" icon="Plus">Add New Attribute</el-button>
            </template>
            <template #filter>
                <el-input @change="getAttributes" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
            <template #columns>
                <el-table-column prop="id" label="ID" width="60" />
                <el-table-column prop="attr_title" label=" Title" width="auto" />
                <el-table-column prop="attr_slug" label="Slug" width="auto" />
                <el-table-column prop="attr_desc" label="Description" width="420" />
                <el-table-column label="Image" width="auto">
                    <template #default="{ row }">
                        <img v-if="row.images?.url" :src="row.images?.url" alt="image" style="width: 60px; height: 60px; object-fit: cover;">
                        <span v-else>No Image</span>
                    </template>
                </el-table-column>
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to edit destinations" placement="top-start">
                            <el-button @click="openUpdateAttributeModal(row)" link type="primary" size="small">
                                <Icon icon="tm-edit" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete destinations" placement="top-start">
                            <el-button @click="openDeleteAttributeModal(row)" link type="primary" size="small">
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
                    :disabled="total_attributes <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_attributes"
                    @size-change="getAttributes"
                    @current-change="getAttributes"
                />
            </template>
        </AppTable>

        <AppModal
            :title="'Update Attributes'"
            :width="800"
            :showFooter="false"
            ref="update_attributes_modal">
            <template #body>
                <AddAttributions :attributes_data="attributes" />
            </template>
        </AppModal>

        <AppModal
            :title="'Delete Attributes'"
            :width="400"
            :showFooter="false"
            ref="delete_attributes_modal">
            <template #body>
                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this attributes</p>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="$refs.delete_attributes_modal.handleClose()" type="default" size="medium">Cancel</el-button>
                    <el-button @click="deleteAttributes" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal>

     </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue";
import AddAttributions from "@/pages/page_trips/attributes/AddAttributions.vue";
import Icon from "@/components/Icons/AppIcon.vue";


export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddAttributions,
        Icon
    },
    data() {
        return {
            search: '',
            attributions: [],
            attributes: {},
            total_attributes: 0,
            loading: false,
            add_attributes_modal: false,
            currentPage: 1,
            pageSize: 10,
            active_id: null
        }
    },

    methods: {
       
        getAttributes() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_attributes",
                    route: "get_attributes",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.attributions = response?.data?.data?.attributions;
                    that.total_attributes = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },
        deleteAttributes() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_attributes",
                    route: "delete_attributes",
                    id: that.active_id,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.getAttributes();
                    that.$refs.delete_attributes_modal.handleClose();
                }).fail((error) => {
                    console.log(error);
                })
        },

        openAttributesAddModal() {
            this.$refs.add_attributes_modal.openModel();
        },

        openUpdateAttributeModal(row) {
            this.attributes = row;
            this.$refs.update_attributes_modal.openModel();
        },

        openDeleteAttributeModal(row) {
            this.active_id = row.id;
            this.$refs.delete_attributes_modal.openModel();
        },

        updateDataAfterNewAddAttribute(new_attributes) {
            this.$refs.add_attributes_modal.handleClose();
            this.attributions.unshift(new_attributes);
        },

       
    },
    created() {
        this.getAttributes();
    },
   
}

</script>


