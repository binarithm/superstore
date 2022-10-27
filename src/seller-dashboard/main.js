import Vue from 'vue'
import Vuetify from '@/settings/vuetify'
import Axios from 'axios'
import Store from './store/index'
import Router from './router'
import VTiptap from '@peepi/vuetify-tiptap'
import '@/settings/maps'
import App from './App.vue'
import '@/settings/base'

Vue.config.productionTip = false
Vue.prototype.$http = Axios
window.$http = Axios
Vue.use(VTiptap)

if (document.getElementById('superstore-seller-dashboard')) {
  new Vue({
    store: Store,
    router: Router,
    vuetify: Vuetify,
    render: h => h(App),
  }).$mount('#superstore-seller-dashboard')
}
