<template>
  <div
    id="superstore-admin-dashboard-getpro-index"
    class="px-10"
  >
    <div
      class="pa-5"
      style="margin-bottom: 100px;"
    >
      <v-img
        class="mx-auto"
        :src="!$vuetify.theme.isDark ? $store.state.global.images.superstore_logo : $store.state.global.images.superstore_logo_dark"
        width="150px"
      />
    </div>

    <div
      class="text-center pa-10 superstore-upcoming"
      style="margin-bottom: 170px;"
    >
      <div :class="`font-weight-thin text-h4 pb-5 ${!$vuetify.theme.isDark ? 'grey--text text--darken-2' : ''}`">
        {{ upcoming.title }}
      </div>

      <div
        :class="`text-subtitle-1 ${!$vuetify.theme.isDark ? 'grey--text text--darken-1' : ''}`"
        v-html="upcoming.notify_desc"
      />
      <v-text-field
        v-model="email"
        dense
        outlined
        :label="upcoming.email_label"
        class="mx-auto"
        hide-details
        style="max-width: 50%;"
      />
      <v-btn
        rounded
        dark
        depressed
        class="mt-3 superstore-seemore-btn"
        @click="notifyOnSPROPulished"
      >
        {{ upcoming.btn_title }}
      </v-btn>
    </div>

    <div
      class="text-center"
      style="margin-bottom: 170px;"
    >
      <div
        :class="`font-weight-bold text-h5 ${!$vuetify.theme.isDark ? 'grey--text text--darken-2' : ''} text-uppercase`"
      >
        {{ topFeaturesSectionTitle }}
      </div>
      <v-divider
        class="mt-5 mb-15 mx-auto superstore-divider"
      />
      <v-row>
        <v-col
          v-for="(topFeature, topFeatureIndex) in topFeatures"
          :key="topFeatureIndex"
          cols="12"
          sm="4"
          lg="3"
          class="text-center"
        >
          <div
            :class="`superstore-feature pa-5 ${!$vuetify.theme.isDark ? 'white' : 'grey darken-4'}`"
            style="width: 100%;height: 100%;border-radius: 4px;"
          >
            <v-btn
              class="mx-auto"
              depressed
              fab
              small
              :color="topFeature.btnColor"
              style="cursor: default;"
            >
              <v-icon
                :color="topFeature.iconColor"
              >
                {{ topFeature.icon }}
              </v-icon>
            </v-btn>

            <div
              :class="`font-weight-bold ${!$vuetify.theme.isDark ? 'grey--text text--darken-1' : ''} text-subtitle-1 my-1`"
            >
              {{ topFeature.title }}
            </div>

            <div
              :class="`${!$vuetify.theme.isDark ? 'grey--text text--darken-1' : ''}`"
            >
              {{ topFeature.description }}
            </div>

            <v-btn
              x-small
              text
              color="primary"
              link
              :href="topFeature.moreLink"
            >
              {{ topFeature.moreTitle }}
            </v-btn>
          </div>
        </v-col>
      </v-row>
      <v-btn
        depressed
        rounded
        link
        target="_blank"
        dark
        class="mt-15 superstore-seemore-btn"
        :href="topFeatureSeeMore.link"
      >
        {{ topFeatureSeeMore.title }}
        <v-icon
          right
        >
          mdi-chevron-right
        </v-icon>
      </v-btn>
    </div>

    <div
      class="text-center"
      style="margin-bottom: 100px;"
    >
      <div
        :class="`font-weight-bold text-h5 ${!$vuetify.theme.isDark ? 'grey--text text--darken-2' : ''} text-uppercase`"
      >
        {{ comparisonSectionTitle }}
      </div>
      <v-divider
        class="mt-5 mb-15 mx-auto superstore-divider"
      />
      <v-card
        class="mx-auto responsive-display"
      >
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th
                  class="text-subtitle-1 font-weight-bold text-center text-uppercase"
                  style="width: 50%;"
                >
                  {{ comparisonHeaders.features }}
                </th>
                <th class="text-subtitle-1 font-weight-bold text-center text-uppercase">
                  {{ comparisonHeaders.free }}
                </th>
                <th class="text-subtitle-1 font-weight-bold text-center text-uppercase">
                  {{ comparisonHeaders.pro }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="([name, one, two], i) in comparisonRows"
                :key="i"
              >
                <td
                  :class="`${!$vuetify.theme.isDark ? 'grey--text text--darken-1' : ''}`"
                  style="letter-spacing: .5px;"
                  v-text="name"
                />

                <td :class="`text-center ${!$vuetify.theme.isDark ? 'grey--text text--darken-1' : ''}`">
                  <template v-if="typeof one === 'boolean'">
                    <v-icon :color="one ? 'success' : 'error'">
                      mdi-{{ one ? 'check' : 'close' }}
                    </v-icon>
                  </template>

                  <template v-else>
                    {{ one }}
                  </template>
                </td>

                <td class="text-center">
                  <template v-if="typeof two === 'boolean'">
                    <v-icon :color="two ? 'success' : 'error'">
                      mdi-{{ two ? 'check' : 'close' }}
                    </v-icon>
                  </template>

                  <template v-else>
                    {{ two }}
                  </template>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card>

      <v-btn
        depressed
        rounded
        link
        target="_blank"
        dark
        class="mt-15 superstore-seemore-btn"
        :href="comparisonSeeMore.link"
      >
        {{ comparisonSeeMore.title }}
        <v-icon
          right
        >
          mdi-chevron-right
        </v-icon>
      </v-btn>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'GetProIndex',

    data () {
      return {
        email: '',
        upcoming: this.$store.state.pro.upcoming,
        comparisonRows: this.$store.state.pro.comparison.rows,
        comparisonHeaders: this.$store.state.pro.comparison.headers,
        comparisonSectionTitle: this.$store.state.pro.comparison.section_title,
        comparisonSeeMore: this.$store.state.pro.comparison.see_more,
        topFeatures: this.$store.state.pro.top_features.content,
        topFeaturesSectionTitle: this.$store.state.pro.top_features.section_title,
        topFeatureSeeMore: this.$store.state.pro.top_features.see_more,
      }
    },

    methods: {
      notifyOnSPROPulished () {
        this.$store.commit('SET_NOTIFY', { enable: false })
        window.$http.post(this.$store.state.global.rest.pro_published_notify_url, { reason: 'superstore-pro-upcoming', email: this.email })
          .then(response => {
            this.$store.commit('SET_NOTIFY', { enable: true, msg: 'You will be notified', type: 'success' })
          })
          .catch(error => {
            this.$store.commit('SET_NOTIFY', { enable: true, msg: error.response.data.message, type: 'unsuccess' })
          })
      },
    },
  }
</script>

<style>
  .superstore-upcoming {
    background: rgb(14,0,255);
    background: linear-gradient(90deg, rgba(14,0,255,0.08) 0%, rgba(141,0,255,0.08) 50%, rgba(239,0,255,0.08) 100%);
    box-shadow: -10px 10px 15px 0px rgba(0,0,0,0.18);
    -webkit-box-shadow: -10px 10px 15px 0px rgba(0,0,0,0.18);
    -moz-box-shadow: -10px 10px 15px 0px rgba(0,0,0,0.18);
  }

  .superstore-feature {
    box-shadow: -4px 4px 7px px rgba(0,0,0,0.1);
    -webkit-box-shadow: -4px 4px 7px 0px rgba(0,0,0,0.1);
    -moz-box-shadow: -4px 4px 7px 0px rgba(0,0,0,0.1);
  }

  .superstore-seemore-btn {
    background: rgb(97,114,255);
    background: linear-gradient(90deg, rgba(97,114,255,1) 0%, rgba(167,100,255,1) 100%);
  }

  .superstore-divider {
    background-color: #ff3385;
    border-width: 1.7px !important;
    width: 40px;
  }
</style>
