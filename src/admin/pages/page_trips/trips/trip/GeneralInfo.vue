<template>
    <div class="tm-trip-general-section">
        <h2 class="section-title">General</h2>
        <div class="tm_form_wrapper">
            <div class="input-wrapper">
                <p class="form-label" for="name">Trip Title *</p>
                <el-input v-model="trip_info.post_title" style="width: 100%" placeholder="Enter Trip Title" size="large" />
                <!-- <p class="error-message">{{ slug_error }}</p> -->
            </div>

            <div class="input-wrapper">
                <p class="form-label" for="name">Trip Code *</p>
                <el-input v-model="meta.general.trip_code" style="width: 100%" placeholder="Enter Unique Trip Code , Ex: WTE-4606" size="large" />
                <!-- <p class="error-message">{{ slug_error }}</p> -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            trip_id: null,
            meta:{
                general: {
                    trip_code: ''
                }
            }
        }
    },
    props: {
        trip_info: {
            type: Object,
            default: () => {}
        }
    },
    mounted() {
        this.trip_id = this.$route.params.id;
    }
}
</script>