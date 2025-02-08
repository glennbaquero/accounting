<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Payment Reversal Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#payment_reversal" data-toggle="tab">Payment Reversal</a></li>
									<li class="nav-item"><a class="nav-link" href="#audit" data-toggle="tab">Audit Trail</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="payment_reversal">
				        		<div class="row">
									<div class="col-md-3">
										<div class="form-group">
										<h4><i class="fas fa-user-tie"></i> Header Information</h4><hr>
										</div>

										<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="payment_reversal_id">Payment Reversal ID <span class="text-danger">*</span></label>
											<input id="payment_reversal_id" name="payment_reversal_id" type="text" class="form-control" :value="item.payment_reversal_id" disabled>
										</div>

										<div class="form-group">
                                            <label for="reversed_date">Reversed Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reversed_date" type="text" class="form-control calendar-form" id="reversed_date" name="reversed_date" v-model="item.reversed_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="reason">Reason <span class="text-danger">*</span></label>
											<input id="reason" name="reason" type="text" class="form-control" v-model="item.reason">
										</div>

										<div class="form-group">
			        		    			<label for="status">Status <span class="text-danger">*</span></label>
											<input id="status" name="status" type="text" class="form-control" v-model="item.status">
										</div>

										<div class="form-group">
                                            <label>Approved</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            	<input type="checkbox" class="custom-control-input" id="approved_checkbox" name="approved_checkbox" :checked="item.approved_checkbox" disabled>
                                                <label class="custom-control-label" for="approved_checkbox">
                                                    <span class="badge" :class="item.approved_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.approved_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="approved_by">Approved By</label>
											<input id="approved_by" name="approved_by" type="text" class="form-control" :value="item.approved_by" disabled>
										</div>

										<div class="form-group">
                                            <label for="approved_date">Approved Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="approved_date" type="text" class="form-control" id="approved_date" name="approved_date" :value="item.approved_date" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Posted</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            	<input type="checkbox" class="custom-control-input" id="posted_checkbox" name="posted_checkbox" :checked="item.posted_checkbox" disabled>
                                                <label class="custom-control-label" for="posted_checkbox">
                                                    <span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.posted_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="posted_by">Posted By</label>
											<input id="posted_by" name="posted_by" type="text" class="form-control" :value="item.posted_by" disabled>
										</div>

										<div class="form-group">
                                            <label for="posted_date">Posted Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="posted_date" type="text" class="form-control" id="posted_date" name="posted_date" :value="item.posted_date" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="voucher">Voucher <span class="text-danger">*</span></label>
											<input id="voucher" name="voucher" type="text" class="form-control" v-model="item.voucher">
										</div>

									</div>

									<div class="col-md-3">
										<h4 class="mb-2"><i class="fas fa-university"></i> Client Bank Account</h4><hr>

                                        <div class="form-group">
                                            <label>Bank Account Number <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.client_bank_account_number" 
                                                :reduce="item => item.bank_account" 
                                                label="bank_account" 
                                                placeholder="Select Bank" 
                                                :options="client_banks"
                                            >
                                                <template #option="{ bank_account, bank_account_number, account_holder, bank_account_type, bank_name }">
                                                    <b>Bank</b> : {{ bank_account }} - 
                                                    <b>Bank Name</b> : {{ bank_name }} - 
                                                    <b>Account Holder</b> : {{ account_holder }} - 
                                                    <b>Account Number</b> : {{ bank_account_number }} - 
                                                    <b>Account Type</b> : {{ bank_account_type }}
                                                </template>
                                            </v-select>
                                        </div>

                                        <div class="form-group">
                                            <label for="client_bank_name">Bank Name</label>
                                            <input type="text" class="form-control" :value="client_bank_account.bank_name" id="client_bank_name" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="client_account_holder">Bank Account Holder</label>
                                            <input type="text" class="form-control" :value="client_bank_account.account_holder" id="client_account_holder" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="client_bank_account_type">Bank Account Type</label>
                                            <input type="text" class="form-control" :value="client_bank_account.bank_account_type" id="client_bank_account_type" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="client_bank_account_expiry">Bank Account Expiry</label>
                                            <input type="text" class="form-control" :value="formatDate(client_bank_account.expiration_date)" id="client_bank_account_expiry" readonly>
                                        </div>

                                        <hr><h4 class="mb-2"><i class="fas fa-university"></i> Customer Bank Account</h4><hr>

                                        <div class="form-group">
                                            <label>Bank Account Number <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.customer_bank_account_number" 
                                                :reduce="item => item.bank_account" 
                                                label="bank_account" 
                                                placeholder="Select Bank" 
                                                :options="customer_banks"
                                            >
                                                <template #option="{ bank_account, bank_account_number, account_holder, bank_account_type, bank_name }">
                                                    <b>Bank</b> : {{ bank_account }} - 
                                                    <b>Bank Name</b> : {{ bank_name }} - 
                                                    <b>Account Holder</b> : {{ account_holder }} - 
                                                    <b>Account Number</b> : {{ bank_account_number }} - 
                                                    <b>Account Type</b> : {{ bank_account_type }}
                                                </template>
                                            </v-select>
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_bank_name">Bank Name</label>
                                            <input type="text" class="form-control" :value="customer_bank_account.bank_name" id="customer_bank_name" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_account_holder">Bank Account Holder</label>
                                            <input type="text" class="form-control" :value="customer_bank_account.account_holder" id="customer_account_holder" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_bank_account_type">Bank Account Type</label>
                                            <input type="text" class="form-control" :value="customer_bank_account.bank_account_type" id="customer_bank_account_type" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_bank_account_expiry">Bank Account Expiry</label>
                                            <input type="text" class="form-control" :value="formatDate(customer_bank_account.expiration_date)" id="customer_bank_account_expiry" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_company">Customer Company</label>
                                            <input type="text" class="form-control" :value="customer_bank_account.customer_company" id="customer_company" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_contact">Customer Contact</label>
                                            <input type="text" class="form-control" :value="customer_bank_account.customer_contact" id="customer_contact" readonly>
                                        </div>

                                        <hr><h4 class="mb-2"><i class="fas fa-store"></i> Vendor Bank Account</h4><hr>

                                        <div class="form-group">
                                            <label>Vendor Account Number <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.vendor_bank_account_number" 
                                                :reduce="item => item.bank_account" 
                                                label="bank_account" 
                                                placeholder="Select Vendor" 
                                                :options="vendor_banks"
                                            >
                                                <template #option="{ bank_account, bank_account_number, account_holder, bank_account_type, bank_name }">
                                                    <b>Bank</b> : {{ bank_account }} - 
                                                    <b>Bank Name</b> : {{ bank_name }} - 
                                                    <b>Account Holder</b> : {{ account_holder }} - 
                                                    <b>Account Number</b> : {{ bank_account_number }} - 
                                                    <b>Account Type</b> : {{ bank_account_type }}
                                                </template>
                                            </v-select>
                                        </div>

                                        <div class="form-group">
                                            <label for="vendor_bank_name">Bank Name</label>
                                            <input type="text" class="form-control" :value="vendor_bank_account.bank_name" id="vendor_bank_name" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="vendor_account_holder">Bank Account Holder</label>
                                            <input type="text" class="form-control" :value="vendor_bank_account.account_holder" id="vendor_account_holder" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="vendor_bank_account_type">Bank Account Type</label>
                                            <input type="text" class="form-control" :value="vendor_bank_account.bank_account_type" id="vendor_bank_account_type" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="vendor_bank_account_expiry">Bank Account Expiry</label>
                                            <input type="text" class="form-control" :value="formatDate(vendor_bank_account.expiration_date)" id="vendor_bank_account_expiry" readonly>
                                        </div>

									</div>

									<div class="col-md-3">
										<h4 class="mb-2"><i class="fas fa-equals"></i> Statements</h4><hr>

										<div class="form-group">
											<label>Bank Statement <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.bank_statement_id" 
												:reduce="item => item.bank_statement_id" 
												label="bank_statement" 
												placeholder="Select a statement" 
												:options="bank_statements"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="bank_statement_issued_date">Bank Statement Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="bank_statement_issued_date" type="text" class="form-control calendar-form" id="bank_statement_issued_date" name="bank_statement_issued_date" v-model="item.bank_statement_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="bank_statement_status">Bank Statement Status <span class="text-danger">*</span></label>
											<input id="bank_statement_status" name="bank_statement_status" type="text" class="form-control" v-model="item.bank_statement_status">
										</div>

										<div class="form-group">
											<label>Cash Register <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.cash_register_id" 
												:reduce="item => item.cashflow_transaction_id" 
												label="cashflow_transaction_id" 
												placeholder="Select a Cash Register" 
												:options="cash_registers"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="cash_register_issued_date">Cash Register Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="cash_register_issued_date" type="text" class="form-control calendar-form" id="cash_register_issued_date" name="cash_register_issued_date" v-model="item.cash_register_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="cash_register_status">Cash Register Status <span class="text-danger">*</span></label>
											<input id="cash_register_status" name="cash_register_status" type="text" class="form-control" v-model="item.cash_register_status">
										</div>

										<div class="form-group">
											<label>Bank Reconciliation <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.bank_reconciliation_id" 
												:reduce="item => item.bank_reconciliation_id" 
												label="bank_reconciliation_id" 
												placeholder="Select a Bank Reconciliation" 
												:options="bank_reconciliations"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="bank_reconciliation_issued_date">Bank Reconciliation Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="bank_reconciliation_issued_date" type="text" class="form-control calendar-form" id="bank_reconciliation_issued_date" name="bank_reconciliation_issued_date" v-model="item.bank_reconciliation_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="bank_reconciliation_status">Bank Reconciliation Status <span class="text-danger">*</span></label>
											<input id="bank_reconciliation_status" name="bank_reconciliation_status" type="text" class="form-control" v-model="item.bank_reconciliation_status">
										</div>

                                        <div class="form-group">
                                            <label>Bank Posting <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.bank_posting" 
                                                :reduce="item => item.id" 
                                                label="bank_transaction_posting" 
                                                placeholder="Select a bank posting" 
                                                :options="bank_postings"
                                            ></v-select>
                                        </div>

                                        <div class="form-group">
                                            <label>Bank Reason <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.bank_reason" 
                                                :reduce="item => item.reason_code" 
                                                label="default_comment" 
                                                placeholder="Select a reason" 
                                                :options="bank_reasons"
                                            ></v-select>
                                        </div>

									</div>

									<div class="col-md-3">
										<h4 class="mb-2"><i class="fas fa-money-check-alt"></i> Payment</h4><hr>

                                        <div class="form-group">
                                            <label for="payment_reference">Payment Refernce <span class="text-danger">*</span></label>
                                            <input id="payment_reference" name="payment_reference" type="text" class="form-control" v-model="item.payment_reference">
                                        </div>

										<div class="form-group">
											<label>Check <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.check_id" 
												:reduce="item => item.check_id" 
												label="check_id" 
												placeholder="Select a Check" 
												:options="checks"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="check_issued_date">Check Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="check_issued_date" type="text" class="form-control calendar-form" id="check_issued_date" name="check_issued_date" v-model="item.check_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Postdated Check Status</label>
                                            <v-select 
                                                v-model="item.postdated_check_status"  
                                                placeholder="Select a Postdated Status" 
                                                :options="statuses"
                                            ></v-select>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="check_number">Check Number <span class="text-danger">*</span></label>
											<input id="check_number" name="check_number" type="text" class="form-control" v-model="item.check_number">
										</div>

										<div class="form-group">
			        		    			<label for="amount">Check Amount <span class="text-danger">*</span></label>
											<input id="amount" name="amount" type="text" class="form-control" v-model="item.amount">
										</div>

										<div class="form-group">
											<label>Deposit <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.deposit_id" 
												:reduce="item => item.deposit_slip_id" 
												label="deposit_slip_id" 
												placeholder="Select a Deposit Slip" 
												:options="deposits"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="deposit_issued_date">Deposit Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="deposit_issued_date" type="text" class="form-control calendar-form" id="deposit_issued_date" name="deposit_issued_date" v-model="item.deposit_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="deposit_status">Deposit Status <span class="text-danger">*</span></label>
											<input id="deposit_status" name="deposit_status" type="text" class="form-control" v-model="item.deposit_status">
										</div>

										<div class="form-group">
											<label>Vendor Payment <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.vendor_payment_id" 
												:reduce="item => item.vendor_payment_number" 
												label="vendor_payment_number" 
												placeholder="Select a Vendor Payment" 
												:options="vendor_payments"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="vendor_payment_issued_date">Vendor Payment Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="vendor_payment_issued_date" type="text" class="form-control calendar-form" id="vendor_payment_issued_date" name="vendor_payment_issued_date" v-model="item.vendor_payment_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Vendor Payment Method <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.vendor_payment_method" 
                                                :reduce="item => item.method_of_payment_id" 
                                                label="method_of_payment" 
                                                placeholder="Select a payment method" 
                                                :options="vendor_payment_methods"
                                            ></v-select>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="vendor">Vendor <span class="text-danger">*</span></label>
											<input id="vendor" name="vendor" type="text" class="form-control" v-model="item.vendor">
										</div>

										<div class="form-group">
											<label>Customer Payment <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.customer_payment_id"
												:reduce="item => item.customer_payment_number"
												label="customer_payment_number"
												placeholder="Select a Customer Payment"
												:options="customer_payments"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="customer_payment_issued_date">Customer Payment Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="customer_payment_issued_date" type="text" class="form-control calendar-form" id="customer_payment_issued_date" name="customer_payment_issued_date" v-model="item.customer_payment_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Customer Payment Method <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.customer_payment_method"
                                                :reduce="item => item.method_of_payment_id"
                                                label="method_of_payment"
                                                placeholder="Select a payment method"
                                                :options="customer_payment_methods"
                                            ></v-select>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="customer">Customer <span class="text-danger">*</span></label>
											<input id="customer" name="customer" type="text" class="form-control" v-model="item.customer">
										</div>

									</div>

			        		    </div>
				        	</div>

				        	<div class="tab-pane" id="audit">
				        		<div class="col-sm-6">
									<div class="form-group">
										<h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
		        		    			<label>Created By</label>
 										<input readonly :value="item.created_by" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Created On</label>
										<input readonly :value="item.created_date" type="text" class="form-control mb-2">
		        		    		
		        		    			<label>Updated By</label>
										<input readonly :value="item.updated_by" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Updated on</label>
		        		                <input readonly :value="item.updated_date" type="text" class="form-control">
									</div>
	        		    		</div>
				        	</div>
			        	</div>
		        	</div>

				</div>
			</card>


			<loader 
	        :loading="loading">
	        </loader>
		</form-request>
	</div>
