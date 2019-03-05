<template>
  <v-container fluid>
    <v-card flat class="ba-solid-1 grey--border border--lighten-3 ba-round-sm">
      <v-card-title>
        <v-btn 
          :to="{name: 'users.user.create'}"
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
            <td width="30" class="pa-0">
              <v-checkbox small hide-details
                class="justify-center"
                v-model="props.selected"
                color="primary"
              ></v-checkbox>
            </td>
          </template>
          <td>{{ props.item.fullName }}</td>
          <td>{{ props.item.username }}</td>
          <td>{{ props.item.email }}</td>
          <td>{{ props.item.groupName }}</td>
          <td>
            <v-checkbox small readonly hide-details
              v-model="props.item.active"
              color="primary"
            ></v-checkbox>
          </td>
          <td>{{ props.item.lastLogin }}</td>
          <td>{{ props.item.updatedAt }}</td>
          <td>
            <v-menu offset-x>
              <v-btn icon small flat
                slot="activator"
                color="primary">
                <v-icon>la-ellipsis-v</v-icon>
              </v-btn>
              <v-list dense>
                <v-list-tile @click="showDetail(props.item)">
                  <v-list-tile-avatar size="">
                    <v-icon class="primary--text">la-file-text-o</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>{{ $t('lb::details') }}</v-list-tile-title>
                </v-list-tile>
                <v-divider />
                <v-list-tile :to="{name: 'users.user.edit', params:{id:props.item.id}}">
                  <v-list-tile-avatar size="">
                    <v-icon class="primary--text">la-edit</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-title>{{ $t('lb::edit') }}</v-list-tile-title>
                </v-list-tile>
                <v-list-tile @click="removeAction(props.item)">
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

    <uf-sidepanel 
      ref="sidepanel" 
      :title="$t('lb::properties')"
      url-json="users/user/item"
      :url-params="sidePanelParams">
      <template slot="item" slot-scope="props">
        <template v-if="props.item">
          <uf-properties :title="props.item.profile.fullName" :subtitle="$t('menu::user')">
            <uf-properties-item
              :label="$t('users::user:username')"
              :title="props.item.username"></uf-properties-item>
            <uf-properties-item
              :label="$t('users::user:email')"
              :title="props.item.email"></uf-properties-item>
            <uf-properties-item
              :label="$t('users::user:group')"
              :title="props.item.group.name"></uf-properties-item>
            <uf-properties-item :label="$t('users::user:is_admin')">
              <v-checkbox small readonly hide-details slot="title"
                v-model="props.item.active"
                color="primary"
              ></v-checkbox>
            </uf-properties-item>
            <uf-properties-item :label="$t('users::user:active')">
              <v-checkbox small readonly hide-details slot="title"
                v-model="props.item.active"
                color="primary"
              ></v-checkbox>
            </uf-properties-item>
            <uf-properties-item
              :label="$t('users::user:last_login')"
              :title="props.item.lastLogin"></uf-properties-item>
            <uf-properties-item
              :label="$t('lb::created_at')"
              :title="props.item.createdAt"></uf-properties-item>
            <uf-properties-item
              :label="$t('lb::updated_at')"
              :title="props.item.updatedAt"></uf-properties-item>
          </uf-properties>
        </template>
      </template>
    </uf-sidepanel>
  </v-container>
</template>

<script>
import { fetchDtRows } from '../../../utils/helpers.js';
import UfSidepanel from '../../../components/UfSidepanel.vue';
import UfProperties from '../../../components/UfProperties.vue';
import UfPropertiesItem from '../../../components/UfPropertiesItem.vue';

export default {
  name: 'users-user-page',
  components: {
    UfSidepanel, UfProperties, UfPropertiesItem
  },
  data() {
    return {
      loading: false,
      selected: [],
      headers: [
        { 
          text: this.$t('users::user:full_name'), 
          value: 'fullName', 
        },
        { 
          text: this.$t('users::user:username'), 
          value: 'username', 
        },
        { 
          text: this.$t('users::user:email'), 
          value: 'email',          
        },
        { 
          text: this.$t('users::user:group'), 
          value: 'groupName',          
        },
        { 
          text: this.$t('users::user:active'), 
          value: 'active',          
        },
        { 
          text: this.$t('users::user:last_login'), 
          value: 'lastLogin',          
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
      sidePanelRow: {},
      sidePanelParams: {}
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
    showDetail(item) {
      this.sidePanelParams = {
        id: item.id
      };
      this.$refs.sidepanel.show();
    },
    async refreshAction() {
      this.selected = [];
      this.loading = true;
      await fetchDtRows('users/user', this.pagination, this.searchText)
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