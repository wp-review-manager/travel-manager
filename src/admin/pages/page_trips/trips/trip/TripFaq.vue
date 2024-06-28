<template>
    <div class="tm-include-exclude-wrapper">
        {{ meta.faqs }}
        <h2 class="section-title">FAQs</h2>
        <div class="tm_form_wrapper">
            <div class="input-wrapper">
                <p class="form-label">Section Title</p>
                <el-input  size="large" type="text" class="form-control" v-model="meta.faqs.title" />
            </div>
        </div>

        <AppCard class="tm_label_card" :title="'Itinerary Plan'" style="margin: 20px; margin-top: 0;">
            <template #body>
                <Options :title="'Questions'" :description="'Answer'" :options="meta.faqs.options"  />
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
    },
    data() {
        return {
        }
    }
}
</script>