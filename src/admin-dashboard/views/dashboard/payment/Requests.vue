<template>
  <base-table>
    <template v-slot:note="item">
      <v-text-field
        :value="item.prop.note"
        dense
        outlined
        label="Note"
        hide-details
        @input="note = $event"
      >
        <template v-slot:append>
          <v-btn
            x-small
            depressed
            color="primary"
            class="ma-0"
            @click="modifyNote({ value: note, name: 'note', id: item.prop.id })"
          >
            {{ $store.state.global.form.submit_button_title }}
          </v-btn>
        </template>
      </v-text-field>
    </template>
    <template v-slot:seller="item">
      <v-row class="my-1">
        <router-link :to="`/seller/${item.prop.user_id}`">
          <v-avatar
            tile
            size="30"
          >
            <v-icon
              v-if="!item.prop.store_profile_picture_src"
            >
              mdi-storefront
            </v-icon>
            <v-img
              v-else
              :src="item.prop.store_profile_picture_src"
            />
          </v-avatar>
          <span class="pl-2">
            {{ item.prop.store_name ? item.prop.store_name : $store.state.global.no_name }}
          </span>
        </router-link>
      </v-row>
    </template>
  </base-table>
</template>

<script>
  export default {
    data () {
      return {
        note: '',
      }
    },

    created () {
      this.$store.commit('SET_TABLE', this.$store.state.tab.body.requests.table)
    },

    methods: {
      modifyNote (data) {
        this.$store.dispatch('payment/edit', data)
      },
    },
  }
</script>
