<template>
  <v-container
    id="superstore-media-upoad-new-file"
    fluid
  >
    <div
      class="ma-10 pa-10 text-center"
      style="border: 1px dashed grey;"
      @dragover.prevent=""
      @dragleave.prevent=""
      @drop.prevent="drop($event)"
    >
      <base-overlay-loading />
      <div>
        <v-icon
          :class="`display-2 ${$vuetify.theme.dark ? 'grey--text text--darken-2' : 'grey--text text--lighten-1'}`"
        >
          mdi-image
        </v-icon>
      </div>
      <div class="text-h5 pa-3">
        {{ $store.state.global.media_uploader.drop_text }}
      </div>

      <v-file-input
        v-model="files"
        color="primary"
        counter
        :label="$store.state.global.media_uploader.add_new_media_choose_files_text"
        prepend-icon=""
        append-icon="mdi-paperclip"
        multiple
        outlined
        type="file"
        :show-size="1000"
        @change="upload()"
      >
        <template v-slot:selection="{ index, text }">
          <v-chip
            v-if="index < 2"
            color="deep-purple accent-4"
            dark
            label
            small
          >
            {{ text }}
          </v-chip>

          <span
            v-else-if="index === 2"
            class="font-weight-bold mx-2"
          >
            +{{ files.length - 2 }}
          </span>
        </template>
      </v-file-input>
    </div>
    <v-row
      v-if="files.length > 0 && $store.state.ajaxResponse !== null"
      class="px-8 py-4"
    >
      <v-col cols="12">
        {{ $store.state.global.media_uploader.add_new_media_uploaded_files_text }}
      </v-col>
      <v-col
        v-for="(file, key) in files"
        :key="key"
        cols="2"
        class="text-center"
      >
        <div class="grey--text text-caption text-left">
          {{ key + 1 }}
        </div>
        <span>
          <img
            v-if="file.type.includes('image')"
            :src="getFileSrc(file)"
            style="width: 170px;height: 170px;object-fit: cover;"
          >
          <v-icon
            v-else
            class="text-h1"
          >
            mdi-file-document
          </v-icon>
        </span>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        files: [],
      }
    },

    methods: {
      getFileSrc (file) {
        return URL.createObjectURL(file)
      },
      drop (event) {
        this.files = Array.from(event.dataTransfer.files)
        for (var i = 0; i < this.files.length; i++) {
          this.uploadFiles(this.files[i])
        }
      },
      upload () {
        for (var i = 0; i < this.files.length; i++) {
          this.uploadFiles(this.files[i])
        }
      },
      uploadFiles (file) {
        this.$store.dispatch('media/uploadFile', file)
      },
    },
  }
</script>
