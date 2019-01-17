<template>
  <div class="container">
    <b-card header="Settings">
    </b-card>
  </div>
</template>

<script>
  export default {
    name: 'settings-page',
    created() {
      this.$axios.get('settings');
    }
  }
</script>