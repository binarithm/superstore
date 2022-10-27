<template>
  <v-data-table
    v-model="selectedRows"
    :headers="table.headers"
    :items="table.rows"
    :loading="table.loading"
    :server-items-length="table.totalItems"
    :no-data-text="global.table.no_item_found_text"
    :no-results-text="global.table.no_search_item_found_text"
    :loading-text="global.table.loading_text"
    sort-by="date_created"
    :show-select="table.actions.length !== 0"
    item-key="id"
    :footer-props="{
      itemsPerPageText: global.table.items_per_page_text,
      itemsPerPageAllText: global.table.items_per_page_all_text,
      showFirstLastPage: true,
      firstIcon: 'mdi-chevron-double-left',
      lastIcon: 'mdi-chevron-double-right',
      prevIcon: 'mdi-chevron-left',
      nextIcon: 'mdi-chevron-right',
      showCurrentPage: true,
    }"
    @update:page="updatePage"
    @update:items-per-page="updatePerPage"
  >
    <template v-slot:top>
      <v-toolbar
        dense
        height="40"
        flat
      >
        <span v-if="table.filterItems.length !== 0">
          <v-btn
            v-for="(filterItem, filterItemIndex) in table.filterItems"
            :key="filterItemIndex + 'filterItemIndex'"
            :color="(filterItem.name === 'all') ? (table.filters !== null ? '' : 'primary') : (((table.filters !== null ) && (table.filters.hasOwnProperty(filterItem.name) && table.filters[filterItem.name] === filterItem.value) ? 'primary' : ''))"
            small
            text
            @click="filter(filterItem.name, filterItem.value, Number(table.count[filterItem.name]))"
          >
            {{ filterItem.title }}({{ table.count[filterItem.name] }})
          </v-btn>
        </span>

        <v-spacer />

        <span v-if="table.links.length !== 0">
          <v-btn
            v-for="(link, linkIndex) in table.links"
            :key="linkIndex + 'linkIndex'"
            color="primary"
            small
            text
            :to="link.to"
            v-html="link.title"
          />
        </span>
      </v-toolbar>

      <v-toolbar
        dense
        height="50"
        flat
      >
        <v-menu
          v-if="table.actions.length !== 0"
          transition="scroll-y-reverse-transition"
          open-on-hover
          bottom
          left
          offset-y
          origin="top right"
          content-class="elevation-2"
        >
          <template v-slot:activator="{ attrs, on }">
            <v-btn
              :class="table.selectedRows.length !== 0 ? 'text-caption primary text-capitalize white--text' : `text-caption text-capitalize grey--text text--${!$vuetify.theme.dark ? 'darken' : 'lighten'}-1`"
              :dark="table.selectedRows.length !== 0"
              small
              :outlined="table.selectedRows.length === 0"
              depressed
              v-bind="attrs"
              v-on="on"
            >
              {{ global.table.bulk_action_title }}({{ table.selectedRows.length }})
              <v-icon right>
                mdi-menu-down
              </v-icon>
            </v-btn>
          </template>

          <v-list
            :tile="false"
            nav
            dense
          >
            <v-hover
              v-for="(action, actionIndex) in table.actions"
              :key="actionIndex + 'actionIndex'"
              v-slot="{ hover }"
            >
              <v-list-item
                v-show="action.skip_in_bulk === undefined || action.skip_in_bulk !== 'yes'"
                :class="`text-caption py-0 px-2 ${hover ? 'primary white--text' : ''}`"
                link
                @click="onAction(action.method, action.name, action.value)"
                v-html="action.title"
              />
            </v-hover>
          </v-list>
        </v-menu>

        <v-spacer />

        <v-text-field
          :value="table.search"
          outlined
          dense
          append-icon="mdi-magnify"
          class="mx-2"
          :label="global.table.search_label"
          single-line
          hide-details
          style="max-width: 30%;"
          @input="search"
        />
      </v-toolbar>
    </template>

    <template
      v-for="(header, headerIndex) in table.headers"
      v-slot:[`item.${header.value}`]="{ item }"
    >
      <slot
        :name="header.value"
        :prop="item"
      >
        <div
          v-if="header.value === 'action'"
          :key="headerIndex + 'headerIndex'"
        >
          <v-hover
            v-for="(rowAction, rowActionIndex) in table.actions"
            :key="rowActionIndex + 'rowActionIndex'"
            v-slot="{ hover }"
          >
            <span
              v-show="rowAction.skip_in_row === undefined || rowAction.skip_in_row !== 'yes'"
              class="text-caption primary--text"
            >
              <span
                :class="`text-caption ${hover ? 'primary white--text' : 'primary--text'}`"
                style="cursor: pointer;"
                @click="onAction(rowAction.method, rowAction.name, rowAction.value, item.id)"
              >
                {{ rowAction.title }}
              </span>
              <template v-if="(rowActionIndex + 1) < table.actions.length"> | </template>
            </span>
          </v-hover>
        </div>

        <div
          v-else-if="header.value === 'date_created'"
          :key="headerIndex + 'headerIndex2'"
        >
          {{ item.date_created | formatDate }}
        </div>

        <div
          v-else
          :key="headerIndex + 'headerIndex'"
          v-html="item[header.value]"
        />
      </slot>
    </template>
  </v-data-table>
</template>

<script>
  import { mapState } from 'vuex'
  export default {
    filters: {
      /**
       * Format a timestamp into a d/m/yyy (because spanish format).
       *
       * @param value
       * @returns {string}
       */
      formatDate (value) {
        if (!value) return ''
        var dateObj = new Date(value)
        var date = dateObj.getDate() + ' ' + dateObj.toLocaleString('en-us', { month: 'short' }) + ' ' + dateObj.getFullYear()
        return date
      },
    },

    computed: {
      ...mapState(['table']),
      ...mapState(['global']),
      selectedRows: {
        get () {
          return this.$store.state.table.selectedRows
        },
        set (val) {
          this.$store.commit('SET_TABLE', { selectedRows: val })
        },
      },
    },

    created () {
      this.$store.dispatch('getTableRows')
    },

    methods: {
      onAction (method, name, value, id = false) {
        this.$store.dispatch(method, { id: id, name: name, value: value })
      },
      async search (event) {
        await this.$store.commit('SET_TABLE', { search: event })
        this.$store.dispatch('getTableRows')
      },
      async filter (name, value = null, count) {
        await this.$store.commit('SET_TABLE', { totalItems: count })

        if (name === 'all') {
          this.$store.commit('SET_TABLE', { filters: null })
        } else {
          this.$store.commit('SET_TABLE', { filters: { [name]: value } })
        }

        this.$store.dispatch('getTableRows')
      },
      async updatePage (event) {
        await this.$store.commit('SET_TABLE', { page: event })
        this.$store.dispatch('getTableRows')
      },
      async updatePerPage (event) {
        await this.$store.commit('SET_TABLE', { itemsPerPage: event })
        this.$store.dispatch('getTableRows')
      },
    },
  }
</script>
