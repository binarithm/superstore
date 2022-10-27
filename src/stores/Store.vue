<template>
  <v-container
    id="superstore-store"
    fluid
    class="pa-0"
    tag="section"
  >
    <v-card
      class="mx-auto elevation-0"
      tile
      min-height="200"
    >
      <div v-if="loading">
        <v-overlay
          v-model="loading"
          :absolute="true"
        >
          <div class="text-center">
            <div>{{ localize.loading }}</div>
            <v-progress-linear
              rounded
              height="6"
              color="white"
              indeterminate
            />
          </div>
        </v-overlay>
      </div>
      <div v-else>
        <div v-if="!store">
          <v-alert
            dense
            outlined
            type="error"
            class="text-left"
          >
            {{ localize.not_found }}
          </v-alert>
        </div>
        <div v-else>
          <v-dialog
            v-model="contactForm"
            max-width="450px"
          >
            <v-card class="text-center">
              <v-form
                ref="formContact"
                v-model="valid"
                lazy-validation
              >
                <div class="text-h5 pt-5">
                  {{ localize.contact }}
                </div>
                <v-row
                  no-gutters
                  class="pa-5 pl-7"
                >
                  <v-col
                    cols="11"
                    class="pb-3"
                  >
                    <v-text-field
                      v-model="contactInputs.name"
                      :label="localize.contact_name"
                      :rules="[value => !!value || localize.valid_required_text]"
                      dense
                      persistent-hint
                      required
                    />
                  </v-col>
                  <v-col
                    cols="11"
                    class="pb-3"
                  >
                    <v-text-field
                      v-model="contactInputs.email"
                      :label="localize.contact_email"
                      :rules="[value => !!value || localize.valid_required_text, v => /.+@.+\..+/.test(v) || localize.valid_email_text]"
                      type="email"
                      dense
                      persistent-hint
                      required
                    />
                  </v-col>
                  <v-col cols="11">
                    <v-textarea
                      v-model="contactInputs.message"
                      :label="localize.contact_message"
                      :rules="[value => !!value || localize.valid_required_text]"
                      type="textarea"
                      counter
                      dense
                      persistent-hint
                      required
                    />
                  </v-col>
                </v-row>
                <v-card-actions class="pb-5">
                  <v-btn
                    color="primary"
                    class="mx-auto px-4 py-1"
                    depressed
                    :disabled="!valid"
                    @click="contactSeller()"
                  >
                    {{ localize.send_msg }}
                  </v-btn>
                </v-card-actions>
              </v-form>
            </v-card>
          </v-dialog>

          <v-dialog
            v-model="openingHours"
            max-width="450px"
          >
            <v-card class="text-center pa-5">
              <div class="text-h5">
                {{ localize.open_schedule }}
              </div>
              <v-divider
                class="my-2"
              />
              <div>
                <span class="px-2">
                  {{ localize.day.sun }}:
                </span>
                <span>
                  <span
                    v-if="store.store_time.open_sunday === 'yes'"
                  >
                    <span v-if="store.store_time.sunday_opening_hours === '24'">
                      {{ store.store_time.open_24_hours_notice }}
                    </span>
                    <span v-else>
                      {{ formatAMPM(store.store_time.sunday_opening_hours) }}
                      <span :class="closeStoreOnTime(store.store_time.sunday_opening_hours, store.store_time.open_sunday) ? 'green--text' : 'yellow--text'">{{ closeStoreOnTime(store.store_time.sunday_opening_hours, store.store_time.open_sunday) ? store.store_time.open_notice : store.store_time.close_notice }}</span>
                    </span>
                  </span>
                  <span
                    v-else
                    class="red--text"
                  >
                    {{ store.store_time.off_day_notice }}
                  </span>
                </span>
              </div>
              <div>
                <span class="px-2">
                  {{ localize.day.mon }}:
                </span>
                <span>
                  <span
                    v-if="store.store_time.open_monday === 'yes'"
                  >
                    <span v-if="store.store_time.monday_opening_hours === '24'">
                      {{ store.store_time.open_24_hours_notice }}
                    </span>
                    <span v-else>
                      {{ formatAMPM(store.store_time.monday_opening_hours) }}
                      <span :class="closeStoreOnTime(store.store_time.monday_opening_hours, store.store_time.open_monday) ? 'green--text' : 'yellow--text'">{{ closeStoreOnTime(store.store_time.monday_opening_hours, store.store_time.open_monday) ? store.store_time.open_notice : store.store_time.close_notice }}</span>
                    </span>
                  </span>
                  <span
                    v-else
                    class="red--text"
                  >
                    {{ store.store_time.off_day_notice }}
                  </span>
                </span>
              </div>
              <div>
                <span class="px-2">
                  {{ localize.day.tue }}:
                </span>
                <span>
                  <span
                    v-if="store.store_time.open_tuesday === 'yes'"
                  >
                    <span v-if="store.store_time.tuesday_opening_hours === '24'">
                      {{ store.store_time.open_24_hours_notice }}
                    </span>
                    <span v-else>
                      {{ formatAMPM(store.store_time.tuesday_opening_hours) }}
                      <span :class="closeStoreOnTime(store.store_time.tuesday_opening_hours, store.store_time.open_tuesday) ? 'green--text' : 'yellow--text'">{{ closeStoreOnTime(store.store_time.tuesday_opening_hours, store.store_time.open_tuesday) ? store.store_time.open_notice : store.store_time.close_notice }}</span>
                    </span>
                  </span>
                  <span
                    v-else
                    class="red--text"
                  >
                    {{ store.store_time.off_day_notice }}
                  </span>
                </span>
              </div>
              <div>
                <span class="px-2">
                  {{ localize.day.wed }}:
                </span>
                <span>
                  <span
                    v-if="store.store_time.open_wednesday === 'yes'"
                  >
                    <span v-if="store.store_time.wednesday_opening_hours === '24'">
                      {{ store.store_time.open_24_hours_notice }}
                    </span>
                    <span v-else>
                      {{ formatAMPM(store.store_time.wednesday_opening_hours) }}
                      <span :class="closeStoreOnTime(store.store_time.wednesday_opening_hours, store.store_time.open_wednesday) ? 'green--text' : 'yellow--text'">{{ closeStoreOnTime(store.store_time.wednesday_opening_hours, store.store_time.open_wednesday) ? store.store_time.open_notice : store.store_time.close_notice }}</span>
                    </span>
                  </span>
                  <span
                    v-else
                    class="red--text"
                  >
                    {{ store.store_time.off_day_notice }}
                  </span>
                </span>
              </div>
              <div>
                <span class="px-2">
                  {{ localize.day.thu }}:
                </span>
                <span>
                  <span
                    v-if="store.store_time.open_thursday === 'yes'"
                  >
                    <span v-if="store.store_time.thursday_opening_hours === '24'">
                      {{ store.store_time.open_24_hours_notice }}
                    </span>
                    <span v-else>
                      {{ formatAMPM(store.store_time.thursday_opening_hours) }}
                      <span :class="closeStoreOnTime(store.store_time.thursday_opening_hours, store.store_time.open_thursday) ? 'green--text' : 'yellow--text'">{{ closeStoreOnTime(store.store_time.thursday_opening_hours, store.store_time.open_thursday) ? store.store_time.open_notice : store.store_time.close_notice }}</span>
                    </span>
                  </span>
                  <span
                    v-else
                    class="red--text"
                  >
                    {{ store.store_time.off_day_notice }}
                  </span>
                </span>
              </div>
              <div>
                <span class="px-2">
                  {{ localize.day.fri }}:
                </span>
                <span>
                  <span
                    v-if="store.store_time.open_friday === 'yes'"
                  >
                    <span v-if="store.store_time.friday_opening_hours === '24'">
                      {{ store.store_time.open_24_hours_notice }}
                    </span>
                    <span v-else>
                      {{ formatAMPM(store.store_time.friday_opening_hours) }}
                      <span :class="closeStoreOnTime(store.store_time.friday_opening_hours, store.store_time.open_friday) ? 'green--text' : 'yellow--text'">{{ closeStoreOnTime(store.store_time.friday_opening_hours, store.store_time.open_friday) ? store.store_time.open_notice : store.store_time.close_notice }}</span>
                    </span>
                  </span>
                  <span
                    v-else
                    class="red--text"
                  >
                    {{ store.store_time.off_day_notice }}
                  </span>
                </span>
              </div>
              <div>
                <span class="px-2">
                  {{ localize.day.sat }}:
                </span>
                <span>
                  <span
                    v-if="store.store_time.open_saturday === 'yes'"
                  >
                    <span v-if="store.store_time.saturday_opening_hours === '24'">
                      {{ store.store_time.open_24_hours_notice }}
                    </span>
                    <span v-else>
                      {{ formatAMPM(store.store_time.saturday_opening_hours) }}
                      <span :class="closeStoreOnTime(store.store_time.saturday_opening_hours, store.store_time.open_saturday) ? 'green--text' : 'yellow--text'">{{ closeStoreOnTime(store.store_time.saturday_opening_hours, store.store_time.open_saturday) ? store.store_time.open_notice : store.store_time.close_notice }}</span>
                    </span>
                  </span>
                  <span
                    v-else
                    class="red--text"
                  >
                    {{ store.store_time.off_day_notice }}
                  </span>
                </span>
              </div>
            </v-card>
          </v-dialog>

          <v-img
            height="250"
            :src="store.banner_src ? store.banner_src : defaultBannerSrc"
          />

          <v-row class="pa-5">
            <v-col
              cols="12"
              md="3"
              class="text-center"
            >
              <v-avatar
                size="100"
                class="elevation-3"
              >
                <v-icon
                  v-if="!store.profile_picture_src"
                  class="text-h3"
                >
                  mdi-storefront
                </v-icon>
                <v-img
                  v-else
                  :src="store.profile_picture_src"
                />
              </v-avatar>
              <div class="pa-2">
                <v-badge
                  v-if="store.featured === 'yes'"
                  overlap
                  color="transparent orange--text font-weight-bold"
                  icon="mdi-star"
                >
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <span
                        class="text-body-1 font-weight-bold"
                        v-bind="attrs"
                        v-on="on"
                      >
                        {{ store.store_name }}
                      </span>
                    </template>
                    <span>{{ localize.featured }}</span>
                  </v-tooltip>
                </v-badge>
                <span
                  v-else
                  class="text-body-1 font-weight-bold"
                >
                  {{ store.store_name }}
                </span>
              </div>
            </v-col>
            <v-col
              cols="12"
              md="8"
            >
              <v-row
                no-gutters
              >
                <v-col
                  cols="12"
                  class="pb-5"
                >
                  <v-btn
                    v-if="store.show_on_store.contact === 'yes'"
                    color="primary"
                    depressed
                    outlined
                    small
                    class="mr-2"
                    @click="contactForm = true"
                  >
                    {{ localize.contact }}
                  </v-btn>
                  <v-btn
                    color="primary"
                    depressed
                    small
                    outlined
                    @click="openingHours = true"
                  >
                    {{ localize.opening_hours }}
                  </v-btn>
                </v-col>
                <v-col
                  cols="12"
                  class="pb-5"
                >
                  <div class="text-body-2 font-weight-bold">
                    {{ localize.contact_details }}
                  </div>

                  <span
                    v-if="store.show_on_store.email === 'yes'"
                    class="pr-2 text-caption"
                  >
                    <v-icon
                      small
                    >
                      mdi-email
                    </v-icon>
                    {{ store.email }}
                  </span>
                  <span
                    v-if="store.show_on_store.phone === 'yes'"
                    class="pr-2 text-caption"
                  >
                    <v-icon
                      small
                    >
                      mdi-phone
                    </v-icon>
                    {{ store.phone }}
                  </span>
                  <span
                    v-if="store.show_on_store.address === 'yes'"
                    class="pr-2 text-caption"
                  >
                    <v-icon
                      small
                    >
                      mdi-map-marker-radius
                    </v-icon>
                    {{ store.address.city ? store.address.city + ', ' : null }}
                    {{ store.address.country && store.address.state ? (states[store.address.country] ? (states[store.address.country][store.address.state] ? states[store.address.country][store.address.state] + ', ' : null) : null) : null }}
                    {{ store.address.country ? (countries[store.address.country] ? countries[store.address.country] : null) : null }}
                  </span>
                </v-col>
                <v-col
                  v-if="store.show_on_store.about === 'yes'"
                  cols="12"
                  class="pb-5"
                >
                  <div class="text-body-2 font-weight-bold">
                    {{ localize.about }}
                  </div>
                  <p class="text-caption">
                    {{ store.about }}
                  </p>
                </v-col>
              </v-row>
            </v-col>
          </v-row>

          <div
            v-if="store.geolocation.latitude && store.geolocation.longitude && store.show_on_store.map === 'yes'"
            class="pa-5"
          >
            <GmapMap
              :center="{
                lat: store.geolocation.latitude ? Number(store.geolocation.latitude) : 0,
                lng: store.geolocation.longitude ? Number(store.geolocation.longitude) : 0,
              }"
              :zoom="13"
              style="width: 100%; height: 300px"
            />
          </div>

          <v-toolbar
            tile
            dense
            color="grey lighten-3"
            flat
          >
            <v-tabs
              v-model="tab"
              grow
            >
              <v-tab
                v-for="item in localize.items"
                :key="item.key"
                class="text-caption text-capitalize grey--text text--darken-2"
                active-class="primary--text"
              >
                {{ item.title }}
              </v-tab>
            </v-tabs>
          </v-toolbar>

          <v-tabs-items v-model="tab">
            <v-tab-item
              v-for="item in localize.items"
              :key="item.key"
              :transition="false"
              :reverse-transition="false"
            >
              <div
                v-if="item.key === 'best-selling'"
                class="pa-5 text-center"
              >
                <v-skeleton-loader
                  v-if="skeletonLoading"
                  :boilerplate="true"
                  type="table-heading, list-item-two-line, image, table-tfoot"
                />
                <v-row v-else-if="bestSellingProducts !== null">
                  <v-col
                    v-for="(n, k) in bestSellingProducts"
                    :key="k"
                    cols="12"
                    sm="4"
                    md="3"
                  >
                    <div
                      style="border: 1px solid rgba(0, 0, 0, .05);"
                    >
                      <a
                        :href="n.permalink"
                        target="_blank"
                      >
                        <v-img
                          :src="n.thumbnail_image.url ? n.thumbnail_image.url : ''"
                        />
                      </a>
                      <v-btn
                        v-if="n.on_sale"
                        small
                        color="primary lighten-1"
                        dark
                        depressed
                        tile
                        class="ma-2"
                      >
                        {{ localize.sale }}
                      </v-btn>
                      <div
                        class="ma-1"
                        style="padding-left: 25% !important;"
                      >
                        <span
                          v-html="n.rating_html"
                        />
                      </div>
                      <a
                        :href="n.permalink"
                        target="_blank"
                      >
                        <div class="ma-1">{{ n.name }}</div>
                      </a>
                      <div
                        class="mb-4"
                        v-html="n.price_html"
                      />
                    </div>
                  </v-col>
                </v-row>
              </div>
              <div
                v-if="item.key === 'latest'"
                class="pa-5 text-center"
              >
                <v-skeleton-loader
                  v-if="skeletonLoading"
                  :boilerplate="true"
                  type="table-heading, list-item-two-line, image, table-tfoot"
                />
                <v-row v-else-if="latestProducts !== null">
                  <v-col
                    v-for="(n, k) in latestProducts"
                    :key="k"
                    cols="12"
                    sm="4"
                    md="3"
                  >
                    <div
                      style="border: 1px solid rgba(0, 0, 0, .05);"
                    >
                      <a
                        :href="n.permalink"
                        target="_blank"
                      >
                        <v-img
                          :src="n.thumbnail_image.url ? n.thumbnail_image.url : ''"
                        />
                      </a>
                      <v-btn
                        v-if="n.on_sale"
                        small
                        color="primary lighten-1"
                        dark
                        depressed
                        tile
                        class="ma-2"
                      >
                        {{ localize.sale }}
                      </v-btn>
                      <div
                        class="ma-1"
                        style="padding-left: 25% !important;"
                      >
                        <span
                          v-html="n.rating_html"
                        />
                      </div>
                      <a
                        :href="n.permalink"
                        target="_blank"
                      >
                        <div class="ma-1">{{ n.name }}</div>
                      </a>
                      <div
                        class="mb-4"
                        v-html="n.price_html"
                      />
                    </div>
                  </v-col>
                </v-row>
              </div>
              <div
                v-if="item.key === 'top-rated'"
                class="pa-5 text-center"
              >
                <v-skeleton-loader
                  v-if="skeletonLoading"
                  :boilerplate="true"
                  type="table-heading, list-item-two-line, image, table-tfoot"
                />
                <v-row v-else-if="topRatedProducts !== null">
                  <v-col
                    v-for="(n, k) in topRatedProducts"
                    :key="k"
                    cols="12"
                    sm="4"
                    md="3"
                  >
                    <div
                      style="border: 1px solid rgba(0, 0, 0, .05);"
                    >
                      <a
                        :href="n.permalink"
                        target="_blank"
                      >
                        <v-img
                          :src="n.thumbnail_image.url ? n.thumbnail_image.url : ''"
                        />
                      </a>
                      <v-btn
                        v-if="n.on_sale"
                        small
                        color="primary lighten-1"
                        dark
                        depressed
                        tile
                        class="ma-2"
                      >
                        {{ localize.sale }}
                      </v-btn>
                      <div
                        class="ma-1"
                        style="padding-left: 25% !important;"
                      >
                        <span
                          v-html="n.rating_html"
                        />
                      </div>
                      <a
                        :href="n.permalink"
                        target="_blank"
                      >
                        <div class="ma-1">{{ n.name }}</div>
                      </a>
                      <div
                        class="mb-4"
                        v-html="n.price_html"
                      />
                    </div>
                  </v-col>
                </v-row>
              </div>
              <div
                v-if="item.key === 'featured'"
                class="pa-5 text-center"
              >
                <v-skeleton-loader
                  v-if="skeletonLoading"
                  :boilerplate="true"
                  type="table-heading, list-item-two-line, image, table-tfoot"
                />
                <v-row v-else-if="featuredProducts !== null">
                  <v-col
                    v-for="(n, k) in featuredProducts"
                    :key="k"
                    cols="12"
                    sm="4"
                    md="3"
                  >
                    <div
                      style="border: 1px solid rgba(0, 0, 0, .05);"
                    >
                      <a
                        :href="n.permalink"
                        target="_blank"
                      >
                        <v-img
                          :src="n.thumbnail_image.url ? n.thumbnail_image.url : ''"
                        />
                      </a>
                      <v-btn
                        v-if="n.on_sale"
                        small
                        color="primary lighten-1"
                        dark
                        depressed
                        tile
                        class="ma-2"
                      >
                        {{ localize.sale }}
                      </v-btn>
                      <div
                        class="ma-1"
                        style="padding-left: 25% !important;"
                      >
                        <span
                          v-html="n.rating_html"
                        />
                      </div>
                      <a
                        :href="n.permalink"
                        target="_blank"
                      >
                        <div class="ma-1">{{ n.name }}</div>
                      </a>
                      <div
                        class="mb-4"
                        v-html="n.price_html"
                      />
                    </div>
                  </v-col>
                </v-row>
              </div>
            </v-tab-item>
          </v-tabs-items>
        </div>
      </div>
    </v-card>
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        valid: true,
        localize: window.superstore.store,
        countries: window.superstore.global.wc_countries,
        states: window.superstore.global.wc_states,
        defaultBannerSrc: window.superstore.global.images.default_banner,
        tab: null,
        store: false,
        loading: false,
        skeletonLoading: false,
        bestSellingProducts: null,
        featuredProducts: null,
        topRatedProducts: null,
        latestProducts: null,
        contactForm: false,
        contactInputs: {},
        openingHours: false,
      }
    },

    created () {
      this.getStoreBySlugName()
    },

    methods: {
      contactSeller () {
        if (!this.$refs.formContact.validate()) {
          return
        }
        const qs = require('qs')
        var data = {
          action: 'superstore_contact_seller',
          nonce: window.superstore.global.nonce,
          values: this.contactInputs,
          seller_id: this.store.id,
        }
        window.$http.post(window.superstore.global.ajaxurl, qs.stringify(data))
          .then(response => {
            this.contactForm = false
            alert(response.data.data)
          })
          .catch(error => {
            console.log(error.response)
          })
          .then(() => {})
      },
      openTime (time24) {
        var startEnd = time24
        if (startEnd !== '') {
          return startEnd.split('-')[0]
        }
      },
      closeTime (time24) {
        var startEnd = time24
        if (startEnd !== '') {
          var startTime = startEnd.split('-')[0]
          var startTimeDash = startTime + '-'
          return startEnd.replace(startTimeDash, '')
        }
      },
      closeStoreOnTime (time24) {
        var startTime = this.openTime(time24)
        var endTime = this.closeTime(time24)

        var currentDate = new Date()

        var startDate = new Date(currentDate.getTime())
        startDate.setHours(startTime.split(':')[0])
        startDate.setMinutes(startTime.split(':')[1])

        var endDate = new Date(currentDate.getTime())
        endDate.setHours(endTime.split(':')[0])
        endDate.setMinutes(endTime.split(':')[1])

        return startDate < currentDate && endDate > currentDate
      },
      formatAMPM (time24) {
        var startEnd = time24
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
      getStoreBySlugName () {
        this.loading = true
        window.$http.get(window.superstore.global.rest.root + window.superstore.global.rest.version + '/sellers/' + this.$route.params.slug)
          .then(response => {
            this.store = response.data
            this.loading = false
            this.getBestSellingProducts(response.data.id, response.data.store_products_per_page)
            this.getFeaturedProducts(response.data.id, response.data.store_products_per_page)
            this.getTopRatedProducts(response.data.id, response.data.store_products_per_page)
            this.getLatestProducts(response.data.id, response.data.store_products_per_page)
          })
          .catch(error => {
            console.log(error)
          })
          .then(() => {
            this.loading = false
          })
      },
      getBestSellingProducts (id, limit) {
        this.skeletonLoading = true
        window.$http.get(window.superstore.global.rest.root + window.superstore.global.rest.version + '/products/best-selling/', {
          params: {
            per_page: Number(limit),
            seller_id: Number(id),
          },
        })
          .then(response => {
            this.bestSellingProducts = response.data
            this.skeletonLoading = false
          })
          .catch(error => {
            console.log(error)
          })
          .then(() => {
            this.skeletonLoading = false
          })
      },
      getFeaturedProducts (id, limit) {
        this.skeletonLoading = true
        window.$http.get(window.superstore.global.rest.root + window.superstore.global.rest.version + '/products/featured/', {
          params: {
            per_page: Number(limit),
            seller_id: Number(id),
          },
        })
          .then(response => {
            this.featuredProducts = response.data
            this.skeletonLoading = false
          })
          .catch(error => {
            console.log(error)
          })
          .then(() => {
            this.skeletonLoading = false
          })
      },
      getTopRatedProducts (id, limit) {
        this.skeletonLoading = true
        window.$http.get(window.superstore.global.rest.root + window.superstore.global.rest.version + '/products/top-rated/', {
          params: {
            per_page: Number(limit),
            seller_id: Number(id),
          },
        })
          .then(response => {
            this.topRatedProducts = response.data
            this.skeletonLoading = false
          })
          .catch(error => {
            console.log(error)
          })
          .then(() => {
            this.skeletonLoading = false
          })
      },
      getLatestProducts (id, limit) {
        this.skeletonLoading = true
        window.$http.get(window.superstore.global.rest.root + window.superstore.global.rest.version + '/products/latest/', {
          params: {
            per_page: Number(limit),
            seller_id: Number(id),
          },
        })
          .then(response => {
            this.latestProducts = response.data
            this.skeletonLoading = false
          })
          .catch(error => {
            console.log(error)
          })
          .then(() => {
            this.skeletonLoading = false
          })
      },
    },
  }
</script>
