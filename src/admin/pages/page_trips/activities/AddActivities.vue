<template>
    <div class="tm_form_wrapper">
        <!-- <h1 class="tm_form_title"></h1> -->
        <div class="input-wrapper">
            <p class="form-label" for="name">Name *</p>
            <el-input required v-model="activities.trip_activity_name" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ name_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Slug *</p>
            <el-input v-model="activities.trip_activity_slug" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ slug_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Description</p>
            <el-input v-model="activities.trip_activity_desc" style="width: 100%" placeholder="Please Input" size="large" type="textarea" />
        </div>

        <div class="input-wrapper">
            <p class="form-label" for="name">Upload Image</p>
            <ImageUpload :image="activities.images" />
        </div>

        <div class="input-wrapper" @click="saveActivities()">
            <el-button size="large" type="primary">Save Activities</el-button>
        </div>

    </div>
</template>

<script>
import ImageUpload from "@/components/AppImageUpload.vue";

export default {
    components: {
        ImageUpload
    },
    data() {
        return {
            activities: {
                trip_activity_name: "",
                trip_activity_slug: "",
                trip_activity_desc: "",
                images: {}
            },
            name_error: "",
            slug_error: ""
        }
    },
    props: {
        activities_data: {
            type: Object,
        }
    },
    watch: {
        // Its required to watch the destination_data to update the destination object
        activities_data: {
            handler: function (val) {
                this.activities = val;
            },
            deep: true
        }
    },
   
    methods: {
        saveActivities() {
            this.name_error = "";
            this.slug_error = "";
            if (this.activities.trip_activity_name === "") {
                this.name_error = "Name is required";
                return;
            }
            if (this.activities.trip_activity_slug === "") {
                this.slug_error = "Slug is required";
                return;
            }

            jQuery
            .post(ajaxurl, {
                action: "tm_activities",
                route: "post_activities",
                tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                data: this.activities
            }).then((response) => {
                this.$emit("updateDataAfterNewAdd", this.activities);
                this.activities = {
                    trip_activity_name: "",
                    trip_activity_slug: "",
                    trip_activity_desc: "",
                    images: {}
                };
                this.$notify({
                    title: 'Success',
                    message: response.data,
                    type: 'success',
                    position: 'bottom-right',
                })
                
            });
        }
    },
    mounted() {
        console.log(this.activities_data);
        if (this.activities_data) {
            this.activities = this.activities_data;
        }
    }
  
}
</script>