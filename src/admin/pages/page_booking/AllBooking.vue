<template>
    <div class="tm_destinations_wrapper">

        <AppTable :tableData="bookings" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Booking</h1>
            </template>

             <template #filter>
                <el-input @change="getBookings" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
           
            <template #columns>
                <el-table-column prop="id" label="ID" width="60" />
                <el-table-column prop="booking_date" label="Booking Date" width="auto" />
                <el-table-column prop="booking_status" label="Status" width="auto" />
                <el-table-column prop="booking_total" label="Total" width="auto" />
                <el-table-column prop="trip_id" label="Trip id" width="auto" />
                <el-table-column prop="traveler_name" label="Customer Name" width="auto" />
                <el-table-column prop="traveler_email" label="Customer Email" width="auto" />
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to view activities" placement="top-start">
                            <el-button @click="redirectPreviewUrl(row)" link type="primary" size="small">
                                <Icon icon="tm-eye" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete activities" placement="top-start">
                            <el-button @click="openDeleteBookingModal(row)" link type="primary" size="small">
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
                    :disabled="total_booking <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_booking"
                    @size-change="getBookings"
                    @current-change="getBookings"
                />
            </template>
        </AppTable>

        <AppModal
            :title="'Delete Booking'"
            :width="400"
            :showFooter="false"
            ref="delete_booking_modal">
            <template #body>
                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this booking</p>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="$refs.delete_booking_modal.handleClose()" type="default" size="medium">Cancel</el-button>
                    <el-button @click="deleteBooking" type="primary" size="medium">Delete</el-button>
                </div>
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
            bookings: [],
            // booking: {},
            total_booking: 0,
            loading: false,
            currentPage: 1,
            pageSize: 10,
            active_id: null
        }
    },

    methods: {
        getBookings() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_bookings",
                    route: "get_bookings",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    console.log(response, 'response data');
                    that.bookings = response?.data?.data?.bookings;
                    that.total_booking = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },

        deleteBooking() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_bookings",
                    route: "delete_booking",
                    id: that.active_id,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.getBookings();
                    that.$refs.delete_booking_modal.handleClose();
                }).fail((error) => {
                    console.log(error);
                })
        },
      
    
        openDeleteBookingModal(row) {
            this.active_id = row.id;
            this.$refs.delete_booking_modal.openModel();
        },

        redirectPreviewUrl(row) {
            this.$router.push(`/booking/${row.id}/view/`)
        },
    },
    
    created() {
        this.getBookings();
    },
  
}
</script>