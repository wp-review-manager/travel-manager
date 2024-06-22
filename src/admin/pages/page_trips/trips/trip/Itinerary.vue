<template>
    <div class="tm-trip-itinerary">
        <h2 class="section-title">Itinerary</h2>
        <div class="tm_form_wrapper">
            <div class="input-wrapper">
                <p class="form-label">Section Title</p>
                <el-input v-model="meta.itinerary.title" style="width: 100%" placeholder="Enter Section Title" size="large" />
            </div>
        </div>

        <AppCard :title="'Itinerary'" style="margin: 20px; margin-top: 0;">
            <template #body>
                <Options :options="meta.itinerary.options"  />
            </template>
        </AppCard>

    </div>
</template>

<script>
import AppCard from "@/components/AppCard.vue";
import Options from "@/pages/page_trips/trips/trip/ItineraryOptions.vue";

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