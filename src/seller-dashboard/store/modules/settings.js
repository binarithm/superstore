export const namespaced = true

export const state = window.superstore.settings

export const actions = {
  async getSettings ({ state, commit, dispatch, rootState }, settingsSection) {
    var data = {
      action: 'superstore_get_seller_settings_values',
      nonce: rootState.global.nonce,
    }

    commit('SET_FORM', state.tab.body[settingsSection].form, { root: true })

    await dispatch('ajax', { method: 'post', qsData: data }, { root: true })

    commit('SET_FORM', { fields: rootState.ajaxResponse }, { root: true })
  },

  async edit ({ commit, dispatch, rootState }) {
    await dispatch('api', { endpoint: '/sellers/' + rootState.form.fields.id, method: 'patch', data: rootState.form.fields, notifyMsg: rootState.global.notify.success_saved, needReload: rootState.form.submitExtraData === 'payment' }, { root: true })
    await dispatch('getSettings', rootState.form.submitExtraData)
  },

  async changePassword ({ commit, state, dispatch, rootState }, data) {
    var data2 = {
      action: 'superstore_change_password',
      nonce: rootState.global.nonce,
      current_password: data.current_password,
      confirm_new_password: data.confirm_new_password,
    }

    dispatch('ajax', { method: 'post', qsData: data2, notifyMsg: '', needReload: true }, { root: true })

    await dispatch('getSettings', 'account')
  },
}
