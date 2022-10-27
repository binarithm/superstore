<template>
  <v-app id="superstore-admin-dashboard-app">
    <base-snackbar />

    <core-app-bar v-show="$route.name !== 'GetProIndex'" />

    <core-drawer v-if="$route.name !== 'GetProIndex'" />

    <setup-wizard />

    <v-main>
      <v-container
        fluid
        class="pa-5"
        style="min-height: 78vh;"
      >
        <base-alert
          v-if="$route.name !== 'GetProIndex'"
        />

        <router-view />
      </v-container>

      <core-footer />
    </v-main>
  </v-app>
</template>

<script>
  import AppBar from './components/core/AppBar'
  import Drawer from './components/core/Drawer'
  import Footer from './components/core/Footer'
  import SetupWizard from './components/SetupWizard'

  export default {
    name: 'Index',

    components: {
      CoreAppBar: AppBar,
      CoreDrawer: Drawer,
      CoreFooter: Footer,
      SetupWizard: SetupWizard,
    },
  }
</script>
