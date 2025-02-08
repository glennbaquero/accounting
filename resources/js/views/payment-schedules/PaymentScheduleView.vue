<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>
					Header Information
					<div class="float-right">
						<a :href="item.customerInvoiceUrl" target="_blank" class="btn btn-secondary" :class="!item ? 'disabled' : ''">Customer Invoice</a>
						<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
						<action-button
	                        v-if="item.approveUrl"
	                        color="btn-success"
	                        :action-url="item.approveUrl"
	                        confirm-dialog
	                        title="Approve Item"
	                        :message="`Are you sure you want to approve ${item.payment_schedule_name}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.approved_checkbox || loading"
                        >
                    		Approve
                    	</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">
						<div class="row">
							<div class="col-md-9">
							    <ul class="nav nav-pills">
							        <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Journal Details</a></li>
							        <li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
							    </ul>
							</div>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-2 mt-2">
										<label>Client</label>
									</div>
									<div class="col-md-10">
										<v-select v-model="item.client_id" :reduce="item => item.id" label="name" :options="clients" placeholder="Select Client"></v-select>
										<input name="client_id" hidden v-model="item.client_id"> 
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
					    <div class="tab-content">
					    	<div class="tab-pane show active" id="details">
					    		<div class="row">
					    			<div class="col-md-4">
					    				<div class="row">
				        		    		<div class="col-sm-12">
												<h4 class="mb-2"><i class="fas fa-tags"></i> Payment Schedule</h4><hr>
											</div>

											<div class="col-sm-12 mb-2">
								                <label>Payment Schedule ID</label>
								                <input readonly type="text" class="form-control" name="payment_schedule_id" :value="item.payment_schedule_id">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label for="payment_schedule_name">Payment Schedule Name <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" id="payment_schedule_name" name="payment_schedule_name" v-model="item.payment_schedule_name">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label for="description">Description <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" id="description" name="description" v-model="item.description">
								            </div>

								            <div class="col-sm-12 mb-2">					
												<label>Schedule Start date <b class="text-danger">*</b></label>
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
													<input ref="schedule_start_date" type="text" class="form-control calendar-form" name="schedule_start_date" v-model="item.schedule_start_date">
												</div>
											</div>

											<div class="col-sm-12 mb-2">					
												<label>Schedule End date <b class="text-danger">*</b></label>
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
													<input ref="schedule_end_date" type="text" class="form-control calendar-form" name="schedule_end_date" v-model="item.schedule_end_date">
												</div>
											</div>

											<div class="col-md-12 mb-2">
												<label>Allocation <b class="text-danger">*</b></label>
												<v-select v-model="item.allocation" :options="allocations" placeholder="Select Allocation"></v-select>
												<input name="allocation" hidden v-model="item.allocation"> 
											</div>

											<div class="col-md-12 mb-2">
												<label>Payment Per <b class="text-danger">*</b></label>
												<v-select v-model="item.payment_per" :options="payment_pers" placeholder="Select Payment For"></v-select>
												<input name="payment_per" hidden v-model="item.payment_per"> 
											</div>

											<div class="col-sm-12 mb-2">
								                <label>No. Of Payment <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="no_of_payments" v-model="item.no_of_payments">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Principal Original Amount <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="principal_original_amount" v-model="item.principal_original_amount">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Minimum Amount <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="minimum_amount" v-model="item.minimum_amount">
								            </div>

								            <div class="col-md-12 mb-2">
												<label>Sales Tax Allocation <b class="text-danger">*</b></label>
												<v-select v-model="item.sales_tax_allocation" :options="sales_tax_allocations" placeholder="Select Sales Tax Allocation"></v-select>
												<input name="sales_tax_allocation" hidden v-model="item.sales_tax_allocation"> 
											</div>
					    				</div>
					    			</div>
					    			<div class="col-md-4">
					    				<div class="row">
					    					<div class="col-sm-12">
		    									<h4 class="mb-2"><i class="fas fa-tags"></i> Status</h4><hr>
		    								</div>

											<div class="col-md-12 mb-2">
												<label>Payment Schedule Status <b class="text-danger">*</b></label>
												<v-select v-model="item.payment_schedule_status" :options="payment_schedule_statuses" placeholder="Select Schedule Status"></v-select>
												<input name="payment_schedule_status" hidden v-model="item.payment_schedule_status"> 
											</div>

											<div class="col-md-12 mb-2">
											    <label for="payment_status">Payment Status <b class="text-danger">*</b></label>
											    <v-select v-model="item.payment_status" :options="payment_statuses" label="value" placeholder="Select Payment Status" :reduce="item => item.value"></v-select>
											</div>

		    								<div class="col-sm-12 mb-2">
	                                            <label for="approved_date">Approved Date</label>
	                                            <div class="input-group">
	                                                <div class="input-group-prepend">
	                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
	                                                </div>
	                                                <input ref="approved_date" type="text" class="form-control" id="approved_date" name="approved_date" v-model="item.approved_date" disabled>
	                                            </div>
	                                        </div>

											<div class="col-sm-12 mb-2">
				        		    			<label for="approved_by">Approved By</label>
												<input id="approved_by" name="approved_by" type="text" class="form-control" v-model="item.approved_by" disabled>
											</div>

											<div class="col-sm-12 mb-2">
	                                            <label>Approved</label>
	                                            <div class="custom-control custom-switch mb-3 mt-2">
	                                            <input type="checkbox" class="custom-control-input" id="approved_checkbox" name="approved_checkbox" :checked="item.approved_checkbox" disabled>
	                                                <label class="custom-control-label" for="closed_checkbox">
	                                                    <span class="badge" :class="item.approved_checkbox ? 'badge-success' : 'badge-danger'">
	                                                        {{ item.approved_checkbox ? 'Yes' : 'No'  }}
	                                                    </span>
	                                                </label>
	                                            </div>
	                                        </div>

				        		    		<div class="col-sm-12">
												<h4 class="mb-2"><i class="fas fa-tags"></i> Installment</h4><hr>
											</div>

											<div class="col-md-12 mb-2">
												<label>Change Allocation <b class="text-danger">*</b></label>
												<v-select v-model="item.charge_allocation" :options="charge_allocations" placeholder="Select Change Allocation"></v-select>
												<input name="charge_allocation" hidden v-model="item.charge_allocation"> 
											</div>

					    				</div>

					    				<div class="row mt-2">
											<div class="col-sm-12">
												<hr><h4 class="mb-2"><i class="fas fa-tags"></i> Related</h4><hr>
											</div>
											<template  v-if="client == 'customer'">
												<div class="col-md-12 mb-2">
													<label>Bills Of Exchange <b class="text-danger">*</b></label>
													<v-select 
														v-model="item.bills_exchange_id" 
														:reduce="item => item.id" 
														label="bills_of_exchange" 
														:options="bills_exchanges"
														placeholder="Select A Bill Of Exchange"
													 ></v-select>
													<input name="bills_exchange_id" hidden v-model="item.bills_exchange_id"> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Bills Of Exchange Issue Date<b class="text-danger">*</b></label>
													<input :value="item.bills_exchange_issue_date"class="form-control"  readonly> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Bills Of Exchange Status<b class="text-danger">*</b></label>
													<input :value="item.bills_exchange_status"class="form-control"  readonly> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Customer Invoice Number <b class="text-danger">*</b></label>
													<v-select 
														v-model="item.customer_invoice_number" 
														:reduce="item => item.customer_invoice_number" 
														label="customer_invoice_number" 
														:options="invoices"
														placeholder="Select Invoice"
													 ></v-select>
													<input name="customer_invoice_number" hidden v-model="item.customer_invoice_number"> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Due From<b class="text-danger">*</b></label>
													<input :value="item.ci_payment_due_date" class="form-control"  readonly> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Due To<b class="text-danger">*</b></label>
													<input :value="item.ci_due_to" class="form-control"  readonly> 
												</div>
											</template>
											<template v-if="client == 'vendor'">
												<div class="col-md-12 mb-2">
													<label>Promissory Note <b class="text-danger">*</b></label>
													<v-select 
														v-model="item.promissory_note_id" 
														:reduce="item => item.id" 
														label="promissory_note" 
														:options="promissory_notes"
														placeholder="Select Promissory Note"
													 ></v-select>
													<input name="promissory_note_id" hidden v-model="item.promissory_note_id"> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Promissory Note Issue Date<b class="text-danger">*</b></label>
													<input :value="item.pn_issue_date"class="form-control"  readonly> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Promissory Note Status<b class="text-danger">*</b></label>
													<input :value="item.pn_status"class="form-control"  readonly> 
												</div>
												<div class="col-md-12 mb-2" v-if="client == 'vendor'">
													<label>Vendor Invoice Number <b class="text-danger">*</b></label>
													<v-select 
														v-model="item.vendor_invoice_id" 
														:reduce="item => item.id" 
														label="vendor_invoice_number" 
														:options="invoices"
														placeholder="Select Invoice"
													 ></v-select>
													<input name="vendor_invoice_id" hidden v-model="item.vendor_invoice_id"> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Due From<b class="text-danger">*</b></label>
													<input :value="item.vi_payment_due_date" class="form-control"  readonly> 
												</div>

												<div class="col-md-12 mb-2">
													<label>Due To<b class="text-danger">*</b></label>
													<input :value="item.vi_due_to" class="form-control"  readonly> 
												</div>
												
											</template>

											<div class="col-md-12 mb-2">
												<label>Terms of Payment <b class="text-danger">*</b></label>
												<v-select 
													v-model="item.terms_of_payment_id" 
													:reduce="item => item.id" 
													label="payment_schedule_name" 
													:options="terms_of_payments"
													placeholder="Select A Bill Of Exchange"
												 ></v-select>
												<input name="terms_of_payment_id" hidden v-model="item.terms_of_payment_id"> 
											</div>

								            <div class="col-md-12 mb-2">
												<label>Client Bank Account <b class="text-danger">*</b></label>
												<v-select 
													class="mb-2" 
													v-model="item.client_bank_account" 
													:reduce="item => item.bank_account" 
													label="bank_name" 
													placeholder="Select Client Bank" 
													:options="client_banks"
												></v-select>
												<input name="client_bank_account" hidden v-model="item.client_bank_account"> 
											</div>
					    				</div>
					    			</div>
					    			<div class="col-md-4" v-if="client == 'customer'">
					    				<div class="row">
	                                        <div class="col-sm-12">
		    									<h4 class="mb-2"><i class="fas fa-tags"></i> Customer</h4><hr>
		    								</div>

		    								<div class="col-md-12 mb-2">
												<label>Customer Account <b class="text-danger">*</b></label>
												<v-select 
													class="mb-2" 
													v-model="item.customer_account" 
													:reduce="item => item.customer_account" 
													label="company" 
													placeholder="Select Customer" 
													:options="customers"
												></v-select>
												<input name="customer_account" hidden v-model="item.customer_account"> 
											</div>

											<div class="col-sm-12 mb-2">
								                <label>Customer Address <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="customer_address" v-model="item.customer_address">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Customer Name <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="customer_name" v-model="item.customer_name">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Customer Contact ID <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="customer_contact_id" v-model="item.customer_contact_id">
								            </div>
					    				</div>
					    			</div>
					    			<div class="col-md-4" v-if="client == 'vendor'">
					    				<div class="row">
	                                        <div class="col-sm-12">
		    									<h4 class="mb-2"><i class="fas fa-tags"></i> Vendor</h4><hr>
		    								</div>

		    								<div class="col-md-12 mb-2">
												<label>Vendor Account <b class="text-danger">*</b></label>
												<v-select 
													class="mb-2" 
													v-model="item.vendor_account" 
													:reduce="item => item.vendor_account" 
													label="fullname" 
													placeholder="Select Vendor" 
													:options="vendors"
												></v-select>
												<input name="vendor_account" hidden v-model="item.vendor_account"> 
											</div>

											<div class="col-sm-12 mb-2">
								                <label>Vendor Address <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="vendor_address" v-model="item.vendor_address">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Vendor Name <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="vendor_name" v-model="item.vendor_name">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Vendor Contact ID <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="vendor_contact_id" v-model="item.vendor_contact_id">
								            </div>
					    				</div>
					    			</div>
					    		</div>
					    	</div>
					    	<div class="tab-pane" id="financial">
					    		<div class="row">
					    			<div class="col-md-6">
					    				<div class="row">
						    				<div class="col-sm-12">
					            				<h4 class="mb-2"><i class="fas fa-tags"></i> Audit</h4><hr>
					            			</div>
						    				<div class="col-sm-12 mb-3">
						    				    <label>Created by</label>
						    				    <input type="text" class="form-control" :value="item.created_by" disabled>
						    				</div>
						    				<div class="col-sm-12 mb-3">
						    				    <label>Created on</label>
						    				    <input type="text" class="form-control" :value="item.created_at" disabled>
						    				</div>
						    				<div class="col-sm-12 mb-3">
						    				    <label>Updated by</label>
						    				    <input type="text" class="form-control" :value="item.updated_by" disabled>
						    				</div>
						    				<div class="col-sm-12 mb-3">
						    				    <label>Updated on</label>
						    				    <input type="text" class="form-control" :value="item.updated_at" disabled>
						    				</div>
						    			</div>
					    			</div>
					    		</div>
					    	</div>
					    	
					    </div>
					</div>
				</div>

			</card>
		</form-request>
	</div>
