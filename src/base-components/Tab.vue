<template>
  <div>
    <v-tabs
      v-model="active"
      height="35"
      class="superstore-tabs-outline"
    >
      <v-tab
        v-for="(tab, key) in $store.state.tab.tabs"
        :key="key"
        :to="tab.route"
        :ripple="false"
        :class="`text-caption text-capitalize ${$vuetify.theme.dark ? 'white--text text--darken-1' : 'grey--text text--darken-2'}`"
        v-text="tab.title"
      />
    </v-tabs>
    <v-tabs-items
      v-model="active"
    >
      <router-view>
        <v-tab-item
          v-for="(body, key) in $store.state.tab.tabs"
          :key="key"
          :value="body.route"
          :transition="false"
          :reverse-transition="false"
        />
      </router-view>
    </v-tabs-items>
  </div>
</template>

<script>
  export default {
    name: 'Tabs',

    computed: {
      active: {
        get () {
          return this.$store.state.tab.active
        },
        set (val) {
          this.$store.commit('SET_TAB', { active: val })
        },
      },
    },
  }
</script>
