<template>
  <v-app id="superstore-seller-dashboard-app">
    <base-snackbar />

    <core-app-bar />

    <core-drawer />

    <setup-wizard />

    <v-main>
      <v-container
        fluid
        class="pa-5"
        style="min-height: 78vh;"
      >
        <base-alert />

        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
  import AppBar from './components/core/AppBar'
  import Drawer from './components/core/Drawer'
  import SetupWizard from './components/SetupWizard'

  export default {
    name: 'Index',

    components: {
      CoreAppBar: AppBar,
      CoreDrawer: Drawer,
      SetupWizard: SetupWizard,
    },

    created () {
      if (this.$store.state.global.notify.account.enabled === 'no' && this.$store.state.global.notify.account.show_ac_disabled_alert === 'yes') {
        this.$store.commit('SET_ALERT', { enable: false })
        this.$store.commit('SET_ALERT', { enable: true, type: 'error', msg: this.$store.state.global.notify.account.message })
      }
    },
  }
</script>
