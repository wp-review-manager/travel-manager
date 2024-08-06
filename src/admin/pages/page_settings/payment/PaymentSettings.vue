<template>
    <el-tabs v-model="activeName" class="trm_tabs trm_payment_settings_wrapper">
         <el-tab-pane v-for="route in routes" :label="route.meta.title" :name="route.name" :key="route.name">
            <GlobalPaymentSettings v-loading="loading" :route="route" :payment_settings="payment_settings" />
        </el-tab-pane>
    </el-tabs>
</template>
<script>
import { get } from 'lodash';
import GlobalPaymentSettings from './GlobalPaymentSettings.vue';
export default {
    components: {
        GlobalPaymentSettings
    },
    data() {
        return {
            routes: [],
            ajaxurl: window.wpTravelManager.ajaxurl,
            nonce: window.wpTravelManager.tm_admin_nonce,
            activeName: 'offline',
            payment_settings: {},
            loading: false
        };
    },
    watch: {
        activeName() {
            this.$router.push('/settings/payment/' + this.activeName);
            this.getPaymentSettings(this.activeName);
        }
    },
    methods: {
        getPaymentSettings(gateWay) {
            this.loading = true;
            jQuery.get(ajaxurl, {
                action: 'get_payment_settings',
                route: 'getPaymentSettings',
                gateway: gateWay,
                tm_admin_nonce: this.nonce
            }).then((response) => {
                this.payment_settings = response?.data?.settings || {};
                this.loading = false;
            }).fail((error) => {
                console.log(error);
                this.loading = false;
            });
        }
    },
    mounted() {
        this.routes = window.wpTravelManager.payment_routes;
        let activeRoute = this.$route.name == 'payment-settings' ? this.routes[0].name : this.$route.name;
        this.activeName = activeRoute;
        this.$router.push('/settings/payment/' + activeRoute);
    }
};
</script>

<style>
.demo-tabs>.el-tabs__content {
    padding: 32px;
    color: #6b778c;
    font-size: 32px;
    font-weight: 600;
}
</style>