<template>
  <base-table>
    <template v-slot:name="item">
      <v-row class="my-1">
        <router-link :to="`/product/${item.prop.id}`">
          <v-avatar
            tile
            size="30"
          >
            <v-img
              :src="!item.prop.thumbnail_image.url ? $store.state.global.images.wc_product_image_placeholder : item.prop.thumbnail_image.url"
            />
          </v-avatar>
          <span class="pl-2">
            {{ item.prop.name }}
          </span>
        </router-link>
      </v-row>
    </template>
    <template v-slot:stock_status="item">
      <v-badge
        v-if="item.prop.in_stock"
        color="green"
        dot
      >
        {{ $store.state.tab.body.products.in_stock_text }}
      </v-badge>
      <v-badge
        v-else
        color="red"
        dot
      >
        {{ $store.state.tab.body.products.out_of_stock_text }}
      </v-badge>
    </template>
  </base-table>
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_TABLE', this.$store.state.tab.body.products.table)
    },
  }
</script>
