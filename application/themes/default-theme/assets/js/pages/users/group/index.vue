<template>
  <div class="container">
    <vuetable ref="vuetable"
      :fields="['name', 'nickname', 'email', 'gender']"
      :api-mode="false"
      :data="localData"
    ></vuetable>
  </div>
</template>

<script>
const Vuetable = () => import('vuetable-2');
export default {
  name: 'users-group-page',
  components: {
    Vuetable
  },
  data() {
    return {
      localData: []
    }
  }
}
</script>