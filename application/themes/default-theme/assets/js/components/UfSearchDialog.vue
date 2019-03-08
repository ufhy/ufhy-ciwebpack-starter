<template>
	<v-dialog 
		v-model="dialogSearch" 
		content-class="v-dialog--top"
		width="500">
		<v-btn icon
			slot="activator" 
			class="uf-search-dialog"
			:light="light" 
			:dark="dark"
			:class="btnClass">
			<v-badge v-model="dialogSearchActive" right color="error" class="search-badge">
				<span slot="badge">&nbsp;</span>
				<v-icon>la-search</v-icon>
			</v-badge>
		</v-btn>

		<v-toolbar dense>
			<v-text-field
				hide-details
				class="mt-0 pt-0"
				prepend-icon="la-search"
				v-model="dialogSearchText"
				single-line
				v-on:keyup.enter="dialogSearchOk"
				placeholder="Search"
				v-on:keyup.esc="dialogSearchEsc"
			></v-text-field>
			<v-btn flat small @click="dialogSearchOk">
				OK
			</v-btn>
		</v-toolbar>
	</v-dialog>
</template>

<script>
export default {
	name: 'uf-dialog-search',
	props: {
		light: {
			type: Boolean,
			default: true,
		},
		dark: {
			type: Boolean,
			default: false,
		},
		btnClass: String,
	},
	data() {
		return {
			dialogSearch: false,
			dialogSearchText: '',
			dialogSearchActive: false,
		}
	},
	methods: {
		dialogSearchOk() {
			if (this.dialogSearchText.length) {
				this.dialogSearchActive = true;
			} else {
				this.dialogSearchActive = false;
			}
			this.tooltipSearch = false;
			this.dialogSearch = false;
			this.$emit('search-action', this.dialogSearchText);
		},
		dialogSearchEsc() {
			if (this.dialogSearchText.length) {
				this.dialogSearchText = '';
			} else {
				this.dialogSearch = false;
			}

			this.tooltipSearch = false;
			this.dialogSearchActive = false;
			this.$emit('search-cancel-action');
		},
	}
}
</script>
