<template>
  <div>
    <h1>Overview</h1>
  </div>
</template>

<script>
export default {
  name: "Overview",
  props: ['nonce', 'isLoggedIn'],
  // beforeRouteEnter(to, from, next) {
  //   next(vm => {
  //     console.log("Overview", "beforeRouteEnter", vm.isLoggedIn)
  //     if (vm.isLoggedIn)
  //       next();
  //     else
  //       window.location.replace(location.pathname);
  //   })
  // },
}
</script>

<style scoped>

</style>