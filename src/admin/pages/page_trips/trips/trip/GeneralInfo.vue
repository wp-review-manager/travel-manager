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
                    <el-input v-model="meta.general.duration.duration" style="width: 100%" type="number" size="large" />
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
                    <el-input v-model="meta.general.nights.duration" style="width: 100%" type="number" size="large" />
                    <el-select v-model="meta.general.nights.type" placeholder="Select Duration Type" size="large" style="width: 240px">
                        <el-option label="Days" value="days" />
                        <el-option label="Hours" value="hours" />
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
                    <div class="input-wrapper" >
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
                            <el-input v-model="meta.general.nights.duration" style="width: 100%" type="number" size="large" />
                            <el-select v-model="meta.general.nights.type" placeholder="Select Duration Type" size="large" style="width: 240px">
                                <el-option label="Days" value="days" />
                                <el-option label="Hours" value="hours" />
                            </el-select>
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
            meta: {
                general: {
                    trip_code: `TM-${this.$route.params.id}`,
                    duration: {
                        type: "days",
                        duration: 2
                    },
                    nights: {
                        type: "nights",
                        duration: 3,
                    },
                    cut_time: {
                        enable: "yes",
                        start_of_date: "2023-11-12",
                        cut_off_date: "2023-11-12",
                    },
                }
            }
        }
    },
    props: {
        trip_info: {
            type: Object,
            default: () => { }
        }
    },
    mounted() {
        this.trip_id = this.$route.params.id;
    }
}
</script>