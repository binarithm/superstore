import Vue from 'vue'
import Vuetify from '@/settings/vuetify'
import Router from './router'
import '@/settings/maps'
import App from './App.vue'

Vue.config.productionTip = false

if (document.getElementById('superstore-stores')) {
  new Vue({
    router: Router,
    vuetify: Vuetify,
    render: h => h(App),
  }).$mount('#superstore-stores')
}
