import Dashboard from '@pages/page_dashboard/Dashboard.vue';
import GlobalSettings from './pages/page_global_settings/GlobalSettings.vue';
// Trip section components
import TripIndex from '@/pages/page_trips/TripIndex.vue';
import AllTrips from '@/pages/page_trips/trips/AllTrips.vue';
import AllDestinations from '@/pages/page_trips/destinations/AllDestinations.vue';
import AllAttributes from '@/pages/page_trips/attributes/AllAttributes.vue';

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
        component: GlobalSettings,
    },
];