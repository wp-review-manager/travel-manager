<template>
    <div class="tm-trip-wrapper">
        <SideNavBar :routes="routes"/>
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
                    label: 'All Trips',
                    icon: 'mdi-bus',
                    to: '/trips/'
                },
                {
                    label: 'Destinations',
                    icon: 'mdi-map-marker',
                    to: '/trips/destinations'
                },
                {
                    label: 'Attributes',
                    icon: 'mdi-settings',
                    to: '/trips/attributes'
                },
                {
                    label: 'Travel Categories',
                    icon: 'mdi-settings',
                    to: '/trips/travel-categories'
                },
                {
                    label: "Activities",
                    icon: "mdi-settings",
                    to: "/trips/activities"
                },
                {
                    label: "Difficulty",
                    icon: "mdi-settings",
                    to: "/trips/difficulty-levels"
                },
                {
                    label: "Pricing Categories",
                    icon: "mdi-settings",
                    to: "/trips/pricing-categories"
                } 
            ]
        }
    },

}
</script>00