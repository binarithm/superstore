import Vue from 'vue'
import Router from 'vue-router'

import Index from './views/dashboard/Index'

import Home from './views/dashboard/Home'

import SellerIndex from './views/dashboard/seller/Index'
import SellerSellers from './views/dashboard/seller/Sellers'
import SellerSingle from './views/dashboard/seller/Single'
import SellerAddNewSeller from './views/dashboard/seller/AddNewSeller'

import PaymentIndex from './views/dashboard/payment/Index'
import PaymentRequests from './views/dashboard/payment/Requests'

import SettingsIndex from './views/dashboard/settings/Index'
import SettingsGeneral from './views/dashboard/settings/General'
import SettingsAppearance from './views/dashboard/settings/Appearance'
import SettingsSeller from './views/dashboard/settings/Seller'
import SettingsPayment from './views/dashboard/settings/Payment'

import GetProIndex from './views/dashboard/getpro/Index'

Vue.use(Router)

export default new Router({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes: [
    {
      name: 'Index',
      path: '/',
      component: Index,
      children: [
        {
          name: 'Home',
          path: '',
          component: Home,
        },
        {
          name: 'SellerIndex',
          path: 'seller',
          component: SellerIndex,
          children: [
            {
              name: 'SellerSellers',
              path: '',
              component: SellerSellers,
            },
            {
              name: 'SellerAddNewSeller',
              path: 'add-new-seller',
              component: SellerAddNewSeller,
            },
          ],
        },
        {
          name: 'SellerSingle',
          path: 'seller/:id',
          component: SellerSingle,
        },
        {
          name: 'PaymentIndex',
          path: 'payment',
          component: PaymentIndex,
          children: [
            {
              name: 'PaymentRequests',
              path: '',
              component: PaymentRequests,
            },
          ],
        },
        {
          name: 'SettingsIndex',
          path: 'settings',
          component: SettingsIndex,
          children: [
            {
              name: 'SettingsGeneral',
              path: '',
              component: SettingsGeneral,
            },
            {
              name: 'SettingsSeller',
              path: 'seller',
              component: SettingsSeller,
            },
            {
              name: 'SettingsPayment',
              path: 'payment',
              component: SettingsPayment,
            },
            {
              name: 'SettingsAppearance',
              path: 'appearance',
              component: SettingsAppearance,
            },
          ],
        },
        {
          name: 'GetProIndex',
          path: 'superstore-pro',
          component: GetProIndex,
        },
      ],
    },
  ],
})
