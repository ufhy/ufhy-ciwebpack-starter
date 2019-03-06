<template>
  <v-dialog scrollable persistent lazy fullscreen
    content-class="v-dialog--top"
    v-model="showModal" 
    transition="slide-y-transition"
    max-width="600px">
    <v-card class="card-dialog">
      <v-toolbar dark color="blue-grey darken-3">
        <v-btn icon @click="closeAction">
          <v-icon>la-times</v-icon>
        </v-btn>
        <v-toolbar-title>
          {{title}}
        </v-toolbar-title>
        <v-spacer></v-spacer>
      </v-toolbar>
      <v-card-text>
        <form v-on:submit.prevent="saveChanges($event)" id="form-permissions" type="post" ref="formPermissions">
          <v-container>
            <template v-if="isAdmin">
              <v-alert :value="true" type="warning" icon="la-exclamation-triangle">
                {{isAdminMsg}}
              </v-alert>
            </template>

            <template v-else>
              <input type="hidden" name="id" v-model="row.id" />
              <template v-for="(moduleRole, moduleRoleIndex) in row.modules">
                <permission-module
                  :key="moduleRoleIndex"
                  :module-role="moduleRole"
                  :data-roles="row.editPermissions"
                ></permission-module>
              </template>

              <v-btn color="primary" type="submit">
                <v-icon left>la-save</v-icon> Save
              </v-btn>
            </template>
          </v-container>
        </form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import PermissionModule from './includes/Module.vue';
import { formmater } from '../../../utils/helpers.js';

export default {
  name: 'users-group-form-page',
  components: {
    PermissionModule
  },
  data() {
    return {
      loading: false,
      showModal: false,
      isAdmin: false,
      isAdminMsg: '',
      row: {
        id: '',
        name: '',
        modules: [],
        editPermissions: {}
      },
    }
  },
  computed: {
    title() {
      return formmater(this.$t('users::group:heading_permissions'), this.row.name);
    }
  },
  async created() {
    this.emptyRow();
    this.row.id = this.$route.params.id ? this.$route.params.id : '';

    this.loading = true;
    await this.fetchRow() 
    this.loading = false;
  },
  mounted() {
    this.showModal = true;
  },
  methods: {
    emptyRow() {
      this.row.id = '';
      this.row.name = '';
      this.row.modules = [];
      this.row.editPermissions = {};
    },
    closeAction() {
      this.showModal = false;
      this.$router.push({
        name: 'users.group.index',
      });
    },
    saveAction() {
      if (this.loading) {
        return false;
      }

      this.$refs.formPermissions.submit();
    },
    fetchRow() {
      this.$axios.get('users/group/permissions', { params: {id: this.row.id} })
        .then(response => {
          const { data } = response;

          if (!data.success) {
            this.row.id = data.groupId;
            this.row.name = data.groupName;
            this.isAdmin = data.isAdmin ? data.isAdmin : false;
            if (data.isAdmin) {
              this.isAdminMsg = data.message;
            }
          }
          else {
            this.row.id = data.groupId;
            this.row.name = data.groupName;
            this.row.modules = data.modules;
            this.row.editPermissions = data.editPermissions;
          }
        })
        .catch(error => {
          console.log(error);
          const {statusText, data} = error;
          if (typeof data.message !== "undefined") {
            this.$ufsnackbars.error(data.message);
          } else {
            this.$ufsnackbars.error(statusText);
          }
        });
    },
    saveChanges(event) {
      event.preventDefault();

      if (this.loading) {
        return false;
      }

      const formData = new FormData(event.target);
      this.loading = true;
      this.$axios({
        url: 'users/group/permissions',
        method: 'post',
        params: { id: this.row.id },
        data: formData
      }).then(response => {
        const { data } = response;
        this.$ufsnackbars.success(data.message);
        if (data.success) {
          this.$router.push({
            name: 'users.group.index',
            params: {refresh: true}
          });
        }
      }).catch((error) => {
        const {statusText} = error;
        this.$ufsnackbars.error(statusText);
      }).then(() => {
        this.loading = false;
      });

    },
  }
}
</script>