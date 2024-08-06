<template>
    <AppCard  :title="route.meta.title" v-if="checkHasData(payment_settings)">
        <template v-slot:actions>
            <div style="display: flex; align-items: center; gap: 4px;">
                <el-switch
                    v-model="payment_settings.is_active.value"
                    size="medium"
                    active-value="yes"
                    inactive-value="no"
                ></el-switch>
            </div>
        </template>
        <template v-slot:body>
            <div class="payment_settings_form">
                <div v-for="(payment_setting, key) in payment_settings" :key="key">

                    <div v-if="key == 'payment_mode'" class="payment_settings_form_group">
                        <p class="form_label">{{ payment_setting.label }}</p>
                        <el-radio-group v-model="payment_setting.value">
                            <el-radio v-for="(option, opt_key) in payment_setting.options"  :label="opt_key" :key="opt_key">{{option}}</el-radio>
                        </el-radio-group>
                    </div>

                    <div v-else-if="payment_settings.payment_mode.value == 'live' && (key == 'live_store_id' || key == 'live_store_pass')" class="payment_settings_form_group">
                        <p class="form_label">{{ payment_setting.label }}</p>
                        <el-input size="large" v-model="payment_setting.value" :placeholder="payment_setting.placeholder"></el-input>
                    </div>

                    <div v-else-if="payment_settings.payment_mode.value == 'test' && (key == 'test_store_id' || key == 'test_store_pass')" class="payment_settings_form_group">
                        <p class="form_label">{{ payment_setting.label }}</p>
                        <el-input size="large" v-model="payment_setting.value" :placeholder="payment_setting.placeholder"></el-input>
                    </div>

                    <div v-else-if="payment_setting.type == 'html_attr'" style="margin-top: 20px;">
                        <div v-html="payment_setting.value"></div>
                    </div>
                </div>

                <div class="payment_settings_form_group">
                    <el-button type="primary" @click="savePaymentSettings">Save</el-button>
                </div>
            </div>
        </template>
    </AppCard>
</template>

<script>
import AppCard from '@/components/AppCard.vue';
export default {
    components: {
        AppCard
    },
    props: {
        route: {
            type: Object,
            required: true
        },
        payment_settings: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            ajaxurl: window.wpTravelManager.ajaxurl,
            nonce: window.wpTravelManager.tm_admin_nonce,
            loading: false
        };
    },
    methods: {
        checkHasData(data) {
            return Object.keys(data).length > 0;
        },
        savePaymentSettings() {
            this.loading = true;
            jQuery.get(ajaxurl, {
                action: 'get_payment_settings',
                route: 'savePaymentSettings',
                gateway: this.route.name,
                tm_admin_nonce: this.nonce,
                settings: this.payment_settings
            }).then((response) => {
                console.log(response);
                // this.payment_settings = response?.data?.settings || {};
                // this.loading = false;
            }).fail((error) => {
                console.log(error);
                this.loading = false;
            });
        }
    },
    mounted() {
        // console.log("this.route wqdgw", this.payment_settings);
        // this.form = this.route.meta.form;
    }
};
</script>