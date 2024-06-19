<template>
    <div class="tm-trip-edit-wrapper">
        <div class="tm-trip-edit-header">
            <div class="header-left">
                <router-link to="/trips/">
                    <el-button type="default"><el-icon class="el-icon--left"><Back /></el-icon>Back To All Trips</el-button>
                </router-link>
            </div>
            <div class="header-right">
                <button class="tm-shortcode" v-clipboard="'dhjfjhgdshjfgjsdgf'" v-clipboard:success="clipboardSuccessHandler">
                    Copy to clipboard
                </button>
                <el-button type="default">Preview</el-button>
                <el-button class="save-btn" type="primary">Save Info</el-button>
            </div>
        </div>
        <div class="tm-trip-edit-body">
            <SideNavBar class="tm-settings-navbar" :width="'220px'" :routes="routes"/>
            <div class="tm-trip-content-wrapper">
                <router-view></router-view>
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
        }
    },
    methods: {
        clipboardSuccessHandler() {
            this.$notify({
                message: "Copied to clipboard",
                type: "success",
                position: "bottom-right"
            });
        }
    },

    mounted () {
        this.trip_id = this.$route.params.id;
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