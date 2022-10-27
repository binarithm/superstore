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
              {{ headersName.account }}
            </v-stepper-step>

            <v-divider />

            <v-stepper-step
              :complete="e1 > 2"
              step="2"
            >
              {{ headersName.payment }}
            </v-stepper-step>

            <v-divider />

            <v-stepper-step
              step="3"
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
                      {{ stepAccount.banner_id.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <base-file-uploader
                      :height="150"
                      :cropping-width="1920"
                      :cropping-height="300"
                      :src="bannerSrc"
                      @change="uploadBanner($event)"
                      @delete="deleteBanner($event)"
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepAccount.profile_picture_id.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <base-file-uploader
                      :height="150"
                      :cropping-width="100"
                      :cropping-height="100"
                      :src="profilePictureSrc"
                      @change="uploadProfilePicture($event)"
                      @delete="deleteProfilePicture($event)"
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepAccount.store_name.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-text-field
                      v-model="inputs.account.store_name"
                      dense
                      outlined
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepAccount.address.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-autocomplete
                      v-model="inputs.account.address_country"
                      :items="countries"
                      item-text="name"
                      item-value="code"
                      dense
                      outlined
                      :label="stepAccount.address.items.address_country.label"
                      @input="getStates($event)"
                    />
                    <v-autocomplete
                      v-if="hasState"
                      v-model="inputs.account.address_state"
                      :items="states"
                      item-text="name"
                      item-value="code"
                      outlined
                      dense
                      :label="stepAccount.address.items.address_state.label"
                    />
                    <v-text-field
                      v-model="inputs.account.address_city"
                      dense
                      outlined
                      :label="stepAccount.address.items.address_city.label"
                    />
                    <v-text-field
                      v-model="inputs.account.address_street_1"
                      dense
                      outlined
                      :label="stepAccount.address.items.address_street_1.label"
                    />
                    <v-text-field
                      v-model="inputs.account.address_street_2"
                      dense
                      outlined
                      :label="stepAccount.address.items.address_street_2.label"
                    />
                    <v-text-field
                      v-model="inputs.account.address_postcode"
                      dense
                      outlined
                      :label="stepAccount.address.items.address_postcode.label"
                    />
                  </v-col>
                </v-row>
              </v-card>

              <v-btn
                small
                depressed
                color="primary"
                @click="saveSettings('account', 2)"
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
                <v-row>
                  <v-col
                    cols="4"
                    class="pr-3"
                  >
                    <div class="text-caption pa-0 ma-0">
                      {{ stepPayment.payment_method_paypal_email.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-text-field
                      v-model="inputs.payment.payment_method_paypal_email"
                      :rules="[v => v !== '' ? /.+@.+\..+/.test(v) || $store.state.global.form.valid_email_text : true]"
                      :hint="stepPayment.payment_method_paypal_email.hint"
                      type="email"
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
                      {{ stepPayment.payment_method_skrill_email.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-text-field
                      v-model="inputs.payment.payment_method_skrill_email"
                      :rules="[v => v !== '' ? /.+@.+\..+/.test(v) || $store.state.global.form.valid_email_text : true]"
                      :hint="stepPayment.payment_method_skrill_email.hint"
                      type="email"
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
                      {{ stepPayment.bank_details.title }}
                    </div>
                  </v-col>
                  <v-col
                    cols="8"
                  >
                    <v-text-field
                      v-model="inputs.payment.payment_method_bank_ac_name"
                      dense
                      outlined
                      :label="stepPayment.bank_details.items.payment_method_bank_ac_name.label"
                    />
                    <v-text-field
                      v-model="inputs.payment.payment_method_bank_ac_number"
                      dense
                      outlined
                      :label="stepPayment.bank_details.items.payment_method_bank_ac_number.label"
                    />
                    <v-text-field
                      v-model="inputs.payment.payment_method_bank_name"
                      dense
                      outlined
                      :label="stepPayment.bank_details.items.payment_method_bank_name.label"
                    />
                    <v-text-field
                      v-model="inputs.payment.payment_method_bank_address"
                      dense
                      outlined
                      :label="stepPayment.bank_details.items.payment_method_bank_address.label"
                    />
                    <v-text-field
                      v-model="inputs.payment.payment_method_bank_routing_number"
                      dense
                      outlined
                      :label="stepPayment.bank_details.items.payment_method_bank_routing_number.label"
                    />
                    <v-text-field
                      v-model="inputs.payment.payment_method_bank_iban"
                      dense
                      outlined
                      :label="stepPayment.bank_details.items.payment_method_bank_iban.label"
                    />
                    <v-text-field
                      v-model="inputs.payment.payment_method_bank_swift"
                      dense
                      outlined
                      :label="stepPayment.bank_details.items.payment_method_bank_swift.label"
                    />
                  </v-col>
                </v-row>
              </v-card>

              <v-btn
                small
                depressed
                color="primary"
                @click="saveSettings('payment', 3)"
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
    name: 'App',
    data: () => ({
      dialog: false,
      runWizard: false,
      stepper: false,
      e1: 1,
      stepAccount: null,
      stepPayment: null,
      inputs: {},
      entry: {},
      finish: {},
      headersName: {},
      continueText: '',
      skip: '',
      countries: [],
      states: [],
      hasState: false,
      overlayLoading: false,
      bannerSrc: false,
      profilePictureSrc: false,
    }),
    created () {
      if (window.superstore.setup_wizard.run === 'yes') {
        this.runWizard = true
      } else {
        this.runWizard = false
      }

      this.entry = window.superstore.setup_wizard.entry
      this.headersName = window.superstore.setup_wizard.headers_name
      this.finish = window.superstore.setup_wizard.finish
      this.continueText = window.superstore.setup_wizard.continue
      this.skip = window.superstore.setup_wizard.skip

      this.assignInput()

      this.setLocation()

      this.getLocation()
    },
    methods: {
      getLocation () {
        if (this.$store.state.global.google_map_api_key === '') {
          return
        }
        this.$getLocation({})
          .then(coordinates => {
            this.saveGeolocation(coordinates.lat, coordinates.lng)
          })
          .catch(error => {
            console.log(error)
          })
      },
      saveGeolocation (latitude, longitude) {
        this.overlayLoading = true
        const qs = require('qs')
        var data = {
          action: 'superstore_save_seller_settings',
          nonce: window.superstore.global.nonce,
          values: {
            geolocation_latitude: latitude,
            geolocation_longitude: longitude,
          },
        }
        window.$http.post(window.superstore.global.ajaxurl, qs.stringify(data))
          .then(response => {
            this.overlayLoading = false
          })
          .catch(error => {
            console.log(error.response.data)
          })
          .then(() => {
            this.overlayLoading = false
          })
      },
      uploadProfilePicture (event) {
        this.inputs.account.profile_picture_id = event.id
        this.profilePictureSrc = event.url
      },
      deleteProfilePicture (event) {
        this.profilePictureSrc = false
        this.inputs.account.profile_picture_id = 0
      },
      uploadBanner (event) {
        this.inputs.account.banner_id = event.id
        this.bannerSrc = event.url
      },
      deleteBanner (event) {
        this.bannerSrc = false
        this.inputs.account.banner_id = 0
      },
      setLocation () {
        var countries = []
        Object.keys(this.$store.state.global.wc_countries).forEach((v, i) => {
          countries[i] = {}
          countries[i] = {
            code: v,
            name: this.$store.state.global.wc_countries[v],
          }
        })

        this.countries = countries
      },
      getStates (event) {
        this.hasState = false
        var states = []
        if (this.$store.state.global.wc_states[event] !== undefined && this.$store.state.global.wc_states[event].length !== 0) {
          this.hasState = true
          Object.keys(this.$store.state.global.wc_states[event]).forEach((v2, i2) => {
            states[i2] = {}
            states[i2] = {
              code: v2,
              name: this.$store.state.global.wc_states[event][v2],
            }
          })
        }
        this.states = states
      },
      async assignInput () {
        this.stepAccount = await window.superstore.setup_wizard.form_step_account
        this.stepPayment = await window.superstore.setup_wizard.form_step_payment

        this.inputs.account = {}
        this.inputs.payment = {}
        Object.keys(this.stepAccount).forEach((v, i) => {
          this.inputs.account[v] = this.stepAccount[v].value
        })
        Object.keys(this.stepPayment).forEach((v, i) => {
          this.inputs.payment[v] = this.stepPayment[v].value
        })
      },
      closeWizard () {
        this.overlayLoading = true
        const qs = require('qs')
        var data = {
          action: 'superstore_close_seller_wizard',
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
          action: 'superstore_save_seller_settings',
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
            console.log(error.response.data)
          })
          .then(() => {
            this.overlayLoading = false
          })
      },
    },
  }
</script>
