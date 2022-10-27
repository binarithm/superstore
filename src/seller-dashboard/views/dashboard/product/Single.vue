<template>
  <v-card
    id="superstore-seller-dashboard-single-products"
    min-height="78vh"
  >
    <base-overlay-loading />

    <div
      v-if="!$store.state.apiResponse && !$store.state.overlayLoading.enable"
      class="pa-10"
    >
      <v-alert
        dense
        outlined
        type="error"
        class="text-left"
      >
        {{ $store.state.product.tab.body.products.single.product_not_found }}
      </v-alert>
    </div>

    <div v-if="$store.state.apiResponse && $store.state.apiResponse.id !== 0">
      <base-form>
        <template v-slot:download_options="item">
          <v-checkbox
            :input-value="$store.state.form.fields.downloadable"
            :name="item.field.items.downloadable.name"
            :hint="item.field.items.downloadable.hint"
            :label="item.field.items.downloadable.label"
            type="checkbox"
            persistent-hint
            outlined
            dense
            @change="$store.commit('UPDATE_FORM_FIELDS', { downloadable: $event })"
          />

          <div
            v-if="$store.state.form.fields.downloadable"
          >
            <v-text-field
              :value="$store.state.form.fields.download_limit"
              :name="item.field.items.download_limit.name"
              :hint="item.field.items.download_limit.hint"
              :label="item.field.items.download_limit.label"
              :placeholder="item.field.items.download_limit.placeholder"
              type="number"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { download_limit: $event })"
            />

            <v-text-field
              :value="$store.state.form.fields.download_expiry"
              :name="item.field.items.download_expiry.name"
              :hint="item.field.items.download_expiry.hint"
              :label="item.field.items.download_expiry.label"
              :placeholder="item.field.items.download_expiry.placeholder"
              type="number"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { download_expiry: $event })"
            />

            <div
              class="pa-2"
              style="border: 1px solid grey;border-radius: 4px;"
            >
              <div class="pb-2">
                {{ item.field.items.downloads.label }}
              </div>

              <div
                v-for="(download, downloadIndex) in $store.state.form.fields.downloads"
                :key="downloadIndex"
              >
                <v-divider
                  v-if="downloadIndex > 0"
                  class="mb-4"
                />
                <v-text-field
                  :value="$store.state.form.fields.downloads[downloadIndex].name"
                  :label="item.field.items.downloads.file_text"
                  type="text"
                  persistent-hint
                  outlined
                  dense
                  @input="$store.commit('UPDATE_MULTI_FORM_FIELDS', { event: $event, inputIndex: 'downloads', multipleInputIndex: 'name', newArrayIndex: Number(downloadIndex) })"
                />
                <v-text-field
                  :value="$store.state.form.fields.downloads[downloadIndex].file"
                  :label="item.field.items.downloads.url_text"
                  type="text"
                  persistent-hint
                  outlined
                  dense
                  @input="$store.commit('UPDATE_MULTI_FORM_FIELDS', { event: $event, inputIndex: 'downloads', multipleInputIndex: 'file', newArrayIndex: Number(downloadIndex) })"
                >
                  <template v-slot:append>
                    <v-btn
                      small
                      depressed
                      color="primary"
                      class="ma-0"
                      @click="chooseFile('downloads', 'file', Number(downloadIndex))"
                    >
                      {{ item.field.items.downloads.choose_btn_text }}
                    </v-btn>
                  </template>
                </v-text-field>
                <v-btn
                  small
                  depressed
                  color="primary"
                  block
                  class="mb-2"
                  @click="$store.commit('SPLICE_MULTI_FORM_FIELDS', { field: 'downloads', index: downloadIndex })"
                >
                  {{ item.field.items.downloads.remove_btn_text }}
                </v-btn>
              </div>
              <v-btn
                small
                depressed
                color="primary"
                block
                @click="$store.commit('PUSH_MULTI_FORM_FIELDS', { field: 'downloads', value: { name: '', file: '' } })"
              >
                {{ item.field.items.downloads.add_btn_text }}
              </v-btn>
            </div>
          </div>
        </template>

        <template v-slot:inventory="item">
          <v-text-field
            :value="$store.state.form.fields.sku"
            :name="item.field.items.sku.name"
            :label="item.field.items.sku.label"
            type="text"
            persistent-hint
            outlined
            dense
            @input="$store.commit('UPDATE_FORM_FIELDS', { sku: $event })"
          />

          <v-radio-group
            :value="$store.state.form.fields.stock_status"
            :name="item.field.items.stock_status.name"
            :label="item.field.items.stock_status.label"
            persistent-hint
            mandatory
            row
            @change="$store.commit('UPDATE_FORM_FIELDS', { stock_status: $event })"
          >
            <v-radio
              v-for="(group, group_index) in item.field.items.stock_status.groups"
              :key="group_index"
              type="radio"
              :label="group.label"
              :value="group.value"
            />
          </v-radio-group>

          <v-checkbox
            :input-value="$store.state.form.fields.manage_stock"
            :name="item.field.items.manage_stock.name"
            :label="item.field.items.manage_stock.label"
            type="checkbox"
            persistent-hint
            outlined
            dense
            @change="$store.commit('UPDATE_FORM_FIELDS', { manage_stock: $event })"
          />

          <div
            v-if="$store.state.form.fields.manage_stock"
          >
            <v-text-field
              :value="$store.state.form.fields.stock_quantity"
              :name="item.field.items.stock_quantity.name"
              :label="item.field.items.stock_quantity.label"
              type="number"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { stock_quantity: $event })"
            />

            <v-text-field
              :value="$store.state.form.fields.low_stock_amount"
              :name="item.field.items.low_stock_amount.name"
              :label="item.field.items.low_stock_amount.label"
              type="number"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { low_stock_amount: $event })"
            />

            <v-select
              :value="$store.state.form.fields.backorders"
              :name="item.field.items.backorders.name"
              :no-data-text="$store.state.global.table.no_item_found_text"
              :label="item.field.items.backorders.label"
              :items="item.field.items.backorders.items"
              type="select"
              persistent-hint
              outlined
              dense
              @input="$store.commit('UPDATE_FORM_FIELDS', { backorders: $event })"
            >
              <template v-slot:item="{item}">
                {{ typeof item === 'object' ? item.name : item }}
              </template>
              <template v-slot:selection="{item}">
                {{ typeof item === 'object' ? item.name : item }}
              </template>
            </v-select>
          </div>
        </template>
      </base-form>
    </div>
  </v-card>
</template>

<script>
  export default {
    async created () {
      this.$store.commit('SET_FORM', this.$store.state.product.tab.body.products.single.form)
      await this.getProduct()
    },

    methods: {
      async getProduct () {
        await this.$store.dispatch('api', { endpoint: '/products/' + this.$route.params.id, method: 'get' })
        this.$store.commit('SET_FORM', { fields: this.$store.state.apiResponse })
      },
      chooseFile (inputIndex, multipleInputIndex, ui) {
        const self = this
        window.wp.media.editor.send.attachment = function (props, attachment) {
          var choosen = attachment.url
          var data = {
            event: choosen,
            inputIndex: inputIndex,
            multipleInputIndex: multipleInputIndex,
            newArrayIndex: ui,
          }

          self.$store.commit('UPDATE_MULTI_FORM_FIELDS', data)
        }
        window.wp.media.editor.open()
      },
    },
  }
</script>
