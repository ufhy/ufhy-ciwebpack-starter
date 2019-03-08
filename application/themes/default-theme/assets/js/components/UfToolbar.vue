<template>
	<v-toolbar app dark extended
		height="45" 
		color="primary" 
		class="uf-toolbar elevation-0"
		extension-height="40">
		<v-toolbar-side-icon 
			v-if="!$vuetify.breakpoint.mdAndUp"
			@click="onClickSideIcon">
			<v-icon>la-bars</v-icon>
		</v-toolbar-side-icon>
		<v-toolbar-title v-if="showToolbarTitle">
			<a :href="siteUrl">
				<span class="font-weight-black white--text">{{siteTitle}}</span>
			</a>
		</v-toolbar-title>

		<v-spacer v-if="showToolbarTitle"></v-spacer>
		
		<v-spacer></v-spacer>

		<uf-search-dialog v-if="!$vuetify.breakpoint.mdAndUp"></uf-search-dialog>
		<v-toolbar-items>
			<v-menu offset-x
				v-model="userMenu"
				:close-on-content-click="false"
				:nudge-width="200">
				<v-btn icon 
					slot="activator">
					<v-avatar right color="blue-grey darken-4" size="30px">
						<template v-if="hasPhotoProfile">
							<img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar">
						</template>
						<template v-else>
							<v-icon>la-user</v-icon>
						</template>
					</v-avatar>
				</v-btn>

				<v-card>
					<v-img :aspect-ratio="16/9" src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
						<v-layout pa-2 column fill-height class="lightbox white--text">
							<v-spacer></v-spacer>
							<v-flex text-xs-center>
								<v-avatar color="teal">
									<template v-if="hasPhotoProfile">
										<img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar">
									</template>
									<template v-else>
										<v-icon class="white--text">la-user</v-icon>
									</template>
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

		<template slot="extension">
			<div class="uf-toolbar__pagetitle">{{pageTitle}}</div>
			<v-btn small 
				v-if="shortcutCreate"
				color="blue-grey darken-4" 
				@click="onClickCreateBtn">
				{{$t('lb::create')}}
			</v-btn>
			<v-btn icon light 
				v-if="shortcutSearch"
				class="ma-0"
				@click="onClickSearchBtn">
				<v-icon>la-search</v-icon>
			</v-btn>
			<v-btn icon light 
				v-if="shortcutRefresh"
				class="ma-0" 
				@click="onClickRefreshBtn">
				<v-icon>la-refresh</v-icon>
			</v-btn>

			<v-spacer></v-spacer>
			<v-breadcrumbs light 
				v-if="hasBreadcrumb"
				class="uf-breadcrumb uf-toolbar__breadcrumb" 
				:items="breadcrumbs">
				<v-icon slot="divider">la-angle-right</v-icon>
			</v-breadcrumbs>
		</template>
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
		},
		pageTitle() {
			return this.$t(this.$route.meta.title);
		},
		breadcrumbs() {
			const that = this;
      let breadcrumbs = [{
				text: ufhy.SITE_TITLE_ABBR,
				disabled: false,
				to: '/'
      }];

      this.$route.matched.forEach(element => {
        breadcrumbs.push({
					text: that.$t(element.meta.title),
					disabled: false,
					to: element.path
				})
			});
			// breadcrumbs[breadcrumbs.length - 1].disabled = true;
      return breadcrumbs;
		},
		hasBreadcrumb() {
			if (this.$vuetify.breakpoint.smAndDown) {
				return false;
			}

      return this.$route.meta.breadcrumb;
		},
		shortcutCreate() {
			if (typeof this.$route.meta.shortcut !== 'undefined') {
				if (this.$route.meta.shortcut.indexOf('create') >= 0 && this.$can('create', this.$route.meta.module)) {
					return true
				}
			}
			return false
		},
		shortcutRefresh() {
			if (typeof this.$route.meta.shortcut !== 'undefined') {
				if (this.$route.meta.shortcut.indexOf('refresh') >= 0) {
					return true
				}
			}
			return false
		},
		shortcutSearch() {
			if (typeof this.$route.meta.shortcut !== 'undefined') {
				if (this.$route.meta.shortcut.indexOf('search') >= 0) {
					return true
				}
			}
			return false
		},
		hasPhotoProfile() {
			return ufhy.USER.photo ? true : false
		}
	},
	methods: {
		onClickSideIcon() {
			this.$emit('toggle-drawer');
		},
		onClickCreateBtn() {
			this.$root.$emit('uf-toolbar:create-action');
		},
		onClickRefreshBtn() {
			this.$root.$emit('uf-toolbar:refresh-action');
		},
		onClickSearchBtn() {
			
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
