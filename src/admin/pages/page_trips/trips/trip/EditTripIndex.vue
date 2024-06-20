<template>
    <div class="tm-trip-edit-wrapper">
        <div class="tm-trip-edit-header">
            <div class="header-left">
                <router-link to="/trips/">
                    <el-button type="default"><el-icon class="el-icon--left"><Back /></el-icon>Back To All Trips</el-button>
                </router-link>
            </div>
            <div class="header-right">
                <button class="tm-shortcode" v-clipboard="trip_info.shortcode" v-clipboard:success="clipboardSuccessHandler">
                    {{ trip_info.shortcode }}
                </button>
                <el-button type="default">Preview</el-button>
                <el-button class="save-btn" type="primary">Save Info</el-button>
            </div>
        </div>
        <div class="tm-trip-edit-body">
            <SideNavBar class="tm-settings-navbar" :width="'220px'" :routes="routes"/>
            <div class="tm-trip-content-wrapper">
                <router-view :trip_info="trip_info"></router-view>
            </div>
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
            trip_id: null,
            routes: [],
            trip_info: {}
        }
    },
    methods: {
        clipboardSuccessHandler() {
            this.$notify({
                message: "Copied to clipboard",
                type: "success",
                position: "bottom-right"
            });
        },
        getTripInfo(tripId) {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_trips",
                    route: "get_trip_info",
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                    trip_id: tripId
                }).then((response) => {
                    that.trip_info = response.data;
                }).fail((error) => {
                    console.log(error);
                })
        }
    },

    mounted () {
        this.trip_id = this.$route.params.id;
        this.getTripInfo(this.trip_id);
        this.routes = [
                {
                    label: 'General',
                    icon: 'mdi-bus',
                    to: `/trip/${this.trip_id}/edit/`
                },
                {
                    label: "Prices And Dates",
                    icon: "mdi-settings",
                    to: `/trip/${this.trip_id}/edit/prices-and-dates`
                }
            ]
    }

}
</script>