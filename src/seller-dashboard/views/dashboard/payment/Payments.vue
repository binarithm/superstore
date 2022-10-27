<template>
  <base-table />
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_TABLE', this.$store.state.tab.body.payments.table)
    },
  }
</script>
