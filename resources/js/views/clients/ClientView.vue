<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Company Information</template>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Name</label>
						<input name="name" class="form-control" v-model="item.name">
					</div>
				</div>
				
				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</template>
			</card>
			
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';

	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
	import ActionButton from 'Components/buttons/ActionButton.vue';


	export default {
		components: {
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton
		},

		data() {
			return {
				item: {}
			}
		},

		mounted() {
			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
			},
		},

		mixins: [ CrudMixin ],
	}
</script>