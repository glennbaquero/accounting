<template>
	<div>
		<!-- <div class="row">
			<div class="col-md-12 text-right">
				<button type="button" @click="newLine" class="btn btn-success w-10" data-toggle="modal" data-target="#po-lines-modal" data-backdrop="static"><i class="fas fa-plus"></i> Create Invoice Line</button>
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

			<div class="col-md-4">
				<div class="form-group">
					<label>Subtotal Amount</label>
					<input name="subtotal_amount" :value="subTotalAmount | currency" type="text"  readonly class="form-control mb-2">
					<label>Total Discount</label>
					<input name="total_discount" :value="totalDiscount | currency" type="text"  readonly class="form-control mb-2">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Total Round Off</label>
					<input name="total_round_off" :value="totalRoundOff | currency" type="text"  readonly class="form-control mb-2">
					<label>Cash Discount</label>
					<input name="cash_discount" :value="totalCashDiscount | currency" type="text"  readonly class="form-control mb-2">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label> Total Charges</label>
					<input name="total_charges"  type="text" :value="totalCharges | currency" readonly class="form-control mb-2">
					<label>Total Sales Tax</label>
					<input name="total_sales_tax" type="text" :value="totalSalesTax | currency" readonly class="form-control mb-2">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Tax Posting</label>
					<select class="form-control mb-2">
						<option></option>
					</select>
				</div>
			</div>
		</div> -->

		<div class="row">
			<div class="col-md-12">	
				<data-table 
					:key="data_table_key"
					ref="data-table"
					:headers="headers" 
					:items="vendor_invoice_lines"
				>
						<template v-slot:body="{ items }">
						<tr v-for="(table_item, key) in items" v-bind:key="key">
							<td>{{ table_item.purchase_delivery_receipt_line_number }}</td>
							<td>{{ table_item.product.product_number }}</td>
							<td>{{ table_item.line_status }}</td>
							<td>{{ table_item.product.name }}</td>
							<td>{{ table_item.variant.name }}</td>
							<td>{{ table_item.variant.size }}</td>
							<td>{{ table_item.variant.color }}</td>
							<td>{{ table_item.quantity }}</td>
							<td>{{ table_item.variant.unit_price | currency }}</td>
							<td>{{ computeSubTotal(table_item) | currency }}</td>
							<td>{{ table_item.charge_on_purchase | currency }}</td>
							<td>{{ table_item.discount | currency }}</td>
							<td>{{ computeTotalAmount(table_item) | currency }}</td>

							<td>
								<template v-if="table_item.id">
									<template v-if="showConfirmButton">
										<action-button
										small 
										color="btn-success"
										alt-color="btn-danger"
										:show-alt="table_item.approved_on && !table_item.rejected_on"
										:action-url="table_item.approveUrl"
										:alt-action-url="table_item.rejectUrl"
										icon="fas fa-check"
										alt-icon="fas fa-times"
										confirm-dialog
										:disabled="loading"
										title="Approve Line"
										alt-title=""
										:message="'Are you sure you want to approved this line ' + table_item.purchase_delivery_receipt_line_number + '?'"
										:alt-message="'Are you sure you want to reject this line ' + table_item.purchase_delivery_receipt_line_number + '?'"
										@load="load"
										@success="$emit('success')"
										></action-button>

										<span v-if="!table_item.approved_on && !table_item.rejected_on">
											<action-button
											small 
											color="btn-success"
											alt-color="btn-danger"
											:show-alt="!table_item.rejected_on"
											:action-url="table_item.approveUrl"
											:alt-action-url="table_item.rejectUrl"
											icon="fas fa-check"
											alt-icon="fas fa-times"
											confirm-dialog
											:disabled="loading"
											title="Approve Line"
											alt-title=""
											:message="'Are you sure you want to approved this line ' + table_item.purchase_delivery_receipt_line_number + '?'"
											:alt-message="'Are you sure you want to reject this line ' + table_item.purchase_delivery_receipt_line_number + '?'"
											@load="load"
											@success="$emit('success')"
											></action-button>
										</span>
									</template>
								</template>


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
                
		<div class="modal fade"  id="po-lines-modal">
			<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Vendor Invoice Line</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<h4><i class="fas fa-info-circle"></i> Details</h4><hr>
									<label>Client</label>
									<input readonly class="form-control mb-2" v-model="client_name">
									<label>Vendor Invoice Line Number</label>
									<input readonly class="form-control mb-2" v-model="item.purchase_delivery_receipt_line_number">
									<label>Vendor Account</label>
									<input readonly v-model="item.vendor_account" class="form-control mb-2">
									<label>Invoice Account</label>
									<input readonly v-model="item.invoice_account" class="form-control mb-2">
									<label>Vendor Name</label>
									<input readonly v-model="item.vendor_name" type="text" class="form-control mb-2">

									<h4><i class="fas fa-truck"></i> Delivery</h4><hr>
									<!-- Inherit from parent -->
									<label>Delivery Date</label>
									<input readonly v-model="item.delivery_date" class="form-control mb-2">
									<label>Delivery Type</label>
									<input readonly v-model="item.mode_of_delivery_type" class="form-control mb-2">

									<h4><i class=""></i> Related</h4>
									<label>Vouchers</label>
									<input readonly v-model="item.vouchers" type="text" class="form-control mb-2">
									<label>Purchase Order Number</label>
									<input readonly class="form-control mb-2" v-model="item.purchase_order_number">

									<h4>&zwnj;</h4><hr>
									
									<h4 class="mt-4"><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
									<label>Cost Center <b class="text-danger">*</b></label>
									<v-select class="mb-2" v-model="item.cost_center_id" :reduce="item => item.id" label="dimension_name" :options="cost_centers"></v-select>
									<label>Department <b class="text-danger">*</b></label>
									<v-select class="mb-2" v-model="item.department_id" :reduce="item => item.id" label="dimension_name" :options="departments"></v-select>
									<label>Expense Purpose <b class="text-danger">*</b></label>
									<v-select class="mb-2" v-model="item.expense_purpose_id" :reduce="item => item.id" label="dimension_name" :options="expense_purposes"></v-select>
					
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
									
									<h4>Status</h4><hr>
									<label>Line Status</label>
									<input readonly class="form-control mb-2" v-model="item.line_status">

									<h4><i class="fas fa-money-bill"></i> Ledger</h4><hr>
									<label>Subledger Journal</label>
									<input  v-model="item.subledger_journal" class="form-control mb-2">
									<label>Ledger Account</label>
									<input v-model="item.ledger_account" class="form-control mb-2">

									<label>Receive Now Quantity</label>
									<input type="number" v-model="item.receive_now_quantity" class="form-control mb-2">

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
									<v-select class="mb-2" v-model="item.service_id" :reduce="item => item.id" label="name" :resetOnOptionsChange="true" placeholder="Select Service" :options="services" :disabled="item.order_line_type === 'product'"></v-select>
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
									<h4><i class="fas fa-cubes"></i> Item</h4><hr>
									<label>Product</label>
									<v-select @input="clearVariant" class="mb-2" v-model="item.product_id" :reduce="item => item.id" label="name" :resetOnOptionsChange="true" :options="products" :disabled="item.order_line_type === 'services'"></v-select>

									<label>Product Number</label>
									<input readonly class="form-control mb-2" :value="item.product.product_number" :readonly="item.order_line_type === 'services'">

									<label>Variant</label>
									<v-select ref="variant" class="mb-2" v-model="item.variant_id" :reduce="item => item.id" label="name" :resetOnOptionsChange="!editing" :options="filtered_variants" :disabled="item.order_line_type === 'services'"></v-select>

									<label>Variant Number</label>
									<input readonly class="form-control mb-2" :value="item.variant.variant_number">
									
									<label>Specification <b class="text-danger">*</b></label>
									<v-select class="mb-2" v-model="item.specification_id" :reduce="item => item.id" label="specification_name" placeholder="Select Specification" :options="specifications"></v-select>

									<label>Procurement Category</label>
									<select ref="procurement_category" v-model="item.procurement_category" class="form-control mb-2">
										<option value="Air">Air</option>
										<option value="Sea">Sea</option>
										<option value="Land">Land</option>
									</select>

									<label>Size</label>
									<input v-model="item.size"  maxlength="8" type="tel" class="form-control mb-2" :readonly="item.order_line_type === 'services'">

									<label>Color</label>
									<input v-model="item.color" maxlength="8" type="tel" class="form-control mb-2" :readonly="item.order_line_type === 'services'">

									<h4>&zwnj;</h4><hr>
									<label>Other Account</label>
									<v-select class="mb-2" v-model="item.procurement_id" placeholder="Select Procurement"  :reduce="item => item.id" label="main_account_name" :options="procurements"></v-select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<h4>&zwnj;</h4><hr>
									<!-- <label>Description</label>
									<input v-model="item.description" type="tel" maxlength="5" class="form-control mb-2"> -->
									<label>Unit Price</label>
									<input @input="renderAmount(item)" v-model="item.unit_price" maxlength="8" type="tel" class="form-control mb-2">
									<label>Quantity <b class="text-danger">*</b></label>
									<input @input="renderAmount(item)" v-model="item.quantity" type="tel" maxlength="5" class="form-control mb-2">
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
									<!-- <label>Discount</label>
									<input @input="calculateGivenDiscount" v-model="item.discount" type="tel" class="form-control mb-2">
									<label>Discount Percentage</label>
									<div class="input-group mb-2">
									<input @input="calculatePercentDiscount" v-model="item.discount_percentage" type="tel" maxlength="4" class="form-control">
										<div class="input-group-append">
											<span class="input-group-text">%</span>
										</div>
									</div>
									<label>Charges on Purchases</label>
									<input v-model="item.charge_on_purchase" type="number" class="form-control mb-2"> -->
									
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
									<input readonly  v-model="item.creator" class="form-control mb-2">
									<label>Created On</label>
									<input readonly :value="item.formatted_created_date" class="form-control mb-2">
									<label>Updated By</label>
									<input readonly :value="item.updater"  class="form-control mb-2">
									<label>Updated On</label>
									<input readonly :value="item.formatted_updated_date" class="form-control mb-2">
								</div>
							</div>
				
				
						</div>

					</div>
					<div class="modal-footer text-right">
						<button v-if="editing" type="button" class="btn btn-primary" @click="saveLine">Save Changes</button>
						<button v-if="!editing" type="button" class="btn btn-primary" @click="addLine">Add Line</button>
					</div>
				</div>
			</div>
			-</form-request>
		</div>
	</div>
