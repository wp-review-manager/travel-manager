<template>
    <div class="tm-trip-wrapper">
        <SideNavBar class="tm-settings-navbar" :width="'220px'"  :routes="routes"/>
        <div class="tm-trip-content-wrapper">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import SideNavBar from "@/components/SideNavBar.vue";

export default {
    components: {
        SideNavBar
    },
    data () {
        return {
            routes: [
                {
                    label: 'General Settings',
                    icon: 'mdi-bus',
                    to: '/settings/'
                },
                {
                    label: 'Payment Settings',
                    icon: 'mdi-map-marker',
                    to: '/settings/payment'
                },
            ]
        }
    },

}
</script>