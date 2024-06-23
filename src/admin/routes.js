import Dashboard from '@pages/page_dashboard/Dashboard.vue';
import GlobalSettings from './pages/page_global_settings/GlobalSettings.vue';
// Trip section components
import TripIndex from '@/pages/page_trips/TripsIndex.vue';
import AllTrips from '@/pages/page_trips/trips/AllTrips.vue';
import AllDestinations from '@/pages/page_trips/destinations/AllDestinations.vue';
import AllAttributes from '@/pages/page_trips/destinations/AllDestinations.vue';

//Trip edit section 
import EditTrip from '@/pages/page_trips/trips/trip/EditTripIndex.vue';
import GeneralInfo from '@/pages/page_trips/trips/trip/GeneralInfo.vue';
import Pricing from "@/pages/page_trips/trips/trip/Pricing.vue"
import OverView from "@/pages/page_trips/trips/trip/OverView.vue"
import Itinerary from "@/pages/page_trips/trips/trip/Itinerary.vue"
import IncludeAndExclude from "@/pages/page_trips/trips/trip/IncludeAndExclude.vue"
// Settings section components
import SettingsIndex from '@/pages/page_settings/SettingsIndex.vue';
import GeneralSettings from '@/pages/page_settings/general/GeneralSettings.vue';
import PaymentSettings from '@/pages/page_settings/payment/PaymentSettings.vue';

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
                component: Dashboard,
            },
            {
                path: 'attributes',
                component: AllAttributes,
            },
            {
                path: 'activities',
                component: Dashboard,
            },
            {
                path: 'deficulty-levels',
                component: Dashboard,
            },
            {
                path: 'pricing-categories',
                component: Dashboard,
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
        ]
    },
    {
        path: '/bookings',
        name: 'bookings',
        component: Dashboard
    },
    {
        path: '/customers',
        name: 'customers',
        component: Dashboard
    },
    {
        path: '/enquiries',
        name: 'enquiries',
        component: Dashboard
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
                name: 'payment-settings',
            },
        ]
    },
];