import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import './sass/overrides.sass'

Vue.use(Vuetify)

const theme = {
  primary: window.superstore.global.dashboard_primary_color ? window.superstore.global.dashboard_primary_color : '#6f6af8',
  secondary: '#4caf50',
}

export default new Vuetify({
  theme: {
    themes: {
      light: theme,
      dark: theme,
    },
  },
})
