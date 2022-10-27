<template>
  <div>
    <v-snackbar
      v-model="snackbar.enable"
      :color="snackbar.color"
      dark
      app
      top
      right
      :timeout="10000"
    >
      {{ snackbar.msg }}
      <template v-slot:action="{ attrs }">
        <v-btn
          icon
          color="white"
          v-bind="attrs"
          class="pa-0 ma-0"
          style="background-color: unset;"
          @click="snackbar.enable = false"
        >
          <v-icon>mdi-close-circle</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
    <v-form method="post">
      <div v-if="!register">
        <div style="margin-bottom: 15px;">
          <label
            for="username"
            class="text-left"
          >
            {{ account.email_username }}
            <strong style="color: red;">*</strong>
          </label>
          <input
            id="username"
            type="text"
            name="username"
            style="display: block;width: 100%;"
          >
        </div>

        <div style="margin-bottom: 15px;">
          <label
            for="password"
            class="text-left"
          >
            {{ account.password }}
            <strong style="color: red;">*</strong>
          </label>
          <div style="display: flex;width: 100%;">
            <input
              id="password"
              :type="showPassword ? 'text' : 'password'"
              name="password"
              class="pl-10"
              style="width: 100%;"
            >
            <v-icon
              color="grey"
              class="pa-2"
              style="position: absolute;min-width: 20px;"
              @click="showPassword = !showPassword"
            >
              {{ showPassword ? 'mdi-eye-off' : 'mdi-eye' }}
            </v-icon>
          </div>
        </div>
        <!-- <input
          type="hidden"
          name="redirect"
          :value="redirect"
        > -->
        <input
          type="hidden"
          name="woocommerce-login-nonce"
          :value="account.nonce"
        >
        <v-row no-gutters>
          <v-checkbox
            name="rememberme"
            type="checkbox"
            true-value="forever"
            hide-details
            :label="account.remember"
          />
          <v-spacer />
          <a
            :href="account.lost_password_url"
            class="mt-auto"
          >
            {{ account.lost_password }}
          </a>
        </v-row>
        <div class="text-center pa-2">
          <v-btn
            color="primary"
            depressed
            type="submit"
            name="login"
            class="text-capitalize font-weight-bold"
          >
            {{ account.login }}
          </v-btn>
        </div>

        <div
          v-if="account.registration_enabled === 'yes'"
          class="text-center pa-2"
        >
          <v-btn
            color="primary"
            text
            small
            class="text-capitalize font-weight-bold"
            @click="register = true"
          >
            {{ account.become_seller }}
          </v-btn>
        </div>
      </div>

      <div
        v-else
      >
        <div style="margin-bottom: 15px;">
          <label
            for="first_name"
            class="text-left"
          >
            {{ account.first_name }}
          </label>
          <input
            id="first_name"
            v-model="firstName"
            type="text"
            name="first_name"
            style="display: block;width: 100%;"
          >
        </div>

        <div style="margin-bottom: 15px;">
          <label
            for="last_name"
            class="text-left"
          >
            {{ account.last_name }}
          </label>
          <input
            id="last_name"
            v-model="lastName"
            type="text"
            name="last_name"
            style="display: block;width: 100%;"
          >
        </div>

        <div style="margin-bottom: 15px;">
          <label
            for="store_name"
            class="text-left"
          >
            {{ account.store_name }}
          </label>
          <input
            id="store_name"
            v-model="storeName"
            type="text"
            name="store_name"
            style="display: block;width: 100%;"
          >
        </div>

        <div style="margin-bottom: 15px;">
          <div>
            <label
              for="store_url_nicename"
              class="text-left"
              style="display: inline;"
            >
              {{ account.store_nicename }}
            </label>
            <span
              style="float: right;"
              :class="`text-caption ${availableText === 'Available' ? 'green--text' : 'red--text'}`"
            >
              {{ storeUrlNicename !== '' ? availableText : '' }}
            </span>
          </div>
          <v-progress-linear
            :active="progressLoading"
            :indeterminate="progressLoading"
            color="primary"
          />
          <input
            id="store_url_nicename"
            v-model="storeUrlNicename"
            type="text"
            name="store_url_nicename"
            style="display: block;width: 100%;"
            @input="checkStorenameAvailability"
          >
          <span class="text-caption">{{ storeUrlNicename !== '' ? account.stores_list_url + '#/' + storeUrlNicename : account.stores_list_url + '#/my-store' }}</span>
        </div>
        <div style="margin-bottom: 15px;">
          <label
            for="email"
            class="text-left"
          >
            {{ account.email }}
            <strong style="color: red;">*</strong>
          </label>
          <input
            id="email"
            v-model="email"
            type="text"
            name="email"
            style="display: block;width: 100%;"
          >
        </div>

        <div
          v-if="account.generate_username !== 'yes'"
          style="margin-bottom: 15px;"
        >
          <label
            for="username"
            class="text-left"
          >
            {{ account.username }}
          </label>
          <input
            id="username"
            v-model="username"
            type="text"
            name="username"
            style="display: block;width: 100%;"
          >
        </div>

        <div
          v-if="account.generate_password !== 'yes'"
          style="margin-bottom: 15px;"
        >
          <label
            for="password"
            class="text-left"
          >
            {{ account.password }}
            <strong style="color: red;">*</strong>
          </label>
          <div style="display: flex;width: 100%;">
            <input
              id="password"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              name="password"
              class="pl-10"
              style="width: 100%;"
            >
            <v-icon
              color="grey"
              class="pa-2"
              style="position: absolute;min-width: 20px;"
              @click="showPassword = !showPassword"
            >
              {{ showPassword ? 'mdi-eye-off' : 'mdi-eye' }}
            </v-icon>
          </div>
        </div>
        <div class="text-center pa-2">
          <v-btn
            :loading="loading"
            :disabled="loading"
            color="primary"
            depressed
            type="submit"
            name="login"
            class="text-capitalize font-weight-bold"
            @click.prevent="submit"
          >
            {{ account.register }}
          </v-btn>
        </div>
        <div class="text-center pa-2">
          <v-btn
            color="primary"
            text
            small
            class="text-capitalize font-weight-bold"
            @click="register = false"
          >
            {{ account.back_login }}
          </v-btn>
        </div>
      </div>
    </v-form>
  </div>
