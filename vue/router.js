import Vue from "vue";
import VueRouter from "vue-router";
import Tester from "./Tester.vue";
import Overview from "./Overview.vue";
import Login from "./Login.vue";
import Offline from "./Offline.vue";
import SpeedTestAPI from "./SpeedTestAPI.vue";

const Foo = {template: '<div>foo</div>'}
const Bar = {template: '<div>bar</div>'}

Vue.use(VueRouter);

async function getIsUserLoggedIn() {
    try {
        let response = await fetch(vars['basePath'] + 'wp-json/custom/isloggedin', {
            method: 'GET',
            headers: {
                'X-WP-Nonce': wpApiSettings['nonce']
            },
        });
        let data = await response.text();
        console.log("index", "determineIsLoggedIn", data);

        return data === "true";
    } catch (e) {
        console.log("getIsUserLoggedIn", e);
        return false;
    }
}

const routes = [
    {
        path: '/',
        component: Tester,
        name: 'Index',
        meta: {
            requiresAuth: true
        },
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
    {
        path: '/login',
        component: Login,
        name: 'Login',
        meta: {
            hideForAuth: true
        }
    },
    {
        path: '/offline',
        component: Offline,
        name: 'Offline'
    },
    {
        path: '/speed',
        component: SpeedTestAPI,
        name: 'Speed'
    },
];

const router = new VueRouter({
    mode: 'hash',
    routes: routes
});

// let isUserLoggedInPromise = getIsUserLoggedIn();

router.beforeEach(async (to, from, next) => {
    if (!navigator.onLine) {
        if (to.name !== 'Offline') {
            console.log("0.1");
            return next({path: '/offline'});
        } else {
            console.log("0.2");
            return next();
        }
    }

    // let isUserLoggedIn = await isUserLoggedInPromise;
    let isUserLoggedIn = customVars.isUserLoggedIn === "true"; // The customVars object is set by "wp_localize_script()" in "functions.php". What "wp_localize_script()" does is add the variable to the HTML page

    // isUserLoggedInPromise.then((isUserLoggedIn) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (isUserLoggedIn) {
            console.log("1.1");
            next();
        } else {
            console.log("1.2");
            next({path: '/login'});
        }
    } else if (to.matched.some(record => record.meta.hideForAuth)) {
        if (isUserLoggedIn) {
            console.log("2.1");
            next({path: '/'});
        } else {
            console.log("2.2");
            next();
        }
    } else if (to.name === 'Offline') { // If we are in this if/else block, we know we're online, so redirect to the front page
        console.log("3");
        next({path: '/'});
    } else {
        console.log("4");
        next();
    }
    // });
});

export default router;