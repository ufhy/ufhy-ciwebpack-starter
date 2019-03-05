<template>
  <v-dialog scrollable persistent lazy 
    content-class="v-dialog--top"
    v-model="showModal" 
    transition="slide-y-transition"
    max-width="600px">
    <v-card class="card-dialog">
      <v-card-title class="title">{{title}}</v-card-title>
      <v-card-text>
        <v-text-field
          :label="$t('users::group:name')"
          :placeholder="$t('users::group:name')"
          v-model.trim="row.name"
          :error-messages="error.name"
        ></v-text-field>
        <v-text-field
          :label="$t('users::group:descr')"
          :placeholder="$t('users::group:descr')"
          v-model.trim="row.descr"
          :error-messages="error.descr"
        ></v-text-field>

        <v-checkbox
          v-model="row.isDefault"
          :label="$t('users::group:is_default')"
        ></v-checkbox>
        <v-checkbox
          v-model="row.isAdmin"
          :label="$t('users::group:is_admin')"
        ></v-checkbox>
      </v-card-text>
      <v-card-actions>
        <form-dialog-action 
          :loading="loading"
          :btn-save="mode === 'new'"
          @save-close-action="saveChanges('saveClose')"
          @save-action="saveChanges('save')"
          @close-action="closeAction"
        ></form-dialog-action>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import FormDialogAction from '../../../components/FormDialogAction.vue';

export default {
  name: 'users-group-form-page',
  components: {
    FormDialogAction
  },
  data() {
    return {
      loading: false,
      showModal: false,
      mode: 'new',
      row: {
        id: '',
        name: '',
        descr: '',
        isDefault: false,
        isAdmin: false
      },
      error: {
        id: '',
        name: '',
        descr: '',
      }
    }
  },
  computed: {
    title() {
      let title = this.$t('users::group:heading_create');
      if (this.mode === 'edit') {
        title = this.$t('users::group:heading_edit');
      }
      return title;
    }
  },
  async created() {
    this.emptyRow();
    this.clearError();
    this.mode = this.$route.params.id ? 'edit' : 'new';
    this.row.id = this.$route.params.id ? this.$route.params.id : '';

    this.loading = true;
    this.mode === 'edit' && await this.fetchRow() 
    this.loading = false;
  },
  mounted() {
    this.showModal = true;
  },
  methods: {
    clearError() {
      this.error.id = '';
      this.error.name = '';
      this.error.descr = '';
    },
    emptyRow() {
      this.row.id = '';
      this.row.name = '';
      this.row.descr = '';
      this.row.isDefault = false;
      this.row.isAdmin = false;
    },
    closeAction() {
      this.showModal = false;
      this.$router.push({
        name: 'users.group.index',
      });
    },
    fetchRow() {
      this.$axios.get('users/group/item', { params: {id: this.row.id} })
        .then(response => {
          const { data } = response;
          const row = data.data;

          this.row.name = row.name;
          this.row.descr = row.descr;
          this.row.isDefault = row.isDefault;
          this.row.isAdmin = row.isAdmin;
        })
        .catch(error => {
          const {statusText, data} = error;
          if (typeof data.message !== "undefined") {
            this.$snackbars.error(data.message);
          } else {
            this.$snackbars.error(statusText);
          }
        });
    },
    saveChanges(mode) {
      if (this.loading) {
        return false;
      }

      this.clearError();
      this.loading = true;

      const item = new FormData();
      item.set('name', this.row.name);
      item.set('descr', this.row.descr);
      item.set('isDefault', this.row.isDefault ? 1 : 0);
      item.set('isAdmin', this.row.isAdmin ? 1 : 0);

      let url = 'users/group/create';
      if (this.mode === 'edit') {
        item.set('id', this.item.id);
        url = 'users/group/edit';
      }

      this.$axios.post(url, item)
        .then((response) => {
          const { data } = response;
          this.$ufsnackbars.show(data.message, {type: data.success ? 'success' : 'error'});

          if (data.success) {
            if (mode === 'saveClose') {
              this.$router.push({
                name: 'users.group.index',
                params: {refresh: true}
              });
            } else {
              this.emptyRow();
            }
          }
        })
        .catch((error) => {
          const {statusText, data} = error;
          this.$ufsnackbars.error(statusText);

          if (typeof data !== "undefined" && typeof data.message !== "undefined") {
            if (typeof data.message === 'object') {
              this.error = Object.assign({}, this.error, data.message);
            }
          }
        })
        .then(() => {
          this.loading = false;
        }) 
    }
  }
}
</script>