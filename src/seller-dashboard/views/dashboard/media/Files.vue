<template>
  <base-table>
    <template v-slot:file="item">
      <v-row class="my-1">
        <v-avatar
          tile
          size="30"
        >
          <img
            v-if="item.prop.type.includes('image')"
            :src="item.prop.src ? item.prop.src : ''"
          >
          <v-icon
            v-else
            class="text-h4"
          >
            mdi-file-document
          </v-icon>
        </v-avatar>
        <span class="pl-2">
          <div class="text-caption">{{ item.prop.title }}</div>
          <span
            class="primary--text text-caption"
            style="cursor: pointer;"
            @click="copyURL(item.prop.src)"
          >
            {{ $store.state.global.copy_text }}
          </span>
        </span>
      </v-row>
    </template>
  </base-table>
</template>

<script>
  export default {
    created () {
      this.$store.commit('SET_TABLE', this.$store.state.tab.body.files.table)
    },

    methods: {
      async copyURL (url) {
        this.$store.commit('SET_NOTIFY', { enable: false })
        await navigator.clipboard.writeText(url)
        this.$store.commit('SET_NOTIFY', { enable: true, msg: this.$store.state.global.notify.text_copied })
      },
    },
  }
</script>
