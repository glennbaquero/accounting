<template>
	<div>
		<!-- <div class="row">
			<div class="col-md-12 text-right">
				<button type="button" @click="newLine" class="btn btn-success w-10" data-toggle="modal" data-target="#so-lines-modal" data-backdrop="static"><i class="fas fa-plus"></i> Create Sales Return Line</button>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Total Quantity</label>
					<input name="total_data_quantity" :value="totalQuantity" type="text"  readonly class="form-control">
				</div>
			</div>	
			<div class="col-md-6">
				<div class="form-group">
					<label>Total Amount</label>
					<input name="total_amount" :value="totalAmount | currency" type="text"  readonly class="form-control">
				</div>
			</div>	        		    
		</div>
		<div class="row">
			<div class="form-group col-md-4">
				<label>Subtotal Amount</label>
				<input name="subtotal_amount" :value="subTotalAmount | currency" type="text"  readonly class="form-control">
				<label>Total Discount</label>
				<input name="total_discount" :value="totalDiscount | currency" type="text"  readonly class="form-control">
			</div>
			<div class="form-group col-md-4">
				<label>Total Round Off</label>
				<input name="total_round_off" :value="totalRoundOff | currency" type="text"  readonly class="form-control">
			</div>
			<div class="form-group col-md-4">
				<label>Total Charges</label>
				<input name="total_charges"  type="text" :value="totalCharges | currency" readonly class="form-control mb-2">
				<label>Total Sales Tax</label>
				<input name="total_sales_tax" type="text" :value="totalSalesTax | currency" readonly class="form-control">
			</div>
			<div class="form-group col-md-4">
				<label>Cash Discount</label>
				<input name="cash_discount" :value="totalCashDiscount | currency" type="text"  readonly class="form-control">
			</div>
			<div class="form-group col-md-4">
				<label>Tax Posting</label>
				<select class="form-control mb-2">
					<option></option>
				</select>
			</div>
		</div> -->

		<div class="row">
			<div class="col-md-12">
				
				<data-table 
					:key="data_table_key"
					ref="data-table"
					:headers="headers" 
					:items="sales_order_lines"
				>
						<template v-slot:body="{ items }">
						<tr v-for="(table_item, key) in items">
							<td>{{ table_item.sales_order_return_line_number }}</td>
							<td>{{ table_item.product.product_number }}</td>
							<td>{{ table_item.line_status }}</td>
							<td>{{ table_item.product.name }}</td>
							<td>{{ table_item.variant.name }}</td>
							<td>{{ table_item.variant.size }}</td>
							<td>{{ table_item.variant.color }}</td>
							<td>{{ table_item.quantity }}</td>
							<td>{{ table_item.variant.unit_price | currency }}</td>
							<td>{{ table_item.amount | currency }}</td>
							<td>
								<template>
									<button type="button" class="btn btn-info btn-sm" @click="editLine(key)">
										<i class="fas fa-eye"></i>
									</button>
									<button type="button" class="btn btn-danger btn-sm" @click="removeLine(key)">
										<i class="fas fa-trash"></i>
									</button>
								</template>
							</td>
						</tr>
						</template>         
				</data-table>
			</div>
		</div>
                
	<div class="modal fade"  id="so-lines-modal" tabindex="-1" role="dialog" aria-labelledby="sales_order_line_modal_label" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"  id="sales_order_line_modal_label">Sales Order Line</h5>
						<button type="button" class="close" data-dismiss="modal" @click="initDefaultValue" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<h4><i class="fas fa-info-circle"></i> Details</h4><hr>
									<label>Sales Order Return Number</label>
									<input readonly class="form-control mb-2" v-model="so.sales_order_return_number">
									<label>Sales Order Line Number</label>
									<input readonly class="form-control mb-2" v-model="item.sales_order_return_line_number">
									<label>Customer Account</label>
									<input readonly v-model="so.customer_account" class="form-control mb-2">
									<label>Invoice Account</label>
									<input readonly v-model="so.invoice_account" class="form-control mb-2">
									<label>Customer Name</label>
									<input readonly v-model="so.customer_name" type="text" class="form-control mb-2">


									<h4 class="mt-4"><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
									<label>Cost Center</label>
									<v-select class="mb-2" v-model="item.cost_center_id" :reduce="item => item.id" label="dimension_name" placeholder="Select Cost Center" :options="cost_centers"></v-select>
									<label>Department</label>
									<v-select class="mb-2" v-model="item.department_id" :reduce="item => item.id" label="dimension_name" placeholder="Select Department" :options="departments"></v-select>
									<label>Expense Purpose</label>
									<v-select class="mb-2" v-model="item.expense_purpose_id" :reduce="item => item.id" label="dimension_name" placeholder="Select Expense Purpose" :options="expense_purposes"></v-select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<h4><i class="fas fa-cash-register"></i> Charges</h4><hr>
									<label>Charges</label>
									<v-select class="mb-3" v-model="item.charge_id" placeholder="Select Charges" :options="charges_on_lines"  :reduce="item => item.id" label="name"></v-select>
									<h4><i class="fas fa-percentage"></i> Discount</h4><hr>
									<label>Discount</label>
									<v-select class="mb-3" v-model="item.discount_id" placeholder="Discount" :options="discount_on_lines"  :reduce="item => item.id" label="name" disabled></v-select>

									<h4><i class="fas fa-truck"></i> Status</h4><hr>
									<label>Line Status <b class="text-danger">*</b></label>
									<v-select class="mb-2" v-model="item.line_status" placeholder="Select Line Status" :options="line_statuses"></v-select>

									<h4><i class="fas fa-money-bill"></i> Ledger</h4><hr>
									<label>Subledger Journal</label>
									<input  v-model="item.subledger_journal" class="form-control mb-2">
									<label>Ledger Account</label>
									<input v-model="item.ledger_account" class="form-control mb-2">

									<!-- <label>Procurement Category <b class="text-danger">*</b></label>
									<v-select class="mb-2" v-model="item.procurement_category" label="name" placeholder="Select Procurement" :options="procurement_categories"></v-select> -->

									<!-- <label>Delivery Date</label>
									<input readonly v-model="so.delivery_date" class="form-control mb-2">
									<label>Delivery Type</label>
									<input readonly v-model="so.mode_of_delivery_type" class="form-control mb-2"> -->
									<h4 class="mt-4"><i class="fas fa-dollar-sign"></i> Sales Tax</h4><hr>
									<label>Item Sales Tax Group</label>
									<input v-model="item.item_sales_tax_group" class="form-control mb-2">
									<label>Sale Tax Group</label>
									<input v-model="item.sale_tax_group" class="form-control mb-2">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<h4><i class="fas fa-cubes"></i> Item Type</h4><hr>

									<label>Order Line Type <b class="text-danger">*</b></label>
									<select class="form-control mb-2" v-model="item.order_line_type">
										<option value="services">Services</option>
										<option value="product">Product</option>
										<option value="asset">Asset</option>
										<option value="others">Others</option>
									</select>
									<label>Description</label>
									<input class="form-control mb-2" v-model="item.description">


									<h4><i class="fas fa-cubes"></i> Service</h4><hr>
									<label>Service <b class="text-danger">*</b></label>
									<v-select @input="clearVariant" class="mb-2" v-model="item.service_id" :reduce="item => item.id" label="name" :resetOnOptionsChange="true" placeholder="Select Service" :options="services" :disabled="item.order_line_type === 'product'"></v-select>
									<label>Service Number</label>
									<input class="form-control mb-2" :value="item.service ? item.service.service_number : null" readonly>
									<label>Service Task</label>
									<v-select class="mb-2" v-model="item.service_task" :reduce="item => item.id" label="service" :resetOnOptionsChange="true" placeholder="Select Service Task" :options="service_tasks" :disabled="item.order_line_type === 'product'"></v-select>
									<label>Service Task Details</label>
									<input class="form-control mb-2" v-model="item.service_task_details" :readonly="item.order_line_type === 'product'">
									<label>RPM Method</label>
									<input class="form-control mb-2" v-model="item.rpm_method" :readonly="item.order_line_type === 'product'">
									<label>Number of Hours</label>
									<input class="form-control mb-2" v-model="item.number_of_hours" :readonly="item.order_line_type === 'product'">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<h4>&zwnj;</h4><hr>
									<label>Product</label>
									<label>Product <b class="text-danger">*</b></label>
									<v-select @input="clearVariant" class="mb-2" v-model="item.product_id" :reduce="item => item.id" label="name" :resetOnOptionsChange="true" placeholder="Select Product" :options="products" :disabled="item.order_line_type === 'services'"></v-select>

									<label>Product Number</label>
									<input readonly class="form-control mb-2" :value="item.product ? item.product.product_number : null" :readonly="item.order_line_type === 'services'">

									<label>Variant <b class="text-danger">*</b></label>
									<v-select ref="variant" class="mb-2" v-model="item.variant_id" :reduce="item => item.id" label="name" :resetOnOptionsChange="!editing" placeholder="Select Variant" :options="filtered_variants" :disabled="item.order_line_type === 'services'"></v-select>

									<label>Variant Number</label>
									<input readonly class="form-control mb-2" :value="item.variant.variant_number" :readonly="item.order_line_type === 'services'">

									<label>Size</label>
									<input readonly v-model="item.variant.size"  type="number" class="form-control mb-2" :readonly="item.order_line_type === 'services'">
									<label>Color</label>
									<input readonly v-model="item.variant.color" type="number" class="form-control mb-2" :readonly="item.order_line_type === 'services'">
									<label>Procurement</label>
									<input readonly v-model="item.procurement_number" class="form-control mb-2" :readonly="item.order_line_type === 'services'">

									<label>Specification <b class="text-danger">*</b></label>
									<v-select class="mb-2" v-model="item.specification_id" :reduce="item => item.id" label="specification_name" placeholder="Select Specification" :options="specifications" :disabled="item.order_line_type === 'services'"></v-select>

									<h4>&zwnj;</h4><hr>
									<label>Other Account</label>
									<v-select class="mb-2" v-model="item.procurement_id" placeholder="Select Procurement"  :reduce="item => item.id" label="main_account_name" :options="procurements"></v-select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<h4>&zwnj;</h4><hr>

									<label>Unit Price</label>
									<input readonly v-model="item.variant.unit_price" type="number" class="form-control mb-2">
									<label>Quantity <b class="text-danger">*</b></label>
									<input :readonly="!item.variant_id" v-model="item.quantity" type="number" class="form-control mb-2">
									<label>Amount</label>
									<input v-model="item.amount" type="number" class="form-control mb-2">
									<label>Less Discount (%)</label>
									<input v-model="item.less_discount" type="number" class="form-control mb-2">
									<label>Cash Discount</label>
									<input v-model="item.cash_discount" type="number" class="form-control mb-2">
									<label>Add Charge</label>
									<input v-model="item.add_charge" type="number" class="form-control mb-2">
									<label>Charge <b class="text-danger">*</b></label>
									<input v-model="item.charge" type="number" class="form-control mb-2">
									<label>Add Fee</label>
									<input v-model="item.add_fee" type="number" class="form-control mb-2">
									<label>Fee <b class="text-danger">*</b></label>
									<input v-model="item.fee" type="number" class="form-control mb-2">
									
									<!-- <label>Discount</label> -->
									<!-- <input v-model="item.discount" type="number" class="form-control mb-2"> -->
									<!-- <label>Discount Percentage</label>
									<div class="input-group mb-2">
									<input :readonly="!item.variant_id" v-model="item.discount_percentage" type="number" class="form-control">
										<div class="input-group-append">
											<span class="input-group-text">%</span>
										</div>
									</div> -->
									<!-- <label>Charges on Purchases</label> -->
									<!-- <input v-model="item.charge_on_purchase" type="number" class="form-control mb-2"> -->

									

								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<h4>&zwnj;</h4><hr>
									<label>Line Amount</label>
									<input v-model="item.line_amount" type="number" class="form-control mb-2">
									<label>Additional Tax</label>
									<input v-model="item.additional_tax" type="number" class="form-control mb-2">
									<label>VAT Amount</label>
									<input v-model="item.vat_amount" type="number" class="form-control mb-2">
									<label>Line VAT</label>
									<input v-model="item.line_vat" type="number" class="form-control mb-2">
									<label>Total Sales(VAT Inclusive)</label>
									<input v-model="item.total_sales_vat_inclusive" type="number" class="form-control mb-2">
									<h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
									<label>Created By</label>
									<input readonly  v-model="created_by" class="form-control mb-2">
									<label>Created On</label>
									<input readonly :value="so.formatted_created_at" class="form-control mb-2">
									<label>Updated By</label>
									<input readonly :value="updated_by"  class="form-control mb-2">
									<label>Updated On</label>
									<input readonly :value="so.formatted_updated_at" class="form-control mb-2">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer text-right">
						<button v-if="editing" type="button" class="btn btn-primary" @click="saveLine">Save Changes</button>
						<button v-if="!editing" type="button" class="btn btn-primary" @click="addLine"><i class="fa fa-plus"></i> Create Purchase Order Line</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import { bus }from 'Root/bus.js';
	import CrudMixin from 'Mixins/crud.js';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import DataTable from 'Components/tables/DataTable.vue';
	import { ModelListSelect } from 'vue-search-select';
	import Vselect from "vue-select";

	export default {

		mixins: [ CrudMixin ],
		
		components: {
			'form-request': FormRequest,
			'data-table': DataTable,
			'v-select' : Vselect,
			ModelListSelect
		},

		props: {
			products: {
				default: [],
				type: Array
			},

			variants: {
				default: [],
				type: Array
			},
			
			// sales order
			so : {
				default : [],
				type : Object
			},

			lines : {
				default : [],
				type : Array,
			},

			cost_center_value_code: {
				default: null,
				type: Number
			},

			department_value_code: {
				default: null,
				type: Number
			},

			expense_purpose_value_code: {
				default: null,
				type: Number
			},
			
			cost_centers: {
				default: [],
				type: Array
			},

			departments: {
				default: [],
				type: Array
			},

			expense_purposes: {
				default: [],
				type: Array
			},
			
			clients: {
				default: [],
				type: Array
			},
			specifications: Array,
			procurements: Array,
			services: Array,
			charges_on_lines: Array,
	        discount_on_lines: Array,
		},

		data() {
			return {
				created_by : null,
				updated_by : null,
				data_table_key : 0,
				item: {
					discount_percentage : 0,
					quantity : 0,
					charge_on_purchase : 0,
					discount : 0,
					amount : 0,
					ledger_account : null,
					cost_center_id : null,
					department_id : null,
					expense_purpose_id : null,
					sale_tax_group : null,
					item_sales_tax_group : null,
					variant : {
						size : null,
						color : null,
						unit_price : 0,
					},

					procurement: {procurement: null},

					product : {
						product_number : null
					}
				},

				sales_order_lines: [],
				service_tasks: [],

				product_number: null,
				editing_key: null,
				editing: false,
				client_name: null,
				filtered_variants: [],
				line_statuses: ['Open Order', 'Recieved', 'Invoiced', 'Canceled'],
				sales_categories: ['Land','Air','Sea'],
			}
		},


        computed: {
            headers() {
                let array = [
                    { text: 'Line #', value: 'line_number' },
                    { text: 'Item #', value: 'product_number' },
                    { text: 'Line Status', value: 'line_status' },
					{ text: 'Product', value: 'name' },
					{ text: 'Variant', value: 'variant' },
                    { text: 'Size', value: 'size' },
                    { text: 'Color', value: 'color' },
                    { text: 'Quanity', value: 'quantity' },
                    { text: 'Unit Price', value: 'unit_price' },
                    { text: 'Amount', value: 'amount' },
                    { text: 'Action', value: null },
                ];

                return array;

            },


            totalQuantity() {
            	var sales_order_lines = this.sales_order_lines;

            	var total = 0;

            	_.each(sales_order_lines, (line) => {
            		total += parseInt(line.quantity);
            	})
            	this.so.total_data_quantity = total;
            	return total;
            },

            subTotalAmount() {
            	var sales_order_lines = this.sales_order_lines;

            	var total = 0;

            	_.each(sales_order_lines, (line) => {
					let variant = line.variant;
            		total += parseInt(line.quantity * parseFloat(variant.unit_price));
            	})

            	this.so.subtotal_amount = total;

            	return total;
            },

            totalDiscount() {
            	var sales_order_lines = this.sales_order_lines;

            	var total = 0;

            	_.each(sales_order_lines, (line) => {
            		total += parseInt(line.discount);
            	})

            	this.so.total_discount = total;

            	return total;
            },

            totalCharges() {
            	var sales_order_lines = this.sales_order_lines;

            	var total = 0;

            	_.each(sales_order_lines, (line) => {
            		total += 100;
            	})

            	this.so.total_charges = total;

            	return total;
            },

            totalSalesTax() {
            	var total = 0;

            	total = this.subTotalAmount * 0.2;

            	this.so.total_sales_tax = total;

            	return total;

            },

            totalRoundOff() {
            	var total = 0;

            	total = this.subTotalAmount - (this.totalDiscount + this.totalCharges + this.totalSalesTax);
            	total = Math.round(total);

            	this.so.total_round_off = total;

            	return total;
            },

            totalAmount() {
            	var total = 0;

            	total = this.subTotalAmount - (this.totalDiscount + this.totalCharges + this.totalSalesTax);

            	this.so.total_amount = total;

            	this.$parent.$parent.$parent.line_total_amount = total;
            	this.$parent.$parent.$parent.item.total_sales_vat_exclusive = total;

            	return total;
            },

            totalCashDiscount() {
            	var sales_order_lines = this.sales_order_lines;

            	var total = 0;

            	_.each(sales_order_lines, (line) => {
            		total += parseInt(line.discount);
            	})

            	this.so.total_cash_discount = total;
            	this.$parent.$parent.$parent.item.less_discount = total;

            	return total;
            },

            disableConfirmButton() {
            	if(this.showConfirmButton) {
	            	return this.item.is_already_confirmed;
            	}

            	return true;
            },

            disableGenerateInvoiceButton() {
            	if(this.showConfirmButton) {
	            	return this.item.hasExistingInvoice;
            	}

            	return true;
            },
        },

		mounted() {
			// default selected value
			this.item.line_status = this.line_statuses[0];
			this.item.sales_category = this.sales_categories[0];
			this.sales_order_lines = this.lines;

			this.generateLineCode('create','SOL');
		},

		watch : {

			'lines'(value) {
				this.sales_order_lines = value;
			},

			'item.product_id'(value) {
				if(value) {
					this.filtered_variants = this.variants.filter(item => item.product_id == value);
					this.item.product = this.products.filter(item => item.id == value)[0];
				}		
			},

			'item.service_id'(value) {
				if(value) {
					this.item.service = this.services.filter(item => item.id == value)[0];
					this.item.variant = {
						unit_price : this.item.service.unit_price,
					};

					this.service_tasks = this.item.service.service_tasks;
				}		
			},

			'item.service_task'(val) {
				var task = _.find(this.service_tasks, (task) => { return task.id === val });

				this.item.service_task_details = task.description;
				this.item.rpm_method = task.rpm_method;
				this.item.number_of_hours = task.base_hour;

	            var service_id = this.item.service_id;
	            var service_task = this.item.service_task;
				var discount = _.find(this.discount_on_lines, (discount) => {
			        return discount.service_id == service_id && discount.service_task_id == service_task; 
				})
				this.item.discount_id = !_.isEmpty(discount) ? discount.id : null;
				this.item.discount_obj = !_.isEmpty(discount) ? discount : null;
				var total_discount = 0;

				if(!_.isEmpty(discount)) {
					console.log('discount : '+ discount)
					switch(discount.discount_category) {
					    case 'Fixed Amount': 
					        total_discount = parseFloat(discount.discount_value);
							console.log('Fixed Amount : '+ total_discount)
					        break;
					    case 'Amount Range':
					        total_discount = parseFloat(discount.from_amount);
							console.log('Amount Range : '+ total_discount)
					        break;
					    case 'Quantity':
					        total_discount = parseFloat(discount.from_quantity);
							console.log('Quantity : '+ total_discount)
					        break;
					    case 'Percentage': 
					        total_discount = parseFloat(discount.discount_percentage)
							console.log('Percentage : '+ total_discount)
					        break;
					}

					this.item.discount = parseFloat(total_discount);
					this.item.cash_discount = parseFloat(total_discount);
				}
			},

			
			'item.variant_id'(value) {
				if(value) {
					this.item.variant = this.variants.filter(item => item.id == value)[0];
					this.item.procurement_number = this.item.variant.procurement ? this.item.variant.procurement.procurement : null;
					this.item.procurement_id = this.item.variant.procurement ? this.item.variant.procurement.id : null;
				}else {
					this.item.variant = {
						size : null,
						color : null,
						unit_price : 0,
					};
					this.item.amount = 0;
					this.item.discount = 0;
				}

				var product_id = this.item.product_id;
				var variant_id = this.item.variant_id;
				var discount = _.find(this.discount_on_lines, (discount) => {
			        return discount.product_id == product_id && discount.variant_id == variant_id; 
				})
				this.item.discount_id = !_.isEmpty(discount) ? discount.id : null;
				this.item.discount_obj = !_.isEmpty(discount) ? discount : null;
				var total_discount = 0;

				if(!_.isEmpty(discount)) {
					console.log('discount : '+ discount)
					switch(discount.discount_category) {
					    case 'Fixed Amount': 
					        total_discount = parseFloat(discount.discount_value);
							console.log('Fixed Amount : '+ total_discount)
					        break;
					    case 'Amount Range':
					        total_discount = parseFloat(discount.from_amount);
							console.log('Amount Range : '+ total_discount)
					        break;
					    case 'Quantity':
					        total_discount = parseFloat(discount.from_quantity);
							console.log('Quantity : '+ total_discount)
					        break;
					    case 'Percentage': 
					        total_discount = parseFloat(discount.discount_percentage)
							console.log('Percentage : '+ total_discount)
					        break;
					}

					this.item.discount = total_discount;
				}


				this.renderAmount(this.item);
			},

			'item.discount_percentage'(value) {
				if(value <= 100 && value >= 0) {
					this.item.discount_percentage = value;
					this.renderAmount(this.item);
				}else {
					this.item.discount = 0;
					this.item.discount = 0;
					this.item.discount_percentage = 0;
				}
			},

			'item.quantity'(value) {
				this.renderAmount(this.item);
			},

			'item.unit_price'() {
				this.renderAmount(this.item);
			},
			
			'item.charge_id'(value) {
				var charge = _.find(this.charges_on_lines, (charge) => { return charge.id == value });

				this.$parent.$parent.$parent.item.add_charge += parseFloat(charge.charge_value);


				// this.item.product_id = charge.product_id;

				// this.item.procurement_id = charge.procurement_id;
				// this.item.service_id = charge.service_id;

				// this.item.service = _.find(this.services, (service) => { return service.id == charge.service_id });
				// this.service_tasks = this.item.service.service_tasks;

				// this.item.variant_id = charge.variant_id;

				// this.item.service_task = charge.service_task_id;
				// var task = _.find(this.service_tasks, (task) => { return task.id === charge.service_task_id });

				// this.item.service_task_details = task.description;
				// this.item.rpm_method = task.rpm_method;
				// this.item.number_of_hours = task.base_hour;
			},

			'item.less_discount'(val) {
				var less = val / 100;
				this.item.cash_discount = less * this.item.amount; 
			},

			'item.add_charge'(val) {
				var charge = val / 100;
				this.item.charge = charge * this.item.amount; 
			},

			'item.add_fee'(val) {
				var add_fee = val / 100;
				this.item.fee = add_fee * this.item.amount; 
			},
			
		},

		methods: {

			addLine() {
				if(this.validateRequiredFields()) {
					this.item.is_new = true;
					this.sales_order_lines.push(this.item);

					this.$emit('newLines',this.sales_order_lines);
					this.initDefaultValue();

					$('#so-lines-modal').modal('hide');
				}
			},

			removeLine(key) {
				this.sales_order_lines.splice(key, 1);
				this.$emit('newLines',this.sales_order_lines);
			},

			generateLineCode(value, prefix) {
				if(value == 'create') {
					var date = new Date();
					var time = Math.round(date.getTime() / 1000);	
					this.item.sales_order_return_line_number = prefix + '-' + date.getFullYear().toString() + ("0" + (date.getMonth() + 1)).slice(-2) + date.getDate().toString()   +'-'+ time.toString();
				}
			},

			computedAmount(item) {
				return 'total';
			},

			renderAmount(item) {
				if(item.quantity > 0 && item.variant.unit_price > 0) {
					let discount = item.discount_percentage;
					let amount = 0;
					let total = item.quantity * item.variant.unit_price;
					if(discount) {
						discount = (discount / 100) * total;
						// this.item.discount =  parseFloat(discount).toFixed(2);
						amount = total - discount;	
					}else {
						// this.item.discount = 0.00;
						amount = item.quantity * item.variant.unit_price;
					}
					this.item.amount = parseFloat(amount).toFixed(2);
				}else {
					this.item.amount = 0.00;
				}
			},

			editLine(key) {
				this.item = Object.assign({}, this.sales_order_lines[key]);
				// console.log(item, 'item');
				this.editing_key = key;
				this.editing = true;
				$('#so-lines-modal').modal('show');
			},

			saveLine() {
				if(this.validateRequiredFields()) {
					$('#so-lines-modal').modal('hide');
					this.sales_order_lines[this.editing_key] = this.item;
					this.editing_key = null;
					this.editing = false;
					this.initDefaultValue();
					this.$emit('newLines',this.sales_order_lines);
					this.generateLineCode('create', 'SOL');
					$('#so-lines-modal').modal('hide');
					this.changeKey();
				}
			},

			setClientName(client_id) {
				let client = this.clients.find(client => client.id == client_id);
				if (client) {
					this.client_name = client.name;
				}
			},

			newLine() {
				var date = new Date();
				var time = Math.round(date.getTime() / 1000);	
				var line_number = date.getDate().toString() + date.getMonth().toString() + date.getFullYear().toString() +'-'+ time.toString();
				line_number += "-" + Math.random().toString(36).substring(2, 6);

				this.initDefaultValue();
				
				this.setClientName(this.so.client_id);

				this.item.cost_center_id = this.cost_center_value_code;
				this.item.department_id = this.department_value_code;
				this.item.expense_purpose_id = this.expense_purpose_value_code;

				this.$nextTick(() => {
					$('#so-lines-modal').modal('toggle');
				})
			},

			initDefaultValue() {
				this.item = {
					discount_percentage : 0,
					quantity : 0,
					amount : 0,
					charge_on_purchase : 0,
					discount : 0,
					line_status: this.line_statuses[0],
					sales_category: this.sales_categories[0],
					cost_center_id : null,
					department_id : null,
					expense_purpose_id : null,
					variant : {},
					product : {},
				};

				this.generateLineCode('create','SOL');
				
				this.product_number = null;
				this.filtered_variants = [];
			},

			validateRequiredFields() {
				// if(!this.item.line_status || !this.item.sales_category || !this.item.variant_id || !this.item.product_id || !this.item.department_id || !this.item.expense_purpose_id || !this.item.cost_center_id)  
				if(!this.item.line_status || !this.item.sales_category || !this.item.department_id || !this.item.expense_purpose_id || !this.item.cost_center_id)  
				{
					swal.fire({
					icon: 'error',
					title: 'Required Fields',
					text: 'Please fill up all the required fields!',
					});

					return false;
				}
				return true
			},
			
			clearVariant() {
				this.$refs['variant'].clearSelection();
			},

			changeKey() {
				this.data_table_key++
			}
		}
	}
</script>