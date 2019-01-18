<template>
  <div class="container">
    <app-page-header title="Profile"></app-page-header>
    <b-form @submit.prevent="saveChanges" type="post">
      <b-form-group
        horizontal
        label-class="required"
        label="Nama lengkap"
        label-for="user-full-name"
        :invalid-feedback="errorMsg.fullName"
        label-cols="2"
      >
        <b-form-input
          id="user-full-name"
          name="user-full-name"
          :state="errorMsg.fullName.length > 1 ? false : null"
          v-model.trim="item.fullName"
          placeholder="Nama lengkap"
        ></b-form-input>
      </b-form-group>
      <b-form-group
        horizontal
        label="Telepon"
        label-for="user-phone"
        :invalid-feedback="errorMsg.phone"
        label-cols="2"
      >
        <b-form-input
          id="user-phone"
          name="user-phone"
          :state="errorMsg.phone.length > 1 ? false : null"
          v-model.trim="item.phone"
          placeholder="Telepon"
        ></b-form-input>
      </b-form-group>
      <b-form-group
        horizontal
        label-class="required"
        label="Email"
        label-for="user-email"
        :invalid-feedback="errorMsg.email"
        label-cols="2"
      >
        <b-form-input
          id="user-email"
          name="user-email"
          :state="errorMsg.email.length > 1 ? false : null"
          v-model.trim="item.email"
          placeholder="Email"
        ></b-form-input>
      </b-form-group>
      <b-form-group
        horizontal
        label-class="required"
        label="Username"
        label-for="user-username"
        :invalid-feedback="errorMsg.username"
        label-cols="2"
      >
        <b-form-input
          id="user-username"
          name="user-username"
          :state="errorMsg.username.length > 1 ? false : null"
          v-model.trim="item.username"
          placeholder="Username"
        ></b-form-input>
      </b-form-group>

      <div class="form-group row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
          <b-form-checkbox 
            id="change-password"
            v-model="item.changePassword">Ubah password</b-form-checkbox>
        </div>
      </div>

      <template v-if="item.changePassword">
        <b-form-group
          horizontal
          label-class="required"
          label="Kata sandi lama"
          label-for="user-old-password"
          :invalid-feedback="errorMsg.oldPassword"
          label-cols="2">
          <b-form-input
            id="user-old-password"
            name="user-old-password"
            type="password"
            :state="errorMsg.oldPassword.length > 1 ? false : null"
            v-model.trim="item.oldPassword"
            placeholder="Kata sandi lama"></b-form-input>
        </b-form-group>
        <b-form-group
          horizontal
          label-class="required"
          label="Kata sandi"
          label-for="user-new-password"
          :invalid-feedback="errorMsg.newPassword"
          label-cols="2">
          <b-form-input
            id="user-new-password"
            name="user-new-password"
            type="password"
            :state="errorMsg.newPassword.length > 1 ? false : null"
            v-model.trim="item.newPassword"
            placeholder="Kata sandi baru"></b-form-input>
        </b-form-group>
        <b-form-group
          horizontal
          label-class="required"
          label="Ulangi kata sandi"
          label-for="user-confirm-new-password"
          :invalid-feedback="errorMsg.confirNewPassword"
          label-cols="2"
        >
          <b-form-input
            id="user-confirm-new-password"
            name="user-confirm-new-password"
            type="password"
            :state="errorMsg.confirNewPassword.length > 1 ? false : null"
            v-model.trim="item.confirNewPassword"
            placeholder="Ulangi kata sandi"
          ></b-form-input>
        </b-form-group>
      </template>

      <hr />

      <div class="form-group row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
          <b-btn type="submit" variant="primary" :disabled="loading" style="margin-left: 0">
            SIMPAN <i class="icon ms-Icon--save icon-right"></i>
          </b-btn>
        </div>
      </div>
    </b-form>
  </div>
</template>

<script>
const AppPageHeader = () => import('../../components/AppPageHeader.vue');
const AppLoader = () => import('../../components/AppLoader.vue');
export default {
  name: 'profile-page',
  components: {
    AppLoader, AppPageHeader
  },
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
            this.$toasted.error(data.message, {
              fullWidth: true,
              fitToScreen: true,
            });
          } else {
            this.$toasted.error(statusText, {
              fullWidth: true,
              fitToScreen: true,
            });
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
      this.$toasted.clear();
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
          this.$toasted.show(data.message, {
            type: data.success ? 'success' : 'error'
          });

          if (data.success) {
            this.loading = false;
            this.fetchItem();
          }
        })
        .catch(error => {
          const {statusText, data} = error;
          this.$toasted.error(statusText, {
            fullWidth: true,
            fitToScreen: true,
          });

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