<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Customer Payment Fee Setup Overview</template>
				
				<div class="row">

					<div class="form-group col-sm-3">
						<label>Client</label> 
						<v-select v-model="item.client_id" placeholder="Select Client" :options="clients" :reduce="item => item.id" label="name"></v-select>
						<input hidden v-model="item.client_id" name="client_id">
					</div>

					<div class="form-group col-sm-3">
						<label>Customer Payment Method</label> 
						<v-select v-model="item.customer_payment_method_id" placeholder="Select Customer Payment Method" :options="payment_methods" :reduce="item => item.id" label="method_of_payment"></v-select>
						<input hidden v-model="item.customer_payment_method_id" name="customer_payment_method_id">
					</div>

					<div class="form-group col-sm-3">
						<label>Fee ID</label>
						<input type="text" class="form-control" v-model="item.fee_id" name="fee_id">
					</div>

					<div class="form-group col-sm-3">
						<label>Tax Account</label>
						<input type="text" class="form-control" v-model="item.tax_account" name="tax_account">
					</div>
				</div>

				<div class="row">

					<div class="form-group col-sm-4">
						<label>Percentage/Amount</label>
						<select class="form-control" name="percentage_amount" v-model="item.percentage_amount">
							<option value="Amount">Amount</option>
							<option value="Percent">Percent</option>
							<option value="Interval">Interval</option>
						</select>
					</div>

					<div class="form-group col-sm-4">
						<label>Payment Specification</label>
						<input type="text" class="form-control" v-model="item.payment_specification" name="payment_specification">
					</div>

					<div class="form-group col-sm-4">
						<label>Fee Amount</label>
						<input type="number" step="any" class="form-control" v-model="item.fee_amount" name="fee_amount">
					</div>

					<div class="form-group col-sm-6">
						<label>Minimum</label>
						<input type="number" step="any" class="form-control" v-model="item.minimum" name="minimum">
					</div>

					<div class="form-group col-sm-6">
						<label>Maximum</label>
						<input type="number" step="any" class="form-control" v-model="item.maximum" name="maximum">
					</div>

					<div class="form-group col-sm-4">
						<label>From Date</label>
						<input type="text" id="from_date" class="form-control" v-model="item.from_date" name="from_date">
					</div>

					<div class="form-group col-sm-4">
						<label>To Date</label>
						<input type="text" id="to_date" class="form-control" v-model="item.to_date" name="to_date">
					</div>

					<div class="form-group col-sm-4">
						<label>Minimum Fee</label>
						<input type="number" step="any" class="form-control" v-model="item.minimum_fee" name="minimum_fee">
					</div>
					<div class="form-group col-sm-6">
						<label>Waive Limit Fee</label>
						<input type="number" step="any" class="form-control" v-model="item.waive_limit_fees" name="waive_limit_fees">
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
			payment_methods: [],
			clients: [],
		}
	},

	methods: {
		fetchSuccess(data) {
			flatpickr('#from_date');
			flatpickr('#to_date');
			this.item = data.item ? data.item : this.item;
			this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
			this.clients = data.clients ? data.clients : this.clients;
		},
	},

	mixins: [ CrudMixin ],
}
</script>