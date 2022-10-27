import Vue from 'vue'
import Vuetify from '@/settings/vuetify'
import App from './App.vue'

Vue.config.productionTip = false

if (document.getElementById('superstore-seller-login-form')) {
  new Vue({
    vuetify: Vuetify,
    render: h => h(App),
  }).$mount('#superstore-seller-login-form')
}
