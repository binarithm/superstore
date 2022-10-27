<template>
  <v-card
    id="superstore-seller-dashboard-settings-payment"
    min-height="78vh"
  >
    <base-overlay-loading />

    <div
      v-if="$store.state.ajaxResponse === null || $store.state.ajaxResponse.id === 0"
      class="pa-10"
    >
      <v-alert
        v-if="!$store.state.overlayLoading.enable"
        dense
        outlined
        type="error"
        class="text-left"
      >
        {{ $store.state.settings.tab.body.payment.settings_not_found }}
      </v-alert>
    </div>

    <div v-else>
      <base-form>
        <template v-slot:payment_method_paypal_email="item">
          <v-text-field
            :value="$store.state.form.fields.payment_method.paypal_email"
            :rules="[v => v !== '' ? /.+@.+\..+/.test(v) || $store.state.global.form.valid_email_text : true]"
            :name="item.field.name"
            :placeholder="item.field.placeholder"
            :hint="item.field.hint"
            type="email"
            persistent-hint
            outlined
            dense
            :disabled="item.field.disabled === 'yes' ? true : false"
            @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.name]: $event === null ? '' : $event })"
          />
        </template>

        <template v-slot:payment_method_skrill_email="item">
          <v-text-field
            :value="$store.state.form.fields.payment_method.skrill_email"
            :rules="[v => v !== '' ? /.+@.+\..+/.test(v) || $store.state.global.form.valid_email_text : true]"
            :name="item.field.name"
            :placeholder="item.field.placeholder"
            :hint="item.field.hint"
            type="email"
            persistent-hint
            outlined
            dense
            :disabled="item.field.disabled === 'yes' ? true : false"
            @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.name]: $event === null ? '' : $event })"
          />
        </template>
      </base-form>
    </div>
  </v-card>
</template>

<script>
  export default {
    created () {
      this.$store.dispatch('settings/getSettings', 'payment')
    },
  }
</script>
