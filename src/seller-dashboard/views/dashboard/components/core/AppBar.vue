<template>
  <v-app-bar
    id="superstore-seller-dashboard-app-bar"
    absolute
    app
    flat
    :color="$vuetify.theme.dark ? '' : 'white'"
    clipped-left
    height="55"
  >
    <v-menu
      transition="scroll-y-reverse-transition"
      :close-on-content-click="false"
      open-on-hover
      bottom
      left
      offset-y
      origin="top right"
      content-class="elevation-2"
    >
      <template v-slot:activator="{ attrs, on }">
        <v-btn
          elevation="2"
          fab
          x-small
          v-bind="attrs"
          v-on="on"
          @click="setDrawer(!drawer)"
        >
          <v-icon v-if="$store.state.drawer">
            mdi-chevron-left
          </v-icon>

          <v-icon v-else>
            mdi-chevron-right
          </v-icon>
        </v-btn>
      </template>

      <v-list
        v-if="!$store.state.drawer"
        :tile="false"
        nav
        dense
      >
        <v-hover
          v-for="(menu, menuIndex) in drawerMenus"
          :key="menuIndex"
          v-slot="{ hover }"
        >
          <v-list-item
            :class="`text-caption ${hover ? 'primary white--text' : ''}`"
            exact
            :to="menu.to"
            v-text="menu.title.replace(/<\/?[^>]+(>|$)/g, '')"
          />
        </v-hover>
      </v-list>
    </v-menu>

    <v-spacer />

    <v-btn
      v-for="(menu, index) in menus"
      :key="index"
      exact
      small
      text
      :to="menu.to"
      class="text-capitalize hidden-sm-and-down"
    >
      {{ menu.title }}
    </v-btn>

    <v-icon
      class="transparent mx-2 pa-1 text-body-1"
      :color="$vuetify.theme.dark ? 'primary' : 'grey darken-2'"
      @click="setDarkMode($vuetify.theme.dark = !$vuetify.theme.dark)"
    >
      mdi-white-balance-sunny
    </v-icon>

    <v-menu
      transition="scroll-y-reverse-transition"
      :close-on-content-click="false"
      bottom
      left
      offset-y
      origin="top right"
      content-class="elevation-2"
    >
      <template v-slot:activator="{ attrs, on }">
        <v-btn
          fab
          text
          small
          class="hidden-md-and-up"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon>mdi-menu</v-icon>
        </v-btn>
      </template>

      <v-list
        :tile="false"
        nav
        dense
      >
        <v-hover
          v-for="(toggleMenu, toggleMenuIndex) in menus"
          :key="toggleMenuIndex"
          v-slot="{ hover }"
        >
          <v-list-item
            :class="`text-caption ${hover ? 'primary white--text' : ''}`"
            :to="toggleMenu.to"
          >
            {{ toggleMenu.title }}
          </v-list-item>
        </v-hover>
      </v-list>
    </v-menu>

    <v-menu
      transition="scroll-y-reverse-transition"
      open-on-hover
      bottom
      left
      offset-y
      origin="top right"
      content-class="elevation-2"
    >
      <template v-slot:activator="{ attrs, on }">
        <v-avatar
          size="30px"
          style="cursor: pointer;"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon>mdi-account-circle</v-icon>
        </v-avatar>
      </template>
      <v-list
        :tile="false"
        nav
        class="pa-3"
        dense
      >
        <v-hover
          v-for="(acMenu, acMenuIndex) in accountMenus"
          :key="acMenuIndex"
          v-slot="{ hover }"
        >
          <v-list-item
            v-if="acMenu.name === 'store_url'"
            :class="`text-caption ${hover ? 'primary white--text' : ''}`"
            target="_blank"
            link
            :href="acMenu.to"
          >
            <v-icon :class="`mr-2 text-body-2 ${hover ? 'white--text' : ''}`">
              {{ acMenu.icon }}
            </v-icon>
            {{ acMenu.title }}
          </v-list-item>
          <v-list-item
            v-else
            :class="`text-caption ${hover ? 'primary white--text' : ''}`"
            :to="acMenu.to"
          >
            <v-icon :class="`mr-2 text-body-2 ${hover ? 'white--text' : ''}`">
              {{ acMenu.icon }}
            </v-icon>
            {{ acMenu.title }}
          </v-list-item>
        </v-hover>

        <v-btn
          class="mt-3 text-caption"
          color="primary"
          depressed
          block
          :loading="$store.state.overlayLoading.enable"
          @click="logout()"
        >
          <v-icon left>
            mdi-logout
          </v-icon>
          {{ $store.state.global.logout_text }}
        </v-btn>
      </v-list>
    </v-menu>
  </v-app-bar>
</template>

<script>
  import { mapState, mapActions } from 'vuex'

  export default {
    data () {
      return {
        drawerMenus: this.$store.state.global.menus.sidebar,
        menus: this.$store.state.global.menus.navbar,
        accountMenus: this.$store.state.global.menus.navbar_account,
      }
    },

    computed: {
      ...mapState(['drawer']),
    },

    created () {
      if (localStorage.getItem('superstoreDarkMode') !== null) {
        var mode = (localStorage.getItem('superstoreDarkMode') === 'true')
        this.$vuetify.theme.dark = mode
      }
    },

    methods: {
      ...mapActions([
        'setDrawer',
      ]),
      ...mapActions([
        'setDarkMode',
      ]),
      logout () {
        var data = {
          action: 'superstore_logout_seller',
          nonce: this.$store.state.global.nonce,
        }

        this.$store.dispatch('ajax', { method: 'post', qsData: data, needReload: true })
      },
    },
  }
</script>
