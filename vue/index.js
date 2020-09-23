import Vue from 'vue'
import App from './App.vue'
import '../assets/js/swScript.js';
import router from "./router.js";
import store from "./store.js";

new Vue({
    render: h => h(App),
    router,
    store
}).$mount('#vue-div')