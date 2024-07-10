<template>
    <div class="tm_form_wrapper">
        <!-- <h1 class="tm_form_title"></h1> -->
        <div class="input-wrapper">
            <p class="form-label" for="name">Difficulty Name *</p>
            <el-input required v-model="difficulty.trip_defaulty_name" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ name_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Difficulty Slug *</p>
            <el-input v-model="difficulty.trip_defaulty_slug" style="width: 100%" placeholder="Please Input" size="large" />
            <p class="error-message">{{ slug_error }}</p>
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Difficulty Description</p>
            <el-input v-model="difficulty.trip_defaulty_desc" style="width: 100%" placeholder="Please Input" size="large" type="textarea" />
        </div>

        <div class="input-wrapper">
            <p class="form-label" for="name">Upload Image</p>
            <ImageUpload :image="difficulty.images" />
        </div>

        <div class="input-wrapper" @click="saveDifficulty()">
            <el-button size="large" type="primary">Save Difficulty</el-button>
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
            difficulty: {
                trip_defaulty_name: "",
                trip_defaulty_slug: "",
                trip_defaulty_desc: "",
                images: {}
            },
            name_error: "",
            slug_error: ""
        }
    },

    props: {
        difficulty_data: {
            type: Object,
        }
    },
    watch: {
        // Its required to watch the difficulty_data to update the destination object
        difficulty_data: {
            handler: function (val) {
                this.difficulty = val;
            },
            deep: true
        }
    },
  
    methods: {
        saveDifficulty() {
            this.name_error = "";
            this.slug_error = "";
            if (this.difficulty.trip_defaulty_name === "") {
                this.name_error = "Name is required";
                return;
            }
            if (this.difficulty.trip_defaulty_slug === "") {
                this.slug_error = "Slug is required";
                return;
            }

            jQuery
            .post(ajaxurl, {
                action: "tm_difficulty",
                route: "post_difficulty",
                tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                data: this.difficulty
            }).then((response) => {
                this.$emit("updateDataAfterNewAdd", this.difficulty);
                this.difficulty = {
                    trip_defaulty_name: "",
                    trip_defaulty_slug: "",
                    trip_defaulty_desc: "",
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
        console.log(this.difficulty_data);
        if (this.difficulty_data) {
            this.difficulty = this.difficulty_data;
        }
    }
  
}
</script>