<template>
    <div class="tm_destinations_wrapper">

        <AppTable :tableData="enquiries" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Enquiries</h1>
            </template>

             <template #filter>
                <el-input @change="getEnquiries" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
           
            <template #columns>
                <el-table-column prop="id" label="ID" width="40" />
                <el-table-column prop="trip_id" label="Trip id" width="auto" />
                <el-table-column prop="name" label="Name" width="auto" />
                <el-table-column prop="email" label="Email" width="auto" />
                <el-table-column prop="phone" label="Phone" width="auto" />
                <el-table-column prop="country" label="Country" width="auto" />
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to edit activities" placement="top-start">
                            <el-button @click="openDetailsModal(row)" link type="primary" size="small">
                                <Icon icon="tm-eye" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete activities" placement="top-start">
                            <el-button @click="openDeleteEnquiriesModal(row)" link type="primary" size="small">
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
                    :disabled="total_enquiries <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_enquiries"
                    @size-change="getEnquiries"
                    @current-change="getEnquiries"
                />
            </template>
        </AppTable>

        <AppModal
            :title="'Delete Enquiry'"
            :width="400"
            :showFooter="false"
            ref="delete_enquiries_modal">
            <template #body>
                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this enquiries</p>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="$refs.delete_enquiries_modal.handleClose()" type="default" size="medium">Cancel</el-button>
                    <el-button @click="deleteEnquiries" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal>

        <AppModal
            :title="'View Enquiries Details'"
            :width="800"
            :showFooter="false"
            ref="update_enquiries_modal">
            <template #body>
                
                <p style=" font-size: 18px; margin-bottom: 10px;"><b>Name : </b>  {{ enquiry.name }}</p>
                <p style=" font-size: 18px; margin-bottom: 10px;"><b>Email : </b> {{ enquiry.email }}</p>
                <p style=" font-size: 18px; margin-bottom: 10px;"><b>Phone : </b> {{ enquiry.phone }}</p>
                <p style=" font-size: 18px; margin-bottom: 10px;"><b>Country : </b> {{ enquiry.country }}</p>
                <p style=" font-size: 18px; margin-bottom: 10px;"><b>Number of Adults : </b> {{ enquiry.number_of_adults }}</p>
                <p style=" font-size: 18px; margin-bottom: 10px;"><b>Number of Children : </b> {{ enquiry.number_of_children }}</p>
                <p style=" font-size: 18px; margin-bottom: 10px;"><b>Subject : </b> {{ enquiry.subject }}</p>
                <p style=" font-size: 18px; margin-bottom: 10px;"><b>Message : </b> {{ enquiry.message }}</p>
            </template>
        </AppModal>


    </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import Icon from "@/components/Icons/AppIcon.vue"

export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        Icon
    },
    data() {
        return {
            search: '',
            enquiries: [],
            enquiry: {},
            total_enquiries: 0,
            loading: false,
            currentPage: 1,
            pageSize: 10,
            active_id: null
        }
    },

    methods: {
        getEnquiries() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_inquiry",
                    route: "get_enquiries",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.enquiries = response?.data?.data?.enquiries;
                    that.total_enquiries = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },

        deleteEnquiries() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_inquiry",
                    route: "delete_enquiries",
                    id: that.active_id,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.getEnquiries();
                    that.$refs.delete_enquiries_modal.handleClose();
                }).fail((error) => {
                    console.log(error);
                })
        },
      
    
        openDeleteEnquiriesModal(row) {
            this.active_id = row.id;
            this.$refs.delete_enquiries_modal.openModel();
        },


        openDetailsModal(row) {
            this.enquiry = row;
            this.$refs.update_enquiries_modal.openModel();
        },
      
    },
       created() {
        this.getEnquiries();
    },
  
}
</script>