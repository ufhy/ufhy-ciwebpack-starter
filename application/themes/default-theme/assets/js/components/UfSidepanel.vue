<template>
	<v-navigation-drawer fixed temporary right
		class="uf-sidepanel"
		v-model="sidepanel">
		<v-toolbar flat height="50" dark>
			<v-toolbar-title>{{title}}</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-btn icon small @click="hide">
				<v-icon>la-times</v-icon>
			</v-btn>
		</v-toolbar>
		<div class="uf-sidepanel__content">
			<slot v-if="responseItem" name="item" :item="responseItem"></slot>
			<slot></slot>

			<div class="uf-sidepanel__progress" v-if="loading">
				<v-progress-circular
					indeterminate
					color="primary"
					class=""
				></v-progress-circular>
			</div>
		</div>
	</v-navigation-drawer>
</template>

<script>
import { CancelToken } from 'axios';

export default {
	name: 'uf-sidepanel',
	props: {
		title: String,
		urlJson: String,
		urlParams: Object,
	},
	data() {
		return {
			sidepanel: false,
			loading: false,
			cancelToken: null,
			responseItem: null
		}
	},
	created() {
		this.cancelToken = CancelToken;
	},
	methods: {
		_fetchJson() {
			this.$nextTick(() => {
				this.loading = true;
				const params = this.urlParams ? this.urlParams : {};
				this.$axios.get(this.urlJson, {
					params: params,
					cancelToken: this.cancelToken.token
				})
					.then(response => {
						const { data } = response;
						if (data.success) {
							this.responseItem = data.row;
						}
					})
					.catch(error => {
						const {statusText, data} = error;
						if (typeof data.message !== "undefined") {
							this.$snackbars.error(data.message);
						} else {
							this.$snackbars.error(statusText);
						}
					})
					.then(() => {
						this.loading = false;
					});
			});
		},
		show() {
			this.sidepanel = true;
			if (typeof this.urlJson === "undefined") {
				return false;
			}

			if (this.loading) {
				return false;
			}

			this._fetchJson();
		},
		hide() {
			this.responseItem = null;
			this.sidepanel = false;
		}
	}
}
</script>
