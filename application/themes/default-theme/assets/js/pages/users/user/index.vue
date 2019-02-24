<template>
  <v-container fluid>
    
  </v-container>
</template>

<script>

export default {
  name: 'users-user-page',
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