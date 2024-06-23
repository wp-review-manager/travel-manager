<template>
    <div class="tm-include-exclude-wrapper">
        <h2 class="section-title">Trip Info</h2>
        <div class="tm_form_wrapper">
            <div class="input-wrapper">
                <p class="form-label">Section Title</p>
                <el-input v-model="meta.trip_info.title" style="width: 100%" placeholder="Enter Cost Tab Section Title" size="large" />
            </div>
        </div>

       

    </div>
</template>

<script>
import AppCard from "@/components/AppCard.vue";
import Options from "@/components/AppOptions.vue";

export default {
    components: {
        AppCard,
        Options
    },
    props: {
        meta: {
            type: Object,
            required: true
        }
    }
}
</script>