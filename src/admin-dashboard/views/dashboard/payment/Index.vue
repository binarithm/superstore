<template>
  <v-card
    id="superstore-admin-dashboard-payment-index"
    min-height="78vh"
  >
    <base-tab />
  </v-card>
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_TAB', this.$store.state.payment.tab)
    },
  }
</script>
