<template>
  <div>
    <h1>Login</h1>

    <button @click="loginTest">Login</button>
    <button @click="isLoggedInTest">Is logged in</button>
    <p></p>

  </div>
</template>

<script>
export default {
  name: "Login",
  props: ['nonce'],
  methods: {
    loginTest: async function () {
      // let d = {"user_login": "admin", "user_password": "admin"};
      let d = {"user_login": "holder100", "user_password": "Holder100"};
      let response = await fetch('http://localhost/kongres-poc/wp-json/custom/login', {
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
      let response = await fetch('http://localhost/kongres-poc/wp-json/custom/isloggedin', {
        method: 'GET',
        headers: {
          'X-WP-Nonce': this.nonce // is_user_logged_in() only seems to work properly when the nonce is provided
        },
      });
      let data = await response.text();
      console.log(data);
    },
  }
}
</script>

<style scoped>

</style>