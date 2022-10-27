<template>
  <v-app id="superstore-login-form">
    <v-main style="background-color: transparent !important;">
      <v-col
        cols="12"
        class="mx-auto"
        md="6"
      >
        <v-card
          class="mx-auto pa-7"
        >
          <tab-style v-if="tabStyle === 'yes'" />

          <general-style v-else />
        </v-card>
      </v-col>
    </v-main>
  </v-app>
</template>

<script>
  import TabStyle from './TabStyle'
  import GeneralStyle from './GeneralStyle'

  export default {
    name: 'App',

    components: {
      TabStyle: TabStyle,
      GeneralStyle: GeneralStyle,
    },

    data () {
      return {
        tabStyle: window.superstore.account.tab_style_form,
      }
    },
  }
</script>

<style scoped lang="scss">
 ::v-deep .v-application--wrap {
    min-height: fit-content;
  }
</style>
