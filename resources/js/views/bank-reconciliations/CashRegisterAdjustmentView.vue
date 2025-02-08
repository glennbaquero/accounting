<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="submitSuccess" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Cash Register Transaction Information
                    <div class="float-right">
                        <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                    </div>
                </template>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#cashflow" data-toggle="tab">Cash Register Transaction</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#audit-log" data-toggle="tab">Audit Log</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="cashflow">
                                        <div class="row">
                                            
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4><i class="fas fa-info-circle"></i> Header Details</h4><hr>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Journal Name <b class="text-danger">*</b></label>
                                                        <input type="text" class="form-control" v-model="item.journal_name">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="voucher_date">Voucher Date  <b class="text-danger">*</b></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                            </div>
                                                            <input ref="voucher_date" type="text" class="form-control calendar-form" id="voucher_date" name="voucher_date" v-model="item.voucher_date" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Vendor Payment Journal Number <b class="text-danger">*</b></label>
                                                        <input type="text" class="form-control" v-model="item.vendor_payment_journal_number">
                                                    </div>
                                              
                                                    <div class="form-group col-md-12">
                                                        <label>Cash Register Transaction ID</label>
                                                        <input type="text" class="form-control" :value="item.cashflow_transaction_id" disabled>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Cash Register Transaction Name <!-- <b class="text-danger">*</b> --></label>
                                                        <input type="text" class="form-control" v-model="item.cashflow_transaction_name">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Transaction Type <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            v-model="item.type" 
                                                            :options="types"
                                                            placeholder="Select a transaction type"
                                                        ></v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Vendor Payment Journal Voucher <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.voucher_number"
                                                            v-model="item.vendor_payment_journal_voucher" 
                                                            :options="vendor_vouchers"
                                                            placeholder="Select a payment voucher"
                                                            label="voucher_number">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Vendor Payment Journal Number <b class="text-danger">*</b></label>
                                                        <input type="text" class="form-control" v-model="item.vendor_payment_journal_number">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Customer Payment Journal Voucher <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.voucher_number"
                                                            v-model="item.customer_payment_journal_voucher" 
                                                            :options="customer_vouchers"
                                                            placeholder="Select a payment customer"
                                                            label="voucher_number">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Customer Payment Journal Number <b class="text-danger">*</b></label>
                                                        <input type="text" class="form-control" v-model="item.customer_payment_journal_number">
                                                    </div>
                              
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4><i class="fas fa-file-invoice-dollar"></i> Invoice</h4><hr>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Vendor Invoice Number <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.vendor_invoice_number"
                                                            v-model="item.vendor_invoice_number" 
                                                            :options="vendorInvoices"
                                                            placeholder="Select invoice"
                                                            label="vendor_invoice_number">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Vendor Account <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.vendor_account"
                                                            v-model="item.vendor_account" 
                                                            :options="vendors"
                                                            placeholder="Select vendor"
                                                            label="fullname">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Customer Invoice Number <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.customer_invoice_number"
                                                            v-model="item.customer_invoice_number" 
                                                            :options="customerInvoices"
                                                            placeholder="Select invoice"
                                                            label="customer_invoice_number">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Customer Account <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.customer_account"
                                                            v-model="item.customer_account" 
                                                            :options="customers"
                                                            placeholder="Select customer"
                                                            label="fullname">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Invoice Date <b class="text-danger">*</b></label>
                                                        <div class="input-group mb-2">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control calendar-form" v-model="item.invoice_date" id="invoice_date" ref="invoice_date">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <h4><i class="fas fa-dollar-sign"></i></i> Sales Tax</h4><hr>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Sales Tax Direction</label>
                                                        <input type="text" class="form-control" v-model="item.sales_tax_direction">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Sales Tax Group</label>
                                                        <input type="text" class="form-control" v-model="item.sales_tax_group">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Withholding Tax Group</label>
                                                        <input type="text" class="form-control" v-model="item.withholding_tax_group">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Item Sales Tax Group</label>
                                                        <input type="text" class="form-control" v-model="item.item_sales_tax_group">
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4>&#8205;</h4><hr>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label>Payment Method - Vendor <b class="text-danger">*</b></label>
                                                        <v-select
                                                            v-model="item.method_of_payment_vendor"
                                                            label="method_of_payment"
                                                            :reduce="item => item.method_of_payment_id"
                                                            placeholder="Select Payment Method"
                                                            :options="vendor_payment_methods"
                                                        ></v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Payment Method - Customer <b class="text-danger">*</b></label>
                                                        <v-select
                                                            v-model="item.method_of_payment_customer"
                                                            label="method_of_payment"
                                                            :reduce="item => item.method_of_payment_id"
                                                            placeholder="Select Payment Method"
                                                            :options="customer_payment_methods"
                                                        ></v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Bank Transaction Type</label>
                                                        <input type="text" class="form-control" v-model="item.bank_transaction_type" name="bank_transaction_type">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Bank Account</label>
                                                        <input type="text" class="form-control" v-model="item.bank_account" name="bank_account">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Description</label>
                                                        <input type="text" class="form-control" v-model="item.description" name="description">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Settlement Type <b class="text-danger">*</b></label>
                                                        <select class="form-control" v-model="item.settlement_type" name="settlement_type">
                                                            <option value="None">None</option>
                                                            <option value="Open transactions">Open transactions</option>
                                                            <option value="Designated transactions">Designated transactions</option>
                                                        </select>
                                                    </div>

                                                    <!-- <div class="form-group col-md-12">
                                                        <label>Voucher Type <b class="text-danger">*</b></label>
                                                        <v-select v-model="item.voucher_type" placeholder="Select Type" :options="voucher_types"></v-select>
                                                    </div> -->

                                                </div>

                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4><i class="fa fa-question-circle"></i> Status</h4><hr>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="payment_status">Payment Status <b class="text-danger">*</b></label>
                                                        <v-select v-model="item.payment_status" :options="payment_statuses" label="value" placeholder="Select Payment Status" :reduce="item => item.value" class="mb-2"></v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="postdated_check_status">Postdated Check Status <b class="text-danger">*</b></label>
                                                        <v-select v-model="item.postdated_check_status" :options="postdated_check_statuses" label="value" placeholder="Postdated Check Status" :reduce="item => item.value" class="mb-2"></v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Posted On</label>
                                                        <input type="text" class="form-control" :value="item.posted_on" name="posted_on" disabled>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Posted By</label>
                                                        <input type="text" class="form-control" :value="item.posted_by" name="posted_by" disabled>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Posted</label>
                                                        <div class="custom-control custom-switch mb-3 mt-2">
                                                        <input type="checkbox" class="custom-control-input" id="posted_checkbox" name="posted_checkbox" v-model="item.posted_checkbox">
                                                            <label class="custom-control-label" for="posted_checkbox">
                                                                <span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
                                                                    {{ item.posted_checkbox ? 'Yes' : 'No'  }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Debit Amount <b class="text-danger">*</b></label>
                                                        <input type="text" class="form-control" v-model="item.debit_amount">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Credit Amount <b class="text-danger">*</b></label>
                                                        <input type="text" class="form-control" v-model="item.credit_amount">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Reconciled</label>
                                                        <div class="custom-control custom-switch mb-3 mt-2">
                                                        <input type="checkbox" class="custom-control-input" id="reconciled_checkbox" name="reconciled_checkbox" v-model="item.reconciled_checkbox">
                                                            <label class="custom-control-label" for="reconciled_checkbox">
                                                                <span class="badge" :class="item.reconciled_checkbox ? 'badge-success' : 'badge-danger'">
                                                                    {{ item.reconciled_checkbox ? 'Yes' : 'No'  }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Reconciled Date</label>
                                                        <input type="text" class="form-control" :value="item.reconciled_date" name="reconciled_date" disabled>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Reconciled By</label>
                                                        <input type="text" class="form-control" :value="item.reconciled_by" name="reconciled_by" disabled>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Adjusted</label>
                                                        <div class="custom-control custom-switch mb-3 mt-2">
                                                        <input type="checkbox" class="custom-control-input" id="adjustment_checkbox" name="adjustment_checkbox" v-model="item.adjustment_checkbox">
                                                            <label class="custom-control-label" for="adjustment_checkbox">
                                                                <span class="badge" :class="item.adjustment_checkbox ? 'badge-success' : 'badge-danger'">
                                                                    {{ item.adjustment_checkbox ? 'Yes' : 'No'  }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Adjustment Date</label>
                                                        <input type="text" class="form-control" :value="item.adjustment_date" name="adjustment_date" disabled>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Adjustment By</label>
                                                        <input type="text" class="form-control" :value="item.adjustment_by" name="adjustment_by" disabled>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Matched</label>
                                                        <div class="custom-control custom-switch mb-3 mt-2">
                                                        <input type="checkbox" class="custom-control-input" id="matched" name="matched" v-model="item.matched">
                                                            <label class="custom-control-label" for="matched">
                                                                <span class="badge" :class="item.matched ? 'badge-success' : 'badge-danger'">
                                                                    {{ item.matched ? 'Yes' : 'No'  }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4><i class="fas fa-money-bill-wave"></i> Payment</h4><hr>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Payment Reference</label>
                                                    <input type="text" class="form-control" v-model="item.payment_reference" name="payment_reference">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Deposit Slip Number</label>
                                                    <input type="text" class="form-control" v-model="item.deposit_slip_number" name="deposit_slip_number">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Check Number</label>
                                                    <input type="text" class="form-control" v-model="item.check_number" name="check_number">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="check_number_issued">Check Number Issued</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="check_number_issued" type="text" class="form-control calendar-form" id="check_number_issued" name="check_number_issued" v-model="item.check_number_issued">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="maturity_date">Maturity Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="maturity_date" type="text" class="form-control calendar-form" id="maturity_date" name="maturity_date" v-model="item.maturity_date">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="received_date">Received Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="received_date" type="text" class="form-control calendar-form" id="received_date" name="received_date" v-model="item.received_date">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="payment_due_date">Payment Due Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="payment_due_date" type="text" class="form-control calendar-form" id="payment_due_date" name="payment_due_date" v-model="item.payment_due_date">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Cashier</label>
                                                    <input type="text" class="form-control" v-model="item.cashier" name="cashier">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Salesperson</label>
                                                    <input type="text" class="form-control" v-model="item.salesperson" name="salesperson">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Issuing Bank Branch</label>
                                                    <input type="text" class="form-control" v-model="item.issuing_bank_branch" name="issuing_bank_branch">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Issuing Bank Name</label>
                                                    <input type="text" class="form-control" v-model="item.issuing_bank_name" name="issuing_bank_name">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Stop Payment</label>
                                                    <div class="custom-control custom-switch mb-3 mt-2">
                                                    <input type="checkbox" class="custom-control-input" id="stop_payment" name="stop_payment" v-model="item.stop_payment">
                                                        <label class="custom-control-label" for="stop_payment">
                                                            <span class="badge" :class="item.stop_payment ? 'badge-success' : 'badge-danger'">
                                                                {{ item.stop_payment ? 'Yes' : 'No'  }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Replacement Check</label>
                                                    <div class="custom-control custom-switch mb-3 mt-2">
                                                    <input type="checkbox" class="custom-control-input" id="replacement_check" name="replacement_check" v-model="item.replacement_check">
                                                        <label class="custom-control-label" for="replacement_check">
                                                            <span class="badge" :class="item.replacement_check ? 'badge-success' : 'badge-danger'">
                                                                {{ item.replacement_check ? 'Yes' : 'No'  }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Original Check</label>
                                                    <div class="custom-control custom-switch mb-3 mt-2">
                                                    <input type="checkbox" class="custom-control-input" id="original_check" name="original_check" v-model="item.original_check">
                                                        <label class="custom-control-label" for="original_check">
                                                            <span class="badge" :class="item.original_check ? 'badge-success' : 'badge-danger'">
                                                                {{ item.original_check ? 'Yes' : 'No'  }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Check Amount</label>
                                                    <input type="text" class="form-control" v-model="item.check_amount">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Recipient Name</label>
                                                    <input type="text" class="form-control" v-model="item.recipient_name">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4><i class="fas fa-users"></i></i> Account</h4><hr>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="postdated_check_status">Main Acount <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.id"
                                                            v-model="item.main_account" 
                                                            :options="mainAccounts"
                                                            placeholder="Select a main account"
                                                            label="main_account_name">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="postdated_check_status">Main Acount Type <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.label"
                                                            v-model="item.account_type" 
                                                            :options="account_types"
                                                            placeholder="Select an account type"
                                                            label="label">
                                                        </v-select>
                                                    </div>


                                                    <div class="form-group col-md-12">
                                                        <label for="postdated_check_status">Offset Acount Type <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.label"
                                                            v-model="item.offset_account_type" 
                                                            :options="account_types"
                                                            placeholder="Select an offset type"
                                                            label="label">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="postdated_check_status">Offset Account <b class="text-danger">*</b></label>
                                                        <v-select class="mb-2" 
                                                            :reduce="item => item.id"
                                                            v-model="item.offset_account" 
                                                            :options="mainAccounts"
                                                            placeholder="Select an offset account"
                                                            label="main_account_name">
                                                        </v-select>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Offset Company Accounts</label>
                                                        <input type="text" class="form-control" v-model="item.offset_company_accounts" name="offset_company_accounts">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Offset Transaction</label>
                                                        <input type="text" class="form-control" v-model="item.offset_transaction_text" name="offset_transaction_text">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Fee Account</label>
                                                        <input type="text" class="form-control" v-model="item.fee_account" name="fee_account">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Fee ID</label>
                                                        <input type="text" class="form-control" v-model="item.fee_id" name="fee_id">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label>Fee Amount</label>
                                                        <input type="text" class="form-control" v-model="item.fee_amount" name="fee_id">
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="audit-log">
                                        <div class="row">
                                            <div class="form-group col-sm-6">
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
    import SetupMixin from 'Mixins/setup.js';

    import FormRequest from 'Components/forms/FormRequest.vue';
    import ActionButton from 'Components/buttons/ActionButton.vue';
    import Datepicker from 'vuejs-datepicker';

    import flatpickr from 'flatpickr';
    import 'flatpickr/dist/flatpickr.css';
    import { ModelListSelect } from 'vue-search-select'
    import Vselect from "vue-select";

    export default {
        mounted() {
            this.mountInputs();
        },

        data() {
            return {
                item: {},
                customer_vouchers: [],
                customer_payment_methods: [],
                vendor_payment_methods: [],
                vendor_vouchers: [],
                client_bank_accounts: [],
                types: ['Vendor', 'Customer'],
                // account_types: ['Ledger', 'Customer', 'Vendor', 'Project', 'Fixed Assets', 'Bank'],
                voucher_types : ['Debit', 'Credit'],

                vendors: [],
                customers: [],
                vendorInvoices: [],
                customerInvoices: [],
                mainAccounts: [],

                postdated_check_statuses: [
                    { id: 1, value: 'Open' },
                    { id: 2, value: 'On hold' },
                    { id: 3, value: 'Paid' },
                    { id: 4, value: 'Posted' },
                    { id: 5, value: 'Cancelled' }
                ],
                payment_statuses: [
                    { value: 'None' },
                    { value: 'Sent' },
                    { value: 'Received' },
                    { value: 'Approved' },
                    { value: 'Rejected' }
                ],
                account_types: [
                    { label: 'Ledger'},
                    { label: 'Customer'},
                    { label: 'Vendor'},
                    { label: 'Project'},
                    { label: 'Fixed assets'},
                    { label: 'Bank'},
                ],
            }
        },

        methods: {
            submitSuccess() {
                this.fetch();
                this.$emit('submit-success');
            },
            
            fetchSuccess(data) {
                this.mainAccounts = data.mainAccounts ? data.mainAccounts : this.mainAccounts;

                this.client_bank_accounts = data.client_bank_accounts ? data.client_bank_accounts : this.client_bank_accounts;
                this.vendor_vouchers = data.vendor_vouchers ? data.vendor_vouchers : this.vendor_vouchers;
                this.customer_vouchers = data.customer_vouchers ? data.customer_vouchers : this.customer_vouchers;
                this.vendor_payment_methods = data.vendor_payment_methods ? data.vendor_payment_methods : this.vendor_payment_methods;
                this.customer_payment_methods = data.customer_payment_methods ? data.customer_payment_methods : this.customer_payment_methods;
                this.item = data.item ? data.item : this.item;
                this.customers = data.customers ? data.customers : this.customers;

                this.vendors = data.vendors ? data.vendors : this.vendors;
                this.vendorInvoices = data.vendorInvoices ? data.vendorInvoices : this.vendorInvoices;
                this.customerInvoices = data.customerInvoices ? data.customerInvoices : this.customerInvoices;
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },

            mountInputs() {
                let options = {
                    enableTime: true,
                };

                flatpickr(this.$refs.invoice_date, options);
                flatpickr(this.$refs.voucher_date);
                flatpickr(this.$refs.check_number_issued);
                flatpickr(this.$refs.maturity_date);
                flatpickr(this.$refs.received_date);
                flatpickr(this.$refs.payment_due_date);
                // flatpickr(this.$refs.bank_statement_from_date, options);
                // flatpickr(this.$refs.bank_statement_to_date, options);
            },
        },

        watch: {
            'item.vendor_payment_journal_voucher'(value) {
                let item = this.vendor_vouchers.find((data) => {
                    return data.voucher_number == value;
                });

                if(item) {
                    this.item = { ...this.item, ...item };
                    this.item.vendor_invoice_number = item.invoice_number;
                }
            },

            'item.customer_payment_journal_voucher'(value) {
                let item = this.customer_vouchers.find((data) => {
                    return data.voucher_number == value;
                });

                if(item) {
                    this.item = { ...this.item, ...item };
                    this.item.customer_payment_journal_number = item.customer_payment_journal_number;
                    this.item.customer_invoice_number = item.invoice_number;
                }
            },
        },

        computed: {
            client_bank_account() {
                let item = this.client_bank_accounts.find((data) => {
                    return data.bank_account == this.item.client_bank_account_number;
                });

                return item ? item : {};
            },

            filteredClientBankAccounts() {
                let items = this.client_bank_accounts.filter((data) => {
                    return data.client_id == this.item.client_id;
                });

                return items;
            },

        },

        components: {
            'form-request': FormRequest,
            'action-button': ActionButton,
            ModelListSelect,
            'v-select' : Vselect,
        },

        mixins: [ CrudMixin, SetupMixin ],

        props: {
            clients: {
                type: Array,
                default: () => [],
            },
        },
    }

</script>