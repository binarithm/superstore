import router from '@/seller-dashboard/router'

export const namespaced = true

export const state = window.superstore.product

export const actions = {
  async addNewProduct ({ commit, dispatch, rootState }) {
    if (rootState.form.fields.id === undefined) {
      await dispatch('api', { endpoint: '/products/', method: 'post', data: rootState.form.fields, notifyMsg: state.tab.body.add_new_product.notify.product_added }, { root: true })

      if (rootState.apiResponse.id) {
        router.push({ path: `/product/${rootState.apiResponse.id}` })
      }
    } else {
      var id = rootState.form.fields.id
      await dispatch('api', { endpoint: '/products/' + id, method: 'patch', data: rootState.form.fields, notifyMsg: rootState.global.notify.success_saved }, { root: true })
      await dispatch('api', { endpoint: '/products/' + id, method: 'get' }, { root: true })
      commit('SET_FORM', { fields: rootState.apiResponse }, { root: true })
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

        await dispatch('api', { endpoint: '/products/' + selectedId, method: 'delete', notifyMsg: (row === rootState.table.selectedRows.length - 1) ? rootState.global.notify.success_delete : undefined }, { root: true })
      }

      commit('SET_TABLE', { selectedRows: [] }, { root: true })

      dispatch('getTableRows', null, { root: true })
    } else {
      if (!confirm(rootState.global.notify.confirm_delete)) {
        return
      }

      await dispatch('api', { endpoint: '/products/' + data.id, method: 'delete', notifyMsg: rootState.global.notify.success_delete }, { root: true })

      if (rootState.apiResponse !== null && router.history.current.fullPath !== '/product') {
        router.push('/product')
      }

      dispatch('getTableRows', null, { root: true })
    }
  },
}
