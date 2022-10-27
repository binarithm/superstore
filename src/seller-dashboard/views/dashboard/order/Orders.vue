<template>
  <base-table>
    <template v-slot:order="item">
      <div
        class="text-caption primary--text"
      >
        <router-link :to="`/order/${item.prop.id}`">
          #{{ item.prop.id }}
        </router-link>
      </div>
    </template>
  </base-table>
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_TABLE', this.$store.state.tab.body.orders.table)
    },
  }
</script>
