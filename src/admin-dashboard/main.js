import Vue from 'vue'
import Vuetify from '@/settings/vuetify'
import Axios from 'axios'
import Store from './store/index'
import Router from './router'
import '@/settings/maps'
import App from './App.vue'
import '@/settings/base'

Vue.config.productionTip = false
Vue.prototype.$http = Axios
window.$http = Axios

/**
 * Because of hash based navigation system the selected admin menu is not highlighting.
 *
 * jQuery is required.
 */
function highlightAdminMenu () {
    var $ = window.jQuery

    const menuRoot = $('#toplevel_page_superstore')
    const currentUrl = window.location.href
    const currentPath = currentUrl.substr(currentUrl.indexOf('admin.php'))

    menuRoot.on('click', 'a', function () {
        var self = $(this)

        $('ul.wp-submenu li', menuRoot).removeClass('current')

        if (self.hasClass('wp-has-submenu')) {
            $('li.wp-first-item', menuRoot).addClass('current')
        } else {
            self.parents('li').addClass('current')
        }
    })

    $('ul.wp-submenu a', menuRoot).each(function (index, el) {
        if ($(el).attr('href') === currentPath) {
            $(el).parent().addClass('current')
        }
    })
}

if (document.getElementById('superstore-admin-dashboard')) {
  new Vue({
    store: Store,
    router: Router,
    vuetify: Vuetify,
    render: h => h(App),
  }).$mount('#superstore-admin-dashboard')

  highlightAdminMenu()
}
