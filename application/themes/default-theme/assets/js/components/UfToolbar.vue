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
			<a :href="siteUrl">
				<span class="font-weight-black blue--text text--darken-3">{{siteTitle}}</span>
			</a>
		</v-toolbar-title>

		<v-spacer v-if="showToolbarTitle"></v-spacer>

		<v-text-field hide-details single-line flat
			v-if="$vuetify.breakpoint.mdAndUp"
			prepend-inner-icon="la-search"
			:placeholder="$t('lb::search')"
			class="uf-toolbar__search-input"
		></v-text-field>
		
		<v-spacer></v-spacer>

		<uf-search-dialog v-if="!$vuetify.breakpoint.mdAndUp"></uf-search-dialog>
		<v-toolbar-items>
			<v-menu offset-x
				v-model="userMenu"
				:close-on-content-click="false"
				:nudge-width="200">
				<v-btn icon 
					slot="activator">
					<v-avatar right size="36px">
						<img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar">
					</v-avatar>
				</v-btn>

				<v-card>
					<v-img :aspect-ratio="16/9" src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
						<v-layout pa-2 column fill-height class="lightbox white--text">
							<v-spacer></v-spacer>
							<v-flex text-xs-center>
								<v-avatar>
									<img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar">
								</v-avatar>
							</v-flex>
							<v-spacer></v-spacer>
							<v-flex shrink>
								<div class="subheading">{{userLogin.fullName}}</div>
								<div class="body-1">{{userLogin.email}}</div>
							</v-flex>
						</v-layout>
					</v-img>
					<v-card-actions>
						<v-btn color="primary" small outline to="/profile" @click="userMenu = false">{{$t('lb::profile')}}</v-btn>
						<v-spacer></v-spacer>
						<v-btn color="error" small outline :href="siteUrl + 'auth/logout'">{{$t('lb::logout')}}</v-btn>
					</v-card-actions>
				</v-card>
			</v-menu>
		</v-toolbar-items>
	</v-toolbar>
</template>

<script>
import UfSearchDialog from '../components/UfSearchDialog.vue';
export default {
	name: 'uf-toolbar',
	components: {
		UfSearchDialog
	},
	props: {
		miniDrawer: {
			type: Boolean,
			required: true
		}
	},
	data() {
		return {
			userMenu: false,
		}
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
		},
		siteUrl() {
			return ufhy.SITE_URL;
		},
		userLogin() {
			return ufhy.USER;
		}
	},
	methods: {
		onClickSideIcon() {
			this.$emit('toggle-drawer');
		}
	}
}
</script>

<style scoped>
.lightbox {
	box-shadow: 0 0 20px inset rgba(0, 0, 0, 0.2);
	background-image: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, transparent 72px);
}
</style>
