<template>
    <div class="general_settings" v-loading="loading">
        <div class="currency_settings">
            <app-card title="Currency Settings">
                <template #body>
                    <div class="input-wrapper">
                        <div class="tooltip-label">
                            <p class="form-label" for="name">Currency *</p>
                            <el-tooltip effect="dark"
                                content="You can globally mange your currency from here" placement="top">
                                <el-icon>
                                    <InfoFilled />
                                </el-icon>
                            </el-tooltip>
                        </div>
                        <el-select size="large" name="currency" id="currency" v-model="currency_settings.currency">
                            <el-option v-for="(currency_key, currency_label) in currencies" :label="currency_key" :value="currency_label"></el-option>
                        </el-select>
                    </div>

                    <div class="input-wrapper">
                        <div class="tooltip-label">
                            <p class="form-label" for="name">Currency Sign Position *</p>
                            <el-tooltip effect="dark"
                                content="You can globally mange your currency position from here" placement="top">
                                <el-icon>
                                    <InfoFilled />
                                </el-icon>
                            </el-tooltip>
                        </div>
                        <el-radio-group v-model="currency_settings.currency_position">
                            <el-radio v-for="position in positions" :value="position.value">{{ position.label }}</el-radio>
                        </el-radio-group>
                    </div>

                    <div class="input-wrapper">
                        <div class="tooltip-label">
                            <p class="form-label" for="name">Currency Separators *</p>
                            <el-tooltip effect="dark"
                                content="You can globally mange your currency separators from here" placement="top">
                                <el-icon>
                                    <InfoFilled />
                                </el-icon>
                            </el-tooltip>
                        </div>
                        <el-select size="large" name="currency" id="currency" v-model="currency_settings.currency_separator">
                            <el-option v-for="currency_separator in currency_separators" :label="currency_separator.label" :value="currency_separator.value">{{ currency_separator.label }}</el-option>
                        </el-select>
                    </div>
                </template>
            </app-card>
        </div>

        <div class="save_settings">
            <el-button type="primary" @click="postCurrencySettings" :loading="loading">Save Settings</el-button>
        </div>
    </div>
</template>

<script>
import AppCard from "@/components/AppCard.vue";

export default {
    components: {
        AppCard
    },
    data() {
        return {
            currencies: window.wpTravelManager.currencies,
            loading: false,
            currency_settings: {
                currency: 'USD',
                currency_position: 'right_space',
                currency_separator: 'none',
            },
            currency_separators: [
                { label: 'None (10000.00)', value: 'none' },
                { label: 'Comma (10,000.00)', value: 'comma' },
                { label: 'Dot (10.000,00)', value: 'dot' },
            ]
        }
    },

    computed: {
        positions() {
            return [
                { label: `Left (${this.currency_settings.currency}1000)`, value: 'left' },
                { label: `Left Space (${this.currency_settings.currency} 1000)`, value: 'left_space' },
                { label: `Right (1000${this.currency_settings.currency} )`, value: 'right' },
                { label: `Right Space (1000 ${this.currency_settings.currency} )`, value: 'right_space' },
            ];
        }
    },

    methods: {
        getCurrencies() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_settings",
                    route: "get_settings",
                    option_key: 'trm_currency_settings',
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.currency_settings = response?.data?.settings || that.currency_settings;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },

        postCurrencySettings() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_settings",
                    route: "post_settings",
                    option_key: 'trm_currency_settings',
                    currency_settings: this.currency_settings,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    this.$notify({
                        message: 'Settings saved successfully',
                        type: "success",
                        position: "bottom-right"
                    });
                }).fail((error) => {
                    this.$notify({
                        message: response.message,
                        type: "error",
                        position: "bottom-right"
                    });
                }).always(() => {
                    that.loading = false;
                })
        }
    },

    mounted() {
        this.getCurrencies();
    }
}
</script>