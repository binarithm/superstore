<template>
  <base-form />
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_FORM', this.$store.state.tab.body.add_new_product.form)
    },
  }
</script>
