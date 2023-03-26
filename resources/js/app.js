/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
// require('./softphone-1.4.0.js');

window.Vue = require('vue').default;
import Vuex from 'vuex';
import App from './components/App';
import VueCookies from 'vue-cookies';
import DatePicker from 'vue2-datepicker';
import VueTheMask from 'vue-the-mask';
import VModal from 'vue-js-modal';
import VueSlimScroll from 'vue-slimscroll';
import 'vue2-datepicker/locale/ru';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import axios from 'axios';
import {store} from './store';
import { routes } from './routes';

Vue.use(Vuex);
Vue.use(VModal);
Vue.use(VueTheMask);
Vue.component('DatePicker', DatePicker);
Vue.use(VueRouter);
Vue.use(VueCookies);
Vue.use(VueAxios, axios);
Vue.use(VueSlimScroll);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: routes
});
router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next();
});

const app = new Vue({
    el: '#app',
    store: store,
    router: router,
    render: h => h(App),
});
