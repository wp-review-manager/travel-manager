import Dashboard from '@pages/page_dashboard/Dashboard.vue';
import GlobalSettings from './pages/page_global_settings/GlobalSettings.vue';
import TripIndex from '@/pages/page_trips/TripIndex.vue';
import AllTrips from '@/pages/page_trips/AllTrips.vue';

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
                component: Dashboard,
            },
            {
                path: 'travel-categories',
                component: Dashboard,
            },
            {
                path: 'attributes',
                component: Dashboard,
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