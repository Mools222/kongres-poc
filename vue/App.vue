<template>
  <div>

<!--    <div v-if="loadingLoginStatus">-->
<!--      Determining logged in-->
<!--    </div>-->

<!--    <div v-else>-->
<!--      <login v-if="!isLoggedIn" :nonce="nonce"></login>-->
<!--      <tester v-else :nonce="nonce"></tester>-->
<!--    </div>-->

    <tester :nonce="nonce"></tester>

  </div>
</template>

<script>
// let test = require('../../../../wp-includes/js/wp-api');
import tester from './Tester.vue';
import login from './Login.vue';

export default {
  name: "App",
  components: {
    tester, login
  },
  data() {
    return {
      nonce: wpApiSettings['nonce'], // The wpApiSettings object comes from wp-api via wp_localize_script (https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/)
      loadingLoginStatus: true,
      isLoggedIn: "null",
    };
  },
  created: async function () {
    await this.determineIsLoggedIn();
  },
  methods: {
    determineIsLoggedIn: async function () {
      let response = await fetch('http://localhost/kongres-poc/wp-json/custom/isloggedin', {
        method: 'GET',
        headers: {
          'X-WP-Nonce': this.nonce
        },
      });
      let data = await response.text();
      console.log("determineIsLoggedIn", data);

      this.isLoggedIn = data === "true";
      this.loadingLoginStatus = false;
    }
  }
}
</script>

<style scoped>

</style>