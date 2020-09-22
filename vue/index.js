import Vue from 'vue'
import VueRouter from "vue-router";
import App from './App.vue'
// import Tester from './Tester.vue'
// import Overview from "./Overview.vue";
import '../assets/js/swScript.js';
// import Login from "./Login.vue";
import router from "./router.js";

// new Vue({
//     render: h => h(App),
// }).$mount('#vue-div')

// console.log(vars);
// console.log(vars2);

new Vue({
    render: h => h(App),
    router
}).$mount('#vue-div')