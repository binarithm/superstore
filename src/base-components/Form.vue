<template>
  <v-form
    ref="form"
    v-model="valid"
    lazy-validation
  >
    <base-overlay-loading />

    <v-row
      class="px-10 py-5"
      :justify="form.sections.length < 2 ? 'center' : 'start'"
    >
      <v-col
        v-for="(section, sectionIndex) in form.sections"
        :key="sectionIndex"
        cols="12"
        :md="md"
      >
        <v-card
          class="mx-auto fill-height ma-0"
          outlined
        >
          <v-card-text
            :class="$vuetify.theme.dark ? 'grey darken-4 text-caption font-weight-bold py-2' : 'grey lighten-3 text-caption font-weight-bold py-2'"
          >
            {{ section.title }}
          </v-card-text>
          <div
            v-for="(field, fieldIndex) in section.fields"
            :key="fieldIndex"
            class="mx-10 my-2"
          >
            <v-row
              v-if="field.type === 'autocomplete'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-autocomplete
                  :value="form.fields[field.name]"
                  :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                  :items="field.items"
                  item-text="label"
                  item-value="value"
                  clearable
                  counter
                  :no-data-text="$store.state.global.table.no_item_found_text"
                  small-chips
                  :multiple="field.multiple === 'yes' ? true : false"
                  :hint="field.hint"
                  persistent-hint
                  outlined
                  dense
                  :disabled="field.disabled === 'yes' ? true : false"
                  @change="UPDATE_FORM_FIELDS({ [field.name]: $event === null ? '' : $event })"
                >
                  <template v-slot:selection="{ attrs, on, item, selected, parent, index }">
                    <v-chip
                      v-if="index < 3"
                      v-bind="attrs"
                      :input-value="selected"
                      small
                      label
                      :class="field.show_priority === 'yes' ? 'pl-0 ma-1' : 'ma-1'"
                      v-on="on"
                    >
                      <v-avatar
                        v-if="field.show_priority === 'yes'"
                        class="primary white--text ml-0"
                        left
                        tile
                        v-text="index + 1"
                      />
                      <span class="pr-2">
                        {{ item.label }}
                      </span>
                    </v-chip>

                    <span
                      v-else-if="index === 3"
                      class="font-weight-bold mx-2"
                    >
                      +{{ form.fields[field.name].length - 3 }}
                    </span>
                  </template>
                </v-autocomplete>
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'color_picker'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-color-picker
                  :value="form.fields[field.name]"
                  mode="hexa"
                  :name="field.name"
                  :placeholder="field.placeholder"
                  :hint="field.hint"
                  elevation="2"
                  @input="UPDATE_FORM_FIELDS({ [field.name]: $event === null ? '' : $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'checkbox'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-checkbox
                  :input-value="form.fields[field.name]"
                  :name="field.name"
                  :placeholder="field.placeholder"
                  :hint="field.hint"
                  :label="field.label"
                  type="checkbox"
                  persistent-hint
                  outlined
                  dense
                  :disabled="field.disabled === 'yes' ? true : false"
                  :true-value="field.true_value !== undefined ? field.true_value : 'yes'"
                  :false-value="field.false_value !== undefined ? field.false_value : 'no'"
                  @change="UPDATE_FORM_FIELDS({ [field.name]: $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'email'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-text-field
                  :value="form.fields[field.name]"
                  :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text, v => v !== '' ? /.+@.+\..+/.test(v) || $store.state.global.form.valid_email_text : true] : [v => v !== '' ? /.+@.+\..+/.test(v) || $store.state.global.form.valid_email_text : true]"
                  :name="field.name"
                  :placeholder="field.placeholder"
                  :hint="field.hint"
                  type="email"
                  persistent-hint
                  outlined
                  dense
                  :disabled="field.disabled === 'yes' ? true : false"
                  @input="UPDATE_FORM_FIELDS({ [field.name]: $event === null ? '' : $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'file'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <base-file-uploader
                  :height="field.height !== undefined ? field.height : 200"
                  :cropping-width="field.cropping_width !== undefined ? field.cropping_width : 200"
                  :cropping-height="field.cropping_height !== undefined ? field.cropping_height : 100"
                  :src="!form.fields[field.name].url ? false : form.fields[field.name].url"
                  @change="UPDATE_FORM_FIELDS({ [field.name]: $event })"
                  @delete="UPDATE_FORM_FIELDS({ [field.name]: $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'files'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <span
                  v-for="(image, imageIndex) in $store.state.form.fields[field.name]"
                  :key="imageIndex"
                >
                  <base-file-uploader
                    :height="100"
                    :change-btn="false"
                    :cropping-width="field.cropping_width !== undefined ? field.cropping_width : 200"
                    :cropping-height="field.cropping_height !== undefined ? field.cropping_height : 100"
                    :src="!image.url ? false : image.url"
                    @delete="$store.commit('SPLICE_MULTI_FORM_FIELDS', { field: field.name, index: imageIndex })"
                  />
                </span>

                <base-file-uploader
                  :height="100"
                  :cropping-width="field.cropping_width !== undefined ? field.cropping_width : 200"
                  :cropping-height="field.cropping_height !== undefined ? field.cropping_height : 100"
                  @change="$store.commit('PUSH_MULTI_FORM_FIELDS', { field: field.name, value: $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'multiple'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <div
                  v-for="(multipleValue, multipleIndex) in field.items"
                  :key="multipleIndex"
                  no-gutters
                >
                  <span
                    v-if="multipleValue.type === 'checkbox'"
                  >
                    <v-checkbox
                      :input-value="form.fields[field.name][multipleValue.child_name]"
                      :name="multipleValue.name"
                      :placeholder="multipleValue.placeholder"
                      :hint="multipleValue.hint"
                      :label="multipleValue.label"
                      type="checkbox"
                      persistent-hint
                      outlined
                      dense
                      :disabled="multipleValue.disabled === 'yes' ? true : false"
                      :true-value="multipleValue.true_value !== undefined ? multipleValue.true_value : 'yes'"
                      :false-value="multipleValue.false_value !== undefined ? multipleValue.false_value : 'no'"
                      @change="field.value_type !== undefined && field.value_type === 'object' ? UPDATE_FORM_FIELDS({ field: field.name, subField: multipleValue.child_name, event: $event }) : UPDATE_FORM_FIELDS({ [multipleValue.name]: $event })"
                    />
                  </span>
                  <span
                    v-if="multipleValue.type === 'select'"
                  >
                    <v-select
                      :value="form.fields[field.name][multipleValue.child_name]"
                      :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                      :name="multipleValue.name"
                      :placeholder="multipleValue.placeholder"
                      :label="multipleValue.label"
                      :hint="multipleValue.hint"
                      :items="multipleValue.items"
                      item-text="title"
                      item-value="value"
                      :no-data-text="$store.state.global.table.no_item_found_text"
                      type="select"
                      persistent-hint
                      outlined
                      dense
                      :disabled="multipleValue.disabled === 'yes' ? true : false"
                      @input="field.value_type !== undefined && field.value_type === 'object' ? UPDATE_FORM_FIELDS({ field: field.name, subField: multipleValue.child_name, event: $event }) : UPDATE_FORM_FIELDS({ [multipleValue.name]: $event })"
                    />
                  </span>
                  <span
                    v-if="multipleValue.type === 'switch'"
                  >
                    <v-switch
                      :input-value="form.fields[field.name][multipleValue.child_name]"
                      :name="multipleValue.name"
                      :hint="multipleValue.hint"
                      persistent-hint
                      :label="multipleValue.label"
                      :disabled="multipleValue.disabled === 'yes' ? true : false"
                      :true-value="multipleValue.true_value !== undefined ? multipleValue.true_value : 'yes'"
                      :false-value="multipleValue.false_value !== undefined ? multipleValue.false_value : 'no'"
                      @change="field.value_type !== undefined && field.value_type === 'object' ? UPDATE_FORM_FIELDS({ field: field.name, subField: multipleValue.child_name, event: $event }) : UPDATE_FORM_FIELDS({ [multipleValue.name]: $event })"
                    />
                  </span>
                  <span
                    v-if="multipleValue.type === 'textarea'"
                  >
                    <v-textarea
                      :value="form.fields[field.name][multipleValue.child_name]"
                      :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                      :name="multipleValue.name"
                      :placeholder="multipleValue.placeholder"
                      :hint="multipleValue.hint"
                      type="textarea"
                      persistent-hint
                      outlined
                      dense
                      clearable
                      counter
                      :disabled="multipleValue.disabled === 'yes' ? true : false"
                      @input="field.value_type !== undefined && field.value_type === 'object' ? UPDATE_FORM_FIELDS({ field: field.name, subField: multipleValue.child_name, event: $event }) : UPDATE_FORM_FIELDS({ [multipleValue.name]: $event })"
                    />
                  </span>
                  <span
                    v-if="multipleValue.type === 'number'"
                  >
                    <v-text-field
                      :value="form.fields[field.name][multipleValue.child_name]"
                      :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                      :name="multipleValue.name"
                      :placeholder="multipleValue.placeholder"
                      :label="multipleValue.label"
                      :hint="multipleValue.hint"
                      type="number"
                      persistent-hint
                      outlined
                      dense
                      :disabled="multipleValue.disabled === 'yes' ? true : false"
                      @input="field.value_type !== undefined && field.value_type === 'object' ? UPDATE_FORM_FIELDS({ field: field.name, subField: multipleValue.child_name, event: $event }) : UPDATE_FORM_FIELDS({ [multipleValue.name]: $event })"
                    />
                  </span>
                  <span
                    v-if="multipleValue.type === 'autocomplete'"
                  >
                    <v-autocomplete
                      :value="form.fields[field.name][multipleValue.child_name]"
                      :disabled="multipleValue.disabled === 'yes' ? true : false"
                      :items="multipleValue.items"
                      :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                      :name="multipleValue.name"
                      :placeholder="multipleValue.placeholder"
                      :label="multipleValue.label"
                      :hint="multipleValue.hint"
                      :no-data-text="$store.state.global.table.no_item_found_text"
                      persistent-hint
                      filled
                      dense
                      outlined
                      item-text="title"
                      item-value="value"
                      @input="field.value_type !== undefined && field.value_type === 'object' ? UPDATE_FORM_FIELDS({ field: field.name, subField: multipleValue.child_name, event: $event }) : UPDATE_FORM_FIELDS({ [multipleValue.name]: $event })"
                    />
                  </span>
                  <span
                    v-if="multipleValue.type === 'text'"
                  >
                    <v-text-field
                      :value="form.fields[field.name][multipleValue.child_name]"
                      :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                      :name="multipleValue.name"
                      :placeholder="multipleValue.placeholder"
                      :label="multipleValue.label"
                      :hint="multipleValue.hint"
                      persistent-hint
                      outlined
                      dense
                      :disabled="multipleValue.disabled === 'yes' ? true : false"
                      @input="field.value_type !== undefined && field.value_type === 'object' ? UPDATE_FORM_FIELDS({ field: field.name, subField: multipleValue.child_name, event: $event }) : UPDATE_FORM_FIELDS({ [multipleValue.name]: $event })"
                    />
                  </span>
                </div>
                <div class="text-caption">
                  {{ field.hint }}
                </div>
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'number'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-text-field
                  :value="form.fields[field.name]"
                  :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                  :name="field.name"
                  :placeholder="field.placeholder"
                  :hint="field.hint"
                  type="number"
                  persistent-hint
                  outlined
                  dense
                  :disabled="field.disabled === 'yes' ? true : false"
                  @input="UPDATE_FORM_FIELDS({ [field.name]: $event === null ? '' : $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'password'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-text-field
                  :value="form.fields[field.name]"
                  :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                  :name="field.name"
                  :placeholder="field.placeholder"
                  :hint="field.hint"
                  :type="showPassword ? 'text' : 'password'"
                  :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                  persistent-hint
                  outlined
                  dense
                  :disabled="field.disabled === 'yes' ? true : false"
                  @click:append="showPassword = !showPassword"
                  @input="UPDATE_FORM_FIELDS({ [field.name]: $event === null ? '' : $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'radio'"
              align="center"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-radio-group
                  :value="form.fields[field.name]"
                  :name="field.name"
                  :hint="field.hint"
                  persistent-hint
                  mandatory
                  row
                  @change="UPDATE_FORM_FIELDS({ [field.name]: $event })"
                >
                  <v-radio
                    v-for="(group, group_index) in field.groups"
                    :key="group_index"
                    type="radio"
                    :label="group.label"
                    :value="group.value"
                  />
                </v-radio-group>
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'read_only'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }}
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <div
                  v-html="form.fields[field.name]"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'select'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-select
                  :value="form.fields[field.name]"
                  :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                  :name="field.name"
                  :placeholder="field.placeholder"
                  :no-data-text="$store.state.global.table.no_item_found_text"
                  :label="field.label"
                  :hint="field.hint"
                  :items="field.items"
                  type="select"
                  persistent-hint
                  outlined
                  dense
                  :disabled="field.disabled === 'yes' ? true : false"
                  @input="UPDATE_FORM_FIELDS({ [field.name]: $event })"
                >
                  <template v-slot:item="{item}">
                    {{ typeof item === 'object' ? item.name : item }}
                  </template>
                  <template v-slot:selection="{item}">
                    {{ typeof item === 'object' ? item.name : item }}
                  </template>
                </v-select>
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'slot'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <slot
                  :name="field.slot_name"
                  :field="field"
                />
              </v-col>
            </v-row>

            <div
              v-if="field.type === 'slot_only'"
            >
              <slot
                :name="field.slot_name"
                :field="field"
              />
            </div>

            <v-row
              v-if="field.type === 'switch'"
            >
              <v-col
                cols="4"
                class="pr-3"
                align-self="center"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-switch
                  :input-value="form.fields[field.name]"
                  :name="field.name"
                  :hint="field.hint"
                  persistent-hint
                  :label="field.label"
                  :disabled="field.disabled === 'yes' ? true : false"
                  :true-value="field.true_value !== undefined ? field.true_value : 'yes'"
                  :false-value="field.false_value !== undefined ? field.false_value : 'no'"
                  @change="UPDATE_FORM_FIELDS({ [field.name]: $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'text'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-text-field
                  :value="form.fields[field.name]"
                  :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                  :name="field.name"
                  :placeholder="field.placeholder"
                  :hint="field.hint"
                  type="text"
                  persistent-hint
                  outlined
                  dense
                  :disabled="field.disabled === 'yes' ? true : false"
                  @input="UPDATE_FORM_FIELDS({ [field.name]: $event === null ? '' : $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'textarea'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-textarea
                  :value="form.fields[field.name]"
                  :rules="field.required === 'yes' ? [value => !!value || $store.state.global.form.valid_required_text] : []"
                  :name="field.name"
                  :placeholder="field.placeholder"
                  :hint="field.hint"
                  type="textarea"
                  persistent-hint
                  outlined
                  dense
                  clearable
                  counter
                  :disabled="field.disabled === 'yes' ? true : false"
                  @input="UPDATE_FORM_FIELDS({ [field.name]: $event === null ? '' : $event })"
                />
              </v-col>
            </v-row>

            <v-row
              v-if="field.type === 'text_editor'"
            >
              <v-col
                cols="4"
                class="pr-3"
              >
                <v-card-title class="text-caption pa-0 ma-0">
                  {{ field.title }} <strong style="color:red;">{{ field.required === 'yes' ? ' *' : null }}</strong>
                </v-card-title>
              </v-col>
              <v-col
                cols="8"
              >
                <v-tiptap
                  :value="form.fields[field.name]"
                  :placeholder="$store.state.global.form.text_editor_placeholder"
                  :name="field.name"
                  :hint="field.hint"
                  locale="en"
                  @input="UPDATE_FORM_FIELDS({ [field.name]: $event })"
                />
              </v-col>
            </v-row>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <div class="text-center">
      <v-btn
        type="submit"
        name="submit"
        color="primary"
        class="text-capitalize ma-2"
        depressed
        :disabled="!valid"
        @click.prevent="submitForm($refs.form)"
      >
        {{ $store.state.global.form.submit_button_title }}
      </v-btn>
      <div
        v-if="!valid"
      >
        {{ $store.state.global.form.submit_button_hint }}<strong style="color:red;">*</strong>
      </div>
    </div>
  </v-form>
</template>

<script>
  import { mapState, mapActions, mapMutations } from 'vuex'

  export default {
    name: 'Form',

    props: {
      md: {
        type: [Number, String],
        default: 6,
      },
    },

    data () {
      return {
        showPassword: false,
      }
    },

    computed: {
      ...mapState(['form']),
      valid: {
        get () {
          return this.$store.state.form.valid
        },
        set (val) {
          this.$store.commit('SET_FORM', { valid: val })
        },
      },
    },

    methods: {
      ...mapMutations(['UPDATE_FORM_FIELDS']),
      ...mapActions(['submitForm']),
    },
  }
</script>
