<template>
    <div class="tm_destinations_wrapper">

        <AppModal :title="'Add New Coupon'" :width="1000" :showFooter="false" ref="add_coupon_modal">
            <template #body>
                <AddCoupon @updateDataAfterNewAdd="updateDataAfterNewAdd"/>
            </template>
        </AppModal>

        <AppTable :tableData="coupons" v-loading="loading">
            <template #header>
                <h1 class="table-title">Available Coupons</h1>
                <el-button @click="openCouponAddModal" size="large" type="primary" icon="Plus">Add New
                    Coupon</el-button>
            </template>

            <template #filter>
                <el-input @change="getCoupons" class="tm-search-input" v-model="search" style="width: 240px"
                    size="large" placeholder="Please Input" prefix-icon="Search" />
            </template>

            <template #columns>
                <el-table-column prop="id" label="ID" width="60" />
                <el-table-column prop="title" label="Title" width="auto" />
                <el-table-column prop="coupon_code" label="Code" width="auto" />
                <el-table-column prop="max_use" label="Use Limit" width="auto" />
                <el-table-column prop="amount" label="Amount" width="auto" />
                <el-table-column prop="formattedEndDate" label="Expire Date" width="auto" />
                <el-table-column prop="coupon_status" label="Status" width="auto" />
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to edit coupon" placement="top-start">
                            <el-button @click="openUpdateCouponsModal(row)" link type="primary" size="small">
                                <Icon icon="tm-edit" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete coupon"
                            placement="top-start">
                            <el-button @click="openDeleteCouponModal(row)" link type="primary" size="small">
                                <Icon icon="tm-delete" />
                            </el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
            </template>

            <template #footer>
                <el-pagination v-model:current-page="currentPage" v-model:page-size="pageSize"
                    :page-sizes="[10, 20, 30, 40]" large :disabled="total_coupons <= pageSize" background
                    layout="total, sizes, prev, pager, next" :total="+total_coupons" @size-change="getCoupons"
                    @current-change="getCoupons" />
            </template>

        </AppTable>

        <AppModal :title="'Update Coupon'" :width="1000" :showFooter="false" ref="update_coupon_modal">
            <template #body>
                <AddCoupon :coupons_data="coupon" />
            </template>
        </AppModal>

        <AppModal :title="'Delete Coupons'" :width="400" :showFooter="false" ref="delete_coupons_modal">
            <template #body>
                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this coupon</p>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="$refs.delete_coupons_modal.handleClose()" type="default"
                        size="medium">Cancel</el-button>
                    <el-button @click="deleteCoupon" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal>

    </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import AddCoupon from "./AddCoupon.vue";
import Icon from "@/components/Icons/AppIcon.vue"


export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddCoupon,
        Icon
    },
    data() {
        return {
            search: '',
            coupons: [],
            coupon: {},
            total_coupons: 0,
            loading: false,
            add_coupon_modal: false,
            currentPage: 1,
            pageSize: 10,
            coupon_id: null
        }
    },

    methods: {
        formatDate(dateString) {
            const date = new Date(dateString);

            const day = date.getDate();
            const month = date.getMonth() + 1; // JavaScript months are 0-based, so add 1
            const year = date.getFullYear();

            // Format the date as "day-month-year"
            return `${day}-${month}-${year}`;
        },

        getCoupons() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_coupon",
                    route: "get_coupons",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    console.log(response);
                    // that.coupons = response?.data?.data?.coupons;
                    that.coupons = response?.data?.data?.coupons.map(coupon => {
                        return {
                            ...coupon,
                            formattedEndDate: that.formatDate(coupon.end_date) // Format each coupon's end date
                        };
                    });
                    that.total_coupons = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },

        deleteCoupon() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_coupon",
                    route: "delete_coupon",
                    id: that.coupon_id,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.getCoupons();
                    that.$refs.delete_coupons_modal.handleClose();
                }).fail((error) => {
                    console.log(error);
                })
        },


        openCouponAddModal() {
            this.$refs.add_coupon_modal.openModel();
        },
        openUpdateCouponsModal(row) {
            this.coupon = row;
            this.$refs.update_coupon_modal.openModel();
        },
        updateDataAfterNewAdd(new_coupons) {
            this.$refs.add_coupon_modal.handleClose();
            this.coupons.unshift(new_coupons);
        },
        openDeleteCouponModal(row) {
            this.coupon_id = row.id;
            this.$refs.delete_coupons_modal.openModel();
        },

    },

    created() {
        this.getCoupons();

    },


}
</script>