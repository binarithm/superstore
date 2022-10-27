<template>
  <v-container
    id="superstore-stores"
    fluid
    tag="section"
  >
    <v-toolbar class="elevation-2">
      <v-toolbar-title class="text-caption">
        {{ localize.total_showing }}: {{ stores.length }} of {{ localize.total }}
      </v-toolbar-title>

      <v-spacer />

      <input
        v-model="search"
        :placeholder="localize.search_by_text"
        style="max-width:40%; border: 1px solid rgba(0, 0, 0, .2);border-radius: 3px;padding: 5px;"
        @input="getStores"
      >
    </v-toolbar>
    <v-card
      v-if="loading"
      min-height="250"
      class="ma-2"
    >
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
    </v-card>
    <div
      v-else
      class="ma-2"
    >
      <v-row
        dense
        justify="space-around"
      >
        <v-col
          v-for="(store, key) in stores"
          :key="key"
          cols="12"
          md="5"
          class="pa-3"
        >
          <v-card
            color="rgba(0, 0, 0, .02)"
            class="pa-4"
          >
            <div class="d-flex flex-no-wrap justify-space-between">
              <div>
                <div
                  class="text-h6 pb-3"
                >
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
                  <span v-else>
                    {{ store.store_name }}
                  </span>
                </div>

                <div
                  v-if="store.show_on_store.phone === 'yes'"
                  class="pb-2 text-caption"
                >
                  <v-icon
                    small
                    left
                  >
                    mdi-phone
                  </v-icon>
                  {{ store.phone }}
                </div>

                <div
                  v-if="store.show_on_store.email === 'yes'"
                  class="pb-2 text-caption"
                >
                  <v-icon
                    small
                    left
                  >
                    mdi-email
                  </v-icon>
                  {{ store.email }}
                </div>

                <v-btn
                  outlined
                  rounded
                  small
                  :to="'/' + store.store_url_nicename"
                  style="text-decoration: none"
                >
                  {{ localize.visit }}
                </v-btn>
              </div>

              <v-avatar
                class="ma-2 ml-4"
                size="80"
                tile
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
            </div>
          </v-card>
        </v-col>
      </v-row>

      <v-pagination
        v-model="page"
        class="pa-5"
        :length="pageLength"
        :total-visible="7"
        @input="getStores"
      />
    </div>
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        localize: window.superstore.stores,
        stores: [],
        loading: false,
        page: 1,
        pageLength: 1,
        search: '',
      }
    },

    created () {
      this.getStores()
    },

    methods: {
      getStores () {
        this.loading = true
        window.$http({
          url: window.superstore.global.rest.root + window.superstore.global.rest.version + '/sellers',
          method: 'get',
          params: {
            page: this.page,
            limit: 10,
            filters: {
              store_name: this.search,
            },
          },
          paramsSerializer: function (params) {
            const qs = require('qs')
            return qs.stringify(params, { arrayFormat: 'brackets' })
          },
        })
          .then(response => {
            this.stores = response.data
            this.loading = false
            this.pageLength = Math.ceil(Number(response.headers['superstore-all']) / 10)
          })
          .catch(error => {
            console.log(error)
          })
          .then(() => {
            this.loading = false
          })
      },
    },
  }
</script>
