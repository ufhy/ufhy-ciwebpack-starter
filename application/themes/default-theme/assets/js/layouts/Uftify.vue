<template>
  <v-app class="uf-app">
    <uf-drawer v-model="drawer" :mini-drawer.sync="miniDrawer"></uf-drawer>
    <uf-toolbar :mini-drawer="miniDrawer" @toggle-drawer="toggleDrawer"></uf-toolbar>
    <v-content>
      <router-view/>
    </v-content>
  </v-app>
</template>

<script>
import UfDrawer from '../components/UfDrawer.vue';
import UfToolbar from '../components/UfToolbar.vue';

export default {
	name: 'uftify-layout',
	components: {
    UfDrawer, UfToolbar,
	},
	data() {
    return {
      drawer: true,
      miniDrawer: true,
    }
	},
  methods: {
    toggleDrawer() {
      this.drawer = !this.drawer;
    }
  }
}
</script>
