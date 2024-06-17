import routes from './routes';
import { createWebHashHistory, createRouter } from 'vue-router'
import WPTravelManager from './Bits/WPTravelManager';
import { ElNotification } from 'element-plus';

const router = createRouter({
    history: createWebHashHistory(),
    routes
});


const framework = new WPTravelManager();

framework.app.config.globalProperties.appVars = window.WPTravelManagerAdmin;
framework.app.config.globalProperties.$notify = ElNotification;

window.WPTravelManagerApp = framework.app.use(router).mount('#TM_app');

router.afterEach((to, from) => {
    jQuery('.TM_menu_item').removeClass('active');
    let active = to.meta.active;
    if (active) {
        jQuery('.TM_main-menu-items').find('li[data-key='+active+']').addClass('active');
    }
});
