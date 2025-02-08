<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Charge Overview</template>
				
				<div class="row">

					<div class="form-group col-sm-3">
						<label>Client</label> 
						<v-select v-model="item.client_id" placeholder="Select Client" :options="clients" :reduce="item => item.id" label="name" :disabled="disabled"></v-select>
						<input hidden v-model="item.client_id" name="client_id">
					</div>

					<div class="form-group col-sm-3">
						<label>Name</label>
						<input type="text" class="form-control" v-model="item.name" name="name" :disabled="disabled">
					</div>
					<div class="form-group col-sm-3">
						<label>Status</label>
						<select class="form-control" name="status" v-model="item.status">
							<option value="Enabled">Enabled</option>
							<option value="Disabled">Disabled</option>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Charge Value</label>
						<input type="number" step="any" class="form-control" v-model="item.charge_value" name="charge_value" :disabled="disabled">
					</div>
					<div class="form-group col-sm-4">
						<label>From Amount</label>
						<input type="number" step="any" class="form-control" v-model="item.from_amount" name="from_amount" :disabled="disabled">
					</div>
					<div class="form-group col-sm-4">
						<label>To Amount</label>
						<input type="number" step="any" class="form-control" v-model="item.to_amount" name="to_amount" :disabled="disabled">
					</div>
					<div class="form-group col-sm-6">
						<label>Quantity</label>
						<input type="number" step="any" class="form-control" v-model="item.quantity" name="quantity" :disabled="disabled">
					</div>
					<div class="form-group col-sm-6">
						<label>Charge Percentage</label>
						<input type="number" step="any" class="form-control" v-model="item.charge_percentage" name="charge_percentage" :disabled="disabled">
					</div>
				</div>
				
				<div class="row">
					
					<div class="form-group col-sm-3">
						<label>Delivery Type</label>
						<select class="form-control" name="delivery_type" v-model="item.delivery_type" :disabled="disabled">
							<option value="Air">Air</option>
							<option value="Sea">Sea</option>
							<option value="Land">Land</option>
						</select>
					</div>
					<div class="form-group col-sm-3">
						<label>Level</label>
						<select class="form-control" name="level" v-model="item.level" :disabled="disabled">
							<option value="Main">Main</option>
							<option value="Line">Line</option>
						</select>
					</div>
					<div class="form-group col-sm-3">
						<label>Applied To</label>
						<select class="form-control" name="applied_to" v-model="item.applied_to" :disabled="disabled">
							<option value="Customer">Customer</option>
							<option value="Vendor">Vendor</option>
							<option value="Product">Product</option>
							<option value="Service">Service</option>
						</select>
					</div>
					<div class="form-group col-sm-3">
						<label>Charge Category</label>
						<select class="form-control" name="charge_category" v-model="item.charge_category" :disabled="disabled">
							<option value="Fixed Amount">Fixed Amount</option>
							<option value="Amount Range">Amount Range</option>
							<option value="Quantity">Quantity</option>
							<option value="Percentage">Percentage</option>
						</select>
					</div>

					<div class="form-group col-sm-6">
						<label>Valid From</label>
						<input type="text" class="form-control" v-model="item.valid_from" name="valid_from" ref="valid_from">
					</div>

					<div class="form-group col-sm-6">
						<label>Valid To</label>
						<input type="text" class="form-control" v-model="item.valid_to" name="valid_to" ref="valid_to">
					</div>
					
					<div class="form-group col-sm-4">
						<label>Service</label> 
						<v-select v-model="item.service_id" placeholder="Select Service" :options="services" :reduce="item => item.id" label="name" :disabled="disabled"></v-select>
						<input hidden v-model="item.service_id" name="service_id">
					</div>
					<div class="form-group col-sm-4">
						<label>Service Task</label> 
						<v-select v-model="item.service_task_id" placeholder="Select Service Task" :options="service_tasks" :reduce="item => item.id" label="service" :disabled="disabled"></v-select>
						<input hidden v-model="item.service_task_id" name="service_task_id">
					</div>
				<!-- 	<div class="form-group col-sm-4">
						<label>Vendor Payment Method</label> 
						<v-select v-model="item.vendor_payment_method_id" placeholder="Select Payment Method for Vendor" :options="vendor_payment_methods" :reduce="item => item.id" label="method_of_payment" :disabled="disabled"></v-select>
						<input hidden v-model="item.vendor_payment_method_id" name="vendor_payment_method_id">
					</div>
					<div class="form-group col-sm-4">
						<label>Customer Payment Method</label> 
						<v-select v-model="item.customer_payment_method_id" placeholder="Select Payment Method for Customer" :options="customer_payment_methods" :reduce="item => item.id" label="method_of_payment" :disabled="disabled"></v-select>
						<input hidden v-model="item.customer_payment_method_id" name="customer_payment_method_id">
					</div> -->
					<div class="form-group col-sm-4">
						<label>Procurement</label> 
						<v-select v-model="item.procurement_id" placeholder="Select Procurement" :options="procurements" :reduce="item => item.id" label="main_account_name" :disabled="disabled"></v-select>
						<input hidden v-model="item.procurement_id" name="procurement_id">
					</div>
					<div class="form-group col-sm-3">
						<label>Product</label> 
						<v-select v-model="item.product_id" placeholder="Select Product" :options="products" :reduce="item => item.id" label="name" :disabled="disabled"></v-select>
						<input hidden v-model="item.product_id" name="product_id">
					</div>
					<div class="form-group col-sm-3">
						<label>Variant</label> 
						<v-select v-model="item.variant_id" placeholder="Select variant" :options="variants" :reduce="item => item.id" label="name" :disabled="disabled"></v-select>
						<input hidden v-model="item.variant_id" name="variant_id">
					</div>
					<div class="form-group col-sm-3">
						<label>Vendor Charge Account</label> 
						<v-select v-model="item.main_account_id" placeholder="Select Vendor Charge Account" :options="main_accounts" :reduce="item => item.id" label="main_account_name" :disabled="disabled"></v-select>
						<input hidden v-model="item.main_account_id" name="main_account_id">
					</div>
					<div class="form-group col-sm-3">
						<label>Customer Charge Account</label> 
						<v-select v-model="item.customer_main_account_id" placeholder="Select Customer Charge Account" :options="main_accounts" :reduce="item => item.id" label="main_account_name" :disabled="disabled"></v-select>
						<input hidden v-model="item.customer_main_account_id" name="customer_main_account_id">
					</div>

				</div>

				<div class="row">
					<text-editor
					v-model="item.description"
					class="col-sm-12"
					label="Description"
					name="description"
					row="5"
					:disabled="disabled"
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
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

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
			services: [],
			service_tasks: [],
			vendor_payment_methods: [],
			customer_payment_methods: [],
			procurements: [],
			products: [],
			variants: [],
			main_accounts: [],
			clients: [],
			disabled: false
		}
	},

	watch: {
		'item.service_id'(val) {
			var service = _.find(this.services, (service) => { return service.id == val });

			this.service_tasks = service.service_tasks;
		},

		'item.product_id'(val) {
			var product = _.find(this.products, (product) => { return product.id == val });

			this.variants = product.variants;
		},

		'item.status'(val) {
			this.disabled = (val == 'Enabled');
		}
	},

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.services = data.services ? data.services : this.services;
			this.vendor_payment_methods = data.vendor_payment_methods ? data.vendor_payment_methods : this.vendor_payment_methods;
			this.customer_payment_methods = data.customer_payment_methods ? data.customer_payment_methods : this.customer_payment_methods;
			this.procurements = data.procurements ? data.procurements : this.procurements;
			this.products = data.products ? data.products : this.products;
			this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
			this.clients = data.clients ? data.clients : this.clients;
			flatpickr(this.$refs.valid_from)
			flatpickr(this.$refs.valid_to)
		},
	},

	mixins: [ CrudMixin ],
}
</script>