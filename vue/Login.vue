<template>
  <div>
    <h1>Login</h1>

    <input type="text" v-model="username">
    <input type="text" v-model="password">

    <button @click="loginTest">Login</button>
    <button @click="isLoggedInTest">Is logged in</button>
    <p></p>

    <button @click="testStore">Store</button>


  </div>
</template>

<script>
export default {
  name: "Login",
  props: ['nonce'],
  data() {
    return {
      username: 'holder100',
      password: 'Holder100',
    }
  },
  methods: {
    loginTest: async function () {
      // let d = {"user_login": "admin", "user_password": "admin"};
      let d = {"user_login": this.username, "user_password": this.password};
      let response = await fetch(customVars.baseUrl + '/wp-json/custom/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(d)
      });
      let data = await response.text();
      console.log(data);

      if (data === "login success")
        window.location.reload(); // The page must be refreshed to update the nonce in wp-api.js
    },
    isLoggedInTest: async function () {
      let response = await fetch(customVars.baseUrl + '/wp-json/custom/isloggedin', {
        method: 'GET',
        headers: {
          'X-WP-Nonce': this.nonce // is_user_logged_in() only seems to work properly when the nonce is provided
        },
      });
      let data = await response.text();
      console.log(data);
    },
    testStore: function () {
      this.$store.dispatch('setCount', 5);
      let count = this.$store.getters.getCount;
      console.log("testStore", count);
    }
  }
}
</script>

<style scoped>

</style>