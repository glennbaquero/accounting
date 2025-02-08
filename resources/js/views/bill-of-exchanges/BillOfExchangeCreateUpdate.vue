<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>
					Header Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				<!-- 		<button class="btn btn-warning" :disabled="hasItem">Approval</button>
                        <button class="btn btn-danger" :disabled="hasItem">Validate</button>
                        <button class="btn btn-success" :disabled="hasItem">Post</button> -->
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">
						<div class="row">
							<div class="col-md-9">
							    <ul class="nav nav-pills">
							        <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Journal Details</a></li>
							        <li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
							        <li class="nav-item"><a class="nav-link" href="#boe" data-toggle="tab">Bill Of Exchange Detail</a></li>
							        <li class="nav-item"><a class="nav-link" href="#other" data-toggle="tab">Posting Setup</a></li>
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
					    			<div class="col-md-3">
					    				<div class="row">
				        		    		<div class="col-sm-12">
												<h4 class="mb-2"><i class="fas fa-tags"></i> Journal Header Details</h4><hr>
											</div>

								            <div class="col-sm-12 mb-2">
								                <label>{{ invoiceHeaderTitle }}</label>
								                <input readonly type="text" class="form-control" :name="invoiceJournalNumber" v-model="item[invoiceJournalNumber]">
								            </div>
								            <div class="col-sm-12 mb-2">
								                <label>Journal Batch #</label>
								                <input  type="text" class="form-control" name="invoice_journal_batch_number" v-model="item.invoice_journal_batch_number">
								            </div>
								            <div class="col-sm-12 mb-2">
								                <label>Journal Name number</label>
								                <input type="text" class="form-control" name="journal_name_number" v-model="item.journal_name_number">
								            </div>
								            <div class="col-sm-12 mb-2">
								                <label>Journal name</label>
								                <input type="text" class="form-control" name="journal_name" v-model="item.journal_name">
								            </div>
								            <div class="col-sm-12 mb-2">
								                <label>Description</label>
								                <input type="text" class="form-control" name="description" v-model="item.description">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Original Journal number</label>
								                <input type="text" class="form-control" name="original_journal_number" v-model="item.original_journal_number">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Journal type</label>
								                <input type="text" class="form-control" name="journal_type" v-model="item.journal_type">
								            </div>

								            <div class="form-group col-sm-12 mb-2">
                                                <label>Method of Payment</label>
                                                <v-select 
                                                    v-model="item.method_of_payment_id" 
                                                    :reduce="item => item.id" 
                                                    label="method_of_payment" 
                                                    placeholder="Select a Method of Payment" 
                                                    :options="payment_methods"
                                                ></v-select>
                                                <input name="method_of_payment_id" hidden v-model="item.method_of_payment_id"> 
                                            </div>
					    				</div>
					    			</div>
					    			<div class="col-md-3">
					    				<div class="row">
				        		    		<div class="col-sm-12">
												<h4 class="mb-2"><i class="fas fa-tags"></i> Journal Balance</h4><hr>
											</div>
								            <div class="col-sm-12 mb-2">
								                <label>(Balance) Journal</label>
								                <input type="number" class="form-control" name="totalBalance" v-model="item.totalBalance">
								            </div>
								            <div class="col-sm-12 mb-2">
								                <label>(Total debit) Journal</label>
								                <input type="number" class="form-control" name="totalDebit" v-model="item.totalDebit">
								            </div>
								            <div class="col-sm-12 mb-2">
								                <label>(Total credit) Journal</label>
								                <input type="number" class="form-control" name="totalCredit" v-model="item.totalCredit">
								            </div>
					    				</div>

					    				<div class="row mt-2">
											<div class="col-sm-12">
												<h4 class="mb-2"><i class="fas fa-tags"></i> Reversing Entry</h4><hr>
											</div>
											<div class="col-sm-12">					
												<label>Reversing date</label>
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
													<input ref="reversing_date" type="text" class="form-control calendar-form" name="reversing_date" v-model="item.reversing_date" readonly>
												</div>
											</div>
											<div class="col-sm-12">
												<label>Reversing Entry</label>
												<div class="custom-control custom-switch mb-3 mt-2">
													<input type="checkbox" class="custom-control-input" name="reversing_entry_checkbox" id="reversing_entry_checkbox" v-model="item.reversing_entry_checkbox">
													<label class="custom-control-label" for="reversing_entry_checkbox">
														<span class="badge" :class="item.reversing_entry_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.reversing_entry_checkbox ? 'Yes' : 'No'  }}
														</span>
													</label>
												</div>
											</div>
											<div class="col-sm-12">
												<label>Show user-created only</label>
												<div class="custom-control custom-switch mb-3 mt-2">
													<input type="checkbox" class="custom-control-input" name="show_user_created_only" id="show_user_created_only" v-model="item.show_user_created_only">
													<label class="custom-control-label" for="show_user_created_only">
														<span class="badge" :class="item.show_user_created_only ? 'badge-success' : 'badge-danger'">
															{{ item.show_user_created_only ? 'Yes' : 'No'  }}
														</span>
													</label>
												</div>
											</div>
					    				</div>
					    			</div>
					    			<div class="col-md-3">
					    				<div class="row">
		    					        	<div class="col-sm-12">
		    									<h4 class="mb-2"><i class="fas fa-tags"></i> Status</h4><hr>
		    								</div>

		    					            <div class="col-sm-12 mb-2">
		    					                <label>Reported as Ready By</label>
		    					                <input type="text" class="form-control" name="reported_as_ready_by_journal" v-model="item.reported_as_ready_by_journal">
		    					            </div>
		    					            <div class="col-sm-12 mb-2">
		    					                <label>Approved by</label>
		    					                <input type="text" class="form-control" name="approved_by_journal" v-model="item.approved_by_journal" disabled>
		    					            </div>
		    					            <div class="col-sm-12 mb-2">
		    					                <label>Approved date</label>
		    					                <input type="text" class="form-control" name="approved_date" v-model="item.approved_date" disabled>
		    					            </div>

		    					            <div class="col-sm-12 mb-2">
		    					                <label>Rejected by</label>
		    					                <input type="text" class="form-control" name="rejected_by_journal" v-model="item.rejected_by_journal" disabled>
		    					            </div>
		    					            <div class="col-sm-12 mb-2">
		    					                <label>Posted</label>
												<div class="custom-control custom-switch mb-3 mt-2">
													<input  disabled type="checkbox" class="custom-control-input"  name="posted_checkbox" id="posted_checkbox" v-model="item.posted_checkbox">
													<label class="custom-control-label" for="posted_checkbox">
														<span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.posted_checkbox ? 'Yes' : 'No'  }}
														</span>
													</label>
												</div>
		    					            </div>
		    					            <div class="col-sm-12 mb-2">
		    					                <label>Posted on</label>
		    					                <input type="text" class="form-control" name="posted_on" v-model="item.posted_on" disabled>
		    					            </div>
		    					            <div class="col-sm-12 mb-2">
		    					                <label>Posted by</label>
		    					                <input type="text" class="form-control" name="posted_by" v-model="item.posted_by" disabled>
		    					            </div>
					    				</div>
					    			</div>
									<div class="col-md-3">
										<div class="col-sm-12">
											<h4 class="mb-2"><i class="fas fa-tags"></i> Logs</h4><hr>
										</div>
										<div class="col-sm-12 mt-2">
											<label>Log</label>
											<div class="custom-control custom-switch mb-3 mt-2">
												<input type="checkbox" class="custom-control-input"  name="log_in_checkbox" id="log_in_checkbox" v-model="item.log_in_checkbox">
												<label class="custom-control-label" for="log_in_checkbox">
													<span class="badge" :class="item.log_in_checkbox ? 'badge-success' : 'badge-danger'">
														{{ item.log_in_checkbox ? 'Yes' : 'No'  }}
													</span>
												</label>
											</div>
										</div>
										<div class="col-sm-12 mb-2">
											<label>Total Logs</label>
											<input type="text" class="form-control" name="total_logs" v-model="item.total_logs" readonly>
										</div>
										<div class="col-sm-12 mb-2">
											<label>Log Message</label>
											<input type="text" class="form-control" name="log_message" v-model="item.log_message" readonly>
										</div>
										<div class="col-sm-12 mb-2">
											<label>In Use</label>
											<div class="custom-control custom-switch mb-3 mt-2">
												<input type="checkbox" class="custom-control-input" name="in_use_checkbox" id="in_use_checkbox" v-model="item.in_use_checkbox">
												<label class="custom-control-label" for="in_use_checkbox">
													<span class="badge" :class="item.in_use_checkbox ? 'badge-success' : 'badge-danger'">
														{{ item.in_use_checkbox ? 'Yes' : 'No'  }}
													</span>
												</label>
											</div>
										</div>
										<div class="col-sm-12 mb-2">
											<label>Used by user</label>
											<input type="text" class="form-control" name="used_by_user" v-model="item.used_by_user">
										</div>
									</div>
					    		</div>
					    	</div>
					    	<div class="tab-pane" id="financial">
					    		<div class="row">
					    			<div class="col-md-6">
					    				<div class="row">
						    				<div class="col-sm-12">
					            				<h4 class="mb-2"><i class="fas fa-tags"></i> Financial Dimensions</h4><hr>
					            			</div>

								            <div class="col-sm-12 mb-3">
								                <label>Cost Center</label>
												<v-select v-model="item.cost_center" :reduce="item => item.financial_dimension_value_code" :options="cost_centers" label="dimension_name" placeholder="Select Cost Center" ></v-select>
												<input hidden v-model="item.cost_center" name="cost_center">
								            </div>
								            <div class="col-sm-12 mb-3">
								                <label>Department</label>
								                <v-select v-model="item.department" :reduce="item => item.financial_dimension_value_code" :options="departments" label="dimension_name" placeholder="Select Department" ></v-select>
												<input hidden v-model="item.department" name="department">
								            </div>
								            <div class="col-sm-12 mb-3">
								                <label>Expense Purpose</label>
								                <v-select v-model="item.expense_purpose" :reduce="item => item.financial_dimension_value_code" :options="expense_purposes" label="dimension_name" placeholder="Select Expense Purposes" ></v-select>
												<input hidden v-model="item.expense_purpose" name="expense_purpose">
								            </div>
					    				</div>
					    			</div>
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
					    	<div class="tab-pane" id="boe">
					    		<div class="row">
						    		<div class="col-sm-6">
						    		    <label>Issued Date</label>
						    		    <input type="text" class="form-control" name="issued_date" v-model="item.issued_date" readonly>
						    		</div>
					    		</div>
					    		<div class="row">
						    		<div class="col-sm-6">
						    			<label>Due From</label>
						    			<input type="text" class="form-control" name="pn_due_from" v-model="item.pn_due_from">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Due To</label>
						    			<input type="text" class="form-control" name="pn_due_to" v-model="item.pn_due_to">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Principal Amount</label>
						    			<input type="number" class="form-control" name="principal_amount" v-model="item.principal_amount">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Number of Times to Settle</label>
						    			<input type="text" class="form-control" name="number_of_time_to_settle" v-model="item.number_of_time_to_settle">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Amount to Settle</label>
						    			<input type="number" class="form-control" name="amount_to_settle" v-model="item.amount_to_settle">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Terms of Payment</label>
						    			<select class="form-control" name="terms_of_payment" v-model="item.terms_of_payment">
						    				<option value="Daily">Daily</option>
						    				<option value="Bi-weekly">Bi-weekly</option>
						    				<option value="Weekly">Weekly</option>
						    				<option value="Semi-monthly">Semi-monthly</option>
						    				<option value="Monthly">Monthly</option>
						    				<option value="Quarterly">Quarterly</option>
						    				<option value="Yearly">Yearly</option>
						    			</select>
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Payment Day</label>
						    			<input type="text" class="form-control" name="payment_day" v-model="item.payment_day">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Interest Rate</label>
						    			<input type="text" class="form-control" name="interest_rate" v-model="item.interest_rate">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Interest Amount</label>
						    			<input type="text" class="form-control" name="interest_amount" v-model="item.interest_amount">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Terms of Interest</label>
						    			<input type="text" class="form-control" name="terms_of_interest" v-model="item.terms_of_interest">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Customer Bank Account</label> 
						    			<v-select v-model="item.customer_bank_account_id" placeholder="Select Customer Bank Account" :options="customer_bank_accounts" :reduce="item => item.id" label="name"></v-select>
						    			<input hidden v-model="item.customer_bank_account_id" name="customer_bank_account_id">
						    		</div>
						    		<div class="col-sm-6">
						    			<label>Client Bank Account</label> 
						    			<v-select v-model="item.client_bank_account_id" placeholder="Select Client Bank Account" :options="client_bank_accounts" :reduce="item => item.id" label="bank_account"></v-select>
						    			<input hidden v-model="item.client_bank_account_id" name="client_bank_account_id">
						    		</div>
						    	</div>
					    	</div>
					    	<div class="tab-pane" id="other">
					    		<div class="row">
					    			<div class="col-md-4">
					    				<div class="row">
					    					<div class="col-sm-12">
					            				<h4 class="mb-2"><i class="fas fa-tags"></i> Debit </h4><hr>
					            			</div>
								            <div class="col-sm-12 mb-2">
					            			    <label>Account Type</label>
												<v-select v-model="item.account_type" :options="account_types" placeholder="Select Account Type"></v-select>
												<input hidden name="account_type" v-model="item.account_type">
					            			</div>
					                    	<div class="col-sm-12 mb-2">
					            			    <label>Document</label>
					            			    <input type="text" class="form-control" name="document" v-model="item.document">
					            			</div>
					            			<div class="col-sm-12 mb-2">
					            			    <label>Detail level</label>
					            			    <input type="text" class="form-control" name="detail_level" v-model="item.detail_level">
					            			</div>
					    				</div>
					    			</div>
					    			<div class="col-md-4">
					    				<div class="row">
					    					<div class="col-sm-12">
					            				<h4 class="mb-2"><i class="fas fa-tags"></i> Credit</h4><hr>
					            			</div>
					            			<div class="col-sm-12 mb-2">
					            			    <label>Offset Account</label>
												<v-select v-model="item.offset_account" :options="account_types" placeholder="Select Account Type"></v-select>
												<input hidden name="offset_account" v-model="item.offset_account">
					            			</div>
					            			<div class="col-sm-12 mb-2">
					            			    <label>Amounts Include Sales Tax</label>
												<div class="custom-control custom-switch mb-3 mt-2">
													<input type="checkbox" class="custom-control-input" name="amounts_include_sales_tax" id="amounts_include_sales_tax" v-model="item.amounts_include_sales_tax">
													<label class="custom-control-label" for="amounts_include_sales_tax">
														<span class="badge" :class="item.amounts_include_sales_tax ? 'badge-success' : 'badge-danger'">
															{{ item.amounts_include_sales_tax ? 'Yes' : 'No'  }}
														</span>
													</label>
												</div>
					            			</div>
					            			<div class="col-sm-12 mb-2">
					            			    <label>Remittance type</label>
					            			    <input type="text" class="form-control" name="remittance_type" v-model="item.remittance_type">
					            			</div>

					            			<!-- <div class="col-sm-12 mb-2">
					            			    <label>Bank account</label>
					            			    <input type="text" class="form-control" name="bank_account" v-model="item.bank_account">
					            			</div> -->

					            			<div class="form-group col-sm-12 mb-2">
                                                <label>Bank account</label>
                                                <v-select 
                                                    v-model="item.bank_account" 
                                                    :reduce="item => item.bank_account" 
                                                    label="bank_name" 
                                                    placeholder="Select a bank" 
                                                    :options="client_banks"
                                                    name="bank_account"
                                                ></v-select>
                                                <input v-model="item.bank_account" name="bank_account" hidden>
                                            </div>
					    				</div>
					    			</div>

					    			<div class="col-md-4">
					    				<div class="row">
					    					<div class="col-sm-12">
					            				<h4 class="mb-2"><i class="fas fa-tags"></i> Posting Details</h4><hr>
					            			</div>
					            			<div class="col-sm-12 mb-2">
					            			    <label>Posting layer</label>
					            			    <input type="text" class="form-control" name="posting_layer" v-model="item.posting_layer">
					            			</div>
					            			<div class="col-sm-12 mb-2">
					            			    <label>Number allocation at posting</label>
					            			    <input type="text" class="form-control" name="number_allocation_at_posting" v-model="item.number_allocation_at_posting">
					            			</div>
					            			<div class="col-sm-12 mb-2">
					            			    <label>Delete Lines After Posting</label>
												<div class="custom-control custom-switch mb-3 mt-2">
													<input type="checkbox" class="custom-control-input" name="delete_lines_after_posting" id="delete_lines_after_posting" v-model="item.delete_lines_after_posting">
													<label class="custom-control-label" for="delete_lines_after_posting">
														<span class="badge" :class="item.delete_lines_after_posting ? 'badge-success' : 'badge-danger'">
															{{ item.delete_lines_after_posting ? 'Yes' : 'No'  }}
														</span>
													</label>
												</div>
					            			</div>
					            			<div class="col-sm-12 mb-2">
					            			    <label>Lines limit</label>
					            			    <input type="text" class="form-control" name="lines_limit" v-model="item.lines_limit">
					            			</div>
					    				</div>
					    			</div>

					    		</div>
					    	</div>
					    </div>
					</div>
				</div>
				<!-- <div class="row">
				    <div class="col-sm-3">
				        <div class="row">
				            
				        </div>
				    </div>
				</div> -->
			</card>
		</form-request>
	</div>
