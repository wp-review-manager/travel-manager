<template>
    <div class="container" v-loading="loading">
        <!-- ====================== -->
        <AppModal :title="'Edit payment status'" :width="800" :showFooter="false" ref="update_status_modal">
            <template #body>
                <div class="trm_status_body">
                    <p class="tm_status">Current Payment Status: <span>{{ transactions.payment_status }}</span></p>

                    <form class="tm_status_form">
                        <div class="tm_form_item">
                            <label class="tm_status name">New Payment Status :</label>
                            <el-radio-group v-model="transactions.payment_status">
                                <el-radio value="paid" size="large">Paid </el-radio>
                                <el-radio value="processing" size="large">Processing</el-radio>
                                <el-radio value="pending" size="large">Pending </el-radio>
                                <el-radio value="failed" size="large">Failed </el-radio>
                                <el-radio value="refunded" size="large">Refunded </el-radio>
                            </el-radio-group>
                        </div>

                        <div class="tm_form_item">
                            <label class="tm_status name" style="padding-right: 100px;" >Payment Note :</label>
                            <el-input v-model="transactions.payment_note" style="width: 73%" :rows="2" type="textarea"
                                placeholder="You may add a note for this status change (optional)" />
                        </div>

                    </form>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="updatePaymentStatus()"  type="primary" size="medium">Confirm</el-button>
                </div>
            </template>
        </AppModal>
        <!-- ====================== -->
        <div class="tm_head_info">
            <div class="tm_entry_title">
                <div class="tm_entries_url">
                    <router-link to="/bookings">
                        Bookings
                    </router-link>
                </div>
                <div class="tm_back_icon">
                    <div class="icon"> > </div>
                </div>
                <div class="tm_payhead_title"> #{{ booking_id }}</div>
            </div>
        </div>
        <!-- ====================== -->
        <div class="tm_entry_layout">
            <!-- ================= -->
            <div class="tm_entry_left">
                <!-- ======================== -->
                <!--Payment Start-->
                <div class="tm_header">

                    <div class="tm_head_top">
                        <!-- ================= -->
                        <div class="tm_header_left">
                            <div class="tm_payment_icon">
                                <Icon icon="tm-money" />
                            </div>
                            <div class="payment_amount_detail">
                                <span class="head_small_title">Payment</span>
                                <div class="head_payment_amount">
                                    <span class="pay_amount">$ {{ transactions.payment_total }}</span>
                                    <span class="tm_pay_status_pending">
                                        <div class="icon">
                                            <Icon icon="tm-pending" />
                                        </div>
                                        {{ transactions.payment_status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- ================= -->
                        <div class="tm_header_right">
                            <div @click="openUpdateStatusModal(booking_id)" class="tm_payment_status"><span>
                                    Change Payment Status
                                </span>
                            </div> <!---->
                            <div class="tm_payment_vendor">
                                <span class="tm_pay_method_offline">
                                    {{ transactions.payment_status }}
                                </span>
                            </div>

                        </div>

                    </div>

                    <div class="tm_head_bottom">

                        <div class="tm_info_block">
                            <div class="tm_info_header">Name</div>
                            <div class="tm_info_value">
                                <span>{{ transactions.payer_name }}</span>
                            </div>
                        </div>
                        <div class="tm_info_block">
                            <div class="tm_info_header">Email</div>
                            <div class="tm_info_value">
                                <span>{{ transactions.payer_email }}</span>
                            </div>
                        </div>
                        <div class="tm_info_block" style="flex-basis: 44%;">
                            <div class="tm_info_header"> Address</div>
                            <div class="tm_info_value">
                                <span>{{ getAddress(transactions.shipping_address) }}
                                </span>
                            </div>
                        </div>


                    </div>

                </div>
                <!--Payment End-->
                <!-- ====================== -->
                <!--Traveler Info Start-->
                <AppCard title="Traveler Info" :icon="'tm-traveler'" style="background: #fff; margin-top: 20px;">
                    <template v-slot:actions>

                    </template>
                    <template v-slot:body>
                        <div class="tm_traveler_info_body">
                            <div class="tm_entry_details">
                                <div class="tm_each_entry">
                                    <div class="tm_entry_label">Name</div>
                                    <div class="tm_entry_value">{{ bookings.traveler_name }}</div>
                                </div>
                                <div class="tm_each_entry">
                                    <div class="tm_entry_label">Email</div>
                                    <div class="tm_entry_value">{{ bookings.traveler_email }}</div>
                                </div>
                                <div class="tm_each_entry">
                                    <div class="tm_entry_label">Phone</div>
                                    <div class="tm_entry_value">{{ bookings.traveler_phone }}</div>
                                </div>
                                <div class="tm_each_entry">
                                    <div class="tm_entry_label">Country</div>
                                    <div class="tm_entry_value">{{ bookings.traveler_country }}</div>
                                </div>
                                <div class="tm_each_entry">
                                    <div class="tm_entry_label">Address</div>
                                    <div class="tm_entry_value">{{ getAddress(bookings.traveler_address) }}</div>
                                </div>

                            </div>
                        </div>
                    </template>
                </AppCard>
                <!--Traveler Info End-->

                <!-- ================================ -->

                <!--Trip Info Start-->
                <AppCard title="Trip Info" :icon="'tm-traveler'" style="background: #fff; margin-top: 20px;">
                    <template v-slot:actions>
                        <el-button type="default" size="mini" @click="viewTrip(trip_info.trip?.preview_url)">
                            View Trip
                        </el-button>
                    </template>
                    <template v-slot:body>
                        <div class="tm_trip_info_details">
                            <h5>{{ trip_info.trip?.post_title }}</h5>
                            <span class="badge">Code : {{ trip_info?.trip_meta?.general?.trip_code }}</span>
                            <span class="badge">Type : {{ trip_info?.trip_meta?.general?.trip_type }}</span>
                            <span class="badge">Status : {{ trip_info?.trip_meta?.general?.trip_status }}</span>
                            <span class="badge">Category : {{ trip_info?.trip_meta?.general?.trip_category }}</span>
                            <span class="badge">Starting Date : {{
                                trip_info?.trip_meta?.general?.cut_time?.start_of_date || 'Available Anytime' }}</span>
                        </div>
                    </template>
                </AppCard>
                <!--Trip Info End-->
                <!-- =================== -->
                <!--Booking Items Start-->
                <AppCard title="Booking Items" :icon="'tm-traveler'" style="background: #fff; margin-top: 20px;">
                    <template v-slot:actions>

                    </template>
                    <template v-slot:body>
                        <div class="tm_booking_info_body">
                            <table class="tm_list_table widefat table table-bordered striped">
                                <thead>
                                    <tr>
                                        <th>Package Type</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Item Price</th>
                                        <th>Line Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in orderItem" :key="index">
                                        <td>{{ item.package_type }}</td>
                                        <td>{{ item.item_name }}</td>
                                        <td>{{ item.item_qty }}</td>
                                        <td>$ {{ item.item_price }}</td>
                                        <td>$ {{ item.line_total }}</td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">Sub Total:</th>
                                        <th>$ {{ bookings.booking_total }}</th>
                                    </tr> <!---->
                                    <tr>
                                        <th colspan="4"><span>Total <span style="color: rgb(19 181 19);">(After
                                                    discount) :</span></span></th>
                                        <th>$ {{ bookings.booking_total }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </template>
                </AppCard>
                <!--Booking Items End-->
                <!-- ================================ -->
                <!-- =================== -->
                <!--discount Items Start-->
                <AppCard title="Discount Based On Coupon Code" :icon="'tm-traveler'"
                    style="background: #fff; margin-top: 20px;">
                    <template v-slot:actions>

                    </template>
                    <template v-slot:body>
                        <div class="tm_booking_info_body">
                            <table class="tm_list_table widefat table table-bordered striped">
                                <thead>
                                    <tr>
                                        <th>Discount</th>
                                        <th class="tm_align_right">Total Discount Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="color: rgb(19, 181, 19);">Discount (Est ea labrum)</td>
                                        <td class="tm_align_right" v-if="!applyCoupon">$ {{ applyCoupon.discount_amount
                                            }}</td>
                                        <td class="tm_align_right" v-else> No Discount</td>

                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="1">Total Discounts:</th>
                                        <th class="tm_align_right">$ {{ bookings.booking_total }}</th>
                                    </tr> <!---->
                                </tfoot>
                            </table>
                        </div>
                    </template>
                </AppCard>
                <!--discount Items End-->
                <!-- ================================ -->
                <!--Transaction Details Start-->
                <AppCard title="Transaction Details" :icon="'tm-traveler'" style="background: #fff; margin-top: 20px;">
                    <template v-slot:actions>

                    </template>
                    <template v-slot:body>
                        <div class="tm_info_body">
                            <div class="tm_entry_transaction">
                                <ul class="tm_list_items">
                                    <li>
                                        <div class="wpf_list_header">ID</div>
                                        <div class="wpf_list_value">{{ transactions.id }}</div>
                                    </li>

                                    <li>
                                        <div class="wpf_list_header">Payment Method</div>
                                        <div class="wpf_list_value"><span>
                                                {{ transactions.payment_method }}
                                            </span></div>
                                    </li> <!---->
                                    <li>
                                        <div class="wpf_list_header">Payment Total</div>
                                        <div class="wpf_list_value">$ {{ transactions.payment_total }}</div>
                                    </li>
                                    <li>
                                        <div class="wpf_list_header">Payment Status</div>
                                        <div class="wpf_list_value wpf_pay_status_pending">
                                            {{ transactions.payment_status }}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="wpf_list_header">Date</div>
                                        <div class="wpf_list_value">
                                            {{ formatDate(transactions.created_at) }}
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </template>
                </AppCard>
                <!--Transaction Details End-->

                <!-- ================================ -->
            </div>
            <!-- ================================== -->
            <div class="tm_entry_right">
                <!-- ================== -->
                <div class="tm_entry_actions">
                    <div class="tm_entry_header">
                        <span>Entry Actions</span>
                    </div>
                    <div class="tm_entry_body">
                        <div class="tm_entry_print">
                            <div class="icon">
                                <Icon icon="tm-pending" />
                            </div>
                            <span> Print This Entry </span>
                        </div>
                        <div class="tm_entry_print">
                            <div class="icon">
                                <Icon icon="tm-pending" />
                            </div>
                            <span> Print This Entry </span>
                        </div>
                        <div class="tm_entry_print">
                            <div class="icon">
                                <Icon icon="tm-pending" />
                            </div>
                            <span> Print This Entry </span>
                        </div>

                    </div>
                </div>
                <!-- ================== -->
                <div class="tm_meta_info_wrapper">
                    <div class="tm_entry_info_header">
                        <div class="tm_info_box_header">Meta Info</div>
                    </div>
                    <div class="tm_entry_info_body">
                        <ul>
                            <li>User Browser: Chrome</li>
                            <li>Platform: Windows</li>
                            <li>IP Address:
                                <a target="_blank" rel="noopener" href="#">
                                    ::1
                                </a>
                            </li>
                            <li>User:<a target="_blank" rel="noopener" href="#"> admin </a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- ===================== -->
            </div>

        </div>
    </div>
</template>

<script>
import Icon from "@/components/Icons/AppIcon.vue";
import AppCard from "@/components/AppCard.vue";
import AppModal from "@/components/AppModal.vue";
export default {
    components: {
        Icon,
        AppCard,
        AppModal
    },
    data() {
        return {
            booking_id: null,
            bookings: {},
            transactions: {},
            orderItem: {},
            applyCoupon: {},
            trip_info: {},
            loading: false
        }
    },
    methods: {
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
            const date = new Date(dateString);
            return date.toLocaleString('en-US', options);
        },

        getAddress(address) {
            return `${address?.address}, ${address?.city}, ${address?.state}, ${address?.zip_code}, ${address?.country}`;
        },

        getBookingDetails(bookingId) {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_bookings",
                    route: "get_booking_details",
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                    booking_id: bookingId,

                }).then((response) => {
                    that.bookings = response.data.bookings;
                    that.transactions = response.data.transactions;
                    that.orderItem = response.data.orderItems;
                    that.trip_info = response.data.trip;
                    that.applyCoupon = response.data.applyCoupon;
                    console.log(response.data);
                    that.loading = false;
                }).fail((error) => {
                    console.log(error);
                    that.loading = false;
                })
        },

        updatePaymentStatus() {
            jQuery
                .post(ajaxurl, {
                    action: "tm_bookings",
                    route: "update_payment_status",
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                    data: this.transactions,
                   
                }).then((response) => {
                    that.getBookings();
                    that.$refs.update_status_modal.handleClose();
                    this.$notify({
                        title: 'Success',
                        message: response.data,
                        type: 'success',
                        position: 'bottom-right',
                    })

                });
        },

        openUpdateStatusModal(booking_id) {
            this.booking_id = booking_id;
            this.$refs.update_status_modal.openModel();
        },
    },

    mounted() {
        this.booking_id = this.$route.params.id;
        this.getBookingDetails(this.booking_id);
    },

}
</script>