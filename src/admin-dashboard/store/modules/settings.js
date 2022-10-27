export const namespaced = true

export const state = window.superstore.settings

export const actions = {
  edit ({ dispatch, rootState }) {
    var data = {
      action: 'superstore_save_settings',
      nonce: rootState.global.nonce,
      values: rootState.form.fields,
      section: rootState.form.submitExtraData,
    }

    dispatch('ajax', { method: 'post', qsData: data }, { root: true })
  },
}
