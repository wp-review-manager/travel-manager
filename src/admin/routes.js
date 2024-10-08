import Dashboard from '@pages/page_dashboard/Dashboard.vue';
// Trip section components
import TripIndex from '@/pages/page_trips/TripsIndex.vue';
import AllTrips from '@/pages/page_trips/trips/AllTrips.vue';
import AllDestinations from '@/pages/page_trips/destinations/AllDestinations.vue';
import AllAttributes from '@/pages/page_trips/attributes/AllAttributes.vue';
import AllCategories from '@/pages/page_trips/categories/AllCategories.vue';
import AllActivities from '@/pages/page_trips/activities/AllActivities.vue';
import AllDifficulty from '@/pages/page_trips/difficulty/AllDifficulty.vue';
import AllPricingCategories from '@/pages/page_trips/pricing_categories/AllPricingCategories.vue';

//Trip edit section 
import EditTrip from '@/pages/page_trips/trips/trip/EditTripIndex.vue';
import GeneralInfo from '@/pages/page_trips/trips/trip/GeneralInfo.vue';
import Pricing from "@/pages/page_trips/trips/trip/Pricing.vue"
import OverView from "@/pages/page_trips/trips/trip/OverView.vue"
import Itinerary from "@/pages/page_trips/trips/trip/Itinerary.vue"
import IncludeAndExclude from "@/pages/page_trips/trips/trip/IncludeAndExclude.vue"
import TripInfo from "@/pages/page_trips/trips/trip/TripInfo.vue"
import Gallery from "@/pages/page_trips/trips/trip/Gallery.vue"
import Map from "@/pages/page_trips/trips/trip/Map.vue"
import TripFaq from "@/pages/page_trips/trips/trip/TripFaq.vue"
// Settings section components
import SettingsIndex from '@/pages/page_settings/SettingsIndex.vue';
import GeneralSettings from '@/pages/page_settings/general/GeneralSettings.vue';
import PaymentSettings from '@/pages/page_settings/payment/PaymentSettings.vue';

// Enquiries section components
import AllEnquiries from './pages/page_enquiries/AllEnquiries.vue';
const childPaymentRoutes = window.wpTravelManager.payment_routes;

//Booking section components
import AllBooking from './pages/page_booking/AllBooking.vue';
import BookingDetails from './pages/page_booking/BookingDetails.vue';
//Coupon section components
import AllCoupon from './pages/page_coupons/AllCoupon.vue';

export default [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            active: 'dashboard'
        },
    },
    {
        path: '/trips',
        name: 'trips',
        component: TripIndex,
        children: [
            {
              path: '',
              component: AllTrips,
              name: 'all-trips',
            },
            {
                path: 'destinations',
                component: AllDestinations,
            },
            {
                path: 'travel-categories',
                component: AllCategories,
            },
            {
                path: 'attributes',
                component: AllAttributes,
            },
            {
                path: 'activities',
                component: AllActivities,
            },
            {
                path: 'difficulty-levels',
                component: AllDifficulty,
            },
            {
                path: 'pricing-categories',
                component: AllPricingCategories,
            },

          ],
    },
    {
        path: '/trip/:id/edit/',
        name: 'edit-trip',
        component: EditTrip,
        children:[
            {
                path: '',
                component: GeneralInfo,
                name: 'general-info',
            },
            {
                path: 'pricing',
                component: Pricing,
                name: 'pricing',
            },
            {
                path: 'overview',
                component: OverView,
                name: 'overview',
            },
            {
                path: 'itinerary',
                component: Itinerary,
                name: 'itinerary',
            },
            {
                path: 'includes-excludes',
                component: IncludeAndExclude,
                name: 'include-exclude',
            },
            {
                path: 'trip-info',
                component: TripInfo,
                name: 'trip-info',
            },
            {
                path: 'gallery',
                component: Gallery,
                name: 'gallery',
            },
            {
                path: 'map',
                component: Map,
                name: 'map',
            },
            {
                path: 'faqs',
                component: TripFaq,
                name: 'faqs',
            }
        ]
    },
    {
        path: '/bookings',
        name: 'bookings',
        component: AllBooking,
    },
   {
    path: '/booking/:id/view/',
    name: 'view-booking',
    component: BookingDetails,
   },
    {
        path: '/customers',
        name: 'customers',
        component: Dashboard
    },
    {
        path: '/enquiries',
        name: 'enquiries',
        component: AllEnquiries
    },
    {
        path: '/coupons',
        name: 'coupons',
        component: AllCoupon,
    },
    {
        path: '/settings',
        name: 'settings',
        component: SettingsIndex,
        children: [
            {
                path: '',
                component: GeneralSettings,
                name: 'general-settings',
            },
            {
                path: 'payment',
                component: PaymentSettings,
                name: 'payment',
                children: childPaymentRoutes
            },
        ]
    },
];