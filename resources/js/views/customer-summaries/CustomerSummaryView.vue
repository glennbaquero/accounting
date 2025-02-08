<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>
					Customer Summary Information
					<div class="float-right">
						<!-- :disabled="loading || item.approved_by" -->
						<action-button type="submit"  class="btn-primary btn-sm">Save Changes</action-button>
						<button type="button" class="btn btn-success btn-sm" @click="confirmedThisInvoice" :disabled="item.approved || !item.id">Approve Summary</button>
					</div>
				</template>
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#information" data-toggle="tab">Customer Summary</a></li>
									<li class="nav-item"><a class="nav-link" href="#lines" data-toggle="tab">Lines</a></li>
								</ul>
							</div>
						</div>
					</div>
				    <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="information">
				        		<div class="row">	
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-file-invoice-dollar"></i> Customer Summary</h4><hr>
										</div>
										<label>Customer Summary ID</label>
										<input readonly class="form-control mb-2" v-model="item.customer_summary_id">
									  
										<label>Summary As Of</label>
										<input type="text" class="form-control mb-2" name="summary_as_of" v-model="item.summary_as_of" >

										<label>Issue Date From<b class="text-danger">*</b></label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input ref="issue_date_from" type="text" class="form-control calendar-form" name="issue_date_from" v-model="item.issue_date_from">
										</div>

										<label>Issue Date To<b class="text-danger">*</b></label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input ref="issue_date_to" type="text" class="form-control calendar-form" name="issue_date_to" v-model="item.issue_date_to">
										</div>

				                        <div class="form-group">
				                            <label for="prepared_by">Prepared By</label>
											<v-select class="mb-2" 
												v-model="item.prepared_by" 
												:options="users"
												:reduce="item => item.id"
												placeholder="Select User"
												label="fullname">
											</v-select>
				                        </div>

										<div class="form-group">
											<h4><i class="far fa-question-circle"></i> Status</h4><hr>

											<label>Approved</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input disabled type="checkbox" class="custom-control-input" id="approve_invoice" v-model="item.approved_invoice_checkbox">
												<label class="custom-control-label" for="approve_invoice">
													<span class="badge" :class="item.approved_date ? 'badge-success' : 'badge-danger'">
														{{ item.approved_date ? 'Approved' : 'Pending'  }}
													</span>
												</label>
											</div>
											<label>Approved Date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input  type="text" class="form-control" v-model="item.approved_date" readonly>
											</div>
											<label>Approved By</label>
											<input  type="text" class="form-control mb-2" v-model="item.approved_by_fullname" readonly>
										</div>
												
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="far fa-question-circle"></i> Totals</h4><hr>
											<div class="form-group">
												<label for="item.invoice_status">Opening Balance <b class="text-danger">*</b></label>
												<input  type="number" step="any" class="form-control mb-2" v-model="item.opening_balance" name="opening_balance">
												<label for="item.invoice_status">Invoice Amount <b class="text-danger">*</b></label>
												<input  type="number" step="any" class="form-control mb-2" v-model="item.invoice_amount" name="invoice_amount">
												<label for="item.invoice_status">Amount Paid <b class="text-danger">*</b></label>
												<input  type="number" step="any" class="form-control mb-2" v-model="item.amount_paid" name="amount_paid">
												<label for="item.invoice_status">Balance Due <b class="text-danger">*</b></label>
												<input  type="number" step="any" class="form-control mb-2" v-model="item.balance_due" name="balance_due">
												<label for="item.invoice_status">Number of Sales Order <b class="text-danger">*</b></label>
												<input  type="number" step="any" class="form-control mb-2" v-model="item.number_of_sales_order" name="number_of_sales_order">
												<label for="item.invoice_status">Number of Customer Invoice <b class="text-danger">*</b></label>
												<input  type="number" step="any" class="form-control mb-2" v-model="item.number_of_customer_invoice" name="number_of_customer_invoice">
												<label for="item.invoice_status">Number of Overdue Invoice <b class="text-danger">*</b></label>
												<input  type="number" step="any" class="form-control mb-2" v-model="item.number_overdue_invoice" name="number_overdue_invoice">
											</div>
										</div>	
									</div>
									<div class="col-md-4">
										<h4><i class="fas fa-user"></i> Customer</h4><hr>
										<label>Customer <b class="text-danger">*</b></label>
										<v-select class="mb-2" v-model="item.customer_id" :reduce="item => item.id" label="fullname" :options="customers"></v-select>
										<label>Customer Account <b class="text-danger">*</b></label>
										<input class="form-control" type="text" v-model="item.customer_account" readonly>
										<input v-model="item.customer_name" type="hidden" class="form-control mb-2" readonly>
										<label>Customer Contact ID <b class="text-danger">*</b></label>
										<input v-model="item.customer_contact_id" type="text" class="form-control mb-2" readonly>
										<label>Customer Address <b class="text-danger">*</b></label>
										<textarea v-model="item.customer_address" class="form-control mb-2" rows="3" readonly></textarea>
									</div>
								</div>
				        	</div>
				        	<div class="tab-pane" id="lines">
			        		   <customer-summary-lines 
									@success="fetch"
									@newLines="getNewlines"
									:lines="customer_summary_lines"
									:cs="item"
									:method_of_payments="method_of_payments"
									:terms_of_payments="terms_of_payments"
									>
							   </customer-summary-lines>
				        	</div>
				        </div>
				    </div>
				</div>
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

