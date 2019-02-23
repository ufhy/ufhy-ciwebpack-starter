<template>
  <v-app class="uf-app">
    <uf-drawer v-model="drawer" :mini-drawer.sync="miniDrawer"></uf-drawer>
    <uf-toolbar :mini-drawer="miniDrawer" @toggle-drawer="toggleDrawer"></uf-toolbar>
    <v-content>
      <uf-breadcrumb v-if="hasBreadcrumb" class="px-4 pb-0"></uf-breadcrumb>
      <router-view/>
    </v-content>
  </v-app>
</template>

<script>
import UfDrawer from '../components/UfDrawer.vue';
import UfToolbar from '../components/UfToolbar.vue';
import UfBreadcrumb from '../components/UfBreadcrumb.vue';

export default {
	name: 'uftify-layout',
	components: {
    UfDrawer, UfToolbar, UfBreadcrumb,
	},
	data() {
    return {
      drawer: true,
      miniDrawer: true,
    }
	},
	computed: {
    hasBreadcrumb() {
      return this.$route.meta.breadcrumb;
    },
  },
  methods: {
    toggleDrawer() {
      this.drawer = !this.drawer;
    }
  }
}
</script>
