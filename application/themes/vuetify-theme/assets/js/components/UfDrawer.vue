<template>
	<v-navigation-drawer app fixed dark
		:class="{'uf-drawer': true, 'uf-drawer_mini__menu': miniVariant}"
		width="248"
		v-model="drawer"
		:mini-variant="miniVariant"
		mini-variant-width="50"
		mobile-break-point="1024">
		<v-toolbar flat dark height="45">
			<v-toolbar-title v-if="!miniVariant">
				<a :href="siteUrl">
					<span class="font-weight-black white--text">{{siteTitle}}</span>
				</a>
			</v-toolbar-title>
			<v-spacer v-if="!miniVariant"></v-spacer>
			<v-toolbar-side-icon @click="onClickSideIcon">
				<v-icon>la-chevron-circle-left</v-icon>
			</v-toolbar-side-icon>
		</v-toolbar>
		
		<template v-if="miniVariant">
			<div class="uf-drawer__mini-content" style="padding-top: 40px">
				<template v-for="(item, index) in menuItems">
					<template v-if="item.childrens">
						<v-menu offset-x full-width
							:key="index" 
							content-class="uf-drawer__mini-menu">
							<v-btn flat
								slot="activator"
								:input-value="isChildActive(item)"
								active-class="blue lighten-5 blue--text text--darken-4">
								<v-icon>{{item.icon}}</v-icon>
							</v-btn>
							<v-list dense>
								<v-subheader class="blue-grey--text text--darken-4 font-weight-bold">{{ $t(item.text) }}</v-subheader>
								<v-list-tile
									v-for="(children, indexChild) in item.childrens"
									:key="index + indexChild"
									:to="children.url">
									<v-list-tile-content class="pl-3">
										<v-list-tile-title>{{ $t(children.text) }}</v-list-tile-title>
									</v-list-tile-content>
								</v-list-tile>
							</v-list>
						</v-menu>
					</template>
					
					<template v-else>
						<v-tooltip right :key="index">
							<v-btn flat 
								slot="activator" 
								:to="item.url"
								active-class="blue lighten-5 blue--text text--darken-4">
								<v-icon>{{ item.icon }}</v-icon>
							</v-btn>
							<span>{{ $t(item.text) }}</span>
						</v-tooltip>
					</template>
				</template>
			</div>
		</template>

		<template v-else>
			<v-list dense expand class="scroll-y pt-2">
				<template v-for="(item, index) in menuItems">
					<template v-if="item.childrens">
						<v-list-group 
							:key="index"
							append-icon="la-chevron-circle-down"
							:value="isChildActive(item)"
							:class="{'uf-drawer__list-tile__active': isChildActive(item)}">
							<v-list-tile slot="activator">
								<v-list-tile-avatar>
									<v-icon>{{item.icon}}</v-icon>
								</v-list-tile-avatar>
								<v-list-tile-content>
									<v-list-tile-title>{{ $t(item.text) }}</v-list-tile-title>
								</v-list-tile-content>
							</v-list-tile>

							<template v-for="(children, indexChild) in item.childrens">
								<v-list-tile 
									active-class="uf-drawer__list-tile__active"
									:key="index + indexChild" 
									:to="children.url">
								<v-list-tile-avatar>
									<v-icon>{{children.icon}}</v-icon>
								</v-list-tile-avatar>
									<v-list-tile-content>
										<v-list-tile-title>{{ $t(children.text) }}</v-list-tile-title>
									</v-list-tile-content>
								</v-list-tile>
							</template>
						</v-list-group>
					</template>
					
					<template v-else>
						<v-list-tile 
							:key="index" 
							:to="item.url"
							active-class="uf-drawer__list-tile__active">
							<v-list-tile-avatar>
								<v-icon>{{item.icon}}</v-icon>
							</v-list-tile-avatar>
							<v-list-tile-content>
								<v-list-tile-title>{{ $t(item.text) }}</v-list-tile-title>
							</v-list-tile-content>
						</v-list-tile>
					</template>
					
				</template>
			</v-list>
		</template>

	</v-navigation-drawer>
</template>

<script>
import { mapState } from 'vuex';
export default {
	name: 'uf-drawer',
	props: {
		value: Boolean,
		miniDrawer: {
			type: Boolean,
			default: true,
		}
	},
	computed: {
		drawer: {
			set(value) {
				this.$emit('input', value);
			},
			get() {
				return this.value
			}
		},
		miniVariant() {
			if (this.$vuetify.breakpoint.mdAndUp && this.miniDrawer) {
				return true;
			} else if (this.$vuetify.breakpoint.mdAndUp && !this.miniDrawer) {
				return false;
			}

			return this.$vuetify.breakpoint.mdAndUp;
		},
		siteTitle() {
			return ufhy.SITE_TITLE_ABBR;
		},
		siteUrl() {
			return ufhy.SITE_URL;
		},
		menuItems() {
			const menuItems = ufhy.MENU_ITEMS;
			let results = [];

			for (const menuItem in menuItems) {
				if (menuItems.hasOwnProperty(menuItem)) {
					const rowMenuItem = menuItems[menuItem];
					
					for (const menu in rowMenuItem) {
						if (rowMenuItem.hasOwnProperty(menu)) {
							const rowMenu = rowMenuItem[menu];
							if (rowMenu.items) {
								let childrens = [];
								for (const subItem in rowMenu.items) {
									if (rowMenu.items.hasOwnProperty(subItem)) {
										const rowSubItem = rowMenu.items[subItem];
										childrens.push({ 
											text: subItem, 
											url: '/' + rowSubItem.url
										});
									}
								}
								results.push({ 
									icon: rowMenu.icon, 
									text: menu, 
									childrens
								});
							} else {
								results.push({ 
									icon: rowMenu.icon, 
									text: menu, 
									url: '/' + rowMenu.url 
								});
							}
						}
					}
				}
			}

			return results;
		}
	},
	methods: {
		isChildActive(item) {
			const that = this;
			if (item.childrens) {
				const child = item.childrens.filter((child) => {
					return child.url === that.$route.path;
				});
				if (child.length > 0) {
					return true;
				}
			}
			return false;
		},
		onClickSideIcon() {
			if (this.$vuetify.breakpoint.mdAndUp) {
				this.$emit('update:miniDrawer', !this.miniDrawer);
			} else {
				this.drawer = false;
			}
		}
	}
}
</script>
