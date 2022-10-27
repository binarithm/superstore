<template>
  <v-card
    id="superstore-seller-dashboard-new-payment-request"
    min-height="78vh"
  >
    <v-alert
      v-if="!$store.state.tab.body.send_new_request.no_active_method.found"
      :value="true"
      dense
      outlined
      type="error"
      class="text-left ma-5"
    >
      <v-row
        align="center"
      >
        <v-col class="grow">
          {{ $store.state.tab.body.send_new_request.no_active_method.alert_text }}
        </v-col>
        <v-col class="shrink">
          <v-btn
            depressed
            small
            text
            color="primary"
            to="/settings/payment"
          >
            {{ $store.state.tab.body.send_new_request.no_active_method.btn_text }}
          </v-btn>
        </v-col>
      </v-row>
    </v-alert>
    <base-form />
  </v-card>
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_FORM', this.$store.state.tab.body.send_new_request.form)
    },
  }
</script>
