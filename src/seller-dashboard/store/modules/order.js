export const namespaced = true

export const state = window.superstore.order

export const actions = {
  async singleEdit ({ commit, dispatch, rootState }) {
    var id = rootState.form.fields.id
    await dispatch('api', { endpoint: '/orders/' + id, method: 'patch', data: rootState.form.fields, notifyMsg: rootState.global.notify.success_saved }, { root: true })
    await dispatch('api', { endpoint: '/orders/' + id, method: 'get' }, { root: true })
    commit('SET_FORM', { fields: rootState.apiResponse }, { root: true })
  },

  async edit ({ state, dispatch, rootState }, data) {
    var value = {}
    var notifyText = rootState.global.notify.success_saved

    if (!data.id) {
      if (rootState.table.selectedRows.length === 0) {
        alert(rootState.global.table.no_item_selected_alert)
        return
      }

      if (data.value !== undefined) {
        for (var row = 0; row < rootState.table.selectedRows.length; row++) {
          var selectedId = rootState.table.selectedRows[row].id
          value[data.name] = data.value
          await dispatch('api', { endpoint: '/orders/' + selectedId, method: 'patch', data: value, notifyMsg: (row === rootState.table.selectedRows.length - 1) ? notifyText : undefined }, { root: true })
        }

        dispatch('getTableRows', null, { root: true })
      }
    } else {
      if (data.value !== undefined) {
        value[data.name] = data.value
        await dispatch('api', { endpoint: '/orders/' + data.id, method: 'patch', data: value, notifyMsg: notifyText }, { root: true })

        dispatch('getTableRows', null, { root: true })
      }
    }
  },

  exportCSV ({ commit, state, dispatch, rootState }, data) {
    commit('SET_NOTIFY', { enable: false }, { root: true })
    commit('SET_TABLE', { loading: true }, { root: true })

    var notifyText = state.tab.body.orders.notify.csv_exported

    if (rootState.table.selectedRows.length === 0) {
      alert(rootState.global.table.no_item_selected_alert)
      return
    }

    var selectedIds = []

    for (var row = 0; row < rootState.table.selectedRows.length; row++) {
      selectedIds[row] = rootState.table.selectedRows[row].id
    }

    const qs = require('qs')
    var data2 = {
      action: 'superstore_export_order_csv',
      nonce: rootState.global.nonce,
      ids: selectedIds,
    }

    window.$http.post(rootState.global.ajaxurl, qs.stringify(data2))
      .then(response => {
        commit('SET_NOTIFY', { enable: true, type: 'success', msg: notifyText }, { root: true })
        commit('SET_TABLE', { loading: false }, { root: true })

        /*
         * Make CSV downloadable
         */
        var downloadLink = document.createElement('a')
        var fileData = ['\ufeff' + response.data]

        var blobObject = new Blob(fileData, {
          type: 'text/csv;charset=utf-8;',
        })

        var url = URL.createObjectURL(blobObject)
        downloadLink.href = url
        downloadLink.download = 'orders-' + new Date().getTime() + '.csv'

        /*
         * Download CSV
         */
        document.body.appendChild(downloadLink)
        downloadLink.click()
        document.body.removeChild(downloadLink)
      })
      .catch(error => {
        commit('SET_NOTIFY', { enable: true, type: 'unsuccess', msg: error.response.data.data[0].message !== undefined ? error.response.data.data[0].message : error }, { root: true })
      })
      .then(() => {
        commit('SET_TABLE', { loading: false }, { root: true })
      })
  },
}
