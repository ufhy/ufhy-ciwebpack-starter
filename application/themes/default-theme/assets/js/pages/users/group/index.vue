<template>
  <v-container fluid>
    <v-card flat class="ba-solid-1 grey--border border--lighten-2 ba-round-sm">
      <v-card-title>
        <v-btn 
          :to="{name: 'users.group.create'}"
          color="primary" 
          class="ma-0">
          {{$t('lb::create')}}
        </v-btn>
        <v-btn icon flat color="primary" @click="refreshAction">
          <v-icon>la-refresh</v-icon>
        </v-btn>
        <v-btn icon flat color="error" class="ml-0">
          <v-icon>la-trash</v-icon>
        </v-btn>
      </v-card-title>
      <v-data-table :select-all="$vuetify.breakpoint.mdAndUp"
        class="uf-datatable"
        item-key="id"
        v-model="selected"
        :headers="headers"
        :items="items"
        :pagination.sync="pagination"
        :total-items="totalItems"
        :loading="loading">
        <template slot="items" slot-scope="props">
          <template v-if="$vuetify.breakpoint.mdAndUp">
            <td width="50" class="pa-0">
              <v-checkbox small hide-details
                class="justify-center"
                v-model="props.selected"
                color="primary"
              ></v-checkbox>
            </td>
          </template>
          <td>
            <v-menu offset-x>
              <v-btn icon small flat
                slot="activator"
                color="primary">
                <v-icon>la-ellipsis-v</v-icon>
              </v-btn>
              <v-list dense>
                <v-list-tile :to="{name: 'users.group.edit', params:{id:props.item.id}}">
                  <v-list-tile-avatar size="">
                    <v-icon class="primary--text">la-edit</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>{{ $t('lb::edit') }}</v-list-tile-title>
                </v-list-tile>
                <v-list-tile @click="removeAction(item)">
                  <v-list-tile-avatar size="">
                    <v-icon class="error--text">la-trash</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>{{ $t('lb::remove') }}</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-menu>
          </td>
          <td>{{ props.item.name }}</td>
          <td>{{ props.item.descr }}</td>
          <td>
            <v-checkbox small readonly hide-details
              class="justify-center"
              v-model="props.item.isDefault"
              color="primary"
            ></v-checkbox>
          </td>
          <td>
            <v-checkbox readonly hide-details
              class="justify-center"
              v-model="props.item.isAdmin"
              color="primary"
            ></v-checkbox>
          </td>
          <td class="text-xs-right">{{ props.item.updatedAt }}</td>
        </template>
      </v-data-table>
    </v-card>

    <router-view />
  </v-container>
</template>

<script>
import { fetchDtRows } from '../../../utils/helpers.js';

export default {
  name: 'users-group-page',
  data() {
    return {
      loading: false,
      selected: [],
      headers: [
        { 
          text: "", 
          value: 'id', 
          sortable: false,
          width: '50px'
        }, 
        { 
          text: this.$t('users::group:name'), 
          value: 'name', 
        },
        { 
          text: this.$t('users::group:descr'), 
          value: 'descr', 
        },
        { 
          text: this.$t('users::group:is_default'), 
          value: 'isDefault', 
          width: '100px'
        },
        { 
          text: this.$t('users::group:is_admin'), 
          value: 'isAdmin', 
          width: '100px'
        },
        { 
          text: this.$t('lb::updated_at'), 
          class: 'text-xs-right',
          value: 'updatedAt', 
          width: '200px'
        },
      ],
      pagination: {
        rowsPerPage: 25,
      },
      items: [],
      totalItems: 0,
      loading: false,
      searchText: '',
    }
  },
  watch: {
    $route: function (route) {
      if (route.params.refresh) {
        this.refreshAction();
      }
    },
    pagination: {
      handler () {
        this.refreshAction();
      },
      deep: true
    }
  },
  methods: {
    async refreshAction() {
      this.selected = [];
      this.loading = true;
      await fetchDtRows('users/group', this.pagination, this.searchText)
        .then(data => {
          this.items = data.items
          this.totalItems = data.total
        })
        .then(() => {
          this.loading = false;
        })
    },
    removeAction(item) {
      if (this.loading) {
        return false;
      }
    },
    removeSelected() {
      if (this.loading) {
        return false;
      }
    }
  }
}
</script>