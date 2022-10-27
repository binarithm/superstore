<template>
  <v-row id="superstore-admin-dashboard-home">
    <v-col
      class="pa-2"
      cols="12"
      md="6"
    >
      <v-card
        v-if="overviewLoading"
        min-height="500"
        class="pa-10 fill-height"
      >
        <v-overlay
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
      </v-card>
      <v-card
        v-else
        min-height="300"
        class="pa-10 fill-height"
      >
        <div class="pb-5 text-h6">
          {{ $store.state.home.overview.title }}
        </div>
        <v-row no-gutters>
          <v-col
            v-for="(n, index) in overviewData"
            :key="index"
            cols="12"
            sm="3"
            class="pa-2"
          >
            <v-card
              flat
              class="pa-3 text-center fill-height ma-auto"
              style="border: .5px solid rgba(0, 0, 0, .1);"
            >
              <v-btn
                class="mx-auto"
                depressed
                fab
                x-small
                :color="n.icon_bg_color"
                style="cursor: default;"
              >
                <v-icon :color="n.icon_color">
                  {{ n.icon }}
                </v-icon>
              </v-btn>

              <div
                class="text-h6 font-weight-bold"
                v-html="n.title"
              />
              <div class="text-caption grey--text">
                {{ n.description }}
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-card>
    </v-col>

    <v-col
      class="pa-2"
      cols="12"
      md="6"
    >
      <v-card
        v-if="top10SellersLoading"
        min-height="500"
        class="pa-10 fill-height"
      >
        <v-overlay
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
      </v-card>
      <v-card
        v-else
        min-height="300"
        class="pa-10 fill-height"
      >
        <div class="pb-5 text-h6">
          {{ $store.state.home.top_10_sellers.title }}
        </div>
        <v-simple-table
          fixed-header
          dense
        >
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left" />
                <th class="text-left">
                  {{ $store.state.home.top_10_sellers.table_header.seller }}
                </th>
                <th class="text-left">
                  {{ $store.state.home.top_10_sellers.table_header.admin_earnings }}
                </th>
                <th class="text-left">
                  {{ $store.state.home.top_10_sellers.table_header.sales }}
                </th>
                <th class="text-left">
                  {{ $store.state.home.top_10_sellers.table_header.ratings }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(seller, index2) in top10SellersData"
                :key="index2"
              >
                <td>#{{ index2 + 1 }}</td>
                <td>
                  <v-btn
                    color="primary"
                    x-small
                    text
                    :to="'/seller/' + seller.id"
                  >
                    {{ seller.store_name !== '' ? seller.store_name : $store.state.global.no_name }}
                  </v-btn>
                </td>
                <td v-html="seller.admin_earnings" />
                <td v-html="seller.sales" />
                <td>{{ seller.ratings }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
        <div
          v-if="top10SellersData.length === 0"
          class="text-center pa-3"
        >
          {{ $store.state.global.table.no_item_found_text }}
        </div>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    name: 'Home',

    data () {
      return {
        overviewLoading: false,
        overviewData: null,
        top10SellersLoading: false,
        top10SellersData: null,
      }
    },

    created () {
      this.getOverview()
      this.getTop10Sellers()
    },

    methods: {
      async getOverview () {
        this.$store.commit('SET_NOTIFY', { enable: false })
        this.overviewLoading = true
        this.overviewData = await null

        await window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/reports/admin-overview',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'get',
        })
          .then(response => {
            this.overviewData = response.data
            this.overviewLoading = false
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response ? error.response.data.message : error })
          })
          .then(() => {
            this.overviewLoading = false
          })
      },
      async getTop10Sellers () {
        this.$store.commit('SET_NOTIFY', { enable: false })
        this.top10SellersLoading = true
        this.top10SellersData = await null

        await window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/reports/top-10-sellers',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'get',
        })
          .then(response => {
            this.top10SellersData = response.data
            this.top10SellersLoading = false
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response ? error.response.data.message : error })
          })
          .then(() => {
            this.top10SellersLoading = false
          })
      },
    },
  }
</script>
