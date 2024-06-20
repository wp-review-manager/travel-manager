<template>
    <div class="tm-trip-general-section">
        <h2 class="section-title">General</h2>
        <div class="tm_form_wrapper">
            <div class="input-wrapper">
                <p class="form-label" for="name">Trip Title *</p>
                <el-input v-model="trip_info.post_title" style="width: 100%" placeholder="Enter Trip Title"
                    size="large" />
                <!-- <p class="error-message">{{ slug_error }}</p> -->
            </div>

            <div class="input-wrapper">
                <p class="form-label" for="name">Trip Code *</p>
                <el-input v-model="meta.general.trip_code" style="width: 100%"
                    placeholder="Enter Unique Trip Code , Ex: WTE-4606" size="large" />
                <!-- <p class="error-message">{{ slug_error }}</p> -->
            </div>

            <div class="input-wrapper">

                <div class="tooltip-label">
                    <p class="form-label" for="name">Duration *</p>
                    <el-tooltip effect="dark"
                        content="Enter the duration ( number ) for the trip and choose desired unit." placement="top">
                        <el-icon>
                            <InfoFilled />
                        </el-icon>
                    </el-tooltip>
                </div>

                <div class="couple-inputs">
                    <el-input min="0" v-model="meta.general.duration.duration" style="width: 100%" type="number" size="large" />
                    <el-select v-model="meta.general.duration.type" placeholder="Select Duration Type" size="large" style="width: 240px">
                        <el-option label="Days" value="days" />
                        <el-option label="Hours" value="hours" />
                    </el-select>
                </div>

            </div>

            <div class="input-wrapper" v-if="meta.general.duration.type == 'days'">

                <div class="tooltip-label">
                    <p class="form-label" for="name">Nights *</p>
                    <el-tooltip effect="dark"
                        content="Enter the trip duration in nights." placement="top">
                        <el-icon>
                            <InfoFilled />
                        </el-icon>
                    </el-tooltip>
                </div>

                <div class="couple-inputs">
                    <el-input min="0" v-model="meta.general.nights.duration" style="width: 100%" type="number" size="large" />
                    <el-select v-model="meta.general.nights.type" placeholder="Select Duration Type" size="large" style="width: 240px">
                        <el-option label="Days" value="nights" />
                    </el-select>
                </div>

            </div>

            <div style="display: flex; gap: 20px;">
                <div style="width: 100%" class="input-wrapper">
                    <div class="tooltip-label">
                        <p class="form-label" for="name">Set MaxiMum Traveler *</p>
                        <el-tooltip effect="dark"
                            content="Specify the maximum capacity for the trip" placement="top">
                            <el-icon>
                                <InfoFilled />
                            </el-icon>
                        </el-tooltip>
                    </div>
                    <el-input min="0" v-model="meta.general.max_traveler" style="width: 100%" type="number" size="large" />
                </div>

                <div style="width: 100%" class="input-wrapper">
                    <p class="form-label" for="name">Trip Type *</p>
                    <el-select v-model="meta.general.trip_type" placeholder="Select Trip Type" size="large" style="width: 100%">
                        <el-option label="Group" value="group" />
                        <el-option label="Private" value="private" />
                    </el-select>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div style="width: 100%" class="input-wrapper">
                    <p class="form-label" for="name">Trip Category *</p>
                    <el-select v-model="meta.general.trip_category" placeholder="Select Trip Type" size="large" style="width: 100%">
                        <el-option label="Adventure" value="adventure" />
                        <el-option label="Religious" value="religious" />
                        <el-option label="Leisure" value="leisure" />
                        <el-option label="Honeymoon" value="honeymoon" />
                    </el-select>
                </div>

                <div style="width: 100%" class="input-wrapper">
                    <p class="form-label" for="name">Trip Status *</p>
                    <el-select v-model="meta.general.trip_status" placeholder="Select Booking Status" size="large" style="width: 100%">
                        <el-option label="Available to booking" value="available_to_booking" />
                        <el-option label="Booking closed" value="booking_closed" />
                        <el-option label="Booking full" value="booking_full" />
                        <el-option label="Booking not started" value="booking_not_started" />
                    </el-select>
                </div>
            </div>

            <app-card :title="'Enable Trip Schedule Time'" :sub_title="'Enable trip schedule time for bookings. The trip schedule time sets the period during which bookings are allowed, both before and after the specified time.'">
                <template v-slot:actions>
                    <el-switch
                        v-model="meta.general.cut_time.enable"
                        size="large"
                        active-value="yes"
                        inactive-value="no"
                    />
                </template>

                <template v-slot:body v-if="meta.general.cut_time.enable == 'yes'">
                    <div style="display: flex; gap: 20px; align-items: center;">
                    <div class="input-wrapper" style="width: 50%;" >
                        <div class="tooltip-label">
                            <p class="form-label" for="name">Start Of Date *</p>
                            <el-tooltip effect="dark"
                                content="Enter the trip start date" placement="top">
                                <el-icon>
                                    <InfoFilled />
                                </el-icon>
                            </el-tooltip>
                        </div>
                        <el-input type="date" v-model="meta.general.cut_time.start_of_date" style="width: 100%"  size="large" />
                    </div>

                    <div class="input-wrapper" style="width: 50%;">
                        <div class="tooltip-label">
                            <p class="form-label" for="name">End Of Date *</p>
                            <el-tooltip effect="dark"
                                content="Enter the trip end date. If you do not want to set an end date, leave this field empty." placement="top">
                                <el-icon>
                                    <InfoFilled />
                                </el-icon>
                            </el-tooltip>
                        </div>
                        <el-input type="date" v-model="meta.general.cut_time.end_of_date" style="width: 100%"  size="large" />
                    </div>
                    </div>
                </template>
                
            </app-card>

            <app-card :title="'Set Minimum And Maximum Age'" :sub_title="'Enable minimum and maximum age required restriction for booking this trip.'">
                <template v-slot:actions>
                    <el-switch
                        v-model="meta.general.min_max_age.enable"
                        size="large"
                        active-value="yes"
                        inactive-value="no"
                    />
                </template>

                <template v-slot:body v-if="meta.general.min_max_age.enable == 'yes'">
                    <div style="display: flex; gap: 20px; align-items: center;">
                    <div class="input-wrapper" style="width: 50%;" >
                        <p class="form-label" for="name">Minimum Age *</p>
                        <el-input min="0" type="number" v-model="meta.general.min_max_age.min_age" style="width: 100%"  size="large" />
                    </div>

                    <div class="input-wrapper" style="width: 50%;">
                        <p class="form-label" for="name">Maximum Age *</p>
                        <el-input min="0" type="number" v-model="meta.general.min_max_age.max_age" style="width: 100%"  size="large" />
                    </div>
                    </div>
                </template>
                
            </app-card>
        </div>
    </div>
</template>

<script>
import AppCard from "@/components/AppCard.vue";
export default {
    components: {
        AppCard
    },
    data() {
        return {
            trip_id: null,
        }
    },
    props: {
        trip_info: {
            type: Object,
            default: () => { }
        },
        meta: {
            type: Object,
            default: () => { }
        }
    },
    mounted() {
        this.trip_id = this.$route.params.id;
    }
}
</script>