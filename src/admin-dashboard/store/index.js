import Vue from 'vue'
import Vuex from 'vuex'
import * as home from './modules/home'
import * as seller from './modules/seller'
import * as payment from './modules/payment'
import * as settings from './modules/settings'
import * as pro from './modules/get-pro'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: process.env.NODE_ENV !== 'production',
  modules: {
    home,
    seller,
    payment,
    settings,
    pro,
  },
  state: {
    global: window.superstore.global,
    drawer: false,
    notify: {
      enable: false,
      type: 'success', // success or unsuccess
      msg: '',
    },
    alert: {
      enable: false,
      type: 'info', // success, info, warning or error
      msg: '',
    },
    overlayLoading: {
      enable: false,
      msg: window.superstore.global.overlay_loading_text,
    },
    tab: {
      active: '',
      tabs: [],
      body: null,
    },
    form: {
      valid: true,
      sections: null,
      fields: {},
      submitEvent: null,
      submitExtraData: null,
    },
    table: {
      loading: false,
      headers: [],
      rows: [],
      selectedRows: [],
      search: '',
      restEndpoint: '',
      page: 1,
      itemsPerPage: 10,
      count: {},
      totalItems: 0,
      pageCount: 0,
      links: [],
      filterItems: [],
      filters: null,
      actions: [],
    },
    ajaxResponse: null,
    apiResponse: null,
  },
  mutations: {
    SET_DRAWER (state, payload) {
      state.drawer = payload
    },
    SET_NOTIFY (state, payload) {
      state.notify = Object.assign({}, state.notify, payload)
    },
    SET_ALERT (state, payload) {
      state.alert = Object.assign({}, state.alert, payload)
    },
    SET_OVERLAY_LOADING (state, payload) {
      state.overlayLoading = Object.assign({}, state.overlayLoading, payload)
    },
    SET_TAB (state, payload) {
      state.tab = Object.assign({}, state.tab, payload)
    },
    SET_FORM (state, payload) {
      state.form = Object.assign({}, state.form, payload)
    },
    UPDATE_FORM_FIELDS (state, payload) {
      if (payload.subField) {
        var multiInputs = {}
        var inputs = {}

        Object.keys(state.form.fields).forEach((v, i) => {
          inputs[v] = state.form.fields[v]
          Object.keys(state.form.fields[payload.field]).forEach((v2, i2) => {
            multiInputs[v2] = state.form.fields[payload.field][v2]
            multiInputs[payload.subField] = payload.event
            inputs[payload.field] = multiInputs
          })
        })

        state.form.fields = inputs
      } else {
        state.form.fields = Object.assign({}, state.form.fields, payload)
      }
    },
    SET_TABLE (state, payload) {
      state.table = Object.assign({}, state.table, payload)
    },
    SET_AJAX_RESPONSE (state, payload) {
      state.ajaxResponse = payload
    },
    SET_API_RESPONSE (state, payload) {
      state.apiResponse = payload
    },
  },
  actions: {
    async setDrawer ({ state, commit }, value) {
      await localStorage.setItem('superstoreDrawer', value)
      var drawer = await (localStorage.getItem('superstoreDrawer') === 'true')
      commit('SET_DRAWER', drawer)
    },
    setDarkMode ({ state, commit }, value) {
      localStorage.setItem('superstoreDarkMode', value)
    },
    async submitForm ({ state, dispatch }, refs) {
      await refs.validate()
      if (state.form.valid) {
        dispatch(state.form.submitEvent)
      }
    },
    async getTableRows ({ state, commit, dispatch }) {
      await commit('SET_TABLE', { rows: [] })
      await dispatch('api', {
        method: 'get',
        endpoint: state.table.restEndpoint,
        params: {
          limit: state.table.itemsPerPage,
          page: state.table.page,
          filters: state.table.filters,
          search: state.table.search,
        },
      })
      if (state.apiResponse !== null) {
        commit('SET_TABLE', { rows: state.apiResponse })
      }
    },
    async api ({ commit, dispatch, state }, data) {
      if (data.notifyMsg !== undefined) {
        commit('SET_NOTIFY', { enable: false })
      }
      commit('SET_TABLE', { loading: true })
      commit('SET_OVERLAY_LOADING', { enable: true })
      await commit('SET_API_RESPONSE', null)

      await window.$http({
        url: state.global.rest.root + state.global.rest.version + data.endpoint,
        headers: { 'X-WP-Nonce': state.global.rest.nonce },
        method: data.method,
        data: data.data, // For post request
        params: data.params, // For get request
        paramsSerializer: function (params) {
          const qs = require('qs')
          return qs.stringify(params, { arrayFormat: 'brackets' })
        },
      })
        .then(response => {
          if (data.notifyMsg !== undefined) {
            commit('SET_NOTIFY', { enable: true, type: 'success', msg: data.notifyMsg })
          }

          var countData = {}

          Object.keys(response.headers).forEach((v, i) => {
            if (v.includes('superstore-')) {
              var countName = v.replace('superstore-', '')

              countData[countName] = response.headers[v]
              commit('SET_TABLE', { count: countData })
            }
          })

          if (state.table.filters === null) {
            commit('SET_TABLE', { totalItems: Number(countData.all) })
          }

          commit('SET_API_RESPONSE', response.data)

          commit('SET_TABLE', { loading: false })
          commit('SET_OVERLAY_LOADING', { enable: false })
        })
        .catch(error => {
          commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response ? error.response.data.message : error })
        })
        .then(() => {
          commit('SET_TABLE', { loading: false })
          commit('SET_OVERLAY_LOADING', { enable: false })
        })
    },
    async ajax ({ commit, dispatch, state }, data) {
      commit('SET_NOTIFY', { enable: false })
      commit('SET_OVERLAY_LOADING', { enable: true })
      commit('SET_AJAX_RESPONSE', null)

      const qs = require('qs')

      var formData = null
      if (data.formData !== undefined) {
        formData = new FormData()
        Object.keys(data.formData).forEach((v, i) => {
          formData.append(v, data.formData[v])
        })
      }

      await window.$http({
        url: state.global.ajaxurl,
        headers: { 'X-WP-Nonce': state.global.nonce },
        method: data.method,
        data: formData !== null ? formData : qs.stringify(data.qsData),
      })
        .then(response => {
          if (response.data.data.message !== undefined) {
            commit('SET_NOTIFY', { enable: true, type: 'success', msg: response.data.data.message })
          }

          commit('SET_AJAX_RESPONSE', response.data.data)

          commit('SET_OVERLAY_LOADING', { enable: false })
        })
        .catch(error => {
          commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response.data.data[0].message !== undefined ? error.response.data.data[0].message : error })
        })
        .then(() => {
          commit('SET_OVERLAY_LOADING', { enable: false })
        })
    },
  },
})
