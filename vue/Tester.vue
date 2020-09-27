<template>
  <div>
    <router-link to="/foo">Go to Foo</router-link>
    <router-link to="/bar">Go to Bar</router-link>
    <router-link to="/oversigt">Oversigt</router-link>
    <router-view :isLoggedIn="isLoggedIn"></router-view>

    <h1>Vue works</h1>

    <p>Nu: {{ time1 }}</p>
    <p>Nu: {{ time2 }}</p>

    <hr>

    <b>Fetch from custom API using fetch</b>
    <div v-if="isLoading">
      Loading...
    </div>
    <div v-else>
      <div v-for="arrangement in arrangements_CustomAPI_fetch">
        ID: {{ arrangement.id }}. Navn (ACF): {{ arrangement.name }}
      </div>

      <p>Time to load: {{ timeToLoad }} sec</p>
    </div>

    <hr>

    <b>Fetch from WP API using fetch</b>
    <div v-if="isLoading2">
      Loading2...
    </div>
    <div v-else>
      <div v-for="arrangement in arrangements_WPAPI_fetch">
        ID: {{ arrangement.id }}. Navn (ACF): {{ arrangement.acf.name }} Date: {{ arrangement.start_date }}. Title: {{ arrangement.title.rendered }}
      </div>

      <p>Time to load2: {{ timeToLoad2 }} sec</p>
    </div>

    <hr>

    <button @click="createArrangementOld">Create arrangement (fetch)</button>
    <p>Reponse: {{ creationResponseOld }}</p>

    <hr>

    <button @click="createArrangement">Create arrangement (backbone)</button>
    <p>Reponse: {{ getCreationResponse }}</p>

    <hr>

    <b>Fetch from WP API using backbone.js</b>
    <br>
    <button @click="readArrangementsAsync">Read arrangement</button>
    <div v-for="arrangement in arrangements_WPAPI_backbone">
      ID: {{ arrangement.id }}. Navn (ACF): {{ arrangement.acf.name }} Date: {{ arrangement.start_date }}. Title: {{ arrangement.title.rendered }}
    </div>
    <p>Time to load3: {{ timeToLoad3 }} sec</p>

    <button @click="updateArrangement">Update arrangement</button>
    <p></p>

    <button @click="deleteArrangement">Delete arrangement</button>
    <p></p>

    <button @click="loginTest">Login</button>
    <button @click="logoutTest">Logout</button>
    <button @click="isLoggedInTest">Is logged in</button>
    <p></p>

    <button @click="backboneTest">Test</button>
    <p></p>

    <button @click="setNonce">Set nonce</button>
    <p></p>

  </div>
</template>

