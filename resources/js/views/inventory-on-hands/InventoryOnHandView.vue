<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#inventory" data-toggle="tab">Inventory On Hand</a></li>
					</ul>
				</div>							
				
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="inventory">
							<div class="card">
								<div class="card-header">
									<b>Inventory Overview</b>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="form-group col-sm-3">
											<label>Client</label>
											<v-select v-model="item.client_id" :options="clients" :reduce="client => client.id" label="name"/></v-select>
								    		<input type="hidden" name="client_id" :value="item.client_id">
										</div>
									</div>
									<div class="row mb-2">
										<div class="form-group col-md-1">
											<div class="custom-control custom-switch">
												<input
												v-model="item.ordered" name="ordered" type="checkbox" class="custom-control-input" id="ordered">
												<label class="custom-control-label" for="ordered">Ordered</label>
											</div>
										</div>
										<div class="form-group col-md-1">
											<div class="custom-control custom-switch">
												<input
												v-model="item.received" name="received" type="checkbox" class="custom-control-input" id="received">
												<label class="custom-control-label" for="received">Received</label>
											</div>
										</div>
										<div class="form-group col-md-2">
											<div class="custom-control custom-switch">
												<input
												v-model="item.closed_inventory_checkbox" name="closed_inventory_checkbox" type="checkbox" class="custom-control-input" id="closed_inventory_checkbox">
												<label class="custom-control-label" for="closed_inventory_checkbox">Closed Inventory</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-sm-3">
											<label>Inventory On Hand Number</label>
											<input v-model="item.inventory_on_hand_number" name="inventory_on_hand_number" type="text" class="form-control">
										</div>

							    		<div class="form-group col-sm-3">
							    			<label>Product</label>
							    			<v-select v-model="item.product" :options="products" :reduce="product => product.id" label="name"/></v-select>
								    		<input type="hidden" name="item_number" :value="item.item_number">
								    		<input type="hidden" name="product_name" :value="item.product_name">
							    		</div>
									</div>

									<div class="row">
							    		<div class="form-group col-sm-4">
							    			<label>Product Size</label>
								    		<input readonly type="text" class="form-control" name="size" :value="item.size">
							    		</div>
							    		<div class="form-group col-sm-4">
							    			<label>Product Color</label>
								    		<input readonly type="text" class="form-control" name="color" :value="item.color">
							    		</div>
							    		<div class="form-group col-sm-4">
							    			<label>Item Unit</label>
								    		<input readonly type="text" class="form-control" name="item_unit" :value="item.item_unit">
							    		</div>
									</div>

									<div class="row">
										
							    		<div class="form-group col-sm-4">
							    			<label>Purchase Quantity</label>
								    		<input type="number" min="0" class="form-control" name="ordered_quantity" v-model="item.ordered_quantity" readonly>
							    		</div>
							    		<div class="form-group col-sm-4">
							    			<label>Purchase Return Quantity</label>
								    		<input type="number" min="0" class="form-control" name="purchase_return" v-model="item.purchase_return" readonly>
							    		</div>
							    		<div class="form-group col-sm-4">
							    			<label>Sales Quantity</label>
								    		<input type="number" min="0" class="form-control" name="sales_quantity" v-model="item.sales_quantity" readonly>
							    		</div>
							    		<div class="form-group col-sm-4">
							    			<label>Received Quantity</label>
								    		<input type="number" min="0" class="form-control" name="received_quantity" v-model="item.received_quantity">
							    		</div>
							    		<div class="form-group col-sm-4">
							    			<label>Posted Quantity</label>
								    		<input type="number" min="0" class="form-control" name="posted_quantity" v-model="item.posted_quantity">
							    		</div>
							    		<div class="form-group col-sm-4">
							    			<label>Total Available</label>
								    		<input type="number" min="0" class="form-control" name="total_available" v-model="item.total_available">
							    		</div>
							    		<div class="form-group col-sm-6">
							    			<label>Physical Cost Amount</label>
								    		<input type="number" min="0" class="form-control" name="physical_cost_amount" v-model="item.physical_cost_amount">
							    		</div>
							    		<div class="form-group col-sm-6">
							    			<label>Financial Cost Amount</label>
								    		<input type="number" min="0" class="form-control" name="financial_cost_amount" v-model="item.financial_cost_amount">
							    		</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';

	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select' : Vselect
		},

		data() {
			return {
				item: {},
				products: [],
				clients: [],
			}
		},

		watch: {
			'item.product'(val) {
				var product = _.find(this.products, (product) => { return product.id == val; });

				this.item.item_number = product.id;
				this.item.product_name = product.name;
				this.item.size = product.size;
				this.item.color = product.color;
				this.item.item_unit = product.unit_price;
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.products = data.products ? data.products : this.products;
				this.clients = data.clients ? data.clients : this.clients;
				if(_.isEmpty(this.item)) {
					this.generateCode('create', 'INVNHND');
				}
			},

			
			generateCode(value, prefix) {
				if(value == 'create') {
					var date = new Date();
					var time = Math.round(date.getTime() / 1000);	
					this.item.inventory_on_hand_number = prefix + '-' + date.getFullYear().toString() + ("0" + (date.getMonth() + 1)).slice(-2) + date.getDate().toString()   +'-'+ time.toString();
				}
			},
		},

		mixins: [ CrudMixin ],
	}
</script>