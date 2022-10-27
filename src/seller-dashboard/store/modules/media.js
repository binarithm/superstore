export const namespaced = true

export const state = window.superstore.media

export const actions = {
  uploadFile ({ dispatch, rootState }, file) {
    var data = {
      action: 'superstore_upload_file',
      nonce: rootState.global.nonce,
      file: file,
    }

    dispatch('ajax', { method: 'post', formData: data, notifyMsg: true }, { root: true })
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

        await dispatch('api', { endpoint: '/media/' + selectedId, method: 'delete', notifyMsg: (row === rootState.table.selectedRows.length - 1) ? rootState.global.notify.success_delete : undefined }, { root: true })
      }

      commit('SET_TABLE', { selectedRows: [] }, { root: true })

      dispatch('getTableRows', null, { root: true })
    } else {
      if (!confirm(rootState.global.notify.confirm_delete)) {
        return
      }

      await dispatch('api', { endpoint: '/media/' + data.id, method: 'delete', notifyMsg: rootState.global.notify.success_delete }, { root: true })

      dispatch('getTableRows', null, { root: true })
    }
  },
}
