export const namespaced = true

export const state = window.superstore.payment

export const actions = {
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
          await dispatch('api', { endpoint: '/payments/' + selectedId, method: 'patch', data: value, notifyMsg: (row === rootState.table.selectedRows.length - 1) ? notifyText : undefined }, { root: true })
        }

        dispatch('getTableRows', null, { root: true })
      }
    } else {
      if (data.value !== undefined) {
        value[data.name] = data.value
        await dispatch('api', { endpoint: '/payments/' + data.id, method: 'patch', data: value, notifyMsg: notifyText }, { root: true })

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

        await dispatch('api', { endpoint: '/payments/' + selectedId, method: 'delete', notifyMsg: (row === rootState.table.selectedRows.length - 1) ? rootState.global.notify.success_delete : undefined }, { root: true })
      }

      commit('SET_TABLE', { selectedRows: [] }, { root: true })

      dispatch('getTableRows', null, { root: true })
    } else {
      if (!confirm(rootState.global.notify.confirm_delete)) {
        return
      }

      await dispatch('api', { endpoint: '/payments/' + data.id, method: 'delete', notifyMsg: rootState.global.notify.success_delete }, { root: true })

      dispatch('getTableRows', null, { root: true })
    }
  },
}
