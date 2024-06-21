<template>
    <div class="tm-trip-edit-wrapper">
        <div class="tm-trip-edit-header">
            <div class="header-left">
                <router-link to="/trips/">
                    <el-button type="default"><el-icon class="el-icon--left">
                            <Back />
                        </el-icon>Back To All Trips</el-button>
                </router-link>
            </div>
            <div class="header-right">
                <button class="tm-shortcode" v-clipboard="trip_info.shortcode"
                    v-clipboard:success="clipboardSuccessHandler">
                    {{ trip_info.shortcode }}
                </button>
                <el-button type="default">Preview</el-button>
                <el-button @click="updateTripInfo()" class="save-btn" type="primary">Save Info</el-button>
            </div>
        </div>
        <div class="tm-trip-edit-body">
            <SideNavBar class="tm-settings-navbar" :width="'220px'" :routes="routes" />
            <div class="tm-trip-content-wrapper">
                <router-view @saveTrip="updateTripInfo" :meta="trip_meta" :trip_info="trip_info"></router-view>
                <div class="tm-trip-edit-footer">
                    <el-button @click="updateTripAndContinueNextRoute()" type="primary" size="large">Save And
                        Continue</el-button>
                </div>
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
    data() {
        return {
            trip_id: null,
            routes: [],
            trip_info: {},
            trip_meta: {
                general: {
                    trip_code: `TM-${this.$route.params.id}`,
                    max_traveler: 10,
                    trip_type: "group",
                    trip_category: "adventure",
                    trip_status: "available_to_booking",
                    duration: {
                        type: "days",
                        duration: 2
                    },
                    nights: {
                        type: "nights",
                        duration: 3,
                    },
                    cut_time: {
                        enable: "no",
                        start_of_date: "",
                        end_of_date: "",
                    },
                    min_max_age: {
                        enable: "yes",
                        min_age: 2,
                        max_age: 44
                    },
                    description: {
                        description: "Trip description",
                        section_title: "Section Title",
                        trip_highlights: {
                            title: "Highlights",
                            options: [{ label: "Highlight 1" }, { label: "Highlight 2" }]
                        }
                    }
                },
                packages: [
                    {
                        id: 1,
                        title: "Trip package name : Ex: Golden/ Regular",
                        enable: "yes",
                        available_booking_date: {
                            enable: "yes",
                            start_date: "2023-11-12",
                            end_date: "2023-11-12"
                        },
                        package_quantity:0,
                        pricing: [
                            {
                                enable: "yes",
                                label: "Adult",
                                price: 500,
                                pricing_type: "per_person/group",// Should research about group
                                selling_price: 400,
                                min_pax: 3,
                                max_pax: 5,
                            }
                        ]
                    }
                ]
            }
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
                    console.log(response);
                    that.trip_info = response.data.trip;
                    that.trip_meta = response.data?.trip_meta ?  JSON.parse(response.data.trip_meta) : that.trip_meta;
                }).fail((error) => {
                    console.log(error);
                })
        },

        updateTripInfo() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_trips",
                    route: "create_or_update_trip",
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                    trip_id: this.trip_id,
                    trip_info: this.trip_info,
                    trip_meta: this.trip_meta
                }).then((response) => {
                    console.log(response);
                    this.$notify({
                        message: response.message,
                        type: "success",
                        position: "bottom-right"
                    });
                }).fail((error) => {
                    console.log(error);
                })
        },

        updateTripAndContinueNextRoute() {
            this.updateTripInfo();
            this.goToNextRoute();
        },
        goToNextRoute() {
            const currentIndex = this.routes.findIndex(route => route.to === this.$route.path);

            if (currentIndex !== -1 && currentIndex < this.routes.length - 1) {
                this.$router.push(this.routes[currentIndex + 1].to);
            }
        }

    },

    mounted() {
        this.trip_id = this.$route.params.id;
        this.getTripInfo(this.trip_id);
        this.routes = [
            {
                label: 'General',
                icon: 'mdi-bus',
                to: `/trip/${this.trip_id}/edit/`
            },
            {
                label: "Pricing",
                icon: "mdi-settings",
                to: `/trip/${this.trip_id}/edit/pricing`
            },
            {
                label: "Overview",
                icon: "mdi-settings",
                to: `/trip/${this.trip_id}/edit/overview`
            }
        ]
    }

}
</script>

<style scoped>
.tm-trip-edit-footer {
    padding: 20px;
    text-align: end;
    border-top: 1px solid #e0e0e0;
}
</style>