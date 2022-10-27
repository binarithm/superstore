<template>
  <v-dialog
    v-model="runWizard"
    persistent
    max-width="700"
  >
    <v-card class="text-center">
      <v-overlay
        v-model="overlayLoading"
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
      <div
        v-if="!stepper"
        class="pa-5"
      >
        <v-img
          :src="logoUrl"
          width="80"
          class="mx-auto"
        />
        <div class="text-h4 pa-2">
          {{ entry.wish }}
        </div>
        <v-btn
          color="primary"
          depressed
          @click="runStepper()"
        >
          {{ entry.go }}
        </v-btn>

        <div>
          <v-btn
            text
            small
            class="text-capitalize"
            color="primary"
            depressed
            @click="closeWizard"
          >
            {{ entry.dashboard }}
          </v-btn>
        </div>
        <v-card-text class="text-overline">
          {{ entry.thanks }}
        </v-card-text>
      </div>

      <div v-else>
        <v-stepper
          v-model="e1"
          flat
        >
          <v-stepper-header>
            <v-stepper-step
              :complete="e1 > 1"
              step="1"
            >
              {{ headersName.general }}
            </v-stepper-step>

            <v-divider />

            <v-stepper-step
              :complete="e1 > 2"
              step="2"
            >
              {{ headersName.seller }}
            </v-stepper-step>

            <v-divider />

            <v-stepper-step
              :complete="e1 > 3"
              step="3"
            >
              {{ headersName.payment }}
            </v-stepper-step>

            <v-divider />

            <v-stepper-step
              step="4"
            >
              {{ headersName.finish }}
            </v-stepper-step>
          </v-stepper-header>

          <v-stepper-items>
            <v-stepper-content step="1">
              <v-card
                class="mb-10 pa-5 text-left"
                min-height="300px"
                flat
              >
                <v-row>
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepGeneral.admin_commission_global.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-select
                      v-model="inputs.superstore_general.admin_commission_global.type"
                      :label="stepGeneral.admin_commission_global.items.type.label"
                      :items="stepGeneral.admin_commission_global.items.type.items"
                      item-text="title"
                      item-value="value"
                      :no-data-text="$store.state.global.table.no_item_found_text"
                      type="select"
                      persistent-hint
                      outlined
                      dense
                    />

                    <v-text-field
                      v-model="inputs.superstore_general.admin_commission_global.rate"
                      :label="stepGeneral.admin_commission_global.items.rate.label"
                      :hint="stepGeneral.admin_commission_global.items.rate.hint"
                      type="number"
                      persistent-hint
                      outlined
                      dense
                    />
                  </v-col>
                </v-row>
              </v-card>

              <v-btn
                small
                depressed
                color="primary"
                @click="saveSettings('superstore_general', 2)"
              >
                {{ continueText }}
              </v-btn>

              <v-btn
                small
                outlined
                color="primary"
                class="mx-2"
                @click="e1 = 2"
              >
                {{ skip }}
              </v-btn>
            </v-stepper-content>

            <v-stepper-content step="2">
              <v-card
                class="mb-10 pa-5 text-left"
                min-height="300px"
                flat
              >
                <v-row align="center">
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepSeller.new_seller_auto_enable.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-checkbox
                      v-model="inputs.superstore_seller.new_seller_auto_enable"
                      :hint="stepSeller.new_seller_auto_enable.hint"
                      persistent-hint
                      :label="stepSeller.new_seller_auto_enable.label"
                      true-value="yes"
                      false-value="no"
                    />
                  </v-col>
                </v-row>

                <v-row align="center">
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepSeller.seller_can_change_order_status.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-checkbox
                      v-model="inputs.superstore_seller.seller_can_change_order_status"
                      type="checkbox"
                      persistent-hint
                      true-value="yes"
                      false-value="no"
                    />
                  </v-col>
                </v-row>

                <v-row align="center">
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepSeller.new_seller_auto_requires_product_publishing_review.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-checkbox
                      v-model="inputs.superstore_seller.new_seller_auto_requires_product_publishing_review"
                      :hint="stepSeller.new_seller_auto_requires_product_publishing_review.hint"
                      type="checkbox"
                      persistent-hint
                      true-value="yes"
                      false-value="no"
                    />
                  </v-col>
                </v-row>
              </v-card>

              <v-btn
                small
                depressed
                color="primary"
                @click="saveSettings('superstore_seller', 3)"
              >
                {{ continueText }}
              </v-btn>

              <v-btn
                small
                outlined
                color="primary"
                class="mx-2"
                @click="e1 = 3"
              >
                {{ skip }}
              </v-btn>
            </v-stepper-content>

            <v-stepper-content step="3">
              <v-card
                class="mb-10 pa-5 text-left"
                min-height="300px"
                flat
              >
                <v-row>
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepPayment.allowed_payment_methods.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-row no-gutters>
                      <v-col
                        v-for="(v, i) in stepPayment.allowed_payment_methods.items"
                        :key="i"
                        cols="3"
                      >
                        <v-checkbox
                          v-model="inputs.superstore_payment.allowed_payment_methods[v.value]"
                          :label="v.label"
                          true-value="yes"
                          false-value="no"
                        />
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepPayment.minimum_withdraw_amount.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-text-field
                      v-model="inputs.superstore_payment.minimum_withdraw_amount"
                      :hint="stepPayment.minimum_withdraw_amount.hint"
                      type="number"
                      persistent-hint
                      outlined
                      dense
                    />
                  </v-col>
                </v-row>

                <v-row>
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepPayment.maximum_withdraw_amount.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-text-field
                      v-model="inputs.superstore_payment.maximum_withdraw_amount"
                      :hint="stepPayment.maximum_withdraw_amount.hint"
                      type="number"
                      persistent-hint
                      outlined
                      dense
                    />
                  </v-col>
                </v-row>

                <v-row align="center">
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepPayment.exclude_cod_payment.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-checkbox
                      v-model="inputs.superstore_payment.exclude_cod_payment"
                      :hint="stepPayment.exclude_cod_payment.hint"
                      type="checkbox"
                      persistent-hint
                      true-value="yes"
                      false-value="no"
                    />
                  </v-col>
                </v-row>
              </v-card>

              <v-btn
                small
                depressed
                color="primary"
                @click="saveSettings('superstore_payment', 4)"
              >
                {{ continueText }}
              </v-btn>

              <v-btn
                small
                outlined
                color="primary"
                class="mx-2"
                @click="e1 = 4"
              >
                {{ skip }}
              </v-btn>
            </v-stepper-content>

            <v-stepper-content step="4">
              <v-card
                class="mb-10 pa-5"
                flat
                height="300px"
              >
                <v-icon x-large>
                  mdi-rocket-launch
                </v-icon>
                <div class="text-h3">
                  {{ finish.excellent }}
                </div>
                <div class="text-h5">
                  {{ finish.ready }}
                </div>

                <div class="pt-4">
                  <div>
                    {{ finish.goto }}
                  </div>
                  <v-btn
                    small
                    depressed
                    color="primary"
                    to="/"
                    @click="closeWizard"
                  >
                    {{ finish.ssdb }}
                  </v-btn>
                  <v-btn
                    small
                    depressed
                    class="mx-2"
                    color="primary"
                    to="/settings"
                    @click="closeWizard"
                  >
                    {{ finish.more_settings }}
                  </v-btn>
                  <v-btn
                    small
                    depressed
                    color="primary"
                    :href="adminUrl"
                    @click="closeWizard"
                  >
                    {{ finish.wpdb }}
                  </v-btn>
                </div>
              </v-card>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    data () {
      return {
        dialog: false,
        runWizard: false,
        stepper: false,
        e1: 1,
        adminUrl: '',
        logoUrl: this.$store.state.global.images.superstore_logo,
        stepGeneral: null,
        stepSeller: null,
        stepPayment: null,
        inputs: {},
        entry: {},
        finish: {},
        headersName: {},
        continueText: '',
        skip: '',
        overlayLoading: false,
      }
    },

    created () {
      if (window.superstore.setup_wizard.run === 'yes') {
        this.runWizard = true
      } else {
        this.runWizard = false
      }

      this.adminUrl = this.$store.state.global.admin_root_url

      this.entry = window.superstore.setup_wizard.entry
      this.headersName = window.superstore.setup_wizard.headers_name
      this.finish = window.superstore.setup_wizard.finish
      this.continueText = window.superstore.setup_wizard.continue
      this.skip = window.superstore.setup_wizard.skip

      this.assignInput()
    },
    methods: {
      async assignInput () {
        this.stepGeneral = await window.superstore.setup_wizard.form_step_general
        this.stepSeller = await window.superstore.setup_wizard.form_step_seller
        this.stepPayment = await window.superstore.setup_wizard.form_step_payment

        this.inputs.superstore_general = {}
        this.inputs.superstore_seller = {}
        this.inputs.superstore_payment = {}

        Object.keys(this.stepGeneral).forEach((v, i) => {
          this.inputs.superstore_general[v] = this.stepGeneral[v].value
        })
        Object.keys(this.stepSeller).forEach((v, i) => {
          this.inputs.superstore_seller[v] = this.stepSeller[v].value
        })
        Object.keys(this.stepPayment).forEach((v, i) => {
          this.inputs.superstore_payment[v] = this.stepPayment[v].value
        })
      },
      closeWizard () {
        this.overlayLoading = true
        const qs = require('qs')
        var data = {
          action: 'superstore_close_admin_wizard',
        }
        window.$http.post(window.superstore.global.ajaxurl, qs.stringify(data))
          .then(response => {
            this.runWizard = false
            this.overlayLoading = false
            window.location.reload()
          })
          .catch(error => {
            console.log(error)
          })
          .then(() => {
            this.overlayLoading = false
          })
      },
      runStepper () {
        this.stepper = true
      },
      saveSettings (section, step) {
        this.overlayLoading = true
        const qs = require('qs')
        var data = {
          action: 'superstore_save_settings',
          nonce: window.superstore.global.nonce,
          values: this.inputs[section],
          section: section,
        }

        window.$http.post(window.superstore.global.ajaxurl, qs.stringify(data))
          .then(response => {
            this.overlayLoading = false
            this.e1 = step
          })
          .catch(error => {
            console.log(error)
          })
          .then(() => {
            this.overlayLoading = false
          })
      },
    },
  }
</script>
