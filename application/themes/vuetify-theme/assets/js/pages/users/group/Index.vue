<template>
  <v-container fluid class="pa-0">
    <v-card flat class="ba-round-0">
      <v-data-table
        class="uf-datatable uf-datatable__striped"
        item-key="id"
        v-model="selected"
        :headers="headers"
        :items="items"
        :pagination.sync="pagination"
        :total-items="totalItems"
        :loading="loading">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.name }}</td>
          <td>{{ props.item.descr }}</td>
          <td>
            <v-checkbox small readonly hide-details
              :ripple="false"
              class="justify-center"
              v-model="props.item.isDefault"
              color="primary"
            ></v-checkbox>
          </td>
          <td>
            <v-checkbox readonly hide-details
              :ripple="false"
              class="justify-center"
              v-model="props.item.isAdmin"
              color="primary"
            ></v-checkbox>
          </td>
          <td>{{ props.item.updatedAt }}</td>
          <td>
            <v-menu offset-y>
              <v-btn icon small flat
                slot="activator"
                color="primary">
                <v-icon>la-ellipsis-v</v-icon>
              </v-btn>
              <v-list dense>
                <v-list-tile v-if="$can('change_permission', $route.meta.module)" :to="{name: 'users.group.permissions', params:{id:props.item.id}}">
                  <v-list-tile-avatar size="">
                    <v-icon class="primary--text">la-unlock</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>{{ $t('lb::permissions') }}</v-list-tile-title>
                </v-list-tile>
                <v-list-tile v-if="$can('edit', $route.meta.module)" :to="{name: 'users.group.edit', params:{id:props.item.id}}">
                  <v-list-tile-avatar size="">
                    <v-icon class="primary--text">la-edit</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>{{ $t('lb::edit') }}</v-list-tile-title>
                </v-list-tile>
                <v-list-tile v-if="$can('remove', $route.meta.module)" @click="removeAction(props.item)">
                  <v-list-tile-avatar size="">
                    <v-icon class="error--text">la-trash</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>{{ $t('lb::remove') }}</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-menu>
          </td>
        </template>
      </v-data-table>
    </v-card>

    <router-view />
  </v-container>
</template>

<script>
import { fetchDtRows, formmater } from '../../../utils/helpers.js';

export default {
  name: 'users-group-page',
  data() {
    return {
      loading: false,
      selected: [],
      headers: [
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
          value: 'updatedAt', 
          width: '200px'
        },
        { 
          text: "", 
          value: 'id', 
          sortable: false,
          width: '50px'
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
  created() {
    this.$root.$on('uf-toolbar:create-action', this.createAction);
    this.$root.$on('uf-toolbar:refresh-action', this.refreshAction);
    this.$root.$on('uf-toolbar:search-action', this.searchAction);
    this.$root.$on('uf-toolbar:search-cancel-action', this.searchClearAction);
  },
  destroyed() {
    this.$root.$off('uf-toolbar:create-action', this.createAction);
    this.$root.$off('uf-toolbar:refresh-action', this.refreshAction);
    this.$root.$off('uf-toolbar:search-action', this.searchAction);
    this.$root.$off('uf-toolbar:search-cancel-action', this.searchClearAction);
  },
  methods: {
    createAction() {
      this.$router.push({
        name: 'users.group.create'
      });
    },
    searchAction(payload) {
      this.searchText = payload;
      this.refreshAction();
    },
    searchClearAction() {
      this.searchText = '';
      this.refreshAction();
    },
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
    async removeAction(item) {
      if (this.loading) {
        return false;
      }

      const that = this;
      const message = formmater(that.$t('confirm::remove_text'), item.name);
      const confirm = await that.$root.confirmDanger(message);
      if (confirm) {
        that.$axios.get('users/group/remove/', {
          params: {id: item.id}
        })
          .then(response => {
            const {data} = response;
            if (data.success) {
              that.$ufsnackbars.success(data.message);
              that.refreshAction();
            }
          })
          .catch(function (error) {
            const { statusText, status } = error;
            that.$ufsnackbars.error('Code: ' + status + ' ' + statusText);
          })
          .then(() => {
            that.loading = false;
          });
      }
    }
  }
}
</script>
