import Vue from 'vue'
import Router from 'vue-router'

import Stores from './Stores'
import Store from './Store'

Vue.use(Router)

export default new Router({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes: [
    {
      name: 'Stores',
      path: '/',
      component: Stores,
    },
    {
      name: 'Store',
      path: '/:slug',
      component: Store,
    },
  ],
})
