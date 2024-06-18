<template>
    <div class="tm_form_wrapper">
        <!-- <h1 class="tm_form_title"></h1> -->
        <div class="input-wrapper">
            <p class="form-label" for="name">Name *</p>
            <el-input required v-model="destination.place_name" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ name_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Slug *</p>
            <el-input v-model="destination.place_slug" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ slug_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Description</p>
            <el-input v-model="destination.place_desc" style="width: 100%" placeholder="Please Input" size="large" type="textarea" />
        </div>

        <div class="input-wrapper">
            <p class="form-label" for="name">Upload Image</p>
            <ImageUpload :image="destination.images" />
        </div>

        <div class="input-wrapper" @click="saveDestination()">
            <el-button size="large" type="primary">Save Destination</el-button>
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
            destination: {
                place_name: "",
                place_slug: "",
                place_desc: "",
                images: {}
            },
            name_error: "",
            slug_error: ""
        }
    },
    props: {
        destination_data: {
            type: Object,
        }
    },
    watch: {
        destination_data: {
            handler: function (val) {
                this.destination = val;
            },
            deep: true
        }
    },
    methods: {
        saveDestination() {
            this.name_error = "";
            this.slug_error = "";
            if (this.destination.place_name === "") {
                this.name_error = "Name is required";
                return;
            }
            if (this.destination.place_slug === "") {
                this.slug_error = "Slug is required";
                return;
            }

            jQuery
            .post(ajaxurl, {
                action: "tm_destinations",
                route: "post_destinations",
                tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                data: this.destination
            }).then((response) => {
                this.$emit("updateDataAfterNewAdd", this.destination)
                this.$notify({
                    title: 'Success',
                    message: response.data,
                    type: 'success',
                    position: 'bottom-right',
                })
                console.log(response);
            });
        }
    },
    mounted() {
        console.log(this.destination_data);
        if (this.destination_data) {
            this.destination = this.destination_data;
        }
    }
}
</script>