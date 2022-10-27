<template>
  <v-overlay
    :value="$store.state.overlayLoading.enable"
    :absolute="true"
  >
    <div class="text-center">
      <div>{{ $store.state.overlayLoading.msg }}</div>
      <v-progress-linear
        rounded
        height="6"
        color="white"
        indeterminate
      />
    </div>
  </v-overlay>
</template>

<script>
  export default {
    name: 'OverlayLoading',
  }
</script>
