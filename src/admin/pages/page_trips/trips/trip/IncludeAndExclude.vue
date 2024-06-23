<template>
    <div class="tm-include-exclude-wrapper">
        <h2 class="section-title">Includes / Excludes</h2>
        <div class="tm_form_wrapper">
            <div class="input-wrapper">
                <p class="form-label">Section Title</p>
                <el-input v-model="meta.inc_exc.section_title" style="width: 100%" placeholder="Enter Cost Tab Section Title" size="large" />
            </div>
        </div>
        <AppCard :title="'Includes'" style="margin: 20px; margin-top: 0;">
            <template #body>
                <div class="input-wrapper">
                    <p class="form-label">Section Title</p>
                    <el-input v-model="meta.inc_exc.includes.title" style="width: 100%" placeholder="Enter Cost Tab Section Title" size="large" />
                </div>
                <Options :options="meta.inc_exc.includes.services"  />
            </template>
        </AppCard>

        <AppCard :title="'Includes'" style="margin: 20px; margin-top: 0;">
            <template #body>
                <div class="input-wrapper">
                    <p class="form-label">Section Title</p>
                    <el-input v-model="meta.inc_exc.excludes.title" style="width: 100%" placeholder="Enter Cost Tab Section Title" size="large" />
                </div>
                <Options :options="meta.inc_exc.excludes.services"  />
            </template>
        </AppCard>

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