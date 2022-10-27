import router from '@/admin-dashboard/router'

export const namespaced = true

export const state = window.superstore.seller

export const actions = {
  async addNewSeller ({ commit, dispatch, rootState }) {
    // If id found then edit user.
    if (rootState.form.fields.id === undefined) {
      await dispatch('api', { endpoint: '/sellers/', method: 'post', data: rootState.form.fields, notifyMsg: state.tab.body.add_new_seller.notify.account_created }, { root: true })

      if (rootState.apiResponse.id) {
        router.push({ path: `/seller/${rootState.apiResponse.id}` })
      }
    } else {
      var id = rootState.form.fields.id
      await dispatch('api', { endpoint: '/sellers/' + id, method: 'patch', data: rootState.form.fields, notifyMsg: rootState.global.notify.success_saved }, { root: true })
      await dispatch('api', { endpoint: '/sellers/' + id, method: 'get' }, { root: true })
      commit('SET_FORM', { fields: rootState.apiResponse }, { root: true })
    }
  },
  async edit ({ state, dispatch, rootState }, data) {
    var value = {}
    var notifyText = null

    if (!data.id) {
      if (rootState.table.selectedRows.length === 0) {
        alert(rootState.global.table.no_item_selected_alert)
        return
      }

      if (data.value !== undefined) {
        if (data.name === 'enabled') {
          if (data.value === 'yes') {
            notifyText = state.tab.body.sellers.notify.activate
          } else {
            notifyText = state.tab.body.sellers.notify.deactivate
          }
        } else if (data.name === 'requires_product_review') {
          if (data.value === 'yes') {
            notifyText = state.tab.body.sellers.notify.enabled_product_review
          } else {
            notifyText = state.tab.body.sellers.notify.disabled_product_review
          }
        } else if (data.name === 'featured') {
          if (data.value === 'yes') {
            notifyText = state.tab.body.sellers.notify.featured
          } else {
            notifyText = state.tab.body.sellers.notify.unfeatured
          }
        } else {
          notifyText = state.tab.body.sellers.notify[data.name]
        }

        for (var row = 0; row < rootState.table.selectedRows.length; row++) {
          var selectedId = rootState.table.selectedRows[row].id
          value[data.name] = data.value
          await dispatch('api', { endpoint: '/sellers/' + selectedId, method: 'patch', data: value, notifyMsg: (row === rootState.table.selectedRows.length - 1) ? notifyText : undefined }, { root: true })
        }

        dispatch('getTableRows', null, { root: true })
      }
    } else {
      if (data.value !== undefined) {
        if (data.name === 'enabled') {
          if (data.value === 'yes') {
            notifyText = state.tab.body.sellers.notify.activate
          } else {
            notifyText = state.tab.body.sellers.notify.deactivate
          }
        } else if (data.name === 'requires_product_review') {
          if (data.value === 'yes') {
            notifyText = state.tab.body.sellers.notify.enabled_product_review
          } else {
            notifyText = state.tab.body.sellers.notify.disabled_product_review
          }
        } else if (data.name === 'featured') {
          if (data.value === 'yes') {
            notifyText = state.tab.body.sellers.notify.featured
          } else {
            notifyText = state.tab.body.sellers.notify.unfeatured
          }
        } else {
          notifyText = state.tab.body.sellers.notify[data.name]
        }

        value[data.name] = data.value
        await dispatch('api', { endpoint: '/sellers/' + data.id, method: 'patch', data: value, notifyMsg: notifyText }, { root: true })

        dispatch('getTableRows', null, { root: true })
      }
    }
  },
  async delete ({ commit, dispatch, rootState }, data) {
    if (!data.id) {
      if (rootState.table.selectedRows.length === 0) {
        alert(rootState.global.table.no_item_selected_alert)
        return
      }

      if (!confirm(rootState.global.notify.confirm_delete)) {
        return
      }

      for (var row = 0; row < rootState.table.selectedRows.length; row++) {
        var selectedId = rootState.table.selectedRows[row].id

        await dispatch('api', { endpoint: '/sellers/' + selectedId, method: 'delete', notifyMsg: (row === rootState.table.selectedRows.length - 1) ? rootState.global.notify.success_delete : undefined }, { root: true })
      }

      commit('SET_TABLE', { selectedRows: [] }, { root: true })

      dispatch('getTableRows', null, { root: true })
    } else {
      if (!confirm(rootState.global.notify.confirm_delete)) {
        return
      }

      await dispatch('api', { endpoint: '/sellers/' + data.id, method: 'delete', notifyMsg: rootState.global.notify.success_delete }, { root: true })

      if (rootState.apiResponse !== null && router.history.current.fullPath !== '/seller') {
        router.push('/seller')
      }

      dispatch('getTableRows', null, { root: true })
    }
  },
}
