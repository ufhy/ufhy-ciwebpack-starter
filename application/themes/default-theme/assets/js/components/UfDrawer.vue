<template>
	<v-navigation-drawer app fixed dark
		:class="{'uf-drawer': true, 'uf-drawer_mini__menu': miniVariant}"
		width="248"
		v-model="drawer"
		:mini-variant="miniVariant"
		mini-variant-width="60"
		mobile-break-point="1024">
		<v-toolbar flat dark height="60">
			<v-toolbar-title v-if="!miniVariant">
				<span class="font-weight-black white--text">{{siteTitle}}</span>
			</v-toolbar-title>
			<v-spacer v-if="!miniVariant"></v-spacer>
			<v-toolbar-side-icon @click="onClickSideIcon">
				<v-icon>la-chevron-circle-left</v-icon>
			</v-toolbar-side-icon>
		</v-toolbar>
		
		<template v-if="miniVariant">
			<div class="uf-drawer__mini-content pt-3">
				<template v-for="(item, index) in items">
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
								<v-subheader class="blue-grey--text text--darken-4">{{item.text}}</v-subheader>
								<v-list-tile
									v-for="(children, indexChild) in item.childrens"
									:key="index + indexChild"
									:to="{name: children.urlName}">
									<v-list-tile-content class="pl-3">
										<v-list-tile-title>{{ children.text }}</v-list-tile-title>
									</v-list-tile-content>
								</v-list-tile>
							</v-list>
						</v-menu>
					</template>
					
					<template v-else>
						<v-tooltip right :key="index">
							<v-btn flat 
								slot="activator" 
								:to="{name: item.urlName}"
								active-class="blue lighten-5 blue--text text--darken-4">
								<v-icon>{{item.icon}}</v-icon>
							</v-btn>
							<span>{{item.text}}</span>
						</v-tooltip>
					</template>
				</template>
			</div>
		</template>

		<template v-else>
			<v-list dense expand class="scroll-y pt-2">
				<template v-for="(item, index) in items">
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
									<v-list-tile-title>{{ item.text }}</v-list-tile-title>
								</v-list-tile-content>
							</v-list-tile>

							<template v-for="(children, indexChild) in item.childrens">
								<v-list-tile 
									active-class="uf-drawer__list-tile__active"
									:key="index + indexChild" 
									:to="{name: children.urlName}">
								<v-list-tile-avatar>
									<v-icon>{{children.icon}}</v-icon>
								</v-list-tile-avatar>
									<v-list-tile-content>
										<v-list-tile-title>{{ children.text }}</v-list-tile-title>
									</v-list-tile-content>
								</v-list-tile>
							</template>
						</v-list-group>
					</template>
					
					<template v-else>
						<v-list-tile 
							:key="index" 
							:to="{name: item.urlName}"
							active-class="uf-drawer__list-tile__active">
							<v-list-tile-avatar>
								<v-icon>{{item.icon}}</v-icon>
							</v-list-tile-avatar>
							<v-list-tile-content>
								<v-list-tile-title>{{ item.text }}</v-list-tile-title>
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
	data() {
		return {
			items: [
				{ icon: 'la-dashboard', text: 'Dashboard', urlName: "dashboard" },
				{ icon: 'la-font', text: 'Typography', urlName: "typography" },
				{ icon: 'la-code',  text: 'Helper Class', urlName: "helperclass" },
				{ icon: 'la-th', text: 'Widgets', urlName: "widgets" },
				{ 
					icon: 'la-laptop', 
					text: 'UI Elements', 
					childrens: [
						{ text: 'Colors', urlName: "uielements.colors" },
						{ text: 'Icons', urlName: "uielements.icons" },
						{ text: 'Buttons', urlName: "uielements.buttons" },
						{ text: 'Timeline', urlName: "uielements.timeline" },
						{ text: 'Dialogs', urlName: "uielements.dialogs" },
					] 
				},
				{ 
					icon: 'la-edit', 
					text: 'Forms', 
					childrens: [
						{ text: 'General', urlName: "forms.general" },
						{ text: 'Advanced', urlName: "forms.advanced" },
						{ text: 'Editors', urlName: "forms.editors" },
					] 
				},
				{ icon: 'la-table', text: 'Datatables', urlName: "datatables" },
				{ icon: 'la-calendar', text: 'Calendar', urlName: "calendar" },
				{ icon: 'la-envelope', text: 'Mailbox', urlName: "mailbox" },
      ]
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
			return SITE_TITLE_ABBR;
		}
	},
	methods: {
		isChildActive(item) {
			const that = this;
			if (item.childrens) {
				const child = item.childrens.filter((child) => {
					return child.urlName === that.$route.name;
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
