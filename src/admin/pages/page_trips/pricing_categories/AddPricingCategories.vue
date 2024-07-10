<template>
    <div class="tm_form_wrapper">
        <!-- <h1 class="tm_form_title"></h1> -->
        <div class="input-wrapper">
            <p class="form-label" for="name">Name *</p>
            <el-input required v-model="pricing_categories.pricing_categories_name" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ name_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Slug *</p>
            <el-input v-model="pricing_categories.pricing_categories_slug" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ slug_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Description</p>
            <el-input v-model="pricing_categories.pricing_categories_desc" style="width: 100%" placeholder="Please Input" size="large" type="textarea" />
        </div>

        <div class="input-wrapper">
            <p class="form-label" for="name">Upload Image</p>
            <ImageUpload :image="pricing_categories.images" />
        </div>

        <div class="input-wrapper" @click="savePricingCategories()">
            <el-button size="large" type="primary">Save Pricing Categories</el-button>
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
            pricing_categories: {
                pricing_categories_name: "",
                pricing_categories_slug: "",
                pricing_categories_desc: "",
                images: {}
            },
            name_error: "",
            slug_error: ""
        }
    },
    props: {
        pricing_categories_data: {
            type: Object,
            
        }
    },
    watch: {
        // Its required to watch the destination_data to update the destination object
        pricing_categories_data: {
            handler: function (val) {
                this.pricing_categories = val;
            },
            deep: true
        }
    },
  
    methods: {
        savePricingCategories() {
            this.name_error = "";
            this.slug_error = "";
            if (this.pricing_categories.pricing_categories_name === "") {
                this.name_error = "Name is required";
                return;
            }
            if (this.pricing_categories.pricing_categories_slug === "") {
                this.slug_error = "Slug is required";
                return;
            }

            jQuery
            .post(ajaxurl, {
                action: "tm_pricing_categories",
                route: "post_pricing_categories",
                tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                data: this.pricing_categories
            }).then((response) => {
                this.$emit("updateDataAfterNewAdd", this.pricing_categories);
                this.pricing_categories = {
                    pricing_categories_name: "",
                    pricing_categories_slug: "",
                    pricing_categories_desc: "",
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
        console.log(this.pricing_categories_data);
        if (this.pricing_categories_data) {
            this.pricing_categories = this.pricing_categories_data;
        }
    }
}
</script>