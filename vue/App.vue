<template>
  <div>

<!--    <div v-if="loadingLoginStatus">-->
<!--      Determining logged in-->
<!--    </div>-->

<!--    <div v-else>-->
<!--      <router-view :nonce="nonce" :isLoggedIn="isLoggedIn" />-->
<!--    </div>-->

    <router-view :nonce="nonce" />

  </div>
</template>

<script>

export default {
  name: "App",
  data() {
    return {
      // The wpApiSettings object comes from calling "wp_enqueue_script('wp-api');" in functions. (https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/)
      // This adds e.g. "var wpApiSettings = {"root":"http:\/\/localhost\/kongres-poc\/wp-json\/","nonce":"ee84dec9c6","versionString":"wp\/v2\/"};" to the page
      nonce: wpApiSettings['nonce'],
      loadingLoginStatus: true,
    };
  }
}
</script>

<style scoped>

</style>