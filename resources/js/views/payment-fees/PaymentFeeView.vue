<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Payment Fee Overview</template>
				
				<div class="row">

					<div class="form-group col-sm-3">
						<label>Client</label> 
						<v-select v-model="item.client_id" placeholder="Select Client" :options="clients" :reduce="item => item.id" label="name"></v-select>
						<input hidden v-model="item.client_id" name="client_id">
					</div>

					<div class="form-group col-sm-3">
						<label>Fee ID</label>
						<input type="text" class="form-control" v-model="item.fee_id" name="fee_id">
					</div>
					<div class="form-group col-sm-3">
						<label>Fee Amount</label>
						<input type="number" step="any" class="form-control" v-model="item.charge_value" name="charge_value">
					</div>
				</div>

				<div class="row">
					
					<div class="form-group col-sm-3">
						<label>Remittance Type</label>
						<select class="form-control" name="remittance_type" v-model="item.remittance_type">
							<option value="None">None</option>
							<option value="Collection">Collection</option>
							<option value="Discount">Discount</option>
						</select>
					</div>
					<div class="form-group col-sm-3">
						<label>Payment Specification</label>
						<input type="text" class="form-control" v-model="item.payment_specification" name="payment_specification">
					</div>


					<div class="form-group col-sm-3">
						<label>Payment Date</label>
						<input type="text" id="payment_date" class="form-control" v-model="item.payment_date" name="payment_date">
					</div>

					<div class="form-group col-sm-3">
						<label>Due Date</label>
						<input type="text" id="due_date" class="form-control" v-model="item.due_date" name="due_date">
					</div>

					<div class="form-group col-sm-4">
						<label>Vendor Payment Method</label> 
						<v-select v-model="item.vendor_payment_method_id" placeholder="Select Payment Method for Vendor" :options="vendor_payment_methods" :reduce="item => item.id" label="method_of_payment"></v-select>
						<input hidden v-model="item.vendor_payment_method_id" name="vendor_payment_method_id">
					</div>
					<div class="form-group col-sm-4">
						<label>Customer Payment Method</label> 
						<v-select v-model="item.customer_payment_method_id" placeholder="Select Payment Method for Customer" :options="customer_payment_methods" :reduce="item => item.id" label="method_of_payment"></v-select>
						<input hidden v-model="item.customer_payment_method_id" name="customer_payment_method_id">
					</div>
					<div class="form-group col-sm-4">
						<label>Client Bank Account</label> 
						<v-select v-model="item.client_bank_account_id" placeholder="Select Client Bank Account" :options="bank_accounts" :reduce="item => item.id" label="bank_account"></v-select>
						<input hidden v-model="item.client_bank_account_id" name="client_bank_account_id">
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
import ActionButton from 'Components/buttons/ActionButton.vue';
import Vselect from 'vue-select';
import flatpickr from 'flatpickr';

export default {

	components: {
		'text-editor': TextEditor,
		'form-request': FormRequest,
		'action-button': ActionButton,
		'v-select' : Vselect
	},

	data() {
		return {
			item: {},
			clients: [],
			vendor_payment_methods: [],
			customer_payment_methods: [],
			bank_accounts: [],
		}
	},

	methods: {
		fetchSuccess(data) {
			flatpickr('#payment_date');
			flatpickr('#due_date');
			this.item = data.item ? data.item : this.item;
			this.vendor_payment_methods = data.vendor_payment_methods ? data.vendor_payment_methods : this.vendor_payment_methods;
			this.customer_payment_methods = data.customer_payment_methods ? data.customer_payment_methods : this.customer_payment_methods;
			this.bank_accounts = data.bank_accounts ? data.bank_accounts : this.bank_accounts;
			this.clients = data.clients ? data.clients : this.clients;
		},
	},

	mixins: [ CrudMixin ],
}
</script>