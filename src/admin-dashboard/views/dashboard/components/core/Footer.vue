<template>
  <v-footer
    id="dashboard-core-footer"
    color="transparent"
    class="pa-3"
  >
    <v-row
      align="center"
      no-gutters
    >
      <a
        v-for="(link, i) in links"
        :key="i"
        :href="link.href"
        :class="`mr-0 px-2 primary--text text--${$vuetify.theme.dark ? 'lighten' : 'darken'}-2`"
        rel="noopener"
        target="_blank"
        v-text="link.title"
      />

      <v-spacer class="hidden-sm-and-down" />

      <v-col
        cols="12"
        md="auto"
      >
        <div
          class="font-weight-light pt-6 pt-md-0 text-center"
          v-html="rateUsText"
        />
      </v-col>
    </v-row>
  </v-footer>
</template>

<script>
  export default {
    name: 'Footer',

    data () {
      return {
        links: this.$store.state.global.menus.footer,
        rateUsText: this.$store.state.global.menus.footer_rate_text,
      }
    },
  }
</script>