import CustomerSummaryLine from './CustomerSummaryLine.vue';

export default {

	components: {
		'text-editor': TextEditor,
		'form-request': FormRequest,
		'action-button': ActionButton,
		'v-select' : Vselect,
		'customer-summary-lines' : CustomerSummaryLine,
	},

	data() {
		return {
			item: {
				customer_summary_lines: []
			},
			users: [],
			customers: [],
			method_of_payments: [],
			terms_of_payments: [],
			customer_summary_lines: [],
		}
	},

	watch: {
		'item.customer_id'(val) {
			var customer = _.find(this.customers, (customer) => { return customer.id == val });
			this.item.customer_contact_id = customer.fullname;
			this.item.customer_address = `${customer.shipping_street}, ${customer.shipping_city}, ${customer.shipping_province}, ${customer.shipping_postal_code}, ${customer.shipping_country}`;
			this.item.customer_account = customer.customer_account;
		},
	},

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.customer_summary_lines = data.item ? data.item.customer_summary_lines : this.item.customer_summary_lines;
			this.users = data.users ? data.users : this.users;
			this.method_of_payments = data.method_of_payments ? data.method_of_payments : this.method_of_payments;
			this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
			this.customers = data.customers ? data.customers : this.customers;
			flatpickr(this.$refs.issue_date_from)
			flatpickr(this.$refs.issue_date_to)

			if(_.isEmpty(data.item)) {
				this.generateID();
			}
		},

		generateID() {
			var date = new Date();
			var time = Math.round(date.getTime() / 1000);	
			this.item.customer_summary_id = date.getDate().toString() + date.getMonth().toString() + date.getFullYear().toString() +'-'+ time.toString();
			this.item.customer_summary_id += "-" + Math.random().toString(36).substring(2, 6);
		},

		approvedThisInvoice() {
			var $this = this;
			swal.fire({
			  title: 'Are you sure?',
			  text: 'Are you sure you want to approved this Summary?',
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonText: 'Confirm',
			  cancelButtonText: 'Cancel'
			}).then((result) => {
			  if (result.value) {
			    axios.post($this.item.approvedUrl)
		    	.then(response => {
				    $this.$notification.show(response.data.message, 'Success')
				    $this.fetch();
		    	}).catch(error => {
				 	$this.$notification.show(error.response.data.errors['On hold'][0], 'On Hold', 'error');
		    	})
			  }
			})
		},
	},

	mixins: [ CrudMixin ],
}
</script>