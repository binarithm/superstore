<template>
  <v-card
    id="superstore-admin-dashboard-seller-index"
    min-height="78vh"
  >
    <base-tab />
  </v-card>
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_TAB', this.$store.state.seller.tab)
    },
  }
</script>
