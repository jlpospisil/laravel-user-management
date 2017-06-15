
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.config.productionTip = false;

Vue.component('user-list', require('./components/user-list.vue'));

const app = new Vue({
    el: '#app',
    store: require('./app-state-management').default,
    mounted() {
        var auth_user = JSON.parse(this.$el.getAttribute('auth_user'))
        var auth_user_roles = JSON.parse(this.$el.getAttribute('auth_user_roles'))
        if (auth_user) {
            auth_user.roles = (auth_user_roles) ? auth_user_roles : []
            this.$store.dispatch('updateAuthUser', auth_user)
        }
    }
});