</template>

<script>

	import CrudMixin from 'Mixins/crud.js';
	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import { ModelListSelect } from 'vue-search-select';
	import Vselect from 'vue-select';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	import DataTable from 'Components/tables/StaticDataTable.vue';
	
	export default {
		mixins: [ CrudMixin ],

		data() {
			return {
				item: {},
				client_banks: [],
				vendor_banks: [],
				customer_banks: [],
				bank_statements: [],
				cash_registers: [],
				bank_reconciliations: [],
				vendor_payments: [],
				customer_payments: [],
				checks: [],
				deposits: [],
                vendor_payment_methods: [],
                customer_payment_methods: [],
                bank_postings: [],
                bank_reasons: [],
				statuses: [
                    'Open',
                    'On Hold',
                    'Void',
                    'Paid',
                    'Posted',
                    'Cancelled',
                    'Pending Cancellation',
                ],
			}
		},

		mounted() {
			this.mountInputs();
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.client_banks = data.client_banks ? data.client_banks : this.client_banks;
				this.vendor_banks = data.vendor_banks ? data.vendor_banks : this.vendor_banks;
				this.customer_banks = data.customer_banks ? data.customer_banks : this.customer_banks;
				this.bank_statements = data.bank_statements ? data.bank_statements : this.bank_statements;
				this.cash_registers = data.cash_registers ? data.cash_registers : this.cash_registers;
				this.bank_reconciliations = data.bank_reconciliations ? data.bank_reconciliations : this.bank_reconciliations;
				this.checks = data.checks ? data.checks : this.checks;
				this.deposits = data.deposits ? data.deposits : this.deposits;
				this.vendor_payments = data.vendor_payments ? data.vendor_payments : this.vendor_payments;
				this.customer_payments = data.customer_payments ? data.customer_payments : this.customer_payments;
                this.vendor_payment_methods = data.vendor_payment_methods ? data.vendor_payment_methods : this.vendor_payment_methods;
                this.customer_payment_methods = data.customer_payment_methods ? data.customer_payment_methods : this.customer_payment_methods;
                this.bank_postings = data.bank_postings ? data.bank_postings : this.bank_postings;
                this.bank_reasons = data.bank_reasons ? data.bank_reasons : this.bank_reasons;
			},

			mountInputs() {
                let options = {
                    enableTime: true,
                };

                flatpickr(this.$refs.reversed_date, options);
                flatpickr(this.$refs.bank_statement_issued_date, options);
                flatpickr(this.$refs.cash_register_issued_date, options);
                flatpickr(this.$refs.bank_reconciliation_issued_date, options);
                flatpickr(this.$refs.check_issued_date, options);
                flatpickr(this.$refs.deposit_issued_date, options);
                flatpickr(this.$refs.vendor_payment_issued_date, options);
                flatpickr(this.$refs.customer_payment_issued_date, options);
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },
		},

		computed: {
			submitParams() {
				let item = this.item;
				return item;
			},

			client_bank_account() {
                let item = this.client_banks.find((data) => {
                    return data.bank_account == this.item.client_bank_account_number;
                });

                return item ? item : {};
            },

            customer_bank_account() {
                let item = this.customer_banks.find((data) => {
                    return data.bank_account == this.item.customer_bank_account_number;
                });

                return item ? item : {};
            },

            vendor_bank_account() {
                let item = this.vendor_banks.find((data) => {
                    return data.bank_account == this.item.vendor_bank_account_number;
                });

                return item ? item : {};
            },
		},

		props: {
            clients: {
                type: Array,
                default: () => [],
            },
        },

		components: {
			'model-list-select': ModelListSelect,
			'action-button': ActionButton,
			'form-request': FormRequest,
			'text-editor': TextEditor,
			'datepicker': Datepicker,
			'data-table': DataTable,
			'v-select': Vselect,
			'card': Card,
		},
	}

</script>