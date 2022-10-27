<template>
  <v-app-bar
    id="superstore-admin-dashboard-app-bar"
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
          depressed
          text
          color="primary"
          class="mx-3"
          small
          v-bind="attrs"
          v-on="on"
        >
          {{ quickLinkTitle }}
          <v-icon
            right
            color="primary"
          >
            mdi-menu-down
          </v-icon>
        </v-btn>
      </template>

      <v-list
        :tile="false"
        nav
        dense
      >
        <v-hover
          v-for="(link, linkIndex) in quickLinks"
          :key="linkIndex"
          v-slot="{ hover }"
        >
          <v-list-item
            :class="`text-caption ${hover ? 'primary white--text' : ''}`"
            link
            target="_blank"
            :href="link.to"
          >
            <v-icon
              :color="$vuetify.theme.dark ? 'white' : `${hover ? 'white' : ''}`"
              class="mr-4 text-subtitle-1"
              left
              v-text="link.icon"
            />
            {{ link.title }}
          </v-list-item>
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
      class="text-capitalize hidden-sm-and-down mx-1"
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
  </v-app-bar>
</template>

<script>
  import { mapState, mapActions } from 'vuex'

  export default {
    data () {
      return {
        drawerMenus: this.$store.state.global.menus.sidebar,
        menus: this.$store.state.global.menus.navbar,
        quickLinks: this.$store.state.global.menus.quick,
        quickLinkTitle: this.$store.state.global.menus.quick_link_title,
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
    },
  }
</script>