</template>
<script type="text/javascript">
	import CrudMixin from 'Mixins/crud.js';
	import { ModelListSelect } from 'vue-search-select';
	import Vselect from 'vue-select';

	export default {
		props: {
			invoiceHeaderTitle: String,
			invoiceJournalNumber: String,
		},

		mixins: [ CrudMixin ],

		components: {
			ModelListSelect,
			'v-select' : Vselect
		},

		computed: {
			hasItem() {
				return !_.isEmpty(this.item.created_at);
			}
		},

		data() {
			return {
				item: {},
				cost_centers: [],
				departments: [],
				expense_purposes: [],
				clients: [],
				account_types : ['Ledger', 'Customer', 'Vendor', 'Project', 'Fixed Assets', 'Bank'],
				client_banks: [],
				payment_methods: [],
				customer_bank_accounts: [],
				client_bank_accounts: [],
			}
		},

		methods: {
			
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.departments = data.departments ? data.departments : this.departments;
				this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
				this.clients = data.clients ? data.clients : this.clients;
				this.client_banks = data.client_banks ? data.client_banks : this.client_banks;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
				this.customer_bank_accounts = data.customer_bank_accounts ? data.customer_bank_accounts : this.customer_bank_accounts;
				this.client_bank_accounts = data.client_bank_accounts ? data.client_bank_accounts : this.client_bank_accounts;
				this.generateVoucherNumber();
				
				this.checker();

				flatpickr(this.$refs.reversing_date, {
				    dateFormat: "m/d/Y",
				});

				this.item.issued_date = data.item ? data.issued_date : moment().format('Y-MM-DD');
			},

			generateVoucherNumber() {
				var date = new Date();
				var time = Math.round(date.getTime() / 1000);	
				var voucher_number = 'JRNLHDR#-' + ("0" + (date.getMonth() + 1)).slice(-2) + date.getFullYear().toString() +'-'+ time.toString();
				voucher_number += "-" + Math.random().toString(36).substring(2, 6);
				this.item[this.invoiceJournalNumber] = voucher_number;
			},

			checker() {
				var $this = this;
				var message = '';
				if(location.hash) {
					switch(location.hash) {
						case '#invoice_approval_journal': 
							message = 'Same financial dimension not found in the list of Invoice Approval Journal Header';
							break;
						case '#customer_invoice_approval_journal': 
							message = 'Same financial dimension not found in the list of Customer Invoice Approval Journal Header';
							break;
						case '#customer_payment_journal': 
							message = 'Same financial dimension not found in the list of Customer Payment Journal Header';
							break;
						case '#vendor_payment_journal': 
							message = 'Same financial dimension not found in the list of Vendor Payment Journal Header';
							break;
					}

					swal.fire({
					    title: 'Ooops',
					    text: message,
					    icon: 'warning',
					    showCancelButton: false,
					    confirmButtonText: 'OK',
					    cancelButtonText: 'Cancel'
					}).then((result) => {
					   history.replaceState(null, null, ' ');
					})
				}
			}
		},
	}
</script>