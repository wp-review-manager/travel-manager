<template>
    <div class="tm_form_wrapper">
        <!-- <h1 class="tm_form_title"></h1> -->
        <div class="input-wrapper">
            <p class="form-label" for="name">Category Name *</p>
            <el-input required v-model="categories.trip_category_name" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ name_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Category Slug *</p>
            <el-input v-model="categories.trip_category_slug" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ slug_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Category Description</p>
            <el-input v-model="categories.trip_category_desc" style="width: 100%" placeholder="Please Input" size="large" type="textarea" />
        </div>

        <div class="input-wrapper">
            <p class="form-label" for="name">Upload Image</p>
            <ImageUpload :image="categories.images" />
        </div>

        <div class="input-wrapper" @click="saveCategory()">
            <el-button size="large" type="primary">Save Category</el-button>
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
            categories: {
                trip_category_name: "",
                trip_category_slug: "",
                trip_category_desc: "",
                images: {}
            },
            name_error: "",
            slug_error: ""
        }
    },
    props: {
        categories_data: {
            type: Object,
        }
    },
    watch: {
        // Its required to watch the categories_data to update the destination object
        categories_data: {
            handler: function (val) {
                this.categories = val;
            },
            deep: true
        }
    },
    methods: {
        saveCategory() {
            this.name_error = "";
            this.slug_error = "";
            if (this.categories.trip_category_name === "") {
                this.name_error = "Name is required";
                return;
            }
            if (this.categories.trip_category_slug === "") {
                this.slug_error = "Slug is required";
                return;
            }

            jQuery
            .post(ajaxurl, {
                action: "tm_categories",
                route: "post_categories",
                tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                data: this.categories
            }).then((response) => {
                this.$emit("updateDataAfterNewAdd", this.categories);
                this.categories = {
                    place_name: "",
                    place_slug: "",
                    place_desc: "",
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
        console.log(this.categories_data);
        if (this.categories_data) {
            this.categories = this.categories_data;
        }
    }
}
</script>