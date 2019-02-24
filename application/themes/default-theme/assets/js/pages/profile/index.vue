<template>
  <v-container fluid fill-height class="white">
    <v-layout align-center justify-center>
      <v-flex xs12 sm8>
        <form @submit.prevent="saveChanges" type="post">
          <div class="display-1 font-weight-thin text-xs-center mb-4">
            Personal info
          </div>
          <v-card flat class="ba-solid-1 ba-round-lg grey--border border--lighten-2">
            <v-card-text class="pa-4">
              <v-layout row wrap>
                <v-flex xs12 md7>
                  <v-text-field outline
                    class="uf-text-field--outline"
                    :label="$t('profile::full_name')"
                    :placeholder="$t('profile::full_name')"
                    v-model="item.fullName"
                    :error-messages="errorMsg.fullName"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md5>
                  <v-text-field outline
                    class="uf-text-field--outline"
                    :label="$t('profile::username')"
                    :placeholder="$t('profile::username')"
                    v-model="item.username"
                    :error-messages="errorMsg.username"
                  ></v-text-field>
                </v-flex>
              </v-layout>

              <v-layout row wrap>
                <v-flex xs12 md6>
                  <v-text-field outline
                    class="uf-text-field--outline"
                    :label="$t('profile::email')"
                    placeholder="email@domain.com"
                    v-model="item.email"
                    :error-messages="errorMsg.email"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md6>
                  <v-text-field outline
                    class="uf-text-field--outline"
                    :label="$t('profile::phone')"
                    :placeholder="$t('profile::phone')"
                    v-model="item.phone"
                    :error-messages="errorMsg.phone"
                  ></v-text-field>
                </v-flex>
              </v-layout>

              <v-checkbox
                v-model="item.changePassword"
                :label="$t('profile::change_password')"
              ></v-checkbox>
              <template v-if="item.changePassword">
                <v-layout row wrap>
                  <v-flex xs12 md4>
                    <v-text-field outline
                      class="uf-text-field--outline"
                      :label="$t('profile::old_password')"
                      :placeholder="$t('profile::old_password')"
                      v-model="item.oldPassword"
                      :error-messages="errorMsg.oldPassword"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md4>
                    <v-text-field outline
                      class="uf-text-field--outline"
                      :label="$t('profile::new_password')"
                      :placeholder="$t('profile::new_password')"
                      v-model="item.newPassword"
                      :error-messages="errorMsg.newPassword"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md4>
                    <v-text-field outline
                      class="uf-text-field--outline"
                      :label="$t('profile::confirm_new_password')"
                      :placeholder="$t('profile::confirm_new_password')"
                      v-model="item.confirNewPassword"
                      :error-messages="errorMsg.confirNewPassword"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </template>
            </v-card-text>

            <v-card-actions class="pa-3">
              <v-btn color="primary" :loading="loading" type="submit">
                <v-icon left>la-save</v-icon>Submit
              </v-btn>
            </v-card-actions>
          </v-card>
        </form>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'profile-page',
  data() {
    return {
      loading: false,
      item: {
        id: '',
        fullName: '',
        phone: '',
        email: '',
        username: '',
        oldPassword: '',
        newPassword: '',
        confirNewPassword: '',
        changePassword: false,
      },
      errorMsg: {
        id: '',
        fullName: '',
        phone: '',
        email: '',
        username: '',
        oldPassword: '',
        newPassword: '',
        confirNewPassword: '',
      },
    }
  },
  created() {
    this.fetchItem();
  },
  methods: {
    clearMessage() {
      this.errorMsg = {
        id: '',
        fullName: '',
        phone: '',
        email: '',
        username: '',
        oldPassword: '',
        newPassword: '',
        confirNewPassword: '',
      };
    },
    fetchItem() {
      if (this.loading) {
        return false;
      }

      this.loading = true;
      this.$axios.get('profile/index')
        .then(response => {
          const { data } = response;
          const row = data.data;

          this.item = {
            id: row.id,
            fullName: row.fullName,
            phone: row.phone ? row.phone : '',
            email: row.email,
            username: row.username,
            oldPassword: '',
            newPassword: '',
            confirNewPassword: '',
            changePassword: false
          };

        })
        .catch(error => {
          const {statusText, data} = error;
          if (typeof data.message !== "undefined") {
            this.$ufsnackbars.error(data.message);
          } else {
            this.$ufsnackbars.error(statusText);
          }
        })
        .then(() => {
          this.loading = false;
        });
    },
    saveChanges() {
      if (this.loading) {
        return false;
      }

      this.clearMessage();
      this.$ufsnackbars.hide();
      this.loading = true;

      const item = new FormData();
      item.set('id', this.item.id);
      item.set('fullName', this.item.fullName);
      item.set('phone', this.item.phone);
      item.set('email', this.item.email);
      item.set('username', this.item.username);
      item.set('changePassword', this.item.changePassword ? '1' : '0');
      if (this.item.changePassword) {
        item.set('oldPassword', this.item.oldPassword);
        item.set('newPassword', this.item.newPassword);
        item.set('confirNewPassword', this.item.confirNewPassword);
      }

      this.$axios.post('profile/save-changes', item)
        .then(response => {
          const { data } = response;
          this.$ufsnackbars.show(data.message, {
            type: data.success ? 'success' : 'error'
          });

          if (data.success) {
            this.fetchItem();
          }
        })
        .catch(error => {
          const {statusText, data} = error;
          this.$ufsnackbars.error(statusText);

          if (typeof data !== "undefined" && typeof data.message !== "undefined") {
            if (typeof data.message === 'object') {
              this.errorMsg = Object.assign({}, this.errorMsg, data.message);
            }
          }
        })
        .then(() => {
          this.loading = false;
        });
    }
  }
}
</script>