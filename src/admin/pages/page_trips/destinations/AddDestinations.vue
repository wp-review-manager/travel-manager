<template>
    <div class="tm_form_wrapper">
        <h1 class="tm_form_title">Add New Destinations</h1>
        <div class="input-wrapper">
            <p class="form-label" for="name">Name</p>
            <el-input v-model="input1" style="width: 100%" placeholder="Please Input" size="large" />
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Slug</p>
            <el-input v-model="input1" style="width: 100%" placeholder="Please Input" size="large" />
        </div>
        <div class="input-wrapper">
            <p class="form-label" for="name">Description</p>
            <el-input v-model="input1" style="width: 100%" placeholder="Please Input" size="large" type="textarea" />
        </div>

        <div class="input-wrapper">
            <p class="form-label" for="name">Upload Image</p>
            <ImageUpload :is_multiple="true" :image="images" />
        </div>

    </div>
</template>

<script>
import ImageUpload from "@/components/AppImageUpload.vue"
export default {
    components: {
        ImageUpload
    },
    data() {
        return {
            input1: '',
            images: [],
        }
    }
}
</script>