</template>

<script>
	import { bus }from 'Root/bus.js';
	import CrudMixin from 'Mixins/crud.js';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import DataTable from 'Components/tables/DataTable.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import Vselect from "vue-select";

	export default {

		mixins: [ CrudMixin ],
		
		components: {
			'action-button': ActionButton,
			'form-request': FormRequest,
			'data-table': DataTable,
			'v-select' : Vselect,
		},

		props: {
			products: {
				default: [],
				type: Array
			},

			variants: {
				default() { return [] },
				type: Array
			},

			vendors: {
				default() { return [] },
				type: Array
			},

			clients: {
				default() { return [] },
				type: Array
			},
			
			// vendor invoice
			vi : {
				default : {},
				type : Object
			},

			lines : {
				default : [],
				type : Array,
			},

			showConfirmButton: {
				default: false,
				type: Boolean
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
			specifications: Array,
			services: Array,
			procurements: Array,
			charges_on_lines: Array,
			discount_on_lines: Array,
		},

		data() {
			return {
				created_by : null,
				updated_by : null,
				item: {
					amount: 0,
					charge_on_pruchase: null,
					color: null,
					cost_center: null,
					department: null,
					discount: null,
					discount_percentage: null,
					expense_purpose: null,
					is_new: true,
					item_sales_tax_group: null,
					ledger_account: null,
					line_status: null,
					procurement_category: null,
					quantity: 0,
					sale_tax_group: null,
					size: null,
					subledger_journal: null,
					unit: null,
					unit_price: null,
					order_line_type: 'product',
					product: {},
					variant: {},
					service: {},
				},

				vendor_invoice_lines: [],
				service_tasks: [],

				item_number: null,
				editing_key: null,
				editing: false,

				client_name: null,
				filtered_variants: [],
				line_statuses: ['Pending', 'Rejected', 'Approved'],
				sales_categories: ['Land','Air','Sea'],
				data_table_key: 0
			}
		},


        computed: {
            headers() {
                let array = [
					{ text: 'Line #', value: 'line_number' },
					{ text: 'Item #', value: 'item_number' },
					{ text: 'Line Status', value: 'line_status' },
					{ text: 'Product', value: 'name' },
					{ text: 'Variant', value: 'variant' },
					{ text: 'Size', value: 'size' },
					{ text: 'Color', value: 'color' },
					{ text: 'Quantity', value: 'quantity' },
					{ text: 'Unit Price', value: 'unit_price' },
					{ text: 'SubTotal', value: 'sub_total' },
					{ text: 'COP', value: 'charge_on_purchase' },
					{ text: 'Discount', value: 'discount' },
					{ text: 'Amount', value: 'amount' },
					{ text: 'Action', value: null },
                ];

                return array;
            },

            totalQuantity() {
            	var vendor_invoice_lines = this.vendor_invoice_lines;

            	var total = 0;

            	_.each(vendor_invoice_lines, (line) => {
            		total += parseInt(line.quantity ? line.quantity : 0);
            	})
            	this.vi.total_data_quantity = total;
            	return total;
            },

            subTotalAmount() {
            	var vendor_invoice_lines = this.vendor_invoice_lines;

            	var total = 0;

            	_.each(vendor_invoice_lines, (line) => {
					let quantity = line.quantity || 0;
					let unit_price = line.unit_price || 0;
            		total += parseFloat(quantity).toFixed(2) * parseFloat(unit_price).toFixed(2)
            	});

            	this.vi.subtotal_amount = total;

            	return total;
            },

            totalDiscount() {
            	var vendor_invoice_lines = this.vendor_invoice_lines;

            	var total = 0;

            	_.each(vendor_invoice_lines, (line) => {
            		total += parseInt(line.discount);
            	})

            	this.vi.total_discount = total;

            	return total;
            },

            totalCharges() {
            	var vendor_invoice_lines = this.vendor_invoice_lines;

            	var total = 0;

            	_.each(vendor_invoice_lines, (line) => {
            		total += parseFloat(line.charge_on_purchase);
            	})

            	this.vi.total_charges = total;

            	return total;
            },

            totalSalesTax() {
            	var total = 0;
				
				this.vi.total_sales_tax = parseFloat(total).toFixed(2);
            	return parseFloat(total).toFixed(2);

            },

            totalRoundOff() {
				var total = 0;
            	total = Math.round(this.totalAmount);
            	
            	this.vi.total_round_off = total;

            	return total;
            },

            totalAmount() {
            	var total = 0;
				let sale = this.totalSalesTax;
				let charge =  this.totalCharges;
				let total_discount = this.totalDiscount;
				
            	total = parseFloat(this.subTotalAmount) + parseFloat(charge) - (parseFloat(sale)  +  parseFloat(total_discount));

            	this.vi.total_amount = total;
            	return parseFloat(total).toFixed(2);
            },

            totalCashDiscount() {
            	this.vi.cash_discount = 0;
            	this.vi.total_line_discount = 0;
            	this.vi.total_discount_group = 0;
            	this.vi.total_discount_percentage = 0;
            	this.vi.total_cash_discount = 0;

            	return 0;
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
			this.generateLineCode('create', 'VIL');
		},

		watch : {
			'vi.purchase_order_number'(value) {
				this.vendor_invoice_lines.forEach(line => line.purchase_order_number = value);
				this.changeKey();
			},

			'vi.vendor_account'(vendor_account) {
				let vendor = Object.assign({}, this.vendors.find(vendor => vendor.vendor_account == vendor_account));
				this.vendor_invoice_lines.forEach(line => {
					line.vendor_account = vendor_account;
					line.vendor_name = vendor.fullname;
					line.vendor_contact_id = vendor.phone;
					line.vendor_address = vendor.address;
					line.invoice_account = vendor_account;
				});
				this.changeKey();
			},

			'lines'(value) {
				if (value) {
					if (! this.vendor_invoice_lines) {
						this.vendor_invoice_lines = [];
					}
					value.forEach(line => {
						const index = this.vendor_invoice_lines.findIndex(vline => vline.purchase_delivery_receipt_line_number == line.purchase_delivery_receipt_line_number);
						if (index != -1) {
							this.vendor_invoice_lines[index] = line;
						} else {
							this.vendor_invoice_lines.push(line);
						}
						this.changeKey();
					});
					
					this.$emit('newLines',this.vendor_invoice_lines);
				}
			},

			'vi.delivery_date'(value) {
				this.vendor_invoice_lines.forEach(line => {
					line.delivery_date = value;
				});
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
					this.item.unit_price = this.item.service.unit_price;
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
					switch(discount.discount_category) {
					    case 'Fixed Amount': 
					        total_discount = discount.discount_value;
					        break;
					    case 'Amount Range':
					        total_discount = discount.from_amount;
					        break;
					    case 'Quantity':
					        total_discount = discount.from_quantity;
					        break;
					    case 'Percentage': 
					        total_discount = discount.discount_percentage
					        break;
					}
				}

				this.item.discount = !_.isEmpty(discount) ? parseFloat(total_discount) : 0;
			},

			'item.variant_id'(value) {
				if(value) {
					this.item.variant = this.variants.filter(item => item.id == value)[0];
					if (this.item.variant) {
						this.item.size = this.item.variant.size;
						this.item.color = this.item.variant.color;
						this.item.unit_price = this.item.variant.unit_price;
					}
				}else {
					this.item.variant = {
						size : null,
						color : null,
						unit_price : 0,
					};

					this.item.size = null;
					this.item.color = null;
					this.item.unit_price = 0;
					this.item.quantity = 0;
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
					switch(discount.discount_category) {
					    case 'Fixed Amount': 
					        total_discount = discount.discount_value;
					        break;
					    case 'Amount Range':
					        total_discount = discount.from_amount;
					        break;
					    case 'Quantity':
					        total_discount = discount.from_quantity;
					        break;
					    case 'Percentage': 
					        total_discount = discount.discount_percentage
					        break;
					}
				}

				this.item.discount = !_.isEmpty(discount) ? parseFloat(total_discount) : 0;

				// this.renderAmount(this.item);
			},

			'vi.created_by'(value) {
				if(value) {
					this.created_by = value.fullname;
				}
			},

			'vi.updated_by'(value) {
				if(value) {
					this.updated_by = value.fullname;
				}
			},

			'item.charge_id'(value) {
				var charge = _.find(this.charges_on_lines, (charge) => { return charge.id == value });

				this.item.product_id = charge.product_id;

				this.item.procurement_id = charge.procurement_id;
				this.item.service_id = charge.service_id;

				this.item.service = _.find(this.services, (service) => { return service.id == charge.service_id });
				this.service_tasks = this.item.service.service_tasks;

				this.item.variant_id = charge.variant_id;

				this.item.service_task = charge.service_task_id;
				var task = _.find(this.service_tasks, (task) => { return task.id === charge.service_task_id });

				this.item.service_task_details = task.description;
				this.item.rpm_method = task.rpm_method;
				this.item.number_of_hours = task.base_hour;
			},


			'item.less_discount'(val) {
				var less = val / 100;
				var total = less * this.item.amount; 
				this.item.cash_discount = Math.round((total + Number.EPSILON) * 100) / 100
			},

			'item.add_charge'(val) {
				var charge = val / 100;
				var total = charge * this.item.amount; 
				this.item.charge =  Math.round((total + Number.EPSILON) * 100) / 100
			},

			'item.add_fee'(val) {
				var add_fee = val / 100;
				var total = add_fee * this.item.amount; 
				this.item.fee =  Math.round((total + Number.EPSILON) * 100) / 100
			},

		},

		methods: {
			newLine() {
				this.initDefaultValue();
				
				this.setClientName(this.vi.client_id);

				this.item.cost_center_id = this.cost_center_value_code;
				this.item.department_id = this.department_value_code;
				this.item.expense_purpose_id = this.expense_purpose_value_code;

				this.$nextTick(() => {
					$('#po-lines-modal').modal('toggle');
				})
			},

			addLine() {
				if(this.validateRequiredFields()) {
					$('#po-lines-modal').modal('hide');
					this.item.is_new = true;
					this.vendor_invoice_lines.push(this.item);
					this.initDefaultValue();
					this.$emit('newLines',this.vendor_invoice_lines);
					this.generateLineCode('create', 'VIL');
				}
			},

			removeLine(key) {
				this.vendor_invoice_lines.splice(key, 1);
				this.$emit('newLines',this.vendor_invoice_lines);
			},

			generateLineCode(value, prefix) {
				if(value == 'create') {
					var date = new Date();
					var time = Math.round(date.getTime() / 1000);	
					this.item.purchase_delivery_receipt_line_number = prefix + '-' + date.getFullYear().toString() + ("0" + (date.getMonth() + 1)).slice(-2) + date.getDate().toString()   +'-'+ time.toString();
				}
			},

			computedAmount(item) {
				return 'total';
			},

			renderAmount(item) {
				if(item.quantity > 0 && item.unit_price > 0) {
					let discount = item.discount_percentage;
					let amount = 0;
					let total = item.quantity * item.unit_price;
					if(discount) {
						discount = (discount / 100) * total;
						this.item.discount =  parseFloat(discount).toFixed(2);
						amount = total - discount;	
					}else {
						this.item.discount = 0.00;
						amount = item.quantity * item.unit_price;
					}
					this.item.amount = parseFloat(amount).toFixed(2);
				}else {
					this.item.amount = 0.00;
				}
			},

			editLine(key) {
				this.item = Object.assign({}, this.vendor_invoice_lines[key]);
				this.editing_key = key;
				this.editing = true;
				this.setClientName(this.item.client_id);
				$('#po-lines-modal').modal('show');
			},

			saveLine() {
				if(this.validateRequiredFields()) {
					$('#po-lines-modal').modal('hide');
					this.vendor_invoice_lines[this.editing_key] = Object.assign({}, this.item);
					this.editing_key = null;
					this.editing = false;
					this.initDefaultValue();
					this.$emit('newLines',this.vendor_invoice_lines);
					this.generateLineCode('create', 'VIL');
					$('#po-lines-modal').modal('hide');
					this.changeKey();
				}
			
			},

			validateRequiredFields() {
				const quantity = this.item.quantity || 0;
				const unit_price = this.item.unit_price || 0;

				// if(!this.item.procurement_category || quantity < 1 || unit_price < 1 ||
				// 	!this.item.cost_center_id || !this.item.department_id || 
				// 	!this.item.expense_purpose_id) {
				if(!this.item.procurement_category || unit_price < 1 ||
					!this.item.cost_center_id || !this.item.department_id || 
					!this.item.expense_purpose_id) {
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
			},

			setClientName(client_id) {
				let client = this.clients.find(client => client.id == client_id);
				if (client) {
					this.client_name = client.name;
				}
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
					cost_center_id: null,
					department_id: null,
					expense_purpose_id: null,
					procurement_category: 'Land',
					purchase_order_number: this.vi.purchase_order_number,

					vendor_account: this.vi.invoice_account,
					purchase_invoice_number: this.vi.purchase_invoice_number,
					purchase_account: this.vi.purchase_account,
					invoice_account: this.vi.invoice_account,
					vendor_name: this.vi.vendor_name,

					variant : {},
					product : {},
					service: {},

					order_line_type: 'product'
				};

				this.generateLineCode('create','VIL');
				
				this.product_number = null;
				this.filtered_variants = [];
			},

			calculateGivenDiscount() {
				const amount = this.item.quantity * this.item.unit_price;
				if (this.item.discount > 0) {
					this.item.amount = amount - this.item.discount;
				} else {
					this.item.amount = amount;
				}
			},
			
			calculatePercentDiscount() {
				if(this.item.discount_percentage <= 100 && this.item.discount_percentage >= 0) {
					this.renderAmount(this.item);
				}else {
					this.item.discount = 0;
					this.item.discount_percentage = 0;
				}
			},

			computeTotalAmount(item) {
				let amount = parseFloat(item.amount) + parseFloat(item.charge_on_purchase ? item.charge_on_purchase : 0);
				return amount;
			},
			computeSubTotal(item) {
				let amount = parseFloat(item.unit_price) * parseFloat(item.quantity);
				return amount;
			}
		}
	}
</script>