<template>
  <v-card
    id="superstore-seller-dashboard-single-orders"
    min-height="78vh"
  >
    <base-overlay-loading />

    <div
      v-if="!$store.state.apiResponse && !$store.state.overlayLoading.enable"
      class="pa-10"
    >
      <v-alert
        dense
        outlined
        type="error"
        class="text-left"
      >
        {{ $store.state.order.tab.body.orders.single.order_not_found }}
      </v-alert>
    </div>

    <div v-if="$store.state.apiResponse && $store.state.apiResponse.id !== 0">
      <base-form>
        <template v-slot:date_created>
          {{ formattedDate($store.state.form.fields.date_created) }}
        </template>
        <template v-slot:products="item">
          <v-simple-table dense>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">
                    {{ item.field.items.line_items.table_name_text }}
                  </th>
                  <th class="text-left">
                    {{ item.field.items.line_items.table_qty_text }}
                  </th>
                  <th class="text-left">
                    {{ item.field.items.line_items.table_total_text }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(product, index) in $store.state.form.fields.line_items"
                  :key="index"
                  class="ssRemoveHover"
                >
                  <td>
                    <v-btn
                      color="primary"
                      text
                      small
                      :to="'/product/' + product.product_id"
                      style="padding: 0 !important;"
                    >
                      {{ product.name }}
                    </v-btn>
                  </td>
                  <td>{{ product.quantity }}</td>
                  <td>{{ product.total }}</td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </template>
        <template v-slot:customer_details="item">
          <v-row v-if="$store.state.form.fields.billing">
            <v-col
              cols="4"
              class="pr-3"
            >
              <v-card-title class="text-caption pa-0 ma-0">
                {{ item.field.items.name.title }}
              </v-card-title>
            </v-col>
            <v-col
              cols="8"
            >
              {{ $store.state.form.fields.billing.first_name }}
              {{ ' ' }}
              {{ $store.state.form.fields.billing.last_name }}
            </v-col>

            <v-col
              cols="4"
              class="pr-3"
            >
              <v-card-title class="text-caption pa-0 ma-0">
                {{ item.field.items.email.title }}
              </v-card-title>
            </v-col>
            <v-col
              cols="8"
            >
              {{ $store.state.form.fields.billing.email }}
            </v-col>

            <v-col
              cols="4"
              class="pr-3"
            >
              <v-card-title class="text-caption pa-0 ma-0">
                {{ item.field.items.phone.title }}
              </v-card-title>
            </v-col>
            <v-col
              cols="8"
            >
              {{ $store.state.form.fields.billing.phone }}
            </v-col>

            <v-col
              cols="4"
              class="pr-3"
            >
              <v-card-title class="text-caption pa-0 ma-0">
                {{ item.field.items.ip.title }}
              </v-card-title>
            </v-col>
            <v-col
              cols="8"
            >
              {{ $store.state.form.fields.customer_ip_address }}
            </v-col>
          </v-row>
        </template>
        <template v-slot:billing_shipping="item">
          <v-row>
            <v-col
              cols="4"
              class="pr-3"
            >
              <v-card-title class="text-caption pa-0 ma-0">
                {{ item.field.billing.title }}
              </v-card-title>
            </v-col>
            <v-col
              v-if="$store.state.form.fields.billing"
              cols="8"
            >
              <div>
                {{ $store.state.form.fields.billing.address_1 ? $store.state.form.fields.billing.address_1 : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.billing.address_2 ? $store.state.form.fields.billing.address_2 : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.billing.city ? $store.state.form.fields.billing.city : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.billing.state && $store.state.form.fields.billing.country ? ($store.state.global.wc_states[$store.state.form.fields.billing.country] ? ($store.state.global.wc_states[$store.state.form.fields.billing.country][$store.state.form.fields.billing.state] ? $store.state.global.wc_states[$store.state.form.fields.billing.country][$store.state.form.fields.billing.state] : '') : '') : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.billing.postcode ? $store.state.form.fields.billing.postcode : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.billing.country ? $store.state.global.wc_countries[$store.state.form.fields.billing.country] : '' }}
              </div>
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="4"
              class="pr-3"
            >
              <v-card-title class="text-caption pa-0 ma-0">
                {{ item.field.shipping.title }}
              </v-card-title>
            </v-col>
            <v-col
              v-if="$store.state.form.fields.shipping"
              cols="8"
            >
              <div>
                {{ $store.state.form.fields.shipping.address_1 ? $store.state.form.fields.shipping.address_1 : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.shipping.address_2 ? $store.state.form.fields.shipping.address_2 : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.shipping.city ? $store.state.form.fields.shipping.city : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.shipping.state && $store.state.form.fields.shipping.country ? ($store.state.global.wc_states[$store.state.form.fields.shipping.country] ? ($store.state.global.wc_states[$store.state.form.fields.shipping.country][$store.state.form.fields.shipping.state] ? $store.state.global.wc_states[$store.state.form.fields.shipping.country][$store.state.form.fields.shipping.state] : '') : '') : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.shipping.postcode ? $store.state.form.fields.shipping.postcode : '' }}
              </div>
              <div>
                {{ $store.state.form.fields.shipping.country ? $store.state.global.wc_countries[$store.state.form.fields.shipping.country] : '' }}
              </div>
            </v-col>
          </v-row>
        </template>
        <template v-slot:downloads="item">
          <v-overlay
            v-model="downloadLoading"
            :absolute="true"
          >
            <v-progress-circular
              indeterminate
              size="30"
            />
          </v-overlay>
          <v-simple-table dense>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">
                    {{ item.field.items.downloadable_items.table_name_text }}
                  </th>
                  <th class="text-left">
                    {{ item.field.items.downloadable_items.table_download_text }}
                  </th>
                  <th class="text-left">
                    {{ item.field.items.downloadable_items.table_remaining_text }}
                  </th>
                  <th class="text-left">
                    {{ item.field.items.downloadable_items.table_expires_text }}
                  </th>
                  <th class="text-left">
                    {{ item.field.items.downloadable_items.table_action_text }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(dItem, dItemIndex) in $store.state.form.fields.downloadable_items"
                  :key="dItemIndex"
                  class="ssRemoveHover"
                >
                  <td>{{ dItem.product_name }}</td>
                  <td>{{ dItem.download_count + ' times' }}</td>
                  <td>{{ dItem.downloads_remaining }}</td>
                  <td>{{ dItem.access_expires ? formattedDate(dItem.access_expires.date) : '' }}</td>
                  <td>
                    <v-btn
                      color="primary"
                      small
                      depressed
                      @click="revokeAccess(dItem.id, dItem.order_id)"
                    >
                      {{ item.field.items.downloadable_items.revoke_text }}
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>

          <v-autocomplete
            v-model="newGrantAccess"
            outlined
            :items="downloadableProducts"
            item-text="formatted_name"
            item-value="id"
            dense
            chips
            small-chips
            :label="item.field.items.downloadable_items.choose_text"
            multiple
            class="my-3"
          >
            <template v-slot:selection="{ attrs, on, item, selected, parent, index }">
              <v-chip
                v-if="index < 3"
                v-bind="attrs"
                :input-value="selected"
                small
                label
                class="ma-1"
                v-on="on"
              >
                <span class="pr-2">
                  {{ item.formatted_name }}
                </span>
              </v-chip>

              <span
                v-else-if="index === 3"
                class="font-weight-bold mx-2"
              >
                +{{ newGrantAccess ? (newGrantAccess.length - 3) : null }}
              </span>
            </template>
            <template v-slot:append>
              <v-btn
                small
                depressed
                color="primary"
                class="ma-0"
                @click="grantAccess()"
              >
                {{ item.field.items.downloadable_items.grant_text }}
              </v-btn>
            </template>
          </v-autocomplete>
        </template>

        <template v-slot:notes="item">
          <div>
            <v-overlay
              v-model="noteLoading"
              :absolute="true"
            >
              <v-progress-circular
                indeterminate
                size="30"
              />
            </v-overlay>
            <div
              v-for="(note2, noteIndex) in notes"
              :key="noteIndex"
            >
              <v-card
                flat
                :color="note2.customer_note === 'yes' ? 'blue lighten-4' : 'grey lighten-3'"
                class="text-caption ma-2 pa-2 black--text"
              >
                <div>
                  {{ note2.content }}
                </div>
                <span class="font-italic">
                  {{ note2.time }} {{ item.field.ago_text }} {{ item.field.by_text }} {{ note2.author }}
                </span>
                <strong
                  class="red--text text--darken-2"
                  style="cursor: pointer;"
                  @click="deleteNotes(note2.note_id)"
                >
                  {{ item.field.delete_text }}
                </strong>
              </v-card>
            </div>
            <v-textarea
              v-model="note"
              :placeholder="item.field.placeholder"
              type="textarea"
              persistent-hint
              outlined
              dense
              clearable
              counter
            />
            <v-select
              v-model="noteType"
              :items="item.field.customer_note"
              item-text="name"
              item-value="value"
              type="select"
              persistent-hint
              outlined
              dense
            />
            <v-btn
              depressed
              color="primary"
              @click="createNotes()"
            >
              {{ item.field.add_text }}
            </v-btn>
          </div>
        </template>
      </base-form>
    </div>
  </v-card>
</template>

<script>
  export default {
    data () {
      return {
        downloadableProducts: [],
        newGrantAccess: null,
        noteType: 'private',
        note: '',
        noteLoading: false,
        notes: [],
        downloadLoading: false,
      }
    },

    async created () {
      this.$store.commit('SET_FORM', this.$store.state.order.tab.body.orders.single.form)
      await this.getOrder()
      this.getDownloadableProducts()
      this.getNotes()
    },

    methods: {
      async getNotes () {
        this.noteLoading = true
        await window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/orders/notes',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'get',
          params: { post_id: this.$route.params.id },
          paramsSerializer: function (params) {
            const qs = require('qs')
            return qs.stringify(params, { arrayFormat: 'brackets' })
          },
        })
          .then(response => {
            this.noteLoading = false
            this.notes = response.data
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response ? error.response.data.message : error })
          })
          .then(() => {
            this.noteLoading = false
          })
      },
      async createNotes () {
        this.noteLoading = true
        await window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/orders/notes',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'post',
          data: { post_id: this.$route.params.id, note_type: this.noteType, note: this.note },
        })
          .then(response => {
            this.noteLoading = false
            this.getNotes()
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response ? error.response.data.message : error })
          })
          .then(() => {
            this.noteLoading = false
          })
      },
      async deleteNotes ($noteId) {
        this.noteLoading = true
        await window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/orders/notes',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'delete',
          params: { post_id: this.$route.params.id, note_id: $noteId },
          paramsSerializer: function (params) {
            const qs = require('qs')
            return qs.stringify(params, { arrayFormat: 'brackets' })
          },
        })
          .then(response => {
            this.noteLoading = false
            this.getNotes()
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response ? error.response.data.message : error })
          })
          .then(() => {
            this.noteLoading = false
          })
      },
      formattedDate (value) {
        if (!value) return ''
        var dateObj = new Date(value)
        var date = dateObj.getDate() + ' ' + dateObj.toLocaleString('en-us', { month: 'short' }) + ' ' + dateObj.getFullYear()
        return date
      },
      async getOrder () {
        await this.$store.dispatch('api', { endpoint: '/orders/' + this.$route.params.id, method: 'get' })
        this.$store.commit('SET_FORM', { fields: this.$store.state.apiResponse })
      },
      getDownloadableProducts () {
        window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/products/downloadable',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'get',
        })
          .then(response => {
            this.downloadableProducts = response.data
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response.data.message ? error.response.data.message : error })
          })
      },
      grantAccess () {
        this.$store.commit('SET_NOTIFY', { enable: false })
        this.downloadLoading = true
        window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/orders/grant-access',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'post',
          data: {
            product_ids: this.newGrantAccess,
            order_id: this.$route.params.id,
          },
        })
          .then(response => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'success', msg: response.data })
            this.newGrantAccess = null
            this.getOrder()
            this.downloadLoading = false
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response.data.message ? error.response.data.message : error })
          })
          .then(() => {
            this.downloadLoading = false
          })
      },
      revokeAccess (permissionId, orderId) {
        this.$store.commit('SET_NOTIFY', { enable: false })
        this.downloadLoading = true
        window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/orders/revoke-access',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'patch',
          data: {
            permission_id: permissionId,
            order_id: orderId,
          },
        })
          .then(response => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'success', msg: response.data })
            this.getOrder()
            this.downloadLoading = false
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response.data.message ? error.response.data.message : error })
          })
          .then(() => {
            this.downloadLoading = false
          })
      },
    },
  }
</script>

<style>
  .ssRemoveHover:hover {
    background-color: transparent !important;
  }
</style>
