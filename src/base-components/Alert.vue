<template>
  <v-alert
    :value="$store.state.alert.enable"
    dense
    outlined
    :type="$store.state.alert.type"
    class="text-left"
  >
    {{ $store.state.alert.msg }}
  </v-alert>
</template>

<script>
  export default {
    name: 'Alert',
  }
</script>