</template>

<script>
  export default {
    name: 'GeneralStyleForm',

    data () {
      return {
        tab: null,
        register: false,
        snackbar: {
          enable: false,
          msg: '',
          color: 'green',
        },
        showPassword: false,
        firstName: '',
        lastName: '',
        storeName: '',
        storeUrlNicename: '',
        email: '',
        password: '',
        username: '',
        loading: false,
        progressLoading: false,
        availableText: '',
        account: window.superstore.account,
      }
    },

    watch: {
      storeName (newVal) {
        this.storeUrlNicename = newVal.split(' ').join('-').toLowerCase().replace(/[^\w\s/-]/g, '').replace(/-+/g, '-').replace(/-$/, '')
        this.checkStorenameAvailability()
      },
      storeUrlNicename (newVal) {
        this.storeUrlNicename = newVal.split(' ').join('-').toLowerCase()
        this.checkStorenameAvailability()
      },
    },

    methods: {
      submit () {
        this.snackbar.enable = false
        this.loading = true
        this.storeUrlNicename = this.storeUrlNicename.replace(/[^\w\s/-]/g, '').replace(/-+/g, '-').replace(/-$/, '').replace(/^-/, '')

        var data = {
          first_name: this.firstName,
          last_name: this.lastName,
          email: this.email,
          password: this.password,
          user_login: this.username,
          store_name: this.storeName,
          store_url_nicename: this.storeUrlNicename,
        }

        window.$http({
          url: window.superstore.global.rest.root + window.superstore.global.rest.version + '/sellers',
          method: 'post',
          data: data,
        })
          .then(response => {
            this.snackbar = {
              enable: true,
              msg: this.account.register_success,
              color: 'green',
            }

            if (this.account.set_logged_in_after_register === 'yes') {
              location.reload()
            } else {
              this.firstName = ''
              this.lastName = ''
              this.storeName = ''
              this.storeUrlNicename = ''
              this.email = ''
              this.password = ''
              this.username = ''
              this.register = false
              this.progressLoading = false
            }

            this.loading = false
          })
          .catch(error => {
            this.snackbar = {
              enable: true,
              msg: error.response.data.message,
              color: 'red',
            }
          })
          .then(() => {
            this.loading = false
          })
      },
      checkStorenameAvailability () {
        this.progressLoading = true
        this.availableText = ''
        this.snackbar = {
          enable: false,
        }

        var formData = new FormData()
        formData.append('action', 'superstore_store_nicename_available')
        formData.append('nonce', window.superstore.global.nonce)
        formData.append('store_nicename', this.storeUrlNicename)

        window.$http.post(window.superstore.global.ajaxurl, formData)
          .then(response => {
            this.availableText = response.data.data
            this.progressLoading = false
          })
          .catch(error => {
            this.snackbar = {
              enable: true,
              msg: error.response.data.data[0].message,
              color: 'red',
            }
          })
          .then(() => {
            this.progressLoading = false
          })
      },
    },
  }
</script>
