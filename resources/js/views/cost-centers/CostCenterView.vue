<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Cost Center Information</template>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Status</label>
						<select class="form-control" name="status" v-model="item.status">
							<option value="Active">Active</option>
							<option value="Inactive">Inactive</option>
						</select>
					</div>
				</div>
				
				<div class="row">
		    		<div class="form-group col-sm-6">
		    			<label>Code</label>
		                <input name="code" v-model="item.code"  type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Name</label>
		                <input name="name" v-model="item.name"  type="text" class="form-control">
		    		</div>
				</div>

				<div class="row">
		    		<div class="form-group col-sm-6">
		    			<label>Active from</label>
		    			<input ref="active_from" type="text" class="form-control" name="active_from" v-model="item.active_from" readonly>
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Active to</label>
		    			<input ref="active_to" type="text" class="form-control" name="active_to" v-model="item.active_to" readonly>
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
	
	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {
		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton,
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