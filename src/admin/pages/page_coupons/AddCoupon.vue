<template>
    <div class="tm_coupon_container">
        <!-- tm_coupon_header start-->
        <div class="tm_coupon_header">
            <div class="tm_coupon_right">
                <div class="tm_coupon_abel">
                    <label>Coupon Title</label>
                </div>

                <el-input v-model="coupons.title" style="width: 100%" size="large" placeholder="Coupon Title" />
                <p class="error-message">{{ title }}</p>
                <p class="tm_coupon_msg">The name of this discount</p>
            </div>
            <div class="tm_coupon_left">
                <div class="tm_coupon_abel">
                    <label>Coupon Code</label>
                </div>
              
                <el-input v-model="coupons.coupon_code" style="width: 100%" size="large" placeholder="Coupon Code" />
                <p class="error-message">{{ coupon_code }}</p>
                <p class="tm_coupon_msg">Enter a code for this discount, such as 10PERCENT. Only use alphabets and
                    numbers as coupon code.</p>
            </div>
        </div>
        <!-- tm_coupon_header end -->

        <!-- tm_coupon_amount_header start -->
        <div class="tm_coupon_amount_header">
            <h1 class="tm_coupon_title">Amount</h1>
            <div class="tm_coupon_header">
                <div class="tm_coupon_right">
                    <div class="tm_coupon_abel">
                        <label>Discount Type</label>
                    </div>
                    <el-select v-model="coupons.coupon_type" placeholder="Discount Type" size="large"
                        style="width: 100%">
                        <el-option label="Percent" value="Percent" />
                        <el-option label="Fixed" value="Fixed" />

                    </el-select>
                    <p class="tm_coupon_msg">The name of this discount</p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Discount Amount / Percent</label>
                    </div>
                   
                    <el-input min="0" v-model="coupons.amount" style="width: 100%" type="number" size="large"
                        placeholder="Discount Amount / Percent" />
                        <p class="error-message">{{ amount }}</p>
                    <p class="tm_coupon_msg">Enter the discount percentage. 10 = 10% </p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Min Purchase Amount</label>
                    </div>
                    <el-input min="0" v-model="coupons.min_amount" style="width: 100%" type="number" size="large"
                        placeholder="Min Purchase Amount" />
                    <p class="tm_coupon_msg">The minimum amount required to use this discount. Leave the field blank if
                        there is no minimum.</p>
                </div>
            </div>
        </div>
        <!-- tm_coupon_amount_header end -->

        <!-- Expiration start -->
        <div class="tm_coupon_amount_header">
            <h1 class="tm_coupon_title">Expiration</h1>
            <div class="tm_coupon_header">
                <div class="tm_coupon_right">
                    <div class="tm_coupon_abel">
                        <label>Limit Per User(Email Based)</label>
                    </div>
                    <el-input min="0" v-model="coupons.max_use" style="width: 100%" type="number" size="large"
                        placeholder="Limit Per User(Email Based)" />
                    <p class="tm_coupon_msg">Enter the usage limit for a single user / Leave '0' for no limitation.</p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Start Date</label>
                    </div>
                    <el-date-picker v-model="coupons.start_date" type="date" style="width: 100%"
                        placeholder="Start Date " size="large" />
                    <p class="tm_coupon_msg">Enter the start date for this discount code in the format of yyyy-mm-dd.
                        For no start date, leave blank.</p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>End Date</label>
                    </div>
                    <el-date-picker v-model="coupons.end_date" type="date" style="width: 100%" placeholder="End Date "
                        size="large" />
                    <p class="tm_coupon_msg">Enter the expiration date for this discount code in the format of
                        yyyy-mm-dd. For no expiration, leave blank</p>
                </div>
            </div>
        </div>
        <!-- Expiration end -->

        <!-- Additional Information's start -->
        <div class="tm_coupon_amount_header">
            <h1 class="tm_coupon_title">Additional Information's </h1>
            <div class="tm_coupon_header">
                <div class="tm_coupon_right">
                    <div class="tm_coupon_abel">
                        <label>Applicable trip id</label>
                    </div>

                    <el-select v-model="coupons.allowed_trip_ids" multiple placeholder="Select" style="width: 100%" size="large">
                        <el-option v-for="item in trips" :key="item.value" :label="item.id" :value="item.post_title" />
                    </el-select>
                    <p class="tm_coupon_msg">Leave blank for applicable for all payment forms</p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Status</label>
                    </div>

                    <el-radio-group v-model="coupons.coupon_status">
                        <el-radio value="Active" size="large">Active </el-radio>
                        <el-radio value="Inactive" size="large">Inactive </el-radio>
                    </el-radio-group>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Stackable </label>
                    </div>
                    <el-radio-group v-model="coupons.stackable">
                        <el-radio value="Yes" size="large">Yes </el-radio>
                        <el-radio value="No" size="large" aria-selected="true">No </el-radio>
                    </el-radio-group>
                    <p class="tm_coupon_msg">Can this coupon code can be used with other coupon code</p>
                </div>
            </div>
        </div>
        <!-- Additional Information's end -->

        <!--footer -->
        <div class="coupon_footer">
            <button type="button" @click="saveCoupons()" class="tm_coupon_button tm_save">Save Coupon</button>
        </div>
    </div>
</template>

<script>
export default {


    data() {
        return {
            trips: [],
            coupons: {
                amount: "",
                coupon_type: "Percent",
                coupon_code: '',
                max_use: "",
                min_amount: "",
                start_date: "",
                end_date: "",
                stackable: "No",
                coupon_status: "Active",
                allowed_trip_ids : "",
                title: "",

            },
            title : "",
            coupon_code : "",
            amount : "",
        }
    },
    props: {
        coupons_data: {
            type: Object,

        }
    },
    watch: {
        // Its required to watch the destination_data to update the destination object
        coupons_data: {
            handler: function (val) {
                this.coupons = val;
            },
            deep: true
        }
    },
    methods: {

        //==========================
        saveCoupons() {
            this.title = "";
            this.coupon_code = "";
            this.amount = "";
            if (this.coupons.title === "") {
                this.title = "Coupon title is required";
                return;
            }
            if (this.coupons.coupon_code === "") {
                this.coupon_code = "Coupon code is required";
                return;
            }
            if (this.coupons.amount === "") {
                this.amount = "Amount is required";
                return;
            }

            jQuery
                .post(ajaxurl, {
                    action: "tm_coupon",
                    route: "post_coupon",
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                    data: this.coupons,
                   
                }).then((response) => {
                    this.$emit("updateDataAfterNewAdd", this.coupons);
                    this.coupons = {
                        coupon_title: "",
                        coupon_code: "",
                        amount: "",
                    };
                    this.$notify({
                        title: 'Success',
                        message: response.data,
                        type: 'success',
                        position: 'bottom-right',
                    })

                });
        },
        //==========================
        getAllTrips() {
            let that = this;
            this.loading = true;
            jQuery
                .post(ajaxurl, {
                    action: "tm_trips",
                    route: "get_trips",
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.trips = response.data.all_trips;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },
    },
    mounted() {
        this.getAllTrips();

        console.log(this.coupons_data);
        if (this.coupons_data) {
            this.coupons = this.coupons_data;
        }
    }
}
</script>