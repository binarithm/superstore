<template>
  <v-snackbar
    :value="$store.state.notify.enable"
    :color="$store.state.notify.type === 'success' ? 'green' : 'red'"
    dark
    app
    top
    right
    :timeout="10000"
  >
    {{ $store.state.notify.msg }}

    <template v-slot:action="{ attrs }">
      <v-btn
        icon
        color="white"
        class="pa-0 ma-0"
        v-bind="attrs"
        @click="$store.commit('SET_NOTIFY', { enable: false })"
      >
        <v-icon>mdi-close-circle</v-icon>
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
  export default {
    name: 'Snackbar',
  }
</script>
