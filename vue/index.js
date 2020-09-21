import Vue from 'vue'
import VueRouter from "vue-router";
import App from './App.vue'
import Tester from './Tester.vue'
import Overview from "./Overview.vue";

const Foo = {template: '<div>foo</div>'}
const Bar = {template: '<div>bar</div>'}

// new Vue({
//     render: h => h(App),
// }).$mount('#vue-div')

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: Tester,
            children: [
                {
                    path: 'foo',
                    component: Foo
                },
                {
                    path: 'bar',
                    component: Bar
                },
                {
                    path: 'oversigt',
                    component: Overview
                }
            ]
        },
    ]
});

new Vue({
    render: h => h(App),
    router
}).$mount('#vue-div')