<template>
    <div class="tm_form_wrapper">
        <!-- <h1 class="tm_form_title"></h1> -->
        <div class="input-wrapper">
            <p class="form-label" for="name">Title *</p>
            <el-input required v-model="attributes.attr_title" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ title_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Slug *</p>
            <el-input v-model="attributes.attr_slug" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ slug_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Description</p>
            <el-input v-model="attributes.attr_desc" style="width: 100%" placeholder="Please Input" size="large" type="textarea" />
        </div>

        <div class="input-wrapper">
            <p class="form-label" for="name">Upload Image</p>
            <ImageUpload :image="attributes.images" />
        </div>

        <div class="input-wrapper" @click="saveAttributes()">
            <el-button size="large" type="primary">Save attributes</el-button>
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
            attributes: {
                attr_title: "",
                attr_slug: "",
                attr_desc: "",
                images: {}
            },
            title_error: "",
            slug_error: ""
        }
    },
    props: {
        attributes_data: {
            type: Object,
        }
    },
    watch: {
        // Its required to watch the attributes_data to update the attributes object
        attributes_data: {
            handler: function (val) {
                this.attributes = val;
            },
            deep: true
        }
    },
    methods: {
        saveAttributes() {
            this.title_error = "";
            this.slug_error = "";
            if (this.attributes.attr_title === "") {
                this.title_error = "Title is required";
                return;
            }
            if (this.attributes.attr_slug === "") {
                this.slug_error = "Slug is required";
                return;
            }

            jQuery
            .post(ajaxurl, {
                action: "tm_attributes",
                route: "post_attributes",
                tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                data: this.attributes
            }).then((response) => {
                this.$emit("updateDataAfterNewAddAttribute", this.attributes);
                this.attributes = {
                    attr_title: "",
                    attr_slug: "",
                    attr_desc: "",
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
        console.log(this.attributes_data);
        if (this.attributes_data) {
            this.attributes = this.attributes_data;
        }
    }
}
</script>

