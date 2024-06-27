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
            <SideNavBar :width="'220px'" :routes="routes" />
            <div class="tm-trip-content-wrapper">
                <router-view @saveTrip="updateTripInfo" :meta="trip_meta" :trip_info="trip_info"></router-view>
            </div>
            <div class="tm-trip-edit-footer">
                <el-button @click="updateTripAndContinueNextRoute()" type="primary" size="large">Save And
                    Continue</el-button>
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
                ],
                itinerary: {
                    title: "Trip itinerary title",
                    options: [
                        {
                            title: "Day 1",
                            description: "itinerary description",
                        },
                        {
                            title: "Day 2",
                            description: "itinerary description",
                        },
                    ]
                },
                inc_exc: {
                    section_title : "Enter the cost tab section title",
                    includes: {
                        title: "Cost Includes Title",
                        services: [{ label: "Dinner" }, { label: "Breakfast" }]
                    },
                    excludes: {
                        title: "Cost excludes Title",
                        services: [{ label: "Lunch" }]
                    },
                },
                trip_gallery: {
                    enable_image_gallery: "yes",
                    images: [{id: "", url:"", name: ""}],
                    enable_video_gallery: "yes",
                    videos: [{video_link: "image_link", alt: "text"}]
                },
                map: {
                    title: "Map section title",
                    image: {id: "", url:"", name: ""},
                    iframe_code: "<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3152.0000000000005!2d144.9630573152587!3d-37.813627079751286!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad6429d7f7d7f5f%3A0x405f7b6f0c1f0e0!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1636710000000!5m2!1sen!2sau' width='600' height='450' style='border:0;' allowfullscreen='' loading='lazy'></iframe>"
                },
                // trip_info: {
                //     title: "section title",
                //     options: [{ label: "WIFI", value: "no" }, { label: "Admission fee", value: "Now not here" }] 
                // }
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
                    that.trip_meta = response.data?.trip_meta ?  response.data.trip_meta : that.trip_meta;
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
            },
            {
                label: "Itinerary",
                icon: "mdi-settings",
                to: `/trip/${this.trip_id}/edit/itinerary`
            },
            {
                label: "Includes/Excludes",
                icon: "mdi-settings",
                to: `/trip/${this.trip_id}/edit/includes-excludes`
            },
            // {
            //     label: "Trip Info",
            //     icon: "mdi-settings",
            //     to: `/trip/${this.trip_id}/edit/trip-info`
            // },
            {
                label: "Gallery",
                icon: "mdi-settings",
                to: `/trip/${this.trip_id}/edit/gallery`
            },
            {
                label: "Map",
                icon: "mdi-settings",
                to: `/trip/${this.trip_id}/edit/map`
            },
            {
                label: "FAQs",
                icon: "mdi-settings",
                to: `/trip/${this.trip_id}/edit/faqs`
            },
            {
                label: "Reviews",
                icon: "mdi-settings",
                to: `/trip/${this.trip_id}/edit/reviews`
            }
        ]
    }

}
</script>

<style scoped>
.tm-trip-edit-footer {
    padding: 20px 0px;
    text-align: end;
    /* border-top: 1px solid #e0e0e0; */
    background-color: transparent;
    width: 100%;
    max-width: 1040px;
    margin: 0 auto;
}
</style>