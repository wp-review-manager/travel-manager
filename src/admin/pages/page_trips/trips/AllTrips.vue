<template>
    <AppTable :tableData="trips" v-loading="loading">
        <template #header>
            <h1 class="table-title">All Trips</h1>
            <el-button @click="createTripAndRedirect()" size="large" type="primary" icon="Plus">Add New Trip </el-button>
        </template>
        <template #filter>
            <el-input class="tm-search-input" @change="getAllTrips()" v-model="search" style="width: 240px" size="large"
                placeholder="Please Input" prefix-icon="Search" />
            <AppDatePicker @changeDate="changeDate" />
        </template>
        <template #columns>
            <el-table-column prop="ID" label="ID" width="100" />
            <el-table-column prop="post_name" label="Trip Title" width="320" />
            <el-table-column prop="shortcode" label="Shortcode" width="220">
                <template #default="{ row }">
                    <el-tooltip class="box-item" effect="dark" content="Click to copy shortcode" placement="top-start">
                        <button class="tm-shortcode" v-clipboard="'dhjfjhgdshjfgjsdgf'" v-clipboard:success="clipboardSuccessHandler">
                            {{ row.shortcode }}
                        </button>
                    </el-tooltip>
                </template>
            </el-table-column>
            <el-table-column prop="post_date" label="Created At" width="180" />
            <el-table-column prop="post_status" label="Status" width="110" />
            <el-table-column  label="Operations" width="180">
                <template #default="{ row }">
                    <el-tooltip class="box-item" effect="dark" content="Click to edit destinations" placement="top-start">
                        <el-button @click="redirectEditPage(row)" link type="primary" size="small">
                            <Icon icon="tm-edit" />
                        </el-button>
                    </el-tooltip>
                    <el-tooltip class="box-item" effect="dark" content="Click to delete destinations" placement="top-start">
                        <el-button @click="confirmDeleteTrip(row)" link type="primary" size="small">
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
                :disabled="total_trips <= pageSize"
                background
                layout="total, sizes, prev, pager, next"
                :total="+total_trips"
                @size-change="getAllTrips"
                @current-change="getAllTrips"
            />
        </template>
    </AppTable>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import Icon from "@/components/Icons/AppIcon.vue"
export default {
    components: {
        AppTable,
        AppDatePicker,
        Icon
    },
    data() {
        return {
            search: '',
            filter_date: '',
            currentPage: 1,
            pageSize: 10,
            total_trips: 100,
            loading: false,
            trips: []
        }
    },
    methods: {
        createTripAndRedirect() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_trips",
                    route: "create_or_update_trip",
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    this.$router.push(`/trip/${response.data}/edit/`)
                }).fail((error) => {
                    console.log(error);
                })
        },

        getAllTrips() {
            let that = this;
            this.loading = true;
            jQuery
                .post(ajaxurl, {
                    action: "tm_trips",
                    route: "get_trips",
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                    page: that.currentPage,
                    per_page: that.pageSize,
                    search: that.search,
                    filter_date: that.filter_date
                }).then((response) => {
                    that.trips = response.data.trips;
                    that.total_trips = response.data.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },
        clipboardSuccessHandler() {
            this.$notify({
                message: "Copied to clipboard",
                type: "success",
                position: "bottom-right"
            });
        },
        redirectEditPage(row) {
            this.$router.push(`/trip/${row.ID}/edit/`)
        },
        changeDate(date) {
            this.filter_date = date;
            this.getAllTrips();
        },
        confirmDeleteTrip(row) {
            this.$confirm('This will delete the trip. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.deleteTrip(row);
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: 'Delete canceled'
                });
            });
        },
        deleteTrip(row) {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_trips",
                    route: "delete_trip",
                    trip_id: row.ID,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.$notify({
                        type: 'success',
                        message: 'Trip deleted successfully',
                        position: 'bottom-right'
                    });
                    that.getAllTrips();
                }).fail((error) => {
                    console.log(error);
                })
        },
    },
    mounted() {
        this.getAllTrips();
    }
}
</script>