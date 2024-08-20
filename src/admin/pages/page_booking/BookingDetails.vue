<template>
    <div class="container">
        <!-- ====================== -->
        <div class="tm_head_info">
            <div class="tm_entry_title">
                <div class="tm_entries_url"><a href="#">
                        <span>Form Entries</span></a></div>
                <div class="tm_back_icon">
                    <div class="icon"> > </div>
                </div>
                <div class="tm_payhead_title"> Blank Form (#68) #2</div>
            </div>
        </div>
        <!-- ====================== -->
        <div class="tm_entry_layout">
            <!-- ================= -->
            <div class="tm_entry_left">
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
                                    <span class="pay_amount">$10</span>
                                    <span class="tm_pay_status_pending">
                                        <div class="icon">
                                            <Icon icon="tm-pending" />
                                        </div>
                                        pending
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- ================= -->
                        <div class="tm_header_right">
                            <div class="tm_payment_status"><span>
                                    Change Payment Status
                                </span>
                            </div> <!---->
                            <div class="tm_payment_vendor">
                                <span class="tm_pay_method_offline"
                                    style="text-transform: capitalize; padding: 1px 7px;">
                                    offline
                                </span>
                            </div>

                        </div>

                    </div>
                    <div class="tm_head_bottom">
                        <div class="tm_info_block">
                            <div class="tm_info_header">Name</div>
                            <div class="tm_info_value">
                                <span>tm@gmail.com</span>
                            </div>
                        </div>
                        <div class="tm_info_block">
                            <div class="tm_info_header">Name</div>
                            <div class="tm_info_value">
                                <span>tm@gmail.com</span>
                            </div>
                        </div>
                        <div class="tm_info_block">
                            <div class="tm_info_header">Name</div>
                            <div class="tm_info_value">
                                <span>tm@gmail.com</span>
                            </div>
                        </div>
                        <div class="tm_info_block">
                            <div class="tm_info_header">Name</div>
                            <div class="tm_info_value">
                                <span>tm@gmail.com</span>
                            </div>
                        </div>
                        <div class="tm_info_block">
                            <div class="tm_info_header">Name</div>
                            <div class="tm_info_value">
                                <span>tm@gmail.com</span>
                            </div>
                        </div>
                        <div class="tm_info_block">
                            <div class="tm_info_header">Name</div>
                            <div class="tm_info_value">
                                <span>tm@gmail.com</span>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- ====================== -->
                <div class="tm_entry_info_box">
                    <div class="tm_entry_info_header">
                        <div class="tm_info_box_header">Form Entry Data</div>
                    </div>
                    <div class="tm_entry_info_body">
                        <div class="tm_entry_details">
                            <div class="tm_each_entry">
                                <div class="tm_entry_label">Name</div>
                                <div class="tm_entry_value">Nitesh Dash</div>
                            </div>
                            <div class="tm_each_entry">
                                <div class="tm_entry_label">Email</div>
                                <div class="tm_entry_value">nitesh@gmail.com</div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- ================================ -->
                <div class="tm_entry_transactions">
                    <div class="tm_info_header">
                        <div class="tm_info_box_header">Transaction Details</div>
                    </div>
                    <div class="tm_info_body">
                        <div class="tm_entry_transaction">
                            <ul class="tm_list_items">
                                <li>
                                    <div class="wpf_list_header">ID</div>
                                    <div class="wpf_list_value">2</div>
                                </li>

                                <li>
                                    <div class="wpf_list_header">Payment Method</div>
                                    <div class="wpf_list_value"><span>
                                            offline
                                        </span></div>
                                </li> <!---->
                                <li>
                                    <div class="wpf_list_header">Payment Total</div>
                                    <div class="wpf_list_value">$10</div>
                                </li>
                                <li>
                                    <div class="wpf_list_header">Payment Status</div>
                                    <div class="wpf_list_value wpf_pay_status_pending">
                                        pending
                                    </div>
                                </li>
                                <li>
                                    <div class="wpf_list_header">Date</div>
                                    <div class="wpf_list_value">
                                        Aug 10, 2024 20:08 PM
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ================================ -->
            </div>
            <!-- ================================== -->
            <div class="tm_entry_right">

            </div>

        </div>

    </div>
</template>

<script>
import Icon from "@/components/Icons/AppIcon.vue"
export default {
    components: {
        Icon
    },

}
</script>