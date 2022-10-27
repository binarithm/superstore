<template>
  <base-table>
    <template v-slot:seller="item">
      <v-row class="my-1">
        <router-link
          :to="`/seller/${item.prop.id}`"
        >
          <v-avatar
            tile
            size="30"
          >
            <v-icon
              v-if="!item.prop.profile_picture_src"
            >
              mdi-storefront
            </v-icon>
            <v-img
              v-else
              :src="item.prop.profile_picture_src"
            />
          </v-avatar>
          <span class="pl-2">
            {{ item.prop.store_name ? item.prop.store_name : $store.state.global.no_name }}
          </span>
        </router-link>
      </v-row>
    </template>
    <template v-slot:earnings="item">
      <div v-html="item.prop.orders_overview.earnings" />
    </template>
    <template v-slot:contact="item">
      <div>
        <div>
          <v-icon class="text-subtitle-2">
            mdi-email
          </v-icon>
          {{ item.prop.email }}
        </div>
        <div
          v-if="item.prop.phone"
        >
          <v-icon class="text-subtitle-2">
            mdi-phone
          </v-icon>
          {{ item.prop.phone }}
        </div>
      </div>
    </template>
  </base-table>
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_TABLE', this.$store.state.tab.body.sellers.table)
    },
  }
</script>
