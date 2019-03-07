<template>
  <v-dialog scrollable persistent lazy 
    content-class="v-dialog--top"
    v-model="showModal" 
    transition="slide-y-transition"
    max-width="600px">
    <v-card class="card-dialog">
      <v-card-title class="subheading font-weight-bold">{{title}}</v-card-title>
      <v-divider />

      <v-card-text>
        <v-container fluid grid-list-lg class="px-0">
          <v-layout row wrap>
            <v-flex xs12 md7 class="py-0">
              <v-text-field
                :label="$t('users::user:full_name')"
                :placeholder="$t('users::user:full_name')"
                v-model.trim="row.fullname"
                :error-messages="error.fullname"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 md5 class="py-0">
              <v-text-field
                :label="$t('users::user:phone')"
                :placeholder="$t('users::user:phone')"
                v-model.trim="row.phone"
                :error-messages="error.phone"
              ></v-text-field>
            </v-flex>

            <v-flex xs12 md6 class="py-0">
              <v-text-field
                :label="$t('users::user:email')"
                :placeholder="$t('users::user:email')"
                v-model.trim="row.email"
                :error-messages="error.email"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 md6 class="py-0">
              <v-text-field
                :label="$t('users::user:username')"
                :placeholder="$t('users::user:username')"
                v-model.trim="row.username"
                :error-messages="error.username"
              ></v-text-field>
            </v-flex>

            <v-flex xs12 class="py-0">
              <v-autocomplete dense
                :label="$t('users::user:group')"
                :placeholder="$t('users::user:group')"
                v-model.trim="row.groupId"
                :error-messages="error.groupId"
                :items="groupOptions"
              ></v-autocomplete>
            </v-flex>

            <v-flex xs12 md6 class="py-0">
              <v-text-field
                :label="$t('users::user:password')"
                :placeholder="$t('users::user:password')"
                v-model.trim="row.password"
                :error-messages="error.password"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 md6 class="py-0">
              <v-text-field
                :label="$t('users::user:rePassword')"
                :placeholder="$t('users::user:rePassword')"
                v-model.trim="row.passwordConfirm"
                :error-messages="error.passwordConfirm"
              ></v-text-field>
            </v-flex>

            <v-flex xs12 class="py-0">
              <v-checkbox
                :label="$t('users::user:active')"
                v-model="row.active"
              ></v-checkbox>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
      <v-divider />
      <v-card-actions class="pa-3">
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
        fullname: '',
        phone: '',
        email: '',
        username: '',
        password: '',
        passwordConfirm: '',
        groupId: '',
        active: true,
      },
      error: {
        id: '',
        fullname: '',
        phone: '',
        email: '',
        username: '',
        password: '',
        passwordConfirm: '',
        groupId: '',
        active: '',
      },
      groupOptions: [],
    }
  },
  computed: {
    title() {
      let title = this.$t('users::user:heading_create');
      if (this.mode === 'edit') {
        title = this.$t('users::user:heading_edit');
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
    await this.fetchOptions();
    this.mode === 'edit' && await this.fetchRow();
    this.loading = false;
  },
  mounted() {
    this.showModal = true;
  },
  methods: {
    clearError() {
      this.error.id = '';
      this.error.fullname = '';
      this.error.phone = '';
      this.error.email = '';
      this.error.username = '';
      this.error.password = '';
      this.error.passwordConfirm = '';
      this.error.groupId = '';
      this.error.active = '';
    },
    emptyRow() {
      this.row.id = '';
      this.row.fullname = '';
      this.row.phone = '';
      this.row.email = '';
      this.row.username = '';
      this.row.password = '';
      this.row.passwordConfirm = '';
      this.row.groupId = null;
      this.row.active = true;
    },
    closeAction() {
      this.showModal = false;
      this.$router.push({
        name: 'users.user.index',
      });
    },
    fetchOptions() {
      return this.$axios.get('users/user/formoptions')
        .then(response => {
          const { data } = response;
          this.groupOptions = data.groups;
        })
        .catch(error => {
          const {statusText, data} = error;
          this.$ufsnackbars.error(statusText);
        });
    },
    fetchRow() {
      return this.$axios.get('users/user/item', { params: {id: this.row.id} })
        .then(response => {
          const { data } = response;
          const row = data.row;

          this.row.fullname = row.profile.fullName;
          this.row.phone = row.profile.phone;
          this.row.email = row.email;
          this.row.username = row.username;
          this.row.password = '';
          this.row.passwordConfirm = '';
          this.row.groupId = row.groupId;
          this.row.active = row.active;
        })
        .catch(error => {
          const {statusText, data} = error;
          if (typeof data.message !== "undefined") {
            this.$ufsnackbars.error(data.message);
          } else {
            this.$ufsnackbars.error(statusText);
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
      item.set('fullname', this.row.fullname);
      item.set('phone', this.row.phone);
      item.set('email', this.row.email);
      item.set('username', this.row.username);
      item.set('password', this.row.password);
      item.set('passwordConfirm', this.row.passwordConfirm);
      item.set('groupId', this.row.groupId);
      item.set('active', this.row.active ? 1 : 0);

      let url = 'users/user/create';
      if (this.mode === 'edit') {
        item.set('id', this.row.id);
        url = 'users/user/edit';
      }

      this.$axios.post(url, item)
        .then((response) => {
          const { data } = response;
          this.$ufsnackbars.show(data.message, {type: data.success ? 'success' : 'error'});

          if (data.success) {
            if (mode === 'saveClose') {
              this.$router.push({
                name: 'users.user.index',
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