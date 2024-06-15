import Admin from './Components/Admin.vue';
import Contact from './Components/Contact.vue';

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