<template>
    <div class="tm-trip-edit-pricing">
        <div style="padding: 20px;">
            <div class="tm-trip-edit-pricing-header">
                <h2>Edit Pricing</h2>
            </div>
            <div class="enable_booking_date">
                <p>Enable Booking Date</p>
                <el-switch
                    v-model="package_info.available_booking_date.enable"
                    size="medium"
                    active-value="yes"
                    inactive-value="no"
                ></el-switch>
            </div>

            <div v-if="package_info.available_booking_date.enable == 'yes'" class="booking_date">
                <div class="input-wrapper">
                    <p class="form-label">Start Date *</p>
                    <el-date-picker
                        v-model="package_info.available_booking_date.start_date"
                        type="date"
                        placeholder="Select Date"
                        size="large"
                        style="width: 100%"
                    ></el-date-picker>
                </div>
                <div class="input-wrapper">
                    <p class="form-label">End Date *</p>
                    <el-date-picker
                        v-model="package_info.available_booking_date.end_date"
                        type="date"
                        placeholder="Select Date"
                        size="large"
                        style="width: 100%"
                    ></el-date-picker>
                </div>
            </div>

            <div class="input-wrapper" style="width: 50%; margin-bottom: 20px;">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <p class="form-label">Package  Quantity</p>
                    <el-tooltip effect="dark" content="Please enter the number of packages available for this trip. If there is no limit, type 0" placement="top">
                        <el-icon>
                            <InfoFilled />
                        </el-icon>
                    </el-tooltip>
                </div>
                <el-input v-model="package_info.package_quantity" style="width: 100%" type="number" min="0" size="large" />
            </div>
        </div>
        <AppCard class="tm_label_card" :title="`Pricing Categories - ${ price.label }`" v-for="(price, index) in package_info.pricing">
            <template v-slot:actions>
                <div style="display: flex; align-items: center; gap: 4px;">
                    <el-switch
                        v-model="price.enable"
                        size="medium"
                        active-value="yes"
                        inactive-value="no"
                    ></el-switch>
                    <div @click="deletePricing(index)" class="delete-icon" style="font-size: 22px; cursor: pointer;">
                        <Icon icon="tm-delete"  />
                    </div>
                </div>
            </template>
            <template v-slot:body>
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="input-wrapper" style="width: 50%;">
                        <p class="form-label">Price Per *</p>
                        <el-select size="large" placeholder="Select Price Per" v-model="price.pricing_type">
                            <el-option value="person">Person</el-option>
                            <el-option value="group">Group</el-option>
                        </el-select>
                    </div>
                    <div class="input-wrapper" style="width: 50%;">
                        <p class="form-label">Price Label *</p>
                        <el-select size="large" placeholder="Select Price Label" v-model="price.label">
                            <el-option value="old">Old</el-option>
                            <el-option value="child">Child</el-option>
                            <el-option value="adult">Adult</el-option>
                        </el-select>
                    </div>
                </div>

                <div style="display: flex; gap: 20px;margin-bottom: 20px;">
                    <div class="input-wrapper" style="width: 50%;">
                        <p class="form-label">Tour Price - Regular *</p>
                        <el-input v-model="price.price" style="width: 100%" type="number" min="0" size="large" />
                    </div>
                    <div class="input-wrapper" style="width: 50%;">
                        <p class="form-label">Tour Price- Sales Price *</p>
                        <el-input v-model="price.selling_price" style="width: 100%" type="number" min="0"
                            size="large" />
                    </div>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="input-wrapper" style="width: 50%;">
                        <p class="form-label">Minimum Pax</p>
                        <el-input v-model="price.min_pax" style="width: 100%" type="number" min="0" size="large" />
                    </div>
                    <div class="input-wrapper" style="width: 50%;">
                        <p class="form-label">Maximum Pax</p>
                        <el-input v-model="price.max_pax" style="width: 100%" type="number" min="0"
                            size="large" />
                    </div>
                </div>
            </template>
        </AppCard>

        <div class="add-new-package">
            <el-button @click="addNewPrice" size="large" link type="primary">Add New Price</el-button>
            <Icon icon="tm-plus" />
        </div>
    </div>
</template>

<script>
import AppCard from "@/components/AppCard.vue";
import Icon from "@/components/Icons/AppIcon";
export default {
    components: {
        AppCard,
        Icon
    },
    props: {
        package_info: {
            type: Object,
            default: () => { }
        }
    },
    methods: {
        addNewPrice() {
            this.package_info.pricing.push({
                enable: "yes",
                pricing_type: "person",
                label: "old",
                price: 100,
                selling_price: 90,
                min_pax: 0,
                max_pax: 0
            });
        },
        deletePricing(index) {
            console.log(index);
            this.package_info.pricing.splice(index, 1);
        }
    }
}
</script>