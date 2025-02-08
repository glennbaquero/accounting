<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="params">
            <card>
                <template v-slot:header>
                    Vendor Payment Information
                    <div class="float-right">
                        <button :disabled="item.approved_payment == 1 ? true : false" class="btn btn-primary btn-sm">Save Changes</button>
                        <button type="button" :disabled="item.approved_payment == 1 ? true : false || !item.id || hasNoApprovedLine" @click="confirmThisForm" class="btn btn-success btn-sm">Approve Payment</button>
                        <button type="button" :disabled="item.approved_payment == 1 ? true : false || item.posted_payment || !item.id" @click="postedThisForm" class="btn btn-success btn-sm">Post</button>
                        <button type="button" :disabled="item.approved_payment == 1 ? true : false || item.posted_payment || !item.id" @click="postedThisForm" class="btn btn-danger btn-sm">Cancel</button>
                        <button type="button" class="btn btn-success" :disabled="!item.id" @click="generatePurchaseDeliveryReceipt">Generate Sales Delivery Receipt</button>
                        <button type="button" class="btn btn-success" :disabled="!item.id" @click="generatePaymentSchedule">Generate Payment Schedule</button>
                    </div>
                </template>

                <div class="card">

                    <!-- Tab Navigation -->

				    <div class="card-header p-2">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#vendor_payment" data-toggle="tab">Vendor Payment</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#financial_dimension" data-toggle="tab">Financial Dimension</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#bank_payment_details" data-toggle="tab">Bank Payment Details</a></li>
                                    <!-- <li class="nav-item"><a class="nav-link" href="#vat" data-toggle="tab">VAT</a></li> -->
                                    <li class="nav-item"><a class="nav-link" href="#vendor_payment_lines" data-toggle="tab">Vendor Payment Lines</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#subsidiary_ledger" data-toggle="tab">Subsidiary Ledger</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#checks" data-toggle="tab">Checks</a></li>
                                </ul>
                            </div>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-3 mt-2">
										<label>Client <template v-if="item.id">:</template></label>
									</div>
									<div class="col-md-9">
										<template v-if="!item.id">
											<v-select ref="client_select" :disabled="item.id ? true : false" v-model="client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</template>
										<template v-else>
											<input readonly class="form-control" :value="item.client ? item.client.name : '---'">
										</template>
									</div>
								</div>
							</div>
                        </div>
				    </div>
                    <div class="card-body">
                        <div class="tab-content">

                            <!-- Vendor Payment - TAB -->

                            <div class="tab-pane show active" id="vendor_payment">
                                <div class="row">

                                    <!-- Vendor Payment Column -->

                                    <div class="col-md-3">
                                        <h4><i class="fa fa-info-circle" aria-hidden="true"></i> Vendor Payment</h4><hr>
                                        <div class="form-group">
                                            <label for="vendor_payment_number">Payment ID</label>
                                            <input type="text" class="form-control" id="vendor_payment_number" name="vendor_payment_number" v-model="item.vendor_payment_number" readonly="readonly">
                                        </div>
                                        <div class="form-group">
                                            <label>Transaction Type <b class="text-danger">*</b></label>
                                            <v-select v-model="item.transaction_type" :options="transaction_types"  placeholder="Select Transaction Type" class="mb-2"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="vendor_invoice_id">Invoice Number</label>
                                            <v-select :disabled="item.created_at ? true : false" @input="inheritInvoiceVendorAccountChange" v-model="item.vendor_invoice_id" :options="vendor_invoices" label="vendor_invoice_number" placeholder="Select Invoice Number" :reduce="item => item.id" class="mb-2"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="issue_date">Issue Date  <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="issue_date" type="text" class="form-control calendar-form" id="issue_date" name="issue_date" v-model="item.issue_date" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="due_date">Due Date <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="due_date" type="text" class="form-control calendar-form" id="due_date" name="due_date" v-model="item.due_date" readonly>
                                            </div>
                                        </div>  
                                        <div class="form-group">
                                            <label for="payment_release_date">Payment Release Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="payment_release_date" type="text" class="form-control calendar-form" id="payment_release_date" name="payment_release_date" v-model="item.payment_release_date" readonly>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="clearing_date">Clearing Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="clearing_date" type="text" class="form-control calendar-form" id="clearing_date" name="clearing_date" v-model="item.clearing_date" readonly>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="payee">Payee</label>
                                            <input type="text" class="form-control" name="payee" v-model="item.payee" id="payee">
                                        </div>
                                        <div class="form-group">
                                            <label for="method_of_payment_id">Method Of Payment <b class="text-danger">*</b></label>
                                            <v-select v-model="item.method_of_payment_id" :options="method_of_payments" label="method_of_payment" placeholder="Select Method of Payment" :reduce="item => item.id" class="mb-2"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="payment_reference">Payment Reference</label>
                                            <input type="text" class="form-control" name="payment_reference" v-model="item.payment_reference" id="payment_reference">
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="cash_amount">Cash Amount <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="cash_amount" v-model="item.cash_amount" id="cash_amount">
                                        </div>
                                        <div class="form-group">
                                            <label for="check_amount">Check Amount <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="check_amount" :disabled="method_of_payment != 'Check Payment'" v-model="item.check_amount" id="check_amount">
                                        </div>
                                        <div class="form-group">
                                            <label for="deposit_amount">Deposit Amount <span class="text-danger">*</span></label>
                                            <input id="deposit_amount" name="deposit_amount" type="text" class="form-control" :disabled="method_of_payment != 'Deposit Payment'" v-model="item.deposit_amount">
                                        </div>
                                        <div class="form-group">
                                            <label for="other_amount">Other Amount <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="other_amount" v-model="item.other_amount" id="other_amount">
                                        </div>
                                        <div class="form-group">
                                            <label for="total_amount">Total Amount <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="total_amount" v-model="item.total_amount" id="total_amount">
                                        </div>
                                        <div class="form-group">
                                            <label for="outstanding">Outstanding <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="outstanding" v-model="item.outstanding" id="outstanding">
                                        </div> -->
                                        <div class="form-group">
                                            <label for="payment_specification">Payment Specification</label>
                                            <input type="text" class="form-control" name="payment_specification" v-model="item.payment_specification" id="payment_specification">
                                        </div>
                                        <div class="form-group">
                                            <label for="settlement_type">Settlement Type <b class="text-danger">*</b></label>
                                            <v-select v-model="item.settlement_type" :options="settlement_type_list" label="value" placeholder="Select Settlement Type" :reduce="item => item.id" class="mb-2"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" v-model="item.description"></textarea>
                                        </div>
                                    </div>

                                    <!-- Status Column -->

                                    <div class="col-md-3">
                                        <h4><i class="fa fa-question-circle" aria-hidden="true"></i> Status</h4><hr>
                                        <div class="form-group">
                                            <label for="payment_status">Payment Status <b class="text-danger">*</b></label>
                                            <v-select v-model="item.payment_status" :options="payment_statuses" label="value" placeholder="Select Payment Status" :reduce="item => item.value" class="mb-2"></v-select>
                                        </div>
                                        
                                        <div class="form-group">
											<label>Approved Payment</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input type="checkbox" disabled class="custom-control-input" id="approved_payment" name="approved_payment" v-model="item.approved_payment">
												<label class="custom-control-label" for="approved_payment">
													<span class="badge" :class="item.approved_payment ? 'badge-success' : 'badge-danger'">
														{{ item.approved_payment ? 'Yes' : 'No'  }}
													</span>
												</label>
											</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="approved_date">Approved Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="approved_date" type="text" readonly="readonly" class="form-control" id="approved_date" name="approved_date" v-model="item.approved_date">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="approved_by">Approved By</label>
                                            <input type="text" class="form-control" id="approved_by" name="approved_by" v-model="item.approved_by_name" readonly="readonly">
                                        </div>
                                        <div class="form-group">
											<label>Posted Payment</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input type="checkbox" disabled class="custom-control-input" id="posted_payment" name="posted_payment" v-model="item.posted_payment">
												<label class="custom-control-label" for="posted_payment">
													<span class="badge" :class="item.posted_payment ? 'badge-success' : 'badge-danger'">
														{{ item.posted_payment ? 'Yes' : 'No'  }}
													</span>
												</label>
											</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="posting_date">Posting Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="posting_date" type="text" class="form-control" id="posting_date" name="posting_date" v-model="item.posting_date" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="posted_by_name">Posted By</label>
                                            <input type="text" class="form-control" id="posted_by_name" name="posted_by_name" v-model="item.posted_by_name" readonly="readonly">
                                        </div>
                                    </div>

                                    <!-- Sales Tax & Cash Discount -->

                                    <div class="col-md-3">
                                        <!-- Sales Tax -->
                                        <h4><i class="fas fa-dollar-sign" aria-hidden="true"></i> Sales Tax</h4><hr>
                                        <div class="form-group">
                                            <label for="sales_tax_group">Sales Tax Group</label>
                                            <input type="text" class="form-control" name="sales_tax_group" v-model="item.sales_tax_group" id="sales_tax_group">
                                        </div>
                                        <div class="form-group">
                                            <label for="tax_exempt_group">Tax Exempt Group</label>
                                            <input type="text" class="form-control" name="tax_exempt_group" v-model="item.tax_exempt_group" id="tax_exempt_group">
                                        </div>
                                        <div class="form-group">
											<label>Prices Include Sales Tax</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input type="checkbox" class="custom-control-input" id="prices_included_sales_tax" name="prices_included_sales_tax" v-model="item.prices_included_sales_tax">
												<label class="custom-control-label" for="prices_included_sales_tax">
													<span class="badge" :class="item.prices_included_sales_tax ? 'badge-success' : 'badge-danger'">
														{{ item.prices_included_sales_tax ? 'Yes' : 'No'  }}
													</span>
												</label>
											</div>
                                        </div>
                                        <div class="form-group">
											<label>Ignore Calculated Tax</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input type="checkbox" class="custom-control-input" id="ignore_calculated_tax" name="ignore_calculated_tax" v-model="item.ignore_calculated_tax">
												<label class="custom-control-label" for="ignore_calculated_tax">
													<span class="badge" :class="item.ignore_calculated_tax ? 'badge-success' : 'badge-danger'">
														{{ item.ignore_calculated_tax ? 'Yes' : 'No'  }}
													</span>
												</label>
											</div>
                                        </div>

                                        <!-- Cash Discount -->
                                        <h4><i class="fa fa-percentage" aria-hidden="true"></i> Cash Discount</h4><hr>

                                        <div class="form-group">
                                            <label for="cash_discount_code">Cash Discount Code</label>
                                            <input type="text" class="form-control" name="cash_discount_code" v-model="item.cash_discount_code" id="cash_discount_code">
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="cash_discount">Cash Discount</label>
                                            <input type="number" class="form-control" name="cash_discount" v-model="item.cash_discount" id="cash_discount">
                                        </div> -->
                                        <!-- <div class="form-group">
                                            <label for="cash_discount_percentage">Cash Discount Percentage</label>
                                            <input type="number" class="form-control" name="cash_discount_percentage" v-model="item.cash_discount_percentage" id="cash_discount_percentage">
                                        </div> -->
                                        <div class="form-group">
                                            <label for="charges_group">Charges Group</label>
                                            <input type="text" class="form-control" name="charges_group" v-model="item.charges_group" id="charges_group">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <h4><i class="fa fa-store" aria-hidden="true"></i> Vendor</h4><hr>
                                        <div class="form-group">
                                            <label for="vendor_account">Vendor Account</label>
                                            <v-select v-model="item.vendor_account_id" :options="vendors" label="company_name" placeholder="Select Vendor" :reduce="item => item.id" class="mb-2"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="vendor_account_number">Vendor Number</label>
                                            <input type="text" class="form-control" name="vendor_account_number" v-model="item.vendor_account" id="vendor_account_number" readonly="readonly">
                                        </div>
                                        <div class="form-group">
                                            <label for="invoice_account">Invoice Account</label>
                                            <input type="text" class="form-control" name="invoice_account" v-model="item.invoice_account" id="invoice_account" readonly="readonly">
                                        </div>
                                        <div class="form-group">
                                            <label for="vendor_contact_id">Vendor Contact Id</label>
                                            <input type="text" class="form-control" name="vendor_contact_id" v-model="item.vendor_contact_id" id="vendor_contact_id">
                                        </div>
                                        <div class="form-group">
                                            <label for="vendor_address">Vendor Address</label>
                                            <input type="text" class="form-control" name="vendor_address" v-model="item.vendor_address" id="vendor_address">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Financial Dimension - TAB -->
                            <div class="tab-pane" id="financial_dimension">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>

                                        <div class="form-group">
                                            <label for="dimension_value_cost_center_id">Cost Center <b class="text-danger">*</b></label>
                                            <v-select v-model="item.dimension_value_cost_center_id" :options="cost_centers" label="dimension_name" placeholder="Select Cost Center" :reduce="item => item.id" class="mb-2"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="dimension_value_department_id">Department <b class="text-danger">*</b></label>
                                            <v-select v-model="item.dimension_value_department_id" :options="departments" label="dimension_name" placeholder="Select Department" :reduce="item => item.id" class="mb-2"></v-select>
                                        </div>  
                                        <div class="form-group">
                                            <label for="expense_purpose">Expense Purpose <b class="text-danger">*</b></label>
                                            <v-select v-model="item.dimension_value_expense_purpose_i" :options="expense_purposes" label="dimension_name" placeholder="Select Expense Purpose" :reduce="item => item.id" class="mb-2"></v-select> 
                                        </div>
                                        <div class="form-group">
                                            <label for="posting_profile">Posting Profile</label>
                                            <v-select v-model="item.posting_profile" :reduce="item => item.id" label="posting_profile" :options="posting_profiles"></v-select>
                                            <input type="hidden" class="form-control" name="posting_profile" v-model="item.posting_profile" id="posting_profile">
                                        </div>
                                        <div class="form-group">
                                            <label for="document">Document</label>
                                            <input type="text" class="form-control" name="document" v-model="item.document" id="document">
                                        </div>
                                        <div class="form-group">
                                            <label for="document_status">Document Status</label>
                                            <input type="text" class="form-control" name="document_status" v-model="item.document_status" id="document_status">
                                        </div>

                                        <div class="form-group">
                                            <label for="accounting_distribution">Accounting Distribution</label>
                                            <input type="text" class="form-control" name="accounting_distribution" v-model="item.accounting_distribution" id="accounting_distribution">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
                                        <div class="form-group">
                                            <label for="created_by">Created By</label>
                                            <input type="text" class="form-control" v-model="item.created_by_name" id="created_by" readonly="readonly">
                                        </div>
                                        <div class="form-group">
                                            <label for="created_on">Created On</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="created_on" type="text" class="form-control" v-model="item.created_at" id="created_on" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="updated_by">Updated By</label>
                                            <input ref="updated_by" type="text" class="form-control" id="updated_by" name="updated_by_name" v-model="item.updated_by_name" readonly="readonly">
                                        </div>
                                        <div class="form-group">
                                            <label for="updated_on">Updated On</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="updated_on" type="text" class="form-control" v-model="item.updated_at" id="updated_on" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Check Payment -->

                            <div class="tab-pane" id="bank_payment_details">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h4 class="mb-2"><i class="fas fa-university"></i> Client Bank Account</h4><hr>

                                        <div class="form-group">
                                            <label>Bank Account Number <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.bank_account" 
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
                                            <label for="client_bank_account_type">Bank Account Type</label>
                                            <input type="text" class="form-control" :value="client_bank_account.bank_account_type" id="client_bank_account_type" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="client_bank_name">Bank Name</label>
                                            <input type="text" class="form-control" :value="client_bank_account.bank_name" id="client_bank_name" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="bank_branch">Bank Branch Name</label>
                                            <input type="text" class="form-control" :value="client_bank_account.bank_branch" id="bank_branch" readonly>
                                        </div>

                                        <h4 class="mb-2"><i class="fas fa-university"></i> Vendor Bank Account</h4><hr>

                                        <div class="form-group">
                                            <label>Bank Account Number <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.vendor_bank_account" 
                                                :reduce="item => item.bank_account" 
                                                label="bank_account" 
                                                placeholder="Select Bank" 
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
                                            <label for="vendor_bank_account_type">Bank Account Type</label>
                                            <input type="text" class="form-control" :value="vendor_bank_account.bank_account_type" id="vendor_bank_account_type" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="vendor_bank_name">Bank Name</label>
                                            <input type="text" class="form-control" :value="vendor_bank_account.bank_name" id="vendor_bank_name" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="bank_branch">Bank Branch Name</label>
                                            <input type="text" class="form-control" :value="vendor_bank_account.bank_branch" id="bank_branch" readonly>
                                        </div>

                                        <h4><i class="fas fa-file-invoice-dollar"></i> Credit</h4> <hr> 


                                        <div class="form-group">
                                            <label>Promissory Note</label>
                                            <input readonly v-model="item.letter_credit" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Promissory Note Issue Date</label>
                                            <input readonly v-model="item.boe_issue_date" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Letter of Credit (Purchase)</label>
                                            <input readonly v-model="item.letter_credit" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Letter of Credit Issue Date(Purchase)</label>
                                            <input readonly v-model="item.letter_credit_issue_date" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Letter of Guarantee</label>
                                            <input readonly v-model="item.guarantee" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Letter of Guarantee Issue Date</label>
                                            <input readonly v-model="item.guarantee_date" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <h4 class="mb-2"><i class="fas fa-money-check-alt"></i> Check</h4><hr>

                                        <div class="form-group">
                                            <label for="postdated_check_status_id">Postdated Check Status <b class="text-danger">*</b></label>
                                            <v-select 
                                                :disabled="method_of_payment != 'Check Payment'" 
                                                v-model="item.postdated_check_status_id" 
                                                :options="postdated_check_statuses" 
                                                label="value" 
                                                placeholder="Postdated Check Status" 
                                                :reduce="item => item.id" class="mb-2"
                                            ></v-select>
                                        </div>

                                        <div class="form-group">
                                            <label>Check ID</label>
                                            <input type="text" class="form-control" :value="item.check_id" id="check_id" readonly>
                                            <!-- <v-select 
                                                :disabled="method_of_payment != 'Check Payment'" 
                                                v-model="item.check_id" 
                                                :reduce="item => item.check_id" 
                                                label="check_id" 
                                                placeholder="Select a Check" 
                                                :options="checks"
                                            ></v-select> -->
                                        </div>

                                        <div class="form-group">
                                            <label for="check_number">Check Number <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="check_number" :disabled="method_of_payment != 'Check Payment'" v-model="item.check_number" id="check_number">
                                        </div>

                                        <div class="form-group">
                                            <label for="check_amount">Check Amount <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="check_amount" :disabled="method_of_payment != 'Check Payment'" v-model="item.check_amount" id="check_amount">
                                        </div>

                                        <div class="form-group">
                                            <label for="recipient_name">Recipient Name <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="recipient_name" :disabled="method_of_payment != 'Check Payment'" v-model="item.recipient_name" id="recipient_name">
                                        </div>

                                        <div class="form-group">
                                            <label for="check_number_issued">Issued Date <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" ref="check_number_issued" class="form-control calendar-form" :disabled="method_of_payment != 'Check Payment'" v-model="item.check_number_issued" id="check_number_issued" name="check_number_issued">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="maturity_date">Maturity Date <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" ref="maturity_date" class="form-control calendar-form" :disabled="method_of_payment != 'Check Payment'" v-model="item.maturity_date" id="maturity_date" name="maturity_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="received_date">Received Date <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" ref="received_date" class="form-control calendar-form" :disabled="method_of_payment != 'Check Payment'" v-model="item.received_date" id="received_date" name="received_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="clearing_date">Clearing Date <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="clearing_date" type="text" class="form-control calendar-form" id="clearing_date" name="clearing_date" :disabled="method_of_payment != 'Check Payment'" v-model="item.clearing_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="original_check_number">Original Check Number <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="original_check_number" :disabled="method_of_payment != 'Check Payment'" v-model="item.original_check_number" id="original_check_number">
                                        </div>

                                        <div class="form-group">
                                            <label for="cashier">Cashier <b class="text-danger">*</b></label>
                                            <input type="text" class="form-control" name="cashier" :disabled="method_of_payment != 'Check Payment'" v-model="item.cashier" id="cashier">
                                        </div>

                                    </div>

                                    <div class="col-md-4">
                                        <h4 class="mb-2"><i class="fas fa-money-bill"></i> Deposit</h4><hr>

                                        <div class="form-group">
                                            <label for="deposit_status">Deposit Status <span class="text-danger">*</span></label>
                                            <input id="deposit_status" name="deposit_status" type="text" class="form-control" :disabled="method_of_payment != 'Deposit Payment'" v-model="item.deposit_status">
                                        </div>

                                        <div class="form-group">
                                            <label>Deposit ID</label>
                                            <input type="text" class="form-control" :value="item.deposit_id" id="deposit_slip_id" readonly>
                                            <!-- <v-select 
                                                :disabled="method_of_payment != 'Deposit Payment'" v-model="item.deposit_id" 
                                                :reduce="item => item.deposit_slip_id" 
                                                label="deposit_slip_id" 
                                                placeholder="Select a Deposit Slip" 
                                                :options="deposits"
                                            ></v-select> -->
                                        </div>

                                        <div class="form-group">
                                            <label for="deposit_slip_number">Deposit Slip Number <span class="text-danger">*</span></label>
                                            <input id="deposit_slip_number" name="deposit_slip_number" type="text" class="form-control" :disabled="method_of_payment != 'Deposit Payment'" v-model="item.deposit_slip_number">
                                        </div>

                                        <div class="form-group">
                                            <label for="deposit_amount">Deposit Amount <span class="text-danger">*</span></label>
                                            <input id="deposit_amount" name="deposit_amount" type="text" class="form-control" :disabled="method_of_payment != 'Deposit Payment'" v-model="item.deposit_amount">
                                        </div>

                                        <div class="form-group">
                                            <label for="deposit_date">Deposit Date <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" ref="deposit_date" class="form-control calendar-form" :disabled="method_of_payment != 'Deposit Payment'" v-model="item.deposit_date" id="deposit_date" name="deposit_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Deposit Payment</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="deposit_payment_checkbox" name="deposit_payment_checkbox" :disabled="method_of_payment != 'Deposit Payment'" v-model="item.deposit_payment_checkbox">
                                                <label class="custom-control-label" for="deposit_payment_checkbox">
                                                    <span class="badge" :class="item.deposit_payment_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.deposit_payment_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <hr><h4 class="mb-2"><i class="fas fa-equals"></i> Statements</h4><hr>
                                        </div>

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

                                        <div class="form-group">
                                            <hr><h4 class="mb-2"><i class="fas fa-balance-scale-right"></i> Bank Reconciliation</h4><hr>
                                        </div>

                                        <div class="form-group">
                                            <label>Bank Reconciliation ID <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.bank_reconciliation_id" 
                                                :reduce="item => item.bank_reconciliation_id" 
                                                label="bank_reconciliation_id" 
                                                placeholder="Select a Bank Reconciliation" 
                                                :options="bank_reconciliations"
                                            ></v-select>
                                        </div>

                                        <div class="form-group">
                                            <label for="reconciled_date">Reconciled Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reconciled_date" type="text" class="form-control calendar-form" id="reconciled_date" name="reconciled_date" v-model="item.reconciled_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="adjustment_date">Adjustment Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="adjustment_date" type="text" class="form-control calendar-form" id="adjustment_date" name="adjustment_date" v-model="item.adjustment_date">
                                            </div>
                                        </div>

                                    </div>
                    
                                </div>
                            </div>

                                <!-- <div class="tab-pane" id="vat">

                                    <div class="row">

                                        <div class="form-group col-md-3">
                                            <label for="total_vattable_sales_vat_exclusive">Total VATTable Sales (VAT Exclusive)</label>
                                            <input type="text" class="form-control" name="total_vattable_sales_vat_exclusive" v-model="item.total_vattable_sales_vat_exclusive" id="total_vattable_sales_vat_exclusive">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="total_sales_vat_exclusive">Total Sales (VAT Exclusive)</label>
                                            <input type="text" class="form-control" name="total_sales_vat_exclusive" v-model="item.total_sales_vat_exclusive" id="total_sales_vat_exclusive">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="less_discount">Less Discount</label>
                                            <input type="text" class="form-control" name="less_discount" v-model="item.less_discount" id="less_discount">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="add_charge">Add Charge</label>
                                            <input type="text" class="form-control" name="add_charge" v-model="item.add_charge" id="add_charge">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="add_12_vat">Add 12% VAT</label>
                                            <input type="text" class="form-control" name="add_12_vat" v-model="item.add_12_vat" id="add_12_vat">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="total_sales_vat_inclusive">Total Sales (VAT Inclusive)</label>
                                            <input type="text" class="form-control" name="total_sales_vat_inclusive" v-model="item.total_sales_vat_inclusive" id="total_sales_vat_inclusive">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="less_withholding_tax">Less Withholding Tax</label>
                                            <input type="text" class="form-control" name="less_withholding_tax" v-model="item.less_withholding_tax" id="less_withholding_tax">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="amount_due">Amount Due</label>
                                            <input type="text" class="form-control" name="amount_due" v-model="item.amount_due" id="amount_due">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="vatable_sales">Valuable Sales</label>
                                            <input type="text" class="form-control" name="vatable_sales" v-model="item.vatable_sales" id="vatable_sales">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="vatexempt_sales">VAT-Exempt Sale</label>
                                            <input type="text" class="form-control" name="vatexempt_sales" v-model="item.vatexempt_sales" id="vatexempt_sales">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="zero_rated_sales">Zero-Rated Sales</label>
                                            <input type="text" class="form-control" name="zero_rated_sales" v-model="item.zero_rated_sales" id="zero_rated_sales">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="vat_amount">Vat Amount</label>
                                            <input type="text" class="form-control" name="vat_amount" v-model="item.vat_amount" id="vat_amount">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="total_amount_due">Total Amount Due</label>
                                            <input type="text" class="form-control" name="total_amount_due" v-model="item.total_amount_due" id="total_amount_due">
                                        </div>

                                    </div>

                                </div> -->

                            <!-- Vendor Payment Lines -->

                            <div class="tab-pane" id="vendor_payment_lines">
                                <div class="col-md-12 text-right">
                                    <button type="button" @click="inheritItemLineDetailsFromParent" class="btn btn-success" data-toggle="modal" data-target="#vendor_payment_line_form">
                                        <i class="fas fa-plus"></i> Create Vendor Line
                                    </button>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <data-table 
                                            :key="data_table_key"
                                            ref="data-table"
                                            :headers="headers" 
                                            :items="item.itemLines"
                                        >
                                            <template v-slot:body="{ items }">
                                                <tr v-for="(table_item, key) in items" v-bind:key="key">
                                                    
                                                    <td>{{ table_item.payment_line_number }}</td>
                                                    <td>{{ table_item.product ? table_item.product.product_number : '' }}</td>
                                                    <td>{{ table_item.line_status }}</td>
                                                    <td>{{ table_item.product ? table_item.product.name : '' }}</td>
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
                                                        <button type="button" :disabled="isNotPending(itemLine)" class="btn-success btn-sm btn" @click="approveVendorLine(itemLine)"><i class="fas fa-check"></i> </button>
                                                        <button type="button" :disabled="isNotPending(itemLine)" class="btn-danger btn-sm btn" @click="rejectVendorLine(itemLine)"><i class="fas fa-times"></i> </button>
                                                        <button type="button" class="btn btn-info btn-sm" @click="editVendorLine(itemLine)" data-toggle="modal" data-target="#vendor_payment_line_form">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" :disabled="isNotPending(itemLine)" class="btn btn-danger btn-sm" @click="archiveLine(itemLine)"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </template>         
                                        </data-table>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-4">
                                        <div class="form-group col-12">
                                            <label for="total_quantity">Total Quantity</label>
                                            <input type="number" class="form-control" id="total_quantity" name="total_quantity" :value="total_quantity" readonly="readonly">
                                        </div>  

                                        <div class="form-group col-12">
                                            <label for="total_vattable_sales_vat_exclusive">Total VATTable Sales (VAT Exclusive)</label>
                                            <input type="text" class="form-control" name="total_vattable_sales_vat_exclusive" :value="total_vattable_sales_vat_exclusive" id="total_vattable_sales_vat_exclusive" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <hr><h4><i class="fa fa-info-circle" aria-hidden="true"></i> Discount</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="less_discount">Less Discount</label>
                                            <input type="text" class="form-control" name="less_discount" :value="less_discount" id="less_discount" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="cash_discount">Cash Discount</label>
                                            <input type="text" class="form-control" name="cash_discount" :value="cash_discount" id="cash_discount" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="line_discount">Line Discount</label>
                                            <input type="text" class="form-control" name="line_discount" :value="line_discount" id="line_discount" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <hr><h4><i class="fa fa-info-circle" aria-hidden="true"></i> Charges</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="add_charge">Add Charge</label>
                                            <input type="text" class="form-control" name="add_charge" :value="add_charge" id="add_charge" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="charge">Charge</label>
                                            <input type="text" class="form-control" name="charge" :value="charge" id="charge" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="line_charge">Line Charge</label>
                                            <input type="text" class="form-control" name="line_charge" :value="line_charge" id="line_charge" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <hr><h4><i class="fa fa-info-circle" aria-hidden="true"></i> Fee</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="add_fee">Add Fee</label>
                                            <input type="text" class="form-control" name="add_fee" readonly :value="add_fee" id="add_fee">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="fee">Fee</label>
                                            <input type="text" class="form-control" name="fee" readonly :value="fee" id="fee">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="line_fee">Line Fee</label>
                                            <input type="text" class="form-control" name="line_fee" :value="line_fee" id="line_fee" readonly>
                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <div class="form-group col-12">
                                            <h4><i class="fa fa-info-circle" aria-hidden="true"></i> VAT</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="add_12_vat">Add 12% VAT</label>
                                            <input type="text" class="form-control" name="add_12_vat" v-model="item.add_12_vat" id="add_12_vat">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="additional_tax">Additional Tax</label>
                                            <input type="text" class="form-control" name="additional_tax" v-model="item.additional_tax" id="additional_tax">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="vat_amount">VAT Amount</label>
                                            <input type="text" class="form-control" name="vat_amount" v-model="item.vat_amount" id="vat_amount">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="line_vat">Line VAT</label>
                                            <input type="text" class="form-control" name="line_vat" v-model="item.line_vat" id="line_vat">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="total_sales_vat_exclusive">Total Sales (VAT Exclusive)</label>
                                            <input type="text" class="form-control" name="total_sales_vat_exclusive" v-model="item.total_sales_vat_exclusive" id="total_sales_vat_exclusive">
                                        </div>

                                        <div class="form-group col-12">
                                            <div class="form-group col-12">
                                                <hr><h4><i class="fa fa-info-circle" aria-hidden="true"></i> Withholding Tax</h4><hr>
                                            </div>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="less_withholding_tax">Less Withholding Tax</label>
                                            <input type="text" class="form-control" name="less_withholding_tax" v-model="item.less_withholding_tax" id="less_withholding_tax">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="withholding_tax">Withholding Tax</label>
                                            <input type="text" class="form-control" name="withholding_tax" v-model="item.withholding_tax" id="withholding_tax">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="amount_due">Amount Due</label>
                                            <input type="text" class="form-control" name="amount_due" v-model="item.amount_due" id="amount_due">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="vatexempt_sales">VAT-Exempt Sale</label>
                                            <input type="text" class="form-control" name="vatexempt_sales" v-model="item.vatexempt_sales" id="vatexempt_sales">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="zero_rated_sales">Zero-Rated Sales</label>
                                            <input type="text" class="form-control" name="zero_rated_sales" v-model="item.zero_rated_sales" id="zero_rated_sales">
                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <div class="form-group col-12">
                                            <h4><i class="fa fa-info-circle" aria-hidden="true"></i> Payment</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="total_round_off">Round Off</label>
                                            <input type="number" class="form-control" id="total_round_off" v-model="item.total_round_off" name="total_round_off" readonly="readonly">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="total_amount_due">Total Amount Due</label>
                                            <input type="text" class="form-control" name="total_amount_due" v-model="item.total_amount" id="total_amount_due">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="cash_payment">Cash Payment</label>
                                            <input type="text" class="form-control" name="cash_payment" v-model="item.cash_payment" id="cash_payment">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="check_payment">Check Payment</label>
                                            <input type="text" class="form-control" name="check_payment" v-model="item.check_payment" id="check_payment">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="deposit_amount">Deposit Amount</label>
                                            <input id="deposit_amount" name="deposit_amount" type="text" class="form-control" :disabled="method_of_payment != 'Deposit Payment'" v-model="item.deposit_amount">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="other_payment">Other Payment</label>
                                            <input type="text" class="form-control" name="other_payment" v-model="item.other_payment" id="other_payment">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="total_amount_paid">Total Amount Paid</label>
                                            <input type="text" class="form-control" name="total_amount_paid" v-model="item.total_amount_paid" id="total_amount_paid">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="outstanding">Outstanding</label>
                                            <input type="text" class="form-control" name="outstanding" v-model="item.outstanding" id="outstanding">
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row">


                                        <!-- <div class="form-group col-md-3">
                                            <label for="total_sales_vat_exclusive">Total Sales (VAT Exclusive)</label>
                                            <input type="text" class="form-control" name="total_sales_vat_exclusive" v-model="item.total_sales_vat_exclusive" id="total_sales_vat_exclusive">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="add_charge">Add Charge</label>
                                            <input type="text" class="form-control" name="add_charge" v-model="item.add_charge" id="add_charge">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="add_12_vat">Add 12% VAT</label>
                                            <input type="text" class="form-control" name="add_12_vat" v-model="item.add_12_vat" id="add_12_vat">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="total_sales_vat_inclusive">Total Sales (VAT Inclusive)</label>
                                            <input type="text" class="form-control" name="total_sales_vat_inclusive" v-model="item.total_sales_vat_inclusive" id="total_sales_vat_inclusive">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="less_withholding_tax">Less Withholding Tax</label>
                                            <input type="text" class="form-control" name="less_withholding_tax" v-model="item.less_withholding_tax" id="less_withholding_tax">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="amount_due">Amount Due</label>
                                            <input type="text" class="form-control" name="amount_due" v-model="item.amount_due" id="amount_due">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="vatable_sales">Valuable Sales</label>
                                            <input type="text" class="form-control" name="vatable_sales" v-model="item.vatable_sales" id="vatable_sales">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="vatexempt_sales">VAT-Exempt Sale</label>
                                            <input type="text" class="form-control" name="vatexempt_sales" v-model="item.vatexempt_sales" id="vatexempt_sales">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="zero_rated_sales">Zero-Rated Sales</label>
                                            <input type="text" class="form-control" name="zero_rated_sales" v-model="item.zero_rated_sales" id="zero_rated_sales">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="vat_amount">Vat Amount</label>
                                            <input type="text" class="form-control" name="vat_amount" v-model="item.vat_amount" id="vat_amount">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="total_amount_due">Total Amount Due</label>
                                            <input type="text" class="form-control" name="total_amount_due" v-model="item.total_amount" id="total_amount_due">
                                        </div>

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="total_quantity">Total Quantity</label>
                                                        <input type="number" class="form-control" id="total_quantity" name="total_quantity" v-model="item.total_quantity" readonly="readonly">
                                                    </div>  
                                                    <div class="form-group">
                                                        <label for="total_discount">Total Discount</label>
                                                        <input type="number" class="form-control" id="total_discount" name="total_discount" v-model="item.total_discount" readonly="readonly">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="total_cash_discount">Total Cash Discount</label>
                                                        <input type="number" class="form-control" id="total_cash_discount" readonly="readonly">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="total_charges">Total Charges</label>
                                                        <input type="number" class="form-control" name="total_charges" v-model="item.total_charges" id="total_charges" readonly="readonly">
                                                    </div>
                                                    <div class="form-group text-bottom">
                                                        <label for="total_sales_tax">Total Sales Tax</label>
                                                        <input type="number" class="form-control" name="total_sales_tax" v-model="item.total_sales_tax" id="total_sales_tax" readonly="readonly">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">  
                                                    <div class="form-group">
                                                        <label for="total_round_off">Total Round Off</label>
                                                        <input type="number" class="form-control" id="total_round_off" v-model="item.total_round_off" name="total_round_off" readonly="readonly">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sub_total_amount">Sub Total Amount</label>
                                                        <input type="number" class="form-control" id="sub_total_amount" v-model="item.sub_total_amount" name="sub_total_amount" readonly="readonly">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="total_amount">Total Amount</label>
                                                        <input type="number" class="form-control" id="total_amount" name="total_amount" v-model="item.total_amount" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->

                                </div>
                            </div>

                            <div class="tab-pane" id="subsidiary_ledger">
                                <subsidiary-view
                                    :clients="clients"
                                    :invoice-approval-url="invoiceApprovalUrl"
                                    :vendor-payment-url="vendorPaymentUrl"
                                    :general-journal-url="generalJournalUrl"
                                ></subsidiary-view>
                            </div>

                            <div class="tab-pane" id="checks">
                                <div class="col-xs-12">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"><a @click="initList('table-7')" class="nav-link active" href="#checks-active" data-toggle="tab">Active</a></li>
                                                <li class="nav-item"><a @click="initList('table-8')" class="nav-link" href="#checks-archived" data-toggle="tab">Archived</a></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane show active" id="checks-active">
                                                    <check-table 
                                                        :clients="clients"
                                                        :fetch-url="checksActive"
                                                        ref="table-7"
                                                    ></check-table>
                                                </div>
                                                <div class="tab-pane" id="checks-archived">
                                                    <check-table 
                                                        :clients="clients"
                                                        :fetch-url="checksArchived"
                                                        ref="table-8"
                                                    ></check-table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </card>
   
        
        <!-- Vendor Payment Line Form -->
        
        <div class="modal fade"  id="vendor_payment_line_form">
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
                                        <input readonly class="form-control mb-2" v-model="clientName">
                                        <label>Vendor Invoice Line Number</label>
                                        <input readonly class="form-control mb-2" v-model="itemLine.vendor_invoice_line_number">
                                        <label>Vendor Account</label>
                                        <input readonly v-model="itemLine.vendor_account" class="form-control mb-2">
                                        <label>Invoice Account</label>
                                        <input readonly v-model="itemLine.invoice_account" class="form-control mb-2">
                                        <label>Vendor Name</label>
                                        <input readonly v-model="itemLine.vendor_name" type="text" class="form-control mb-2">

                                        <h4><i class="fas fa-truck"></i> Delivery</h4><hr>
                                        <!-- Inherit from parent -->
                                        <label>Delivery Date</label>
                                        <input readonly v-model="itemLine.delivery_date" class="form-control mb-2">
                                        <label>Delivery Type</label>
                                        <input readonly v-model="itemLine.mode_of_delivery_type" class="form-control mb-2">

                                        <h4><i class=""></i> Related</h4>
                                        <label>Vouchers</label>
                                        <input readonly v-model="itemLine.vouchers" type="text" class="form-control mb-2">
                                        <label>Purchase Order Number</label>
                                        <input readonly class="form-control mb-2" v-model="itemLine.purchase_order_number">
                        
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h4>Status</h4><hr>
                                        <label>Line Status</label>
                                        <input readonly class="form-control mb-2" v-model="itemLine.line_status">

                                        <h4><i class="fas fa-money-bill"></i> Ledger</h4><hr>
                                        <label>Subledger Journal</label>
                                        <input  v-model="itemLine.subledger_journal" class="form-control mb-2">
                                        <label>Ledger Account</label>
                                        <input v-model="itemLine.ledger_account" class="form-control mb-2">

                                        <label>Receive Now Quantity</label>
                                        <input type="number" v-model="itemLine.receive_now_quantity" class="form-control mb-2">

                                        <h4 class="mt-4"><i class="fas fa-dollar-sign"></i> Sales Tax</h4><hr>
                                        <label>Item Sales Tax Group</label>
                                        <input v-model="itemLine.item_sales_tax_group" class="form-control mb-2">
                                        <label>Sale Tax Group</label>
                                        <input v-model="itemLine.sale_tax_group" class="form-control mb-2">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h4><i class="fas fa-cubes"></i> Item Type</h4><hr>

                                        <label>Order Line Type <b class="text-danger">*</b></label>
                                        <select class="form-control mb-2" v-model="itemLine.order_line_type">
                                            <option value="services">Services</option>
                                            <option value="product">Product</option>
                                            <option value="asset">Asset</option>
                                            <option value="others">Others</option>
                                        </select>
                                        <label>Description</label>
                                        <input class="form-control mb-2" v-model="itemLine.description">


                                        <h4><i class="fas fa-cubes"></i> Service</h4><hr>
                                        <label>Service <b class="text-danger">*</b></label>
                                        <v-select class="mb-2" v-model="itemLine.service_id" :reduce="item => item.id" label="name" :resetOnOptionsChange="true" placeholder="Select Service" :options="services" :disabled="itemLine.order_line_type === 'product'"></v-select>
                                        <label>Service Number</label>
                                        <input class="form-control mb-2" :value="itemLine.service ? itemLine.service.service_number : null" readonly>
                                        <label>Service Task</label>
                                        <v-select class="mb-2" v-model="itemLine.service_task" :reduce="item => item.id" label="service" :resetOnOptionsChange="true" placeholder="Select Service Task" :options="service_tasks" :disabled="itemLine.order_line_type === 'product'"></v-select>
                                        <!-- <input class="form-control mb-2" v-model="itemLine.service_task" :readonly="itemLine.order_line_type === 'product'"> -->
                                        <label>Service Task Details</label>
                                        <input class="form-control mb-2" v-model="itemLine.service_task_details" :readonly="itemLine.order_line_type === 'product'">
                                        <label>RPM Method</label>
                                        <input class="form-control mb-2" v-model="itemLine.rpm_method" :readonly="itemLine.order_line_type === 'product'">
                                        <label>Number of Hours</label>
                                        <input class="form-control mb-2" v-model="itemLine.number_of_hours" :readonly="itemLine.order_line_type === 'product'">

                                        
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h4><i class="fas fa-cubes"></i> Item</h4><hr>
                                        <label>Product</label>
                                        <v-select v-model="itemLine.product_id" @input="displayRelatedVariants" :options="items" label="name" placeholder="Select Product" :reduce="item => item.id" class="mb-2" :disabled="itemLine.order_line_type === 'services'"></v-select>

                                        <label>Variant</label>
                                        <v-select ref="variant" @input="findAndInheritVariantDetails" class="mb-2" v-model="itemLine.variant_id" :reduce="item => item.id" label="name" :options="related_variants" :disabled="itemLine.order_line_type === 'services'"></v-select>
                                        
                                        <label>Specification <b class="text-danger">*</b></label>
                                        <v-select class="mb-2" v-model="itemLine.specification_id" :reduce="item => itemLine.id" label="specification_name" placeholder="Select Specification" :options="specifications" :disabled="itemLine.order_line_type === 'services'"></v-select>

                                        <label>Procurement Category</label>
                                        <select ref="procurement_category" v-model="itemLine.procurement_category" class="form-control mb-2">
                                            <option value="Air">Air</option>
                                            <option value="Sea">Sea</option>
                                            <option value="Land">Land</option>
                                        </select>

                                        <label>Size</label>
                                        <input v-model="itemLine.size"  maxlength="8" type="tel" class="form-control mb-2" :readonly="itemLine.order_line_type === 'services'">

                                        <label>Color</label>
                                        <input v-model="itemLine.color" maxlength="8" type="tel" class="form-control mb-2" :readonly="itemLine.order_line_type === 'services'">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h4>&zwnj;</h4><hr>
                                        <!-- <label>Description</label>
                                        <input v-model="itemLine.description" type="tel" maxlength="5" class="form-control mb-2"> -->
                                        <label>Unit Price</label>
                                        <input type="number" class="form-control" name="price_per_unit" v-model="itemLine.price_per_unit" id="price_per_unit" readonly="readonly">
                                        <label>Quantity <b class="text-danger">*</b></label>
                                        <input type="number" class="form-control" id="total_quantity" name="total_quantity" v-model="item.total_quantity" readonly="readonly">
                                        <label>Amount</label>
                                        <input v-model="itemLine.amount" type="number" class="form-control mb-2">
                                        <label>Discount</label>
                                        <input type="number" class="form-control" name="cash_discount" v-model="item.cash_discount" id="cash_discount">
                                        <label>Discount Percentage</label>
                                        <input type="number" class="form-control" name="cash_discount_percentage" v-model="item.cash_discount_percentage" id="cash_discount_percentage">
                                        <label>Charges on Purchases</label>
                                        <input v-model="itemLine.charge_on_purchase" type="number" class="form-control mb-2">
                                        <h4>&zwnj;</h4><hr>
                                        <label>Account</label>
                                        <v-select class="mb-2" v-model="itemLine.procurement_id" placeholder="Select Procurement"  :reduce="item => itemLine.id" label="main_account_name" :options="procurements"></v-select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
                                        <label>Created By</label>
                                        <input readonly  v-model="itemLine.creator" class="form-control mb-2">
                                        <label>Created On</label>
                                        <input readonly :value="itemLine.formatted_created_date" class="form-control mb-2">
                                        <label>Updated By</label>
                                        <input readonly :value="itemLine.updater"  class="form-control mb-2">
                                        <label>Updated On</label>
                                        <input readonly :value="itemLine.formatted_updated_date" class="form-control mb-2">

                                        <h4>&zwnj;</h4><hr>
                                        
                                        <h4 class="mt-4"><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
                                        <label>Cost Center <b class="text-danger">*</b></label>
                                        <v-select class="mb-2" v-model="itemLine.cost_center_id" :reduce="item => itemLine.id" label="dimension_name" :options="cost_centers"></v-select>
                                        <label>Department <b class="text-danger">*</b></label>
                                        <v-select class="mb-2" v-model="itemLine.department_id" :reduce="item => itemLine.id" label="dimension_name" :options="departments"></v-select>
                                        <label>Expense Purpose <b class="text-danger">*</b></label>
                                        <v-select class="mb-2" v-model="itemLine.expense_purpose_id" :reduce="item => item.id" label="dimension_name" :options="expense_purposes"></v-select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" :disabled="isNotPending(itemLine) || isItemLineFormNotValid(itemLine)" class="btn btn-primary" @click="saveLine">Save changes</button>
                        </div>
                    </div>
                </div>
            </form-request>
        </div>
    </form-request>
    </div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import ResponseMixin from 'Mixins/response.js';
    import Card from 'Components/containers/Card.vue';
    import Datepicker from 'vuejs-datepicker';
    import flatpickr from 'flatpickr';
	import { ModelListSelect } from 'vue-search-select'
    import Vselect from 'vue-select';
    import FormRequest from 'Components/forms/FormRequest.vue';
    import DataTable from 'Components/tables/DataTable.vue';
    
    export default {
        mixins: [ CrudMixin, ResponseMixin ],
        
        props: {
            vendorInvoiceCode : String,
            paymentNumber: String,
            submitItemLineUrl: String,

            invoiceApprovalUrl: String,
            vendorPaymentUrl: String,
            generalJournalUrl: String,

            checksActive: String,
            checksArchived: String,
        },

		components: {
            'form-request': FormRequest,
            'card': Card,
		    'datepicker': Datepicker,
            'v-select' : Vselect, 
            'data-table': DataTable,
			ModelListSelect
        },

        data() {            
            return {
                item: { itemLines: [] },
                data_table_key : null,
                itemLine: this.defaultItemLine(),
                formKey: 0,
                vendor_invoices: [],
                vendors: [],
                items: [],
                variants: [],
                related_variants: [],
                method_of_payments: [],
                cost_centers: [],
                expense_purposes: [],
                departments: [],
                settlement_type_list: [],
                bank_transaction_types: [],
                postdated_check_statuses: [],
                clients: [],
                services: [],
                service_tasks: [],
                procurements: [],
                specifications: [],
                clientName: null,
                item_line_statuses: [],
                charges_on_lines: [],
                charges_on_header: [],
                procurement_categories: [ 
                    { value: "Air" }, 
                    { value: "Land" }, 
                    { value: "Sea" }
                ],
                payment_statuses: [
                    { value: 'None' },
                    { value: 'Sent' },
                    { value: 'Received' },
                    { value: 'Approved' },
                    { value: 'Rejected' }
                ],
                transaction_types : ['Sales', 'Purchase', 'Both'],
                posting_profiles: [],
                client_id : null,
                client_banks: [],
                vendor_banks: [],
                checks: [],
                deposits: [],
                bank_statements: [],
                bank_postings: [],
                bank_reasons: [],
                bank_reconciliations: [],
                method_of_payment: '',
            }
        },

        computed: {
            total_quantity() {
                let result = 0;
                let itemLines = this.item.itemLines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        result += line.quantity;
                    });
                }

                return result;
            },

            total_vattable_sales_vat_exclusive() {
                let result = 0;
                let itemLines = this.item.itemLines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.amount);
                        result += amount;
                    });
                }

                return result;
            },

            less_discount() {
                let result = 0;
                let itemLines = this.item.itemLines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.less_discount);
                        result += amount;
                    });
                }

                return result;
            },

            cash_discount() {
                let result = 0;

                return result;
            },

            charge() {
                let result = 0;

                return result;
            },

            add_charge() {
                let result = 0;
                let itemLines = this.item.itemLines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.add_charge);
                        result += amount;
                    });
                }

                return result;
            },

            add_fee() {
                let result = 0;

                return result;
            },

            fee() {
                let result = 0;

                return result;
            },

            line_fee() {
                let result = 0;

                return result;
            },

            line_discount() {
                let result = 0;
                let itemLines = this.item.itemLines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.discount);
                        result += amount;
                    });
                }

                return result;
            },

            line_charge() {
                let result = 0;
                let itemLines = this.item.itemLines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.charges_on_purchases);
                        result += amount;
                    });
                }

                return result;
            },

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

            hasNoApprovedLine() {
                if (! this.item.itemLines) return false;
                var approvedItem = this.item.itemLines.find(item => item.approved_by_id != null);
                return approvedItem == null;
            },

            client_bank_account() {
                let item = this.client_banks.find((data) => {
                    return data.bank_account == this.item.bank_account;
                });

                if(item) {
                    this.item.bank_account_type = item.bank_account_type;
                    this.item.issuing_bank_branch = item.bank_name;
                    this.item.issuing_bank_branch_name = item.bank_branch;
                }

                return item ? item : {};
            },

            vendor_bank_account() {
                let item = this.vendor_banks.find((data) => {
                    return data.bank_account == this.item.vendor_bank_account;
                });

                return item ? item : {};
            },

            params() {
                return { ... this.item, method_of_payment:this.method_of_payment }
            },

            totalVat() {
                var gross_amount = parseFloat(this.item.total_sales_vat_exclusive);
                var vat = 12 / 100;

                this.item.add_12_vat = gross_amount * vat;

                return gross_amount * vat;
            },

            totalSales() {

                var gross_amount = parseFloat(this.item.total_sales_vat_exclusive);
                var less_amount = parseFloat(this.item.less_discount);
                var charge = parseFloat(this.item.add_charge);
                var vat = parseFloat(this.item.add_12_vat);

                this.item.total_sales_vat_inclusive = (gross_amount - less_amount) + charge + vat;
                this.item.total_round_off = Math.round(gross_amount)

                return (gross_amount - less_amount) + charge + vat;
            }
        },

        // watch the data value change 
        watch : {
            'client_id'(value) {
                if(value) {
                    let client = this.clients.filter(item => item.id == value)[0];
            
                    if(client.code) {
                        this.item.vendor_payment_number = client.code;
                        this.item.client = client;
                    }else {
                        this.item.vendor_payment_number = this.paymentNumber;
                    }
                }
				this.item.client_id = value;
			},

            'item.client_id'(value) {
                this.item.itemLines.forEach(line => {
                    line.client_id = value;
                    this.inheritClientName(value);
                });
            },

            'item.payee'(value) {
                if (this.timeout) {
                    clearTimeout(this.timeout);
                }

                this.timeout = setTimeout(() => {
                    if (value) {
                        if (this.item.itemLines) {
                            this.item.itemLines.forEach((item, index) => {
                                item.payee = value;
                            });
                        }
                    }
                }, 200);
            },

            'itemLine.service_id'(value) {
                if(value) {
                    this.itemLine.service = this.services.filter(item => item.id == value)[0];
                    this.itemLine.price_per_unit = this.itemLine.service.unit_price;
                    
                    this.service_tasks = this.itemLine.service.service_tasks;
                }       
            },

            'itemLine.service_task'(value) {
                if(value) {
                    var task = _.find(this.service_tasks, (task) => { return task.id === value });
                    this.itemLine.service_task_details = task.description;
                    this.itemLine.rpm_method = task.rpm_method;
                    this.itemLine.number_of_hours = task.base_hour;
                }       
            },


            'item.method_of_payment_id'(value) {
                let method = this.method_of_payments.find(item => {
                    return item.id == value;
                });

                if(method) {
                    this.item.method_of_payment = method.method_of_payment;
                    this.method_of_payment = method.method_of_payment;
                    switch(method.method_of_payment) {
                        case 'Check Payment':
                        break;
                        case 'Deposit Payment':
                        break;
                    }
                }
            },

            'itemLine.charge_id'(value) {
                var charge = _.find(this.charges_on_lines, (charge) => { return charge.id == value });

                this.itemLine.product_id = charge.product_id;
                this.related_variants = this.variants.filter(variant => variant.product_id == charge.product_id);

                this.itemLine.procurement_id = charge.procurement_id;
                this.itemLine.service_id = charge.service_id;

                this.itemLine.service = _.find(this.services, (service) => { return service.id == charge.service_id });
                this.service_tasks = this.itemLine.service.service_tasks;
                this.itemLine.variant_id = charge.variant_id;

                this.itemLine.service_task = charge.service_task_id;
                var task = _.find(this.service_tasks, (task) => { return task.id === charge.service_task_id });

                this.itemLine.service_task_details = task.description;
                this.itemLine.rpm_method = task.rpm_method;
                this.itemLine.number_of_hours = task.base_hour;

                var variant = _.find(this.variants, (variant) => { return variant.id == charge.variant_id });

                this.itemLine.price_per_unit = this.itemLine.order_line_type === 'services' ? this.itemLine.service.unit_price : variant.unit_price;

            },
        },

        mounted() {

            flatpickr(this.$refs.issue_date);
            flatpickr(this.$refs.payment_release_date);
            flatpickr(this.$refs.clearing_date);
            flatpickr(this.$refs.due_date);
            flatpickr(this.$refs.check_number_issued);
            flatpickr(this.$refs.maturity_date);
            flatpickr(this.$refs.received_date);
            flatpickr(this.$refs.deposit_date);
            flatpickr(this.$refs.bank_statement_issued_date);
            flatpickr(this.$refs.reconciled_date);
            flatpickr(this.$refs.adjustment_date);

            this.settlement_type_list = [
                { id: 1, value: 'None' },
                { id: 2, value: 'Open Transactions' },
                { id: 3, value: 'Designated Transactions' },
            ];

            this.item_line_statuses = [
                { id: 1, value: 'None' },
                { id: 2, value: 'Sent' },
                { id: 3, value: 'Received' },
                { id: 4, value: 'Approved' },
                { id: 5, value: 'Rejected' }
            ]

            this.bank_transaction_types = [
                { id: 1,name: 'Deposit' },
                { id: 2,name: 'Deposit-Electronic' },
                { id: 3,name: 'Payment-Fixed' },
                { id: 4,name: 'Payment-Manual' },
                { id: 5,name: 'Payment-Electronic' },
                { id: 6,name: 'Transfer' },
                { id: 7,name: 'Transfer-Wire' },
                { id: 8,name: 'Non-sufficient '}
            ];

            this.postdated_check_statuses = [
                { id: 1, value: 'Open' },
                { id: 2, value: 'On hold' },
                { id: 3, value: 'Paid' },
                { id: 4, value: 'Posted' },
                { id: 5, value: 'Cancelled' }
            ];

            this.initItem();
        },

        methods: {
            fetchSuccess(data) {
                this.item = data.item ?? this.item;
          
                this.vendors = data.vendors ?? this.vendors;
                this.items = data.items ?? this.items;
                this.variants = data.variants ?? this.variants;
                this.method_of_payments = data.method_of_payments ?? this.method_of_payments;
                this.cost_centers = data.cost_centers ?? this.cost_centers;
                this.expense_purposes = data.expense_purposes ?? this.expense_purposes;
                this.departments = data.departments ?? this.departments;
                this.settlement_type_list = data.settlement_type_list ?? this.settlement_type_list;

                this.clients = data.clients ?? this.clients;
                this.services = data.services ?? this.services;
                this.procurements = data.procurements ?? this.procurements;
                this.specifications = data.specifications ?? this.specifications;
                
                this.vendor_invoices = data.vendor_invoices ?? this.vendor_invoices;
                this.posting_profiles = data.posting_profiles ?? this.posting_profiles;

                this.client_banks = data.client_banks ?? this.client_banks;
                this.vendor_banks = data.vendor_banks ?? this.vendor_banks;
                this.checks = data.checks ?? this.checks;
                this.deposits = data.deposits ?? this.deposits;
                this.bank_statements = data.bank_statements ?? this.bank_statements;
                this.bank_postings = data.bank_postings ?? this.bank_postings;
                this.bank_reasons = data.bank_reasons ?? this.bank_reasons;
                this.bank_reconciliations = data.bank_reconciliations ?? this.bank_reconciliations;
                
                this.charges_on_lines = data.charges_on_lines ?? this.charges_on_lines;
                this.charges_on_header = data.charges_on_header ?? this.charges_on_header;

                if (this.vendorInvoiceCode) {
                    this.assignInvoiceNumber(this.vendorInvoiceCode);
                }

            },

            generatePurchaseDeliveryReceipt() {
                var $this = this;
                swal.fire({
                  title: 'Are you sure?',
                  text: 'Are you sure you want to generate sales delivery receipt?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Confirm',
                  cancelButtonText: 'Cancel'
                }).then((result) => {
                  if (result.value) {
                    window.location.href = $this.item.generatePurchaseDeliveryReceiptUrl;
                  }
                })
            },

            generatePaymentSchedule() {
                var $this = this;
                swal.fire({
                  title: 'Are you sure?',
                  text: 'Are you sure you want to generate payment schedule?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Confirm',
                  cancelButtonText: 'Cancel'
                }).then((result) => {
                  if (result.value) {
                    window.location.href = $this.item.generatePaymentScheduleUrl;
                  }
                })
            },

            saveLine() {
                $('#vendor_payment_line_form').modal('hide');
                if (! this.item.itemLines) {
                    this.item['itemLines'] = [];
                }

                const itemLineIndex = this.item.itemLines.findIndex(itemLine => itemLine.payment_line_number == this.itemLine.payment_line_number);
                if (itemLineIndex != -1) {
                    // update new item line
                    this.item.itemLines[itemLineIndex] = this.itemLine;
                } else {
                    // create new item line
                    this.item.itemLines.push(this.itemLine);
                }
                this.computeVendorPaymentLines();
                this.resetItemLine();
            },

            // Computer Vendor Payment in Header
            computeVendorPaymentLines() {
                this.item.total_quantity = 0;
                this.item.total_discount = 0;
                this.item.total_sales_tax = 0;
                this.item.total_charges = 0;
                this.item.sub_total_amount = 0;
                this.item.total_amount = 0;
                this.total_round_off = 0;
                
                this.item.itemLines.forEach((itemLine, _) => {
                    this.item.total_quantity += 1*itemLine.quantity || 0;
                    this.item.total_discount += 1*itemLine.total_discount || 0; // round
                    this.item.total_sales_tax += 1*itemLine.sales_tax_amount || 0; // round
                    this.item.total_charges += 1*itemLine.charges_on_purchases || 0; // round
                    this.item.sub_total_amount += 1*itemLine.sub_total_amount || 0;
                    this.item.total_amount += 1*itemLine.amount || 0;
                });

                this.item.total_discount = this.roundNumber(this.item.total_discount);
                this.item.total_sales_tax = this.roundNumber(this.item.total_sales_tax);
                this.item.total_round_off = Math.round(this.item.total_amount);
                this.item.sub_total_amount = this.roundNumber(this.item.sub_total_amount);

                this.item.total_charges = this.roundNumber(this.item.total_charges);
                this.item.total_amount = this.roundNumber(this.item.total_amount);

                this.item.check_amount = this.item.total_amount;

            },

            initItem() {
                this.item = {
                    settlement_type: null,
                    dimension_value_cost_center_id: null,
                    dimension_value_department_id: null,
                    dimension_value_expense_purpose_id: null,
                    method_of_payment_id: null,
                    payment_status: 'None',
                    transaction_type: 'Sales',
                    vendor_account_id: null,
                    itemLines: []
                };
                if (this.paymentNumber) {
                    this.item['vendor_payment_number'] = this.paymentNumber;
                }
            },

            // inherit similar attributes from vendor invoice
            inheritInvoiceVendorAccount(invoiceId) {
                // TODO: change this to binary search
                // TODO: better yet use axios to GET this from the backend
                // rushing us so yeah...
                const invoice = this.vendor_invoices.find(invoice => invoice.id == invoiceId);
                const vendor = this.vendors.find(vendor => vendor.vendor_account == invoice.vendor_account);
                // header inherit
                this.client_id = invoice.client_id;
                this.item.id = invoice.id;
                this.item.client_id = invoice.client_id;
                this.item.transaction_type = invoice.transaction_type;
                this.item.vendor_account_id = vendor.id;
                this.item.vendor_account = invoice.vendor_account;
                this.item.invoice_account = invoice.invoice_account;
                this.item.vendor_name = invoice.vendor_name;
                this.item.vendor_address = invoice.vendor_address;
                this.item.vendor_contact_id = invoice.vendor_contact_id;

                this.item.due_date = invoice.payment_due_date;
                this.item.payment_release_date = invoice.invoice_payment_release_date ?? this.item.payment_release_date;
                this.item.sales_tax_group = invoice.sales_tax_group;
                this.item.tax_exempt_group = invoice.tax_exempt_number;
                this.item.cash_discount = invoice.cash_discount;
                this.item.cash_discount_code = invoice.cash_discount_code;

                this.item.prices_included_sales_tax = invoice.prices_include_sales_tax_checkbox;
                this.item.ignore_calculated_tax = invoice.ignore_calculated_sales_tax_checkbox;

                // Financial Dimension inherit
                this.item.dimension_value_cost_center_id = invoice.cost_center_id;
                this.item.dimension_value_department_id = invoice.department_id;
                this.item.dimension_value_expense_purpose_id = invoice.expense_purpose_id;

                this.item.posting_profile = invoice.posting_profile;
                this.item.accounting_distribution = invoice.accounting_distribution;
                
                // Check Payment Inherit
                const settlement_type = this.settlement_type_list.find(settlement_type => settlement_type.value == 'Open Transactions');
                if (settlement_type) {
                    this.item.settlement_type = settlement_type.id;
                }
                this.item.method_of_payment_id = invoice.method_of_payment;
                this.item.payment_specification = invoice.payment_specification;

                let itemLines = [];
                invoice.vendor_invoice_lines.forEach((item, _) => {
                    let itemLine = item;
                    itemLine.vendor_account = this.item.vendor_account;
                    itemLine.vendor_id = this.item.vendor_account_id;
                    itemLine.payee = this.item.payee;
                    itemLine.invoice_account = this.item.invoice_account;

                    itemLine.payee = this.item.payee;

                    itemLine.invoice_number = invoice.vendor_invoice_number;
                    itemLine.purchase_order_number = invoice.purchase_order_number;

                    itemLine.payment_line_number = this.generateLineNumber();
                    itemLine.vendor_invoice_id = invoice.id;
                    itemLine.item_sales_tax_group = item.item_sales_tax_group;
                    itemLine.sales_tax_group = item.sales_tax_group;
                    
                    itemLine.procurement_category = item.procurement_category;

                    // inheirt line product 
                    itemLine.subledger_journal = item.subledger_journal;
                    itemLine.ledger_account = item.ledger_account;
                    itemLine.line_status = item.line_status;


                    // inherit item line financial dimension
                    itemLine.dimension_value_cost_center_id = invoice.cost_center_id;
                    itemLine.dimension_value_department_id = invoice.department_id;
                    itemLine.dimension_value_expense_purpose_id = invoice.expense_purpose_id;

                    itemLines.push(itemLine);   
                });
                this.item.itemLines = itemLines;
                this.computeVendorPaymentLines(); 
            },

            findCostCenter(code) {
                // TODO: refactor this to axios get or binary search
                //       rushing us so yeah...
                return this.cost_centers.find(cost_center => cost_center.financial_dimension_value_code == code);
            },

            findDepartment(code) {
                // TODO: refactor this to axios get or binary search
                //       rushing us so yeah...
                return this.departments.find(department => department.financial_dimension_value_code == code);
            },

            findExpensePurpose(code) {
                // TODO: refactor this to axios get or binary search
                //       rushing us so yeah...
                return this.expense_purposes.find(expense_purpose => expense_purpose.financial_dimension_value_code == code);
            },

            displayRelatedVariants(itemId) {
                this.itemLine.item = this.items.find(item => item.id == itemId);
                this.setRelatedVariants(itemId);
            },

            setRelatedVariants(itemId) {
                this.related_variants = this.variants.filter(variant => variant.product_id == itemId);
            },

            findAndInheritVariantDetails(itemId) {
                // TODO: change this to binary search
                // TODO: better yet use axios to GET this from the backend
                // rushing us so yeah...
                const variant = this.related_variants.find(variant => variant.id == itemId);
                this.inheritVariantDetails(variant);
            },

            inheritVariantDetails(variant, itemLine) {
                if (! itemLine) {
                    itemLine = this.itemLine;
                }

                if (variant) {
                    delete variant.quantity;
                    delete variant.is_available;
                    itemLine.variant = variant;
                }
            },

            generateLineNumber() {
                var date = new Date();
                var time = Math.round(date.getTime() / 1000);
                var lineNumber = date.getDate().toString() + date.getMonth().toString() + date.getFullYear().toString() +'-'+ time.toString();
                lineNumber += "-" + Math.random().toString(36).substring(2, 6);
                return lineNumber;
            },

            inheritItemLineDetailsFromParent() {
                
                this.resetItemLine();
                this.itemLine.payment_line_number = this.generateLineNumber();

                this.itemLine.vendor_account = this.item.vendor_account;
                this.itemLine.vendor_id = this.item.vendor_account_id;
                this.itemLine.payee = this.item.payee;
                this.itemLine.invoice_account = this.item.invoice_account;
                this.itemLine.vendor_payment_id = this.item.id;
                
                // inherit financial dimension
                this.itemLine.dimension_value_cost_center_id = this.item.dimension_value_cost_center_id;
                this.itemLine.dimension_value_expense_purpose_id = this.item.dimension_value_expense_purpose_id;
                this.itemLine.dimension_value_department_id = this.item.dimension_value_department_id;

                const invoice = this.vendor_invoices.find(invoice => invoice.id == this.item.vendor_invoice_id);
                if (invoice) {
                    this.itemLine.invoice_number = invoice.vendor_invoice_number;
                    this.itemLine.vendor_invoice_id = invoice.id;
                    this.itemLine.purchase_order_number = invoice.purchase_order_number;
                }

                this.inheritClientName(this.item.client_id);

                this.formKey += 1;
            },

            inheritClientName(clientId) {
                const client = this.clients.find(client => client.id == clientId) ?? {};
                this.clientName = client.name;
            },

            calculateAmount() {
                this.itemLine.total_discount = 0;
                if (this.itemLine.price_per_unit && this.itemLine.quantity) {
                    this.itemLine.sub_total_amount = this.itemLine.price_per_unit * this.itemLine.quantity;
                    this.itemLine.amount = this.itemLine.sub_total_amount;
                    if (this.itemLine.discount) {
                        this.itemLine.total_discount = 1*this.itemLine.discount;
                        this.itemLine.amount -= this.itemLine.discount;
                    }
                    if (this.itemLine.discount_percentage) {
                        const discount_percent_value = this.itemLine.amount * (this.itemLine.discount_percentage / 100);
                        this.itemLine.total_discount += 1*discount_percent_value; 
                        this.itemLine.amount -= discount_percent_value;
                    }
                    this.itemLine.sub_total_amount = this.roundNumber(this.itemLine.sub_total_amount);
                    this.itemLine.total_discount = this.roundNumber(this.itemLine.total_discount);
                    this.itemLine.sales_tax_amount = this.roundNumber(this.itemLine.amount * .12);

                    // total charges are computed before sales tax computation
                    if (this.itemLine.charges_on_purchases) {
                        this.itemLine.amount -= this.itemLine.charges_on_purchases;
                    }

                    this.itemLine.amount -= this.itemLine.sales_tax_amount;
                    this.itemLine.amount = this.roundNumber(this.itemLine.amount);
                }
            },

            inputInheritVendorAccount(vendorId) {
                const vendor = this.vendors.find(vendor => vendor.id == vendorId);
                if (vendor) {
                    this.item.vendor_account_id = vendor.id;
                    this.item.vendor_account = vendor.vendor_account;
                    this.item.invoice_account = vendor.vendor_account;
                    this.item.vendor_name = vendor.fullname;
                    this.item.vendor_address = vendor.address;
                    this.item.vendor_contact_id = vendor.fullname;

                    this.item.itemLines.forEach(line => {
                        line.vendor_account = this.item.vendor_account;
                        line.vendor_id = this.item.vendor_account_id;
                        line.invoice_account = this.item.invoice_account;
                    });
                } else {
                    this.item.vendor_account = null;
                    this.item.invoice_account = null;
                    this.item.vendor_name = null;
                    this.item.vendor_address = null;
                    this.item.vendor_contact_id = null;

                    this.item.itemLines.forEach(line => {
                        line.vendor_account = null;
                        line.vendor_id = null;
                        line.invoice_account = null;
                    });
                }
            },

            archiveLine(itemLine) {
                var index = this.item.itemLines.findIndex(line => line.payment_line_number == itemLine.payment_line_number);
                this.item.itemLines.splice(index, 1);
                
                // request to backend to archive itemLine
                if (itemLine.id) {
                    if (! this.item.removeItemLines) {
                        this.item.removeItemLines = [];
                    }
                    this.item.removeItemLines.push(itemLine.id);
                }

                this.computeVendorPaymentLines();
            },
            

            rejectVendorLine(itemLine) {
                itemLine.is_rejected = true;
                itemLine.approved_payment = false;

                var index = this.item.itemLines.findIndex(line => line.payment_line_number == itemLine.payment_line_number);
                this.item.itemLines.splice(index, 1);

                // request to backend to reject this itemLine
                if (itemLine.id) {
                    if (! this.item.rejectItemLines) {
                        this.item.rejectItemLines = [];
                    }
                    this.item.rejectItemLines.push(itemLine);
                }

                this.computeVendorPaymentLines();
            },

            approveVendorLine(itemLine) {
                itemLine.approved_payment = true;
                itemLine.is_rejected = false;
            },

            editVendorLine(itemLine) {
                this.setRelatedVariants(itemLine.product_id);
                if (! itemLine.created_by_user) {
                    itemLine.created_by_user = {};
                }
                if (! itemLine.updated_by_user) {
                    itemLine.updated_by_user = {};
                }
                this.itemLine = Object.assign({}, itemLine);
            },

            roundNumber(number) {
                return Math.round((number + Number.EPSILON) * 100) / 100;
            },

            displayStatusState(itemLine) {
                if (itemLine.approved_payment == 1) {
                    return "Approved";
                }
                if (itemLine.is_rejected == 1) {
                    return "Rejected";
                }
                return "Pending";
            },

            isNotPending(itemLine) {
                return this.displayStatusState(itemLine) != 'Pending';
            },

            resetItemLine() {
                this.itemLine = this.defaultItemLine();
            },

            defaultItemLine() {
                return {
                    line_status : null,
                    variant_id: null,
                    variant: {}, 
                    product_id: null,
                    quantity: null,
                    product: {}, 
                    created_by_user: {}, 
                    updated_by_user: {}, 
                    dimension_value_cost_center_id: null,
                    dimension_value_expense_purpose_id: null,
                    dimension_value_department_id: null,
                    approved_payment: false, 
                    is_rejected: false 
                }
            },

            assignInvoiceNumber(value) {
                const invoice_id = parseInt(value)
                this.item.vendor_invoice_id = parseInt(invoice_id);
                this.inheritInvoiceVendorAccount(parseInt(invoice_id));
            },

            areItemLinesValid(itemLines) {
                return itemLines.find(itemLine => !this.isItemLineFormNotValid(itemLine)) !== undefined;
            },

            isItemLineFormNotValid(itemLine) {
                // const quantity = itemLine.quantity || 0;
                // const price_per_unit = itemLine.price_per_unit || 0;
                // return !itemLine.status || !itemLine.product_id || !itemLine.variant_id ||
                //         quantity < 1 || price_per_unit < 1;
                // const quantity = itemLine.quantity || 0;
                const price_per_unit = itemLine.price_per_unit || 0;
                return !itemLine.status || price_per_unit < 1;
            },

            confirmThisForm() {
                swal.fire({
                    icon: 'warning',
                    title: 'Approve this form?',
                    confirmButtonText: 'Approve',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                }).then(async result => {
                    if (result.value) {
                        this.$loading.show(true);
                        try {
                            var response = await axios.post(this.item.approvalUrl);
                            this.$notification.show(response.data.message, 'Success');
                            this.fetch();
                            this.$loading.show(false);
                        } catch (err) {
                            swal.fire({
                                icon: 'error',
                                title: 'Failed to approve'
                            });
                            this.$loading.show(false);
                        }
                    }
                });
            },

            postedThisForm() {
                swal.fire({
                    icon: 'warning',
                    title: 'Set this form as posted?',
                    confirmButtonText: 'Post',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                }).then(async result => {
                    if (result.value) {
                        this.$loading.show(true);
                        try {
                            var response = await axios.post(this.item.postUrl);
                            this.$notification.show(response.data.message, 'Success');
                            this.fetch();
                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            }
                            this.$loading.show(false);
                        } catch(err) {
                            this.parseError(err, null, {});
                            console.error(err, 'err');
                            this.$loading.show(false);                        
                        }
                    }
                });
            },

            cancel() {
                swal.fire({
                    icon: 'warning',
                    title: 'Are you sure you want to cancel this VP?',
                    confirmButtonText: 'Post',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                }).then(async result => {
                    if (result.value) {
                        this.$loading.show(true);
                        try {
                            var response = await axios.post(this.item.cancelUrl);
                            this.$notification.show(response.data.message, 'Success');
                            this.fetch();
                            this.$loading.show(false);
                        } catch(err) {
                            this.parseError(err, null, {});
                            console.error(err, 'err');
                            this.$loading.show(false);                        
                        }
                    }
                });
            },

            inheritInvoiceVendorAccountChange(value) {
                if(value) {
                    this.inheritInvoiceVendorAccount(value);
                }else {
                    this.item = {
                        itemLines: [],
                        vendor_payment_number : this.paymentNumber 
                    };
                    this.client_id = null;
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