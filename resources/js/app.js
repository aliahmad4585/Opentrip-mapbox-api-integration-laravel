
window.Vue = require('vue/dist/vue');

import App from './components/App'
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios)
require('./bootstrap');

new Vue({
    el: '#app',
    template: '<App/>',
    components: { App },
})