import Vue from 'vue'
import Router from 'vue-router'

import Index from './views/dashboard/Index'

import Home from './views/dashboard/Home'

import MediaIndex from './views/dashboard/media/Index'
import MediaFiles from './views/dashboard/media/Files'
import MediaUploadNewFile from './views/dashboard/media/UploadNewFile'

import ProductIndex from './views/dashboard/product/Index'
import ProductProducts from './views/dashboard/product/Products'
import ProductSingle from './views/dashboard/product/Single'
import ProductAddNewProduct from './views/dashboard/product/AddNewProduct'

import OrderIndex from './views/dashboard/order/Index'
import OrderOrders from './views/dashboard/order/Orders'
import OrderSingle from './views/dashboard/order/Single'

import PaymentIndex from './views/dashboard/payment/Index'
import PaymentPayments from './views/dashboard/payment/Payments'
import PaymentSendNewRequest from './views/dashboard/payment/SendNewRequest'

import SettingsIndex from './views/dashboard/settings/Index'
import SettingsAccount from './views/dashboard/settings/Account'
import SettingsPayment from './views/dashboard/settings/Payment'

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
          name: 'MediaIndex',
          path: 'media',
          component: MediaIndex,
          children: [
            {
              name: 'MediaFiles',
              path: '',
              component: MediaFiles,
            },
            {
              name: 'MediaUploadNewFile',
              path: 'upload-new-file',
              component: MediaUploadNewFile,
            },
          ],
        },
        {
          name: 'ProductIndex',
          path: 'product',
          component: ProductIndex,
          children: [
            {
              name: 'ProductProducts',
              path: '',
              component: ProductProducts,
            },
            {
              name: 'ProductAddNewProduct',
              path: 'add-new-product',
              component: ProductAddNewProduct,
            },
          ],
        },
        {
          name: 'ProductSingle',
          path: 'product/:id',
          component: ProductSingle,
        },
        {
          name: 'OrderIndex',
          path: 'order',
          component: OrderIndex,
          children: [
            {
              name: 'OrderOrders',
              path: '',
              component: OrderOrders,
            },
          ],
        },
        {
          name: 'OrderSingle',
          path: 'order/:id',
          component: OrderSingle,
        },
        {
          name: 'PaymentIndex',
          path: 'payment',
          component: PaymentIndex,
          children: [
            {
              name: 'PaymentPayments',
              path: '',
              component: PaymentPayments,
            },
            {
              name: 'PaymentAddNewPayment',
              path: 'send-new-request',
              component: PaymentSendNewRequest,
            },
          ],
        },
        {
          name: 'SettingsIndex',
          path: 'settings',
          component: SettingsIndex,
          children: [
            {
              name: 'SettingsAccount',
              path: '',
              component: SettingsAccount,
            },
            {
              name: 'SettingsPayment',
              path: 'payment',
              component: SettingsPayment,
            },
          ],
        },
      ],
    },
  ],
})
