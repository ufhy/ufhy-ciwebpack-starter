<template>
	<v-toolbar app flat 
		height="60" 
		color="white" 
		class="uf-toolbar">
		<v-toolbar-side-icon 
			v-if="!$vuetify.breakpoint.mdAndUp"
			@click="onClickSideIcon">
			<v-icon>la-bars</v-icon>
		</v-toolbar-side-icon>
		<v-toolbar-title v-if="showToolbarTitle">
			<span class="font-weight-black blue--text text--darken-3">{{siteTitle}}</span>
		</v-toolbar-title>

		<v-spacer v-if="showToolbarTitle"></v-spacer>

		<v-text-field hide-details single-line flat
			v-if="$vuetify.breakpoint.mdAndUp"
			prepend-inner-icon="la-search"
			placeholder="Search"
			class="uf-toolbar__search-input"
		></v-text-field>
		
		<v-spacer></v-spacer>

		<uf-search-dialog v-if="!$vuetify.breakpoint.mdAndUp"></uf-search-dialog>
		<v-toolbar-items>
			<v-btn icon>
				<v-avatar right size="36px">
					<img
						src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460"
						alt="Avatar">
				</v-avatar>
			</v-btn>
		</v-toolbar-items>
	</v-toolbar>
</template>

<script>
import UfSearchDialog from '../components/UfSearchDialog.vue';
export default {
	name: 'uf-toolbar',
	props: {
		miniDrawer: {
			type: Boolean,
			required: true
		}
	},
	components: {
		UfSearchDialog
	},
	computed: {
		showToolbarTitle() {
			if (this.$vuetify.breakpoint.mdAndDown) {
				return true
			}
			if (this.$vuetify.breakpoint.mdAndUp && this.miniDrawer) {
				return true
			}
		},
		siteTitle() {
			return ufhy.SITE_TITLE_ABBR;
		}
	},
	methods: {
		onClickSideIcon() {
			this.$emit('toggle-drawer');
		}
	}
}
</script>
