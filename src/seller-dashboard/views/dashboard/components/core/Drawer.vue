<template>
  <v-navigation-drawer
    id="superstore-seller-dashboard-navigation-drawer"
    :value="$store.state.drawer"
    :color="$vuetify.theme.dark ? '#1A1A1D' : '#ffffff'"
    disable-resize-watcher
    app
    absolute
    width="200"
    height="100%"
    clipped
    v-bind="$attrs"
  >
    <v-list
      nav
      class="px-2"
      dense
    >
      <v-list-item
        v-for="(item, i) in drawerMenus"
        :key="i"
        :to="item.to"
        active-class="primary white--text"
        :ripple="false"
      >
        <v-list-item-icon class="my-auto mr-1">
          <v-icon
            class="text-subtitle-1"
            v-text="item.icon"
          />
        </v-list-item-icon>

        <v-list-item-content v-if="item.title">
          <v-list-item-title
            class="text-caption"
            v-html="item.title"
          />
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  export default {
    data () {
      return {
        drawerMenus: this.$store.state.global.menus.sidebar,
      }
    },

    created () {
      if (localStorage.getItem('superstoreDrawer') !== null) {
        var drawer = (localStorage.getItem('superstoreDrawer') === 'true')
        this.$store.commit('SET_DRAWER', drawer)
      }
    },
  }
</script>
