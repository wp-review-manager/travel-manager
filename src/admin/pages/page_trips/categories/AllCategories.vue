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

        <AppTable :tableData="destinations" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Travel Categories</h1>
                <el-button @click="openCategoryAddModal" size="large" type="primary" icon="Plus">Add New Category</el-button>
            </template>
           
        </AppTable>
        
        <!-- <AppModal
            :title="'Update Destinations'"
            :width="800"
            :showFooter="false"
            ref="update_destination_modal">
            <template #body>
                <AddDestinations :destination_data="destination" />
            </template>
        </AppModal>

        <AppModal
            :title="'Delete Destinations'"
            :width="400"
            :showFooter="false"
            ref="delete_destination_modal">
            <template #body>
                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this destinations</p>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="$refs.delete_destination_modal.handleClose()" type="default" size="medium">Cancel</el-button>
                    <el-button @click="deleteDestination" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal> -->

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
            destinations: [],
            destination: {},
            total_destinations: 0,
            loading: false,
            add_category_modal: false,
            currentPage: 1,
            pageSize: 10,
            active_id: null
        }
    },
    methods: {
      
        openCategoryAddModal() {
            this.$refs.add_category_modal.openModel();
        },

    },

}
</script>