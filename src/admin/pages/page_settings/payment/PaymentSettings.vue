<template>
    <el-tabs v-model="activeName" class="demo-tabs">
         <el-tab-pane v-for="route in routes" :label="route.name" :name="route.name" :key="route.name">
            <div >{{activeName}}</div>
        </el-tab-pane>
    </el-tabs>
    <GlobalPaymentSettings :route_name="activeName" />
</template>
<script>
import GlobalPaymentSettings from './GlobalPaymentSettings.vue';
export default {
    components: {
        GlobalPaymentSettings
    },
    data() {
        return {
            routes: [],
            activeName: 'offline'
        };
    },
    watch: {
        activeName() {
            this.$router.push('/settings/payment/' + this.activeName);
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