<script>
export default {
  name: "Tester",
  props: ['nonce', 'isLoggedIn'],
  // beforeRouteEnter (to, from, next) {
  //   next(vm => {
  //     console.log("Tester", "beforeRouteEnter", vm.isLoggedIn)
  //     if (vm.isLoggedIn)
  //       next();
  //   })
  // },
  data() {
    return {
      time1: '',
      time2: '',
      arrangements_CustomAPI_fetch: [],
      isLoading: false,
      timeToLoad: '',
      arrangements_WPAPI_fetch: [],
      isLoading2: false,
      timeToLoad2: '',
      creationResponseOld: null,
      creationResponse: null,
      timeToLoad3: '',
      arrangements_WPAPI_backbone: [],
      newNonce: ""
    }
  },
  mounted: async function () {
    await this.loadClient();
    this.fetchArrangementsFromCustomAPI(); // Reads from custom API with fetch
    this.fetchArrangementsFromWordPressAPI(); // Reads from WP API with fetch
    this.readArrangementsAsync(); // Reads from WP API with backbone.js

    this.calculateNow();
  },
  computed: {
    getCreationResponse: function () {
      if (this.creationResponse !== null)
        return this.creationResponse.message;
      else
        return "N/A";
    }
  },
  methods: {
    /**
     * Client startup is asynchronous. If the api schema is localized, the client can start immediately; if not the
     * client makes an ajax request to load the schema. The client exposes a load promise for provide a reliable wait
     * to wait for client to be ready: [wp.api.loadPromise]
     *
     * What happens is this:
     * wp-api.min.js sends (through backbone.min.js & jquery.js) a GET request to http://localhost/kongres-poc/wp-json/wp/v2/
     * This returns a list of all routes. These are then used to create the api scheme (i.e. wp.api.models & wp.api.collections)
     * The promise in wp.api.loadPromise resolves when this is done
     */
    loadClient: async function () {
      console.log("loadClient");
      let endpoint = await wp.api.loadPromise; // wp.api.loadPromise calls wp.api.init() in "wp-api.js". The wp.api.init function returns a promise that will resolve with the endpoint once it is ready.
      // console.log(endpoint);
    },
    calculateNow: function () {
      let date = new Date();
      let dateString1 = date.toUTCString();
      let dateString2 = date.toLocaleString();
      this.time1 = dateString1;
      this.time2 = dateString2;
    },
    fetchArrangementsFromCustomAPI: async function () {
      this.isLoading = true;
      let startLoadingTime = new Date();

      let response = await fetch(customVars.baseUrl + '/api/');
      let data = await response.json();
      this.arrangements_CustomAPI_fetch = data;

      let endLoadingTime = new Date();
      this.isLoading = false;

      this.timeToLoad = (endLoadingTime.getTime() - startLoadingTime.getTime()) / 1000;
    },
    fetchArrangementsFromWordPressAPI: async function () {
      this.isLoading2 = true;
      let startLoadingTime = new Date();

      let response = await fetch(customVars.baseUrl + '/wp-json/wp/v2/arrangement');
      let data = await response.json();
      this.arrangements_WPAPI_fetch = data;

      let endLoadingTime = new Date();
      this.isLoading2 = false;

      this.timeToLoad2 = (endLoadingTime.getTime() - startLoadingTime.getTime()) / 1000;
    },
    createArrangementOld: async function () {
      let attributes = {
        title: 'Kongres 44',
        status: 'publish',
        arrangement_name: "Kongres D",
        arrangement_start_date: Math.floor(Date.now() / 1000),
      };

      let response = await fetch(customVars.baseUrl + '/wp-json/wp/v2/arrangement', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': this.nonce
        },
        body: JSON.stringify(attributes) // body data type must match "Content-Type" header
      });
      let data = await response.json();
      this.creationResponseOld = data;
    },
    createArrangement: function () {
      // wp.api.loadPromise.done(() => { // Using an arrow function to preserve "this"
      let attributes = {
        title: 'Kongres 4',
        status: 'publish',
        arrangement_name: "Kongres D",
        arrangement_start_date: Math.floor(Date.now() / 1000),
      };

      let arrangement = new wp.api.models.Arrangement(attributes);
      arrangement.save(null, {
        error: (model, response, options) => { // Using an arrow function again to preserve "this"
          this.creationResponse = response.responseJSON;
        },
        success: (model, response, options) => { // Using an arrow function again to preserve "this"
          this.creationResponse = {message: options.xhr.statusText};
        },
      });
      // });
    },
    readArrangements: function () {
      let startLoadingTime = new Date();
      // wp.api.loadPromise.done(() => {
      let allArrangements = new wp.api.collections.Arrangement();
      allArrangements.fetch({
            error: (model, response, options) => {
              console.log(response);
            },
            success: (model, response, options) => {
              this.arrangements_WPAPI_backbone = response;
              let endLoadingTime = new Date();
              this.timeToLoad3 = (endLoadingTime.getTime() - startLoadingTime.getTime()) / 1000;
            }
          }
      );
      // });
    },
    readArrangementsAsync: function () {
      let startLoadingTime = new Date();
      // wp.api.loadPromise.done(() => { // For some reason, this function cannot be async
      (async () => {
        let allArrangements = new wp.api.collections.Arrangement();
        let response = await allArrangements.fetch();
        this.arrangements_WPAPI_backbone = response;

        let endLoadingTime = new Date();
        this.timeToLoad3 = (endLoadingTime.getTime() - startLoadingTime.getTime()) / 1000;
      })();
      // });
    },
    updateArrangement: async function () {
      try {
        let arrangement = new wp.api.models.Arrangement({id: 61});

        let attributes = {
          arrangement_name: "Kongres " + Math.random(),
        };

        let res = await arrangement.save(attributes);
        console.log(res);
      } catch (e) {
        console.log(e.responseJSON);
      }
    },
    deleteArrangement: async function () {
      try {
        let arrangement = new wp.api.models.Arrangement({id: 61});
        let res = await arrangement.destroy();
        console.log(res);
      } catch (e) {
        console.log(e.responseJSON);
      }
    },
    loginTest: async function () {
      // let d = {"user_login": "admin", "user_password": "admin"};
      let d = {"user_login": "holder100", "user_password": "Holder100"};
      let response = await fetch(customVars.baseUrl + '/wp-json/custom/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(d) // body data type must match "Content-Type" header
      });
      let data = await response.text();
      console.log(data);

      // this.nonce = data;
      // console.log("old nonce", wpApiSettings['nonce']);
      // wpApiSettings['nonce'] = data;
      // console.log("new nonce", wpApiSettings['nonce']);
    },
    logoutTest: async function () {
      let response = await fetch(customVars.baseUrl + '/wp-json/custom/logout');
      let data = await response.text();
      console.log(data);

      if (data === "logout success")
        // window.location.reload(); // The page must be refreshed to update the nonce in wp-api.js
        window.location.replace(location.pathname); // The page must be refreshed to update the nonce in wp-api.js
    },
    isLoggedInTest: async function () {
      let response = await fetch(customVars.baseUrl + '/wp-json/custom/isloggedin', {
        method: 'GET',
        headers: {
          'X-WP-Nonce': this.nonce
        },
      });
      let data = await response.text();
      console.log(data);
    },
    setNonce: async function () {
      // wpApiSettings['nonce'] = this.nonce;
      // wpApiSettings['nonce'] = "asd";

      let response = await fetch(customVars.baseUrl + '/wp-json/custom/getnonce');
      let data = await response.text();
      console.log("setNonce", data);
      this.newNonce = data;
    },
    backboneTest: async function () {
      // let t1 = wp;
      // let t2 = wp.api;
      // let t3 = wp.api.models;
      // // let t31 = wp.api.models.Base; // Undefined
      // let t4 = wp.api.collections;
      //
      // console.log(t1);

      // var post = new wp.api.models.Post( { id: 1 } );
      // post.fetch();
      // post.getCategories().done( function( postCategories ) {
      //   console.log( postCategories[0].name );
      // } );

      // var post = new wp.api.models.Arrangement({id: 61});
      // post.fetch();
      // post.getAuthorUser().done(function (postCategories) {
      //   console.log(postCategories[0].name);
      // });

      // let a = new wp.api.models.Arrangement({id: 61});
      // a.fetch({
      //   error: (model, response, options) => {
      //     console.log(response);
      //   },
      //   success: (model, response, options) => {
      //     this.arrangements_WPAPI_backbone = response;
      //     let endLoadingTime = new Date();
      //     this.timeToLoad3 = (endLoadingTime.getTime() - startLoadingTime.getTime()) / 1000;
      //   }
      // });
      // let b = a.getCategories().done(function (meta) {
      //   console.log(meta);
      // });
      // console.log(b);

    }
  }
}
</script>

<style scoped>

</style>