<template>
    <div class="trip_over_view_wrapper">
        <h2 class="section-title">Overview</h2>
        <app-card :title="'Trip Description'" style="margin-top: 20px;">
            <template v-slot:body>
                <div class="input-wrapper">
                    <p class="form-label">Section Title</p>
                    <el-input v-model="meta.general.description.section_title" size="large" />
                </div>

                <div class="input-wrapper">
                    <p class="form-label">Trip Description</p>
                    <el-input v-model="meta.general.description.description" type="textarea" size="large" />
                </div>
            </template>
        </app-card>

        <app-card :title="'Trip Highlights'" style="margin-top: 20px;">
            <template v-slot:body>
                <div class="input-wrapper">
                    <p class="form-label">Section Title</p>
                    <el-input v-model="meta.general.description.trip_highlights.title" size="large" />
                </div>

                <div class="input-wrapper">
                    <p class="form-label">Trip Highlights</p>
                    <Options :options="meta.general.description.trip_highlights.options" />
                </div>
            </template>
        </app-card>
    </div>
</template>
<script>
import AppCard from "@/components/AppCard.vue";
import Options from "@/components/AppOptions.vue";
export default {
    name: 'OverView',
    components: {
        AppCard,
        Options
    },
    props: {
        meta: {
            type: Object,
            default: () => { }
        }
    }
}
</script>