</template>
<script type="text/javascript">

	import CrudMixin from 'Mixins/crud.js';
	import { ModelListSelect } from 'vue-search-select';
	import Vselect from 'vue-select';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {
		props: {
			clients: Array,
			invoice: Object,
			billExchange: {
				default: null,
				type: Object
			},
			pn: {
				default: null,
				type: Object
			},
			client: {
				default: 'customer',
				type: String
			}
		},

		mixins: [ CrudMixin ],

		components: {
			ModelListSelect,
			'v-select' : Vselect
		},

		mounted() {
			flatpickr(this.$refs.schedule_start_date);
			flatpickr(this.$refs.schedule_end_date);
		},

		watch: {
			'item.customer_account'(val) {
				_.each(this.customers, (customer) => {
					if(customer.customer_account == val) {
						this.item.customer_name = customer.fullname;
						this.item.customer_address = `${customer.shipping_street}, ${customer.shipping_city}, ${customer.shipping_province}, ${customer.shipping_postal_code}, ${customer.shipping_country}`;
						this.item.customer_contact_id = customer.fullname;
					} else {
						this.item.customer_name = null;
						this.item.customer_address = null;
						this.item.customer_contact_id = null;
					}
				})
			},
			'item.bills_exchange_id'(val) {
				_.each(this.bills_exchanges, (boe) => {
					if(boe.id == val) {
						this.item.bills_exchange_issue_date = boe.issue_date;
						this.item.bills_exchange_status = boe.status;
					}
				})
			},

			'item.customer_invoice_number'(val) {
				_.each(this.invoices, (invoice) => {
					if(invoice.customer_invoice_number == val) {
						this.item.ci_payment_due_date = invoice.payment_due_date;
						this.item.ci_due_to = invoice.payment_due_date;
					}
				})
			},

			'item.promissory_note_id'(val) {
				_.each(this.promissory_notes, (pn) => {
					if(pn.id == val) {
						this.item.pn_issue_date = pn.issue_date;
						this.item.pn_status = pn.status;
					}
				})
			},

			'item.vendor_account'(val) {
				let vendor = this.vendors.filter(item => item.vendor_account == val)[0];
				if(vendor) {
					this.item.vendor_account = vendor.vendor_account;
					this.item.vendor_address = vendor.address;
					this.item.vendor_contact_id = vendor.mobile_number;
					this.item.vendor_name = vendor.fullname;
					this.item.vendor_id = vendor.id;
				} else {
					this.item.vendor_account = null;
					this.item.vendor_address = null;
					this.item.vendor_contact_id = null;
					this.item.vendor_name = null;
				}
			},

			'item.vendor_invoice_id'(val) {
				_.each(this.vendor_invoices, (invoice) => {
					if(invoice.id == val) {
						this.item.vi_payment_due_date = invoice.payment_due_date;
						this.item.vi_due_to = invoice.payment_due_date;
					}
				})
			},
		},

		data() {
			return {
				item: {},
				invoices: [],
				bills_exchanges: [],
				terms_of_payments: [],
				customers: [],
				vendors: [],
				vendor_invoices: [],
				promissory_notes: [],
				client_banks: [],
				allocations: [
					'Specified', 
					'Fixed Quantity', 
					'Fixed Amount',
				],
				payment_pers: [
					'Days',
				 	'Months', 
					'Years',
				],
				sales_tax_allocations: [
					'Proportionate', 
					'First installment', 
					'Last installment',
				],
				charge_allocations: [
					'Proportionate', 
					'First installment', 
					'Last installment',
				],
				payment_schedule_statuses: [
					'Active', 
					'On Hold', 
					'Closed',
				],
                payment_statuses: [
                    { value: 'None' },
                    { value: 'Sent' },
                    { value: 'Received' },
                    { value: 'Approved' },
                    { value: 'Rejected' }
                ],
			}
		},

		methods: {
			
			fetchSuccess(data) {

				this.item = data.item ? data.item : this.item;
				this.invoices = data.invoices ? data.invoices : this.invoices;
				this.bills_exchanges = data.bills_exchanges ? data.bills_exchanges : this.bills_exchanges;
				this.customers = data.customers ? data.customers : this.customers;
				this.client_banks = data.client_banks ? data.client_banks : this.client_banks;
				this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
				this.vendors = data.vendors ? data.vendors : this.vendors;
				this.vendor_invoices = data.vendor_invoices ? data.vendor_invoices : this.vendor_invoices;
				this.promissory_notes = data.promissory_notes ? data.promissory_notes : this.promissory_notes;
				if(!_.isEmpty(this.invoice)) {
					this.item.customer_invoice_number = this.invoice.customer_invoice_number;
					this.item.customer_account = this.invoice.customer_account;
					this.item.customer_name = this.invoice.customer_name;
					this.item.customer_contact_id = this.invoice.customer_contact_id;
					this.item.customer_address = this.invoice.customer_address;
				}
				
				if(!_.isEmpty(this.billExchange)) {
					this.item.bills_exchange_id = this.billExchange.id;
				}
			},

		},
	}
</script>