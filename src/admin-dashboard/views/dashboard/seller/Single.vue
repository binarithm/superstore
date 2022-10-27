<template>
  <v-card
    id="superstore-admin-dashboard-single-seller"
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
        {{ $store.state.seller.tab.body.sellers.single.seller_not_found }}
      </v-alert>
    </div>

    <div v-if="$store.state.apiResponse && $store.state.apiResponse.id !== 0">
      <base-file-uploader
        :height="300"
        :cropping-width="1920"
        :cropping-height="300"
        :src="$store.state.form.fields.banner_src ? $store.state.form.fields.banner_src : $store.state.global.images.default_banner"
        @change="uploadFile($event, 'banner')"
        @delete="deleteFile($event, 'banner')"
      />

      <v-row
        class="pa-5"
      >
        <v-col
          cols="12"
          md="2"
          class="text-center"
        >
          <base-file-uploader
            :avatar="true"
            :only-icon-btn="true"
            :hide-icon-placeholder="true"
            height="auto"
            :cropping-width="100"
            :cropping-height="100"
            :src="$store.state.form.fields.profile_picture_src"
            @change="uploadFile($event, 'profile_picture')"
            @delete="deleteFile($event, 'profile_picture')"
          />
          <div class="pa-2">
            <span class="text-body-1 font-weight-bold">{{ $store.state.form.fields.store_name }}</span>
          </div>
        </v-col>
        <v-col
          cols="10"
        >
          <v-row no-gutters>
            <v-col
              cols="12"
              class="pb-5"
            >
              <v-menu
                transition="scroll-y-reverse-transition"
                open-on-hover
                bottom
                left
                offset-y
                origin="top right"
                content-class="elevation-2"
                :close-on-content-click="false"
              >
                <template v-slot:activator="{ attrs, on }">
                  <v-btn
                    outlined
                    color="primary"
                    class="my-2 mx-1"
                    small
                    v-bind="attrs"
                    v-on="on"
                  >
                    {{ $store.state.seller.tab.body.sellers.single.link_btn_action_title }}
                    <v-icon right>
                      mdi-menu-down
                    </v-icon>
                  </v-btn>
                </template>

                <v-list
                  :tile="false"
                  nav
                  dense
                >
                  <v-hover
                    v-for="(action, actionIndex) in $store.state.seller.tab.body.sellers.table.actions"
                    :key="actionIndex"
                    v-slot="{ hover }"
                  >
                    <v-list-item
                      :class="`py-0 px-2 ${hover ? 'primary white--text' : ''}`"
                      link
                      @click="onAction(action.method, action.name, action.value)"
                      v-text="action.title"
                    />
                  </v-hover>
                </v-list>
              </v-menu>
              <v-btn
                v-for="(linkBtn, linkBtnIndex) in $store.state.seller.tab.body.sellers.single.link_btns"
                :key="linkBtnIndex"
                outlined
                color="primary"
                class="my-2 mx-1"
                small
                target="_blank"
                link
                :href="linkBtns(linkBtn.name)"
                v-html="linkBtn.title"
              />
            </v-col>
            <v-col
              cols="12"
              class="pb-5"
            >
              <div class="text-body-2 font-weight-bold pb-2">
                {{ $store.state.seller.tab.body.sellers.single.short_details_text }}
              </div>

              <span
                class="pr-2 text-caption"
              >
                <v-icon
                  small
                >
                  mdi-calendar-month
                </v-icon>
                {{ formatDate($store.state.form.fields.date_created) }}
              </span>

              <span
                class="pr-2 text-caption"
              >
                <v-icon
                  small
                >
                  mdi-email
                </v-icon>
                {{ $store.state.form.fields.email }}
              </span>
              <span
                class="pr-2 text-caption"
              >
                <v-icon
                  small
                >
                  mdi-phone
                </v-icon>
                {{ $store.state.form.fields.phone }}
              </span>
              <span
                class="pr-2 text-caption"
              >
                <v-icon
                  small
                >
                  mdi-map-marker-radius
                </v-icon>
                {{ $store.state.form.fields.address.city !== '' ? $store.state.form.fields.address.city + ', ' : null }}
                {{ $store.state.form.fields.address.country !== '' && $store.state.form.fields.address.state !== '' && $store.state.global.wc_states[$store.state.form.fields.address.country] !== undefined ? $store.state.global.wc_states[$store.state.form.fields.address.country][$store.state.form.fields.address.state] + ', ' : null }}

                {{ $store.state.form.fields.address.country !== '' ? $store.state.global.wc_countries[$store.state.form.fields.address.country] : null }}
              </span>
            </v-col>
            <v-col
              cols="12"
            >
              <div class="text-body-2 font-weight-bold">
                {{ $store.state.seller.tab.body.sellers.single.about_title }}
              </div>
              <p class="text-caption">
                {{ $store.state.form.fields.about }}
              </p>
            </v-col>
          </v-row>
        </v-col>
      </v-row>

      <div
        v-show="$store.state.form.fields.geolocation.latitude !== 0 && $store.state.form.fields.geolocation.longitude !== 0"
        class="pa-5"
      >
        <GmapMap
          :center="{
            lat: $store.state.form.fields.geolocation.latitude ? Number($store.state.form.fields.geolocation.latitude) : 0,
            lng: $store.state.form.fields.geolocation.longitude ? Number($store.state.form.fields.geolocation.longitude) : 0,
          }"
          :zoom="14"
          style="width: 100%; height: 300px"
        />
      </div>

      <v-row
        class="pa-5"
      >
        <v-col
          v-for="(overview, overviewIndex) in overviews"
          :key="overviewIndex"
          cols="12"
          md="3"
          class="text-center"
        >
          <v-card
            class="mx-auto fill-height ma-0"
            outlined
          >
            <v-card-text
              :class="$vuetify.theme.dark ? 'grey darken-4 text-caption font-weight-bold py-2' : 'grey lighten-3 text-caption font-weight-bold py-2'"
            >
              {{ overview.title }}
            </v-card-text>
            <div
              v-for="(overviewItem, overviewItemIndex) in overview.items"
              :key="overviewItemIndex"
              class="mx-10 my-2"
            >
              <v-row>
                <v-col
                  cols="4"
                  class="pr-3"
                >
                  <v-card-title class="text-caption pa-0 ma-0">
                    {{ overviewItem.title }}
                  </v-card-title>
                </v-col>
                <v-col
                  cols="8"
                >
                  <span v-html="overviewItem.value" />
                </v-col>
              </v-row>
            </div>
          </v-card>
        </v-col>
      </v-row>

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

        <template v-slot:address="item">
          <v-autocomplete
            :value="$store.state.form.fields.address.country"
            :items="item.field.items.address_country.items"
            item-text="title"
            item-value="value"
            :name="item.field.items.address_country.name"
            :label="item.field.items.address_country.label"
            :no-data-text="$store.state.global.table.no_item_found_text"
            persistent-hint
            clearable
            dense
            outlined
            @change="getStates($event, item.field.items.address_country.name)"
          />
          <v-autocomplete
            v-if="hasState"
            :value="$store.state.form.fields.address.state"
            :items="states"
            item-text="title"
            item-value="value"
            :name="item.field.items.address_state.name"
            :label="item.field.items.address_state.label"
            :no-data-text="$store.state.global.table.no_item_found_text"
            persistent-hint
            clearable
            outlined
            dense
            @change="updateStates($event, item.field.items.address_state.name)"
          />
          <v-text-field
            :value="$store.state.form.fields.address.postcode"
            :name="item.field.items.address_postcode.name"
            :label="item.field.items.address_postcode.label"
            type="text"
            persistent-hint
            outlined
            dense
            @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.address_postcode.name]: $event })"
          />
          <v-text-field
            :value="$store.state.form.fields.address.city"
            :name="item.field.items.address_city.name"
            :label="item.field.items.address_city.label"
            type="text"
            persistent-hint
            outlined
            dense
            @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.address_city.name]: $event })"
          />
          <v-text-field
            :value="$store.state.form.fields.address.street_1"
            :name="item.field.items.address_street_1.name"
            :label="item.field.items.address_street_1.label"
            type="text"
            persistent-hint
            outlined
            dense
            @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.address_street_1.name]: $event })"
          />
          <v-text-field
            :value="$store.state.form.fields.address.street_2"
            :name="item.field.items.address_street_2.name"
            :label="item.field.items.address_street_2.label"
            type="text"
            persistent-hint
            outlined
            dense
            @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.address_street_2.name]: $event })"
          />
        </template>

        <template v-slot:store_time="item">
          <v-checkbox
            :input-value="$store.state.form.fields.store_time.enabled"
            :name="item.field.items.store_time_enabled.name"
            :label="item.field.items.store_time_enabled.label"
            type="checkbox"
            persistent-hint
            outlined
            dense
            true-value="yes"
            false-value="no"
            @change="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.store_time_enabled.name]: $event })"
          />

          <div v-if="$store.state.form.fields.store_time_enabled === 'yes' || $store.state.form.fields.store_time.enabled === 'yes'">
            <v-text-field
              :value="$store.state.form.fields.store_time.open_notice"
              :name="item.field.items.store_time_open_notice.name"
              :label="item.field.items.store_time_open_notice.label"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.store_time_open_notice.name]: $event })"
            />
            <v-text-field
              :value="$store.state.form.fields.store_time.close_notice"
              :name="item.field.items.store_time_close_notice.name"
              :label="item.field.items.store_time_close_notice.label"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.store_time_close_notice.name]: $event })"
            />
            <v-text-field
              :value="$store.state.form.fields.store_time.off_day_notice"
              :name="item.field.items.store_time_off_day_notice.name"
              :label="item.field.items.store_time_off_day_notice.label"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.store_time_off_day_notice.name]: $event })"
            />
            <v-text-field
              :value="$store.state.form.fields.store_time.open_24_hours_notice"
              :name="item.field.items.store_time_open_24_hours_notice.name"
              :label="item.field.items.store_time_open_24_hours_notice.label"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { [item.field.items.store_time_open_24_hours_notice.name]: $event })"
            />

            <div
              v-for="(item2, item2Index) in item.field.items2"
              :key="item2Index"
              class="pa-2 mb-3"
              style="border: 0.5px solid grey;border-radius: 5px;"
            >
              <div class="pb-2">
                {{ item2.title }}
              </div>
              <v-radio-group
                :value="$store.state.form.fields.store_time[item2.child_name]"
                :name="item2.name"
                mandatory
                row
                class="ma-0"
                @change="$store.commit('UPDATE_FORM_FIELDS', { [item2.name]: $event })"
              >
                <v-radio
                  v-for="(group, group_index) in item2.groups"
                  :key="group_index"
                  type="radio"
                  :label="group.label"
                  :value="group.value"
                  class="mr-7"
                />
              </v-radio-group>
              <div
                v-if="$store.state.form.fields[item2.name] !== undefined ? $store.state.form.fields[item2.name] === 'yes' : $store.state.form.fields.store_time[item2.child_name] === 'yes'"
              >
                <v-menu
                  ref="menu"
                  v-model="menu[item2.child_name]"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  offset-y
                  class="pa-0 ma-0"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      :value="formatAMPM(item2.opening_hours.child_name)"
                      :name="item2.opening_hours.name"
                      :label="item2.opening_hours.label"
                      readonly
                      persistent-hint
                      outlined
                      dense
                      v-bind="attrs"
                      v-on="on"
                    />
                  </template>
                  <v-card
                    elevation="0"
                    class="pa-3 ma-0"
                  >
                    <v-row
                      class="text-center"
                    >
                      <v-col
                        cols="12"
                      >
                        <v-btn
                          depressed
                          small
                          block
                          color="primary"
                          @click="open24(item2.opening_hours.name, item2.child_name)"
                        >
                          {{ 'Click for 24 hours open' }}
                        </v-btn>
                      </v-col>
                      <v-col
                        cols="6"
                      >
                        <div class="text-body-1 font-weight-bold">
                          {{ item.field.open_hour_text }}
                        </div>
                        <v-time-picker
                          :value="openTime(item2.opening_hours.child_name)"
                          scrollable
                          full-width
                          ampm-in-title
                          elevation="1"
                          class="mt-0"
                          @input="updateStartInput($event, item2.opening_hours.name)"
                        />
                      </v-col>
                      <v-col cols="6">
                        <div class="text-body-1 font-weight-bold">
                          {{ item.field.close_hour_text }}
                        </div>
                        <v-time-picker
                          :value="closeTime(item2.opening_hours.child_name)"
                          scrollable
                          full-width
                          ampm-in-title
                          elevation="1"
                          class="mt-0"
                          @input="updateEndInput($event, item2.opening_hours.name)"
                          @update:period="menu[item2.child_name] = false"
                        />
                      </v-col>
                    </v-row>
                    <v-col
                      cols="12"
                    >
                      <v-btn
                        depressed
                        small
                        block
                        color="primary"
                        @click="menu[item2.child_name] = false"
                      >
                        {{ item.field.close_menu_text }}
                      </v-btn>
                    </v-col>
                  </v-card>
                </v-menu>
              </div>

              <div>
                <div
                  v-if="$store.state.form.fields.store_time[item2.child_name] === 'yes'"
                >
                  <span v-if="$store.state.form.fields.store_time[item2.opening_hours.child_name] === '24'">
                    {{ $store.state.form.fields.store_time.open_24_hours_notice }}
                  </span>
                  <span
                    v-else
                    :class="closeStoreOnTime(item2.opening_hours.child_name) ? 'green--text' : 'yellow--text'"
                  >
                    {{ closeStoreOnTime(item2.opening_hours.child_name) ? $store.state.form.fields.store_time.open_notice : $store.state.form.fields.store_time.close_notice }}
                  </span>
                </div>
                <div
                  v-else
                  class="red--text"
                >
                  {{ $store.state.form.fields.store_time.off_day_notice }}
                </div>
              </div>
            </div>
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
        hasState: false,
        states: [],
        menu: {
          open_sunday: false,
          open_monday: false,
          open_tuesday: false,
          open_wednesday: false,
          open_thursday: false,
          open_friday: false,
          open_saturday: false,
        },
        inputs: {},
        openTimeEvent: '',
        closeTimeEvent: '',
        overviews: null,
      }
    },

    async created () {
      this.$store.commit('SET_FORM', this.$store.state.seller.tab.body.sellers.single.form)
      await this.getSeller()
      if (this.$store.state.form.fields.address.country !== '') {
        this.getStates(this.$store.state.form.fields.address.country, 'address_country')
      }
      this.setOverviews()
    },

    methods: {
      setOverviews () {
        var data = this.$store.state.seller.tab.body.sellers.single.overviews

        const self = this

        Object.keys(self.$store.state.apiResponse.products_overview).forEach((v, i) => {
          if (data.products_overview.items[v] !== undefined) {
            data.products_overview.items[v].value = self.$store.state.apiResponse.products_overview[v]
          }
        })

        Object.keys(self.$store.state.apiResponse.orders_overview).forEach((v, i) => {
          if (data.orders_overview.items[v] !== undefined) {
            data.orders_overview.items[v].value = self.$store.state.apiResponse.orders_overview[v]
          }
        })

        Object.keys(self.$store.state.apiResponse.payments_overview).forEach((v, i) => {
          if (data.payments_overview.items[v] !== undefined) {
            data.payments_overview.items[v].value = self.$store.state.apiResponse.payments_overview[v]
          }
        })

        Object.keys(self.$store.state.apiResponse.media_overview).forEach((v, i) => {
          if (data.media_overview.items[v] !== undefined) {
            data.media_overview.items[v].value = self.$store.state.apiResponse.media_overview[v]
          }
        })

        this.overviews = data
      },
      open24 (inputIndex, menuName) {
        this.menu[menuName] = false
        this.$store.commit('UPDATE_FORM_FIELDS', { [inputIndex]: '24' })
      },
      closeStoreOnTime (fieldName) {
        var startTime = this.openTime(fieldName)
        var endTime = this.closeTime(fieldName)

        var currentDate = new Date()

        var startDate = new Date(currentDate.getTime())
        startDate.setHours(startTime.split(':')[0])
        startDate.setMinutes(startTime.split(':')[1])

        var endDate = new Date(currentDate.getTime())
        endDate.setHours(endTime.split(':')[0])
        endDate.setMinutes(endTime.split(':')[1])

        return startDate < currentDate && endDate > currentDate
      },
      updateTimeInput (inputIndex) {
        this.$store.commit('UPDATE_FORM_FIELDS', { [inputIndex]: this.openTimeEvent + '-' + this.closeTimeEvent })
      },
      updateStartInput (event, inputIndex) {
        this.openTimeEvent = event
        this.updateTimeInput(inputIndex)
      },
      updateEndInput (event, inputIndex) {
        this.closeTimeEvent = event
        this.updateTimeInput(inputIndex)
      },
      openTime (fieldName) {
        var startEnd = this.$store.state.form.fields.store_time[fieldName]
        if (startEnd !== '' && startEnd !== '24') {
          return startEnd.split('-')[0]
        }
      },
      closeTime (fieldName) {
        var startEnd = this.$store.state.form.fields.store_time[fieldName]
        if (startEnd !== '' && startEnd !== '24') {
          var startTime = startEnd.split('-')[0]
          var startTimeDash = startTime + '-'
          return startEnd.replace(startTimeDash, '')
        }
      },
      formatAMPM (fieldName) {
        var startEnd = this.$store.state.form.fields[`store_time_${fieldName}`] !== undefined ? this.$store.state.form.fields[`store_time_${fieldName}`] : this.$store.state.form.fields.store_time[fieldName]
        if (startEnd === '24') {
          return '24'
        }
        // Check correct time format and split into components
        if (startEnd !== '') {
          var startTime = startEnd.split('-')[0]
          var startTimeDash = startTime + '-'
          var endTime = startEnd.replace(startTimeDash, '')

          startTime = startTime.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [startTime]

          if (startTime.length > 1) { // If time format correct
            startTime = startTime.slice(1) // Remove full string match value
            startTime[5] = +startTime[0] < 12 ? 'AM' : 'PM' // Set AM/PM
            startTime[0] = +startTime[0] % 12 || 12 // Adjust hours
          }

          endTime = endTime.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [endTime]

          if (endTime.length > 1) { // If time format correct
            endTime = endTime.slice(1) // Remove full string match value
            endTime[5] = +endTime[0] < 12 ? 'AM' : 'PM' // Set AM/PM
            endTime[0] = +endTime[0] % 12 || 12 // Adjust hours
          }

          var time = startTime.join('') + '-' + endTime.join('')

          return time // return adjusted time or original string
        }
      },
      formatDate (value) {
        if (!value) return ''
        var dateObj = new Date(value)
        var date = dateObj.getDate() + ' ' + dateObj.toLocaleString('en-us', { month: 'short' }) + ' ' + dateObj.getFullYear()
        return date
      },
      updateStates (event, name = '') {
        this.$store.commit('UPDATE_FORM_FIELDS', { [name]: event !== null ? event : '' })
      },
      getStates (event, name = '') {
        this.states = []
        this.hasState = false
        if (!Array.isArray(this.$store.state.global.wc_states[event]) && this.$store.state.global.wc_states[event] !== undefined) {
          this.hasState = true
          const self = this
          Object.keys(this.$store.state.global.wc_states[event]).forEach((v, i) => {
            self.states[i] = {}
            self.states[i].title = this.$store.state.global.wc_states[event][v]
            self.states[i].value = v
          })
        }

        this.$store.commit('UPDATE_FORM_FIELDS', { [name]: event !== null ? event : '' })
      },
      async uploadFile (event, fieldName) {
        var id = fieldName + '_id'
        var src = fieldName + '_src'
        await this.$store.commit('UPDATE_FORM_FIELDS', { [id]: event, [src]: event.url })
        this.$store.dispatch('seller/addNewSeller')
      },
      async deleteFile (event, fieldName) {
        var id = fieldName + '_id'
        var src = fieldName + '_src'
        await this.$store.commit('UPDATE_FORM_FIELDS', { [id]: event, [src]: event.url })
        this.$store.dispatch('seller/addNewSeller')
      },
      async getSeller () {
        await this.$store.dispatch('api', { endpoint: '/sellers/' + this.$route.params.id, method: 'get' })
        this.$store.commit('SET_FORM', { fields: this.$store.state.apiResponse })
      },
      onAction (method, name, value) {
        this.$store.dispatch(method, { id: this.$route.params.id, name: name, value: value })
      },
      linkBtns (name) {
        if (name === 'products') {
          return this.$store.state.global.admin_root_url + 'edit.php?post_type=product&author=' + this.$route.params.id
        } else if (name === 'orders') {
          return this.$store.state.global.admin_root_url + 'edit.php?post_type=shop_order&seller_id=' + this.$route.params.id
        } else if (name === 'files') {
          return this.$store.state.global.admin_root_url + 'upload.php?author=' + this.$route.params.id
        } else if (name === 'edit_more') {
          return this.$store.state.global.admin_root_url + 'user-edit.php?user_id=' + this.$route.params.id
        } else if (name === 'visit_store') {
          return this.$store.state.global.stores_url + this.$store.state.form.fields.store_url_nicename
        }
      },
    },
  }
</script>
