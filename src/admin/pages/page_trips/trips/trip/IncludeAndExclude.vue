<template>
    <div class="tm-include-exclude-wrapper">
        <!-- <h2 class="section-title">Includes / Excludes</h2> -->

        <app-card :title="'Includes / Excludes'" :sub_title="' '">
            <template v-slot:actions>
                <el-switch v-model="meta.inc_exc.enable" size="large" active-value="yes" inactive-value="no" />
            </template>

            <template v-slot:body v-if="meta.inc_exc.enable == 'yes'">
                <div class="tm_form_wrapper">
                    <div class="input-wrapper">
                        <p class="form-label">Section Title</p>
                        <el-input v-model="meta.inc_exc.section_title" style="width: 100%"
                            placeholder="Enter Cost Tab Section Title" size="large" />
                    </div>
                </div>
                <AppCard class="tm_label_card" :title="'Includes'" style="margin: 20px; margin-top: 0;">
                    <template #body>
                        <div class="input-wrapper">
                            <p class="form-label">Section Title</p>
                            <el-input v-model="meta.inc_exc.includes.title" style="width: 100%"
                                placeholder="Enter Cost Tab Section Title" size="large" />
                        </div>
                        <Options :options="meta.inc_exc.includes.services" />
                    </template>
                </AppCard>

                <AppCard class="tm_label_card" :title="'Excludes'" style="margin: 20px; margin-top: 0;">
                    <template #body>
                        <div class="input-wrapper">
                            <p class="form-label">Section Title</p>
                            <el-input v-model="meta.inc_exc.excludes.title" style="width: 100%"
                                placeholder="Enter Cost Tab Section Title" size="large" />
                        </div>
                        <Options :options="meta.inc_exc.excludes.services" />
                    </template>
                </AppCard>
            </template>

        </app-card>
        <!-- //-------------------------- -->


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