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
                            <el-option v-for="currency in currencies" :label="currency.name" :value="currency.name">{{ currency.name }}</el-option>
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
            currencies: [
                {
                    id: 1,
                    name: 'USD'
                },
                {
                    id: 2,
                    name: 'EUR'
                },
                {
                    id: 3,
                    name: 'GBP'
                },
                {
                    id: 4,
                    name: 'INR'
                },
                {
                    id: 5,
                    name: 'AUD'
                },
                {
                    id: 6,
                    name: 'CAD'
                },
                {
                    id: 7,
                    name: 'SGD'
                },
                {
                    id: 8,
                    name: 'JPY'
                },
                {
                    id: 9,
                    name: 'CNY'
                },
                {
                    id: 10,
                    name: 'HKD'
                },
                {
                    id: 11,
                    name: 'NZD'
                },
                {
                    id: 12,
                    name: 'SEK'
                },
                {
                    id: 13,
                    name: 'KRW'
                },
                {
                    id: 14,
                    name: 'NOK'
                },
                {
                    id: 15,
                    name: 'TRY'
                },
                {
                    id: 16,
                    name: 'RUB'
                },
                {
                    id: 17,
                    name: 'BRL'
                },
                {
                    id: 18,
                    name: 'ZAR'
                },
                {
                    id: 19,
                    name: 'AED'
                },
                {
                    id: 20,
                    name: 'SAR'
                },
                {
                    id: 21,
                    name: 'QAR'
                },
                {
                    id: 22,
                    name: 'KWD'
                },
                {
                    id: 23,
                    name: 'OMR'
                },
                {
                    id: 24,
                    name: 'BHD'
                },
                {
                    id: 25,
                    name: 'MYR'
                },
                {
                    id: 26,
                    name: 'IDR'
                },
                {
                    id: 27,
                    name: 'PHP'
                },
                {
                    id: 28,
                    name: 'THB'
                },
                {
                    id: 29,
                    name: 'VND'
                }
            ],
            loading: false,
            currency_settings: {
                currency: 'USD',
                currency_position: 'right_space',
                currency_separator: 'none',
            },
            positions: [],
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
                    console.log(response.data.settings, 'response data');
                    that.currency_settings = response?.data?.settings;
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