<template>
  <v-menu offset-y>
    <v-btn slot="activator" small icon class="ma-0" :color="color" :class="btnClass">
      <v-icon>la-ellipsis-v</v-icon>
    </v-btn>
    <v-list dense>
      <v-list-tile>
        <v-list-tile-avatar size="">
          <v-icon class="primary--text">la-download</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('files::download') }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-list-tile>
        <v-list-tile-avatar size="">
          <v-icon class="primary--text">la-edit</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('files::rename_file') }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-list-tile>
        <v-list-tile-avatar size="">
          <v-icon class="error--text">la-times</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title>{{ $t('files::remove') }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </v-menu>
</template>

<script>
export default {
  name: 'files-menu-item',
  props: {
    item: {
      type: Object,
      required: true
    },
    color: String,
    btnClass: String
  }
}
</script>
