<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Service Overview</template>
				
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Service #</label>
						<input readonly name="service_number" class="form-control" v-model="item.service_number">
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Name</label>
						<input name="name" class="form-control" v-model="item.name" maxlength="15">
					</div>
					<div class="form-group col-sm-4">
						<label>Client</label> 
						<v-select :disabled="item.id ? true : false"  :reduce="item => item.id" v-model="item.client_id"  placeholder="Select Client" label='name' :options="clients"></v-select>
						<input hidden v-model="item.client_id" name="client_id">
					</div>
					<div class="form-group col-sm-4">
						<label>Vendor</label> 
						<v-select :disabled="item.id ? true : false"  :reduce="item => item.id" v-model="item.vendor_id" placeholder="Select Vendor" label='fullname' :options="vendors"></v-select>
						<input hidden v-model="item.vendor_id" name="vendor_id">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Unit Price</label>
						<input class="form-control"  v-model="item.unit_price" name="unit_price">
					</div>
					<div class="form-group col-sm-4">
						<label>Service Type</label> 
						<v-select v-model="item.service_type" placeholder="Select Service Type" :options="service_types"></v-select>
						<input hidden v-model="item.service_type" name="service_type">
					</div>
					<div class="form-group col-sm-4">
						<label>Work Type</label>
						<v-select v-model="item.work_type"   placeholder="Select Work Type" :options="work_types"></v-select>
						<input hidden v-model="item.work_type" name="work_type">
					</div>
				</div>

				<div class="row">
					<text-editor
					v-model="item.description"
					class="col-sm-12"
					label="Description"
					name="description"
					row="5"
					></text-editor>
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
import ActionButton from 'Components/buttons/ActionButton.vue';
import Vselect from 'vue-select';

export default {

	components: {
		'text-editor': TextEditor,
		'form-request': FormRequest,
		'action-button': ActionButton,
		'v-select' : Vselect
	},

	props : {
		service_code : {
			default : null,
			type : String,
		}
	},

	data() {
		return {
			item: {},
			clients : [],
			vendors : [],
			service_types : [
				'Logistics',
				'Transportation',
				'Financial Services',
				'Product as a Services',
				'Professional Service'
			],
			work_types : [
				'Commissions',
				'Hourly',
				'Weekly',
				'Daily',
				'Montly',
				'Quarterly',
				'Yearly',
			],
		}
	},

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.vendors = data.vendors ? data.vendors : this.vendors;
			this.clients = data.clients ? data.clients : this.clients;

			if(!this.item.id) {
				this.item.service_number = this.service_code;
			}
		},
	},

	mixins: [ CrudMixin ],
}
</script>