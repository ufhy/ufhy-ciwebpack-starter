<template>
	<div>
		<v-btn :small="small" color="primary"
			v-if="btnSaveClose" 
			@click="saveCloseAction" 
			:loading="loading">
			{{$t('lb::save_close')}}<v-icon right>la-save</v-icon>
		</v-btn>
		<v-btn :small="small" color="primary"
			v-if="btnSave" 
			@click="saveAction" 
			:loading="loading">
			{{$t('lb::save')}}<v-icon right>la-save</v-icon>
		</v-btn>
		<v-btn :small="small" color="secondary"
			v-if="btnCancel" 
			@click="closeAction">
			{{$t('lb::cancel')}}<v-icon right>la-times</v-icon>
		</v-btn>
	</div>
</template>

<script>
export default {
	name: 'form-dialog-action',
	props: {
		loading: {
			type: Boolean,
			default: false
		},
		btnSaveClose: {
			type: Boolean,
			default: true
		},
		btnSave: {
			type: Boolean,
			default: true
		},
		btnCancel: {
			type: Boolean,
			default: true
		},
		small: {
			type: Boolean,
			default: true
		}
	},
	methods: {
		saveCloseAction() {
			this.$emit('save-close-action');
		},
		saveAction() {
			this.$emit('save-action');
		},
		closeAction() {
			this.$emit('close-action');
		},
	}
}
</script>
