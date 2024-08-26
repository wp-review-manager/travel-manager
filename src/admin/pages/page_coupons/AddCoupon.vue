<template>
    <div class="tm_coupon_container">
        <!-- tm_coupon_header start-->
        <div class="tm_coupon_header">
            <div class="tm_coupon_right">
                <div class="tm_coupon_abel">
                    <label>Coupon Title</label>
                </div>
                <input type="text" autocomplete="off" placeholder="Coupon Title" class="tm_coupon_input">
                <p class="tm_coupon_msg">The name of this discount</p>
            </div>
            <div class="tm_coupon_left">
                <div class="tm_coupon_abel">
                    <label>Coupon Code</label>
                </div>
                <input type="text" autocomplete="off" placeholder="Coupon Code" class="tm_coupon_input">
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
                    <select autocomplete="off" placeholder="Discount Type" class="tm_coupon_input">
                        <option>Fixed</option>
                        <option>Percent</option>
                    </select>
                    <p class="tm_coupon_msg">The name of this discount</p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Discount Amount / Percent</label>
                    </div>
                    <input type="number" autocomplete="off" placeholder="Discount Amount / Percent"
                        class="tm_coupon_input">
                    <p class="tm_coupon_msg">Enter the discount percentage. 10 = 10% </p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Min Purchase Amount</label>
                    </div>
                    <input type="number" autocomplete="off" placeholder="Min Purchase Amount" class="tm_coupon_input">
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
                    <input type="number" autocomplete="off" placeholder="Limit Per User(Email Based)"
                        class="tm_coupon_input">
                    <p class="tm_coupon_msg">Enter the usage limit for a single user / Leave '0' for no limitation.</p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Start Date</label>
                    </div>
                    <input type="date" autocomplete="off" placeholder="Start Date" class="tm_coupon_input">
                    <p class="tm_coupon_msg">Enter the start date for this discount code in the format of yyyy-mm-dd.
                        For no start date, leave blank.</p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>End Date</label>
                    </div>
                    <input type="date" autocomplete="off" placeholder="End Date" class="tm_coupon_input">
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
                        <label>Applicable Forms</label>
                    </div>
                    <select autocomplete="off" placeholder="Discount Type" class="tm_coupon_input">
                        <option>Blank form #01</option>
                        <option>Blank form #013 </option>
                    </select>
                    <p class="tm_coupon_msg">Leave blank for applicable for all payment forms</p>
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Status</label>
                    </div>
                    <div class="">
                        <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                        <label for="vehicle1" style="padding-right: 50px; color: #2a2929; font-weight: 600;"> Active</label>
                        <input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
                        <label for="vehicle2" style=" color: #2a2929; font-weight: 600;"> Inactive</label>
                    </div>
                  
                    
                </div>
                <div class="tm_coupon_left">
                    <div class="tm_coupon_abel">
                        <label>Stackable  </label>
                    </div>
                    <div class="">
                        <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                        <label for="vehicle1" style="padding-right: 50px; color: #2a2929; font-weight: 600;"> Yes</label>
                        <input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
                        <label for="vehicle2" style=" color: #2a2929; font-weight: 600;"> No</label>
                    </div>
                    <p class="tm_coupon_msg">Can this coupon code can be used with other coupon code</p>
                </div>
            </div>
        </div>
        <!-- Additional Information's end -->

        <!--footer -->
        <div class="coupon_footer">
            <button type="button" class="tm_coupon_button" style="margin-right: 15px;">Cancel</button>
            <button type="button" class="tm_coupon_button tm_save">Save Coupon</button>
        </div>
    </div>
</template>