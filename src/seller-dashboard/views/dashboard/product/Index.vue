<template>
  <v-card
    id="superstore-seller-dashboard-product-index"
    min-height="78vh"
  >
    <base-tab />
  </v-card>
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_TAB', this.$store.state.product.tab)
    },
  }
</script>
