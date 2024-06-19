<template>
    <div class="tm-trip-edit-wrapper">
        <div class="tm-trip-edit-header">
            <div class="header-left">
                <el-button type="default">Back To All Trips</el-button>
            </div>
            <div class="header-right">
                <button class="shortcode" v-clipboard="'dhjfjhgdshjfgjsdgf'" v-clipboard:success="clipboardSuccessHandler">
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
            routes: [
                {
                    label: 'General',
                    icon: 'mdi-bus',
                    to: '/trip/444/edit/'
                },
                {
                    label: "Prices And Dates",
                    icon: "mdi-settings",
                    to: "/trip/444/edit/prices-and-dates"
                }
            ]
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
    }

}
</script>