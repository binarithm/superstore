import Vue from 'vue'
import VueGeolocation from 'vue-browser-geolocation'
import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueGeolocation)

Vue.use(
  VueGoogleMaps, {
    load: {
      key: window.superstore.global.google_map_api_key,
      libraries: 'places',
    },
})
