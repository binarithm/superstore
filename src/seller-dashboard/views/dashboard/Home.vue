<template>
  <v-row id="superstore-seller-dashboard-home">
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
        v-if="top10ProductsLoading"
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
          {{ $store.state.home.top_10_products.title }}
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
                  {{ $store.state.home.top_10_products.table_header.product }}
                </th>
                <th class="text-left">
                  {{ $store.state.home.top_10_products.table_header.items_sold }}
                </th>
                <th class="text-left">
                  {{ $store.state.home.top_10_products.table_header.ratings }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(product, index2) in top10ProductsData"
                :key="index2"
              >
                <td>#{{ index2 + 1 }}</td>
                <td>
                  <v-btn
                    color="primary"
                    x-small
                    text
                    :to="'/product/' + product.id"
                  >
                    {{ product.name }}
                  </v-btn>
                </td>
                <td>{{ product.items_sold }}</td>
                <td>{{ product.ratings }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
        <div
          v-if="top10ProductsData.length === 0"
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
        top10ProductsLoading: false,
        top10ProductsData: null,
      }
    },

    created () {
      this.getOverview()
      this.getTop10Products()
    },

    methods: {
      async getOverview () {
        this.$store.commit('SET_NOTIFY', { enable: false })
        this.overviewLoading = true
        this.overviewData = await null

        await window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/reports/seller-overview',
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
      async getTop10Products () {
        this.$store.commit('SET_NOTIFY', { enable: false })
        this.top10ProductsLoading = true
        this.top10ProductsData = await null

        await window.$http({
          url: this.$store.state.global.rest.root + this.$store.state.global.rest.version + '/reports/seller-top-10-products',
          headers: { 'X-WP-Nonce': this.$store.state.global.rest.nonce },
          method: 'get',
        })
          .then(response => {
            this.top10ProductsData = response.data
            this.top10ProductsLoading = false
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response ? error.response.data.message : error })
          })
          .then(() => {
            this.top10ProductsLoading = false
          })
      },
    },
  }
</script>
