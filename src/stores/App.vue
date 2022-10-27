<template>
  <v-app id="superstore-stores-app">
    <v-main style="background-color: transparent !important;">
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
  export default {
    name: 'App',
  }
</script>

<style scoped lang="scss">
 ::v-deep .v-application--wrap {
    min-height: fit-content;
  }
</style>
