<template>
  <div
    class="text-center ma-2"
    style="border: 1px dashed rgba(0, 0, 0, .08);"
  >
    <v-hover>
      <template v-slot:default="{ hover }">
        <v-card
          class="mx-auto ma-0 pa-0"
          flat
        >
          <span>
            <v-avatar
              v-if="avatar"
              size="100"
              class="elevation-3"
            >
              <v-img
                :src="!src ? '' : src"
                :height="height"
                :contain="contain"
              />
            </v-avatar>

            <v-img
              v-else
              :src="!src ? '' : src"
              :height="height"
              :contain="contain"
            />
          </span>

          <v-overlay
            v-if="!src"
            absolute
            opacity="0"
          >
            <v-icon
              v-if="!hideIconPlaceholder"
              :class="`display-2 ${$vuetify.theme.dark ? 'grey--text text--darken-2' : 'grey--text text--lighten-1'}`"
            >
              mdi-image
            </v-icon>

            <div>
              <v-btn
                v-if="!onlyIconBtn"
                color="primary"
                depressed
                small
                class="ma-1"
                @click="chooseFile()"
              >
                {{ $store.state.global.media_uploader.upload_text }}
                <v-icon right>
                  mdi-upload
                </v-icon>
              </v-btn>
              <v-btn
                v-else
                icon
                outlined
                color="primary"
                small
                class="ma-1"
                @click="chooseFile()"
              >
                <v-icon>
                  mdi-upload
                </v-icon>
              </v-btn>
            </div>
            <div :class="`text-caption ${$vuetify.theme.dark ? 'white' : 'black'}--text`">
              {{ croppingWidth }}x{{ croppingHeight }} {{ format }}
            </div>
          </v-overlay>

          <v-fade-transition>
            <v-overlay
              v-if="hover && src !== false"
              absolute
            >
              <span v-if="!onlyIconBtn">
                <v-btn
                  v-if="changeBtn"
                  color="primary"
                  depressed
                  small
                  class="ma-1"
                  @click="chooseFile()"
                >
                  {{ $store.state.global.media_uploader.edit_text }}
                  <v-icon right>
                    mdi-pencil
                  </v-icon>
                </v-btn>
                <v-btn
                  v-if="deleteBtn"
                  color="primary"
                  depressed
                  small
                  class="ma-1"
                  @click="deleteImage"
                >
                  {{ $store.state.global.media_uploader.delete_text }}
                  <v-icon right>
                    mdi-close
                  </v-icon>
                </v-btn>
              </span>
              <span v-else>
                <v-btn
                  v-if="changeBtn"
                  icon
                  outlined
                  color="primary"
                  small
                  class="ma-1"
                  @click="chooseFile()"
                >
                  <v-icon>
                    mdi-pencil
                  </v-icon>
                </v-btn>
                <v-btn
                  v-if="deleteBtn"
                  icon
                  outlined
                  color="primary"
                  small
                  class="ma-1"
                  @click="deleteImage"
                >
                  <v-icon>
                    mdi-close
                  </v-icon>
                </v-btn>
              </span>
            </v-overlay>
          </v-fade-transition>
        </v-card>
      </template>
    </v-hover>
  </div>
</template>

<script>
  export default {
    props: {
      src: {
        type: [String, Boolean],
        default: false,
      },
      hideIconPlaceholder: {
        type: Boolean,
        default: false,
      },
      contain: {
        type: Boolean,
        default: true,
      },
      avatar: {
        type: Boolean,
        default: false,
      },
      onlyIconBtn: {
        type: Boolean,
        default: false,
      },
      deleteBtn: {
        type: Boolean,
        default: true,
      },
      changeBtn: {
        type: Boolean,
        default: true,
      },
      format: {
        type: String,
        default: 'pixels',
      },
      croppingWidth: {
        type: [Number, String],
        default: 200,
      },
      croppingHeight: {
        type: [Number, String],
        default: 100,
      },
      height: {
        type: [Number, String],
        default: 200,
      },
    },

    methods: {
      deleteImage () {
        this.$emit('delete', { id: false, url: false })
      },
      chooseFile () {
        const self = this
        window.wp.media.editor.send.attachment = function (props, attachment) {
          self.$emit('change', attachment)
        }
        window.wp.media.editor.open()
      },
    },
  }
</script>
