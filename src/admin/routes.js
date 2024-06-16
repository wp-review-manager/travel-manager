import Admin from '@pages/page_dashboard/Dashboard.vue';
import Contact from './pages/page_dashboard/Dashboard.vue';

export default [{
        path: '/',
        name: 'dashboard',
        component: Admin,
        meta: {
            active: 'dashboard'
        },
    },
    {
        path: '/trips',
        name: 'trips',
        component: Contact
    },
    {
        path: '/bookings',
        name: 'bookings',
        component: Contact
    },
    {
        path: '/customers',
        name: 'customers',
        component: Contact
    }
];