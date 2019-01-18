<template>
  <div class="container">
    <app-page-header title="Users" breadcrumbs></app-page-header>
    <vuetable ref="vuetable"
      :fields="tableOptions.fields"
      :api-mode="false"
      :data="localData"
    ></vuetable>
  </div>
</template>

<script>
const Vuetable = () => import('vuetable-2');
const AppPageHeader = () => import('../../../components/AppPageHeader.vue');
export default {
  name: 'users-user-page',
  components: {
    Vuetable, AppPageHeader
  },
  data() {
    return {
      tableOptions: {
        fields: ['Name', 'Description', 'Default', 'Admin', 'Updated At']
      },
      localData: []
    }
  }
}
</script>