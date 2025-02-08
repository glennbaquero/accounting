<template>
	<div>
		
		<div class="row">
			<div class="col-md-12 text-right">
				<button type="button" @click="newLine" class="btn btn-success w-10" data-toggle="modal" data-target="#cs-lines-modal" data-backdrop="static"><i class="fas fa-plus"></i> Create Customer Summary Line</button>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">	
				<data-table 
					:key="data_table_key"
					ref="data-table"
					:headers="headers" 
					:items="customer_summary_lines"
				>
					<template v-slot:body="{ items }">
						<tr v-for="(table_item, key) in items" v-bind:key="key">
							<td>{{ table_item.transaction_date }}</td>
							<td>{{ table_item.due_date }}</td>
							<td>{{ table_item.transation_number }}</td>
							<td>{{ table_item.transation_type }}</td>
							<td>{{ table_item.invoice_status }}</td>
							<td>{{ table_item.payment_status }}</td>
							<td>{{ table_item.pdc_status }}</td>
							<td>{{ table_item.transaction_status }}</td>
							<td>{{ table_item.amount_inclusive_tax | currency }}</td>
							<td>{{ table_item.payments }}</td>
							<td>{{ table_item.outstanding }}</td>
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
										:message="'Are you sure you want to approved this line ' + table_item.vendor_invoice_line_number + '?'"
										:alt-message="'Are you sure you want to reject this line ' + table_item.vendor_invoice_line_number + '?'"
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
											:message="'Are you sure you want to approved this line ' + table_item.vendor_invoice_line_number + '?'"
											:alt-message="'Are you sure you want to reject this line ' + table_item.vendor_invoice_line_number + '?'"
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
                
		<div class="modal fade"  id="cs-lines-modal">
			<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Customer Summary Line</h5>
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
									<input readonly class="form-control mb-2" v-model="item.vendor_invoice_line_number">
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
			
			// vendor invoice
			customer_summary : {
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
						
			method_of_payments: Array,
			terms_of_payments: Array,
		},

		data() {
			return {
				created_by : null,
				updated_by : null,
				item: {
					customer_summary_id: 0,
					transaction_date: null,
					due_date: null,
					transation_number: null,
					transation_type: null,
					method_of_payment_id: null,
					terms_of_payment_id: null,
					invoice_status: null,
					payment_status: true,
					pdc_status: null,
					transaction_status: null,
					amount_inclusive_tax: 0,
					payments: null,
					outstanding: null,
				},

				customer_summary_lines: [],
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
					{ text: 'Transaction Date', value: 'transaction_date' },
					{ text: 'Due Date', value: 'due_date' },
					{ text: 'Transaction Number', value: 'transation_number' },
					{ text: 'Transaction Type', value: 'transation_type' },
					{ text: 'Invoice Status', value: 'invoice_status' },
					{ text: 'Payment Status', value: 'payment_status' },
					{ text: 'PDC Status', value: 'pdc_status' },
					{ text: 'Transaction Status', value: 'transaction_status' },
					{ text: 'Amount Inclusive Tax', value: 'amount_inclusive_tax' },
					{ text: 'Payments', value: 'payments' },
					{ text: 'Outstanding', value: 'outstanding' },
					{ text: 'Action', value: null },
                ];

                return array;
            },
        },
        methods: {
			newLine() {
				
				this.item.cost_center_id = this.cost_center_value_code;
				this.item.department_id = this.department_value_code;
				this.item.expense_purpose_id = this.expense_purpose_value_code;

				this.$nextTick(() => {
					$('#cs-lines-modal').modal('toggle');
				})
			},

			addLine() {
				if(this.validateRequiredFields()) {
					$('#cs-lines-modal').modal('hide');
					this.item.is_new = true;
					this.initDefaultValue();
					this.$emit('newLines',this.customer_summary_lines);
					this.generateLineCode('create', 'VIL');
				}
			},

			removeLine(key) {
				this.customer_summary_lines.splice(key, 1);
				this.$emit('newLines',this.customer_summary_lines);
			},
		}
	}

</script>