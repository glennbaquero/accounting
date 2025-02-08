<template>
	<div class="modal fade" :id="modalId">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>{{ modalTitle }}</b></h5>
                    <div class="float-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"  @click="addVoucher">Save Changes</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-info-circle"></i> Header Details</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Voucher Number <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control" v-model="item[voucherIdentification]" :name="voucherIdentification" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice Approval Journal Number </label>
                                    <input type="text" class="form-control" :value="journal[journalIdentification]" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice Approval Journal Batch Number </label>
                                    <input type="text" class="form-control" :value="journal.invoice_journal_batch_number" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Journal name </label>
                                    <input type="text" class="form-control" :value="journal.journal_name" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Cost Center </label>
                                    <input type="text" class="form-control" :value="journal.cost_center" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Department </label>
                                    <input type="text" class="form-control" :value="journal.department" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Expense Purpose </label>
                                    <input type="text" class="form-control" :value="journal.expense_purpose" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                                <div class="row">
                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-info-circle"></i> Payments</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment ID <b class="text-danger">*</b></label>
                                    <v-select class="mb-2" 
                                        v-model="selectedPaymentId" 
                                        :options="invoices"
                                        :label="invoice_number">
                                    </v-select>
                                    <!-- <input type="text" class="form-control" v-model="item.payment_id" name="payment_id"> -->
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice Number <b class="text-danger">*</b></label>
                                    <!-- <input type="text" class="form-control" v-model="item.invoice_number" name="invoice_number"> -->
                                    <v-select class="mb-2" 
                                        v-model="selectedInvoiceNumber" 
                                        :options="invoices"
                                        :label="invoice_number">
                                    </v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Credit <b class="text-danger">*</b></label>
                                    <input type="number" class="form-control" v-model="item.credit_amount" name="credit_amount">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Debit <b class="text-danger">*</b></label>
                                    <input type="number" class="form-control" v-model="item.debit_amount" name="debit_amount">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment Method</label>
                                    <select v-model="item.method_of_payment" name="method_of_payment" class="form-control mb-2">
                                        <option v-for="method in payment_methods" :value="method.id">{{ method.name }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-info-circle"></i> Client</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Client <b class="text-danger">*</b></label>
                                    <v-select class="mb-2" 
                                        v-model="selectedClient" 
                                        :options="clients"
                                        label="name">
                                    </v-select>
                                   <!--  <model-list-select :list="clients"
                                    v-model="journal.client_id"
                                    :is-disabled="journal.client_id ? true : false"
                                    option-value="id"
                                    option-text="name"
                                    placeholder="Select Client"
                                    class="form-control pull-right">
                                    </model-list-select>
                                    <input name="client_id" hidden v-model="journal.client_id">  -->
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
                                    <label>Terms Of Payment</label>
                                    <select v-model="item.terms_of_payment" name="terms_of_payment" class="form-control mb-2">
                                        <option v-for="terms in terms_of_payments" :value="terms.terms_of_payment">{{ terms.terms_of_payment }}</option>
                                    </select>
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
                                    <label>Payment Specification</label>
                                    <input type="text" class="form-control" v-model="item.payment_specification" name="payment_specification">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment Deposit Slip</label>
                                    <input type="text" class="form-control" v-model="item[depositType]" name="depositType">
                                </div>

                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-info-circle"></i> {{ isVendor ? 'Vendor' : 'Customer' }}</h4><hr>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>{{ isVendor ? 'Vendor Account' : 'Customer Account' }} <b class="text-danger">*</b></label>
                                    <select v-model="selectedCustOrVend" class="form-control mb-2">
                                        <option v-for="customerOrVendorList in customerOrVendorList" :value="customerOrVendorList">{{ customerOrVendorList.fullname }}</option>
                                    </select>

                                    <input type="text" :name="customerOrVendorDisplay" :value="selectedCustOrVend[customerOrVendorDisplay]" hidden>
                                    <input type="text" :name="customerOrVendorValue" :value="selectedCustOrVend[customerOrVendorValue]" hidden>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-info-circle"></i> Offset Account</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Main Account <b class="text-danger">*</b></label>
                                    <!-- <input type="text" class="form-control" v-model="item.main_account" name="main_account"> -->
                                    <select class="form-control" v-model="item.main_account" name="main_account">
                                        <option v-for="main_account in client_main_accounts" :value="main_account.id">{{ main_account.main_account_name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Account Type <b class="text-danger">*</b></label>
                                    <select v-model="item.account_type" class="form-control" name="account_type">
                                        <option value="Ledger">Ledger</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Vendor">Vendor</option>
                                        <option value="Project">Project</option>
                                        <option value="Fixed assets">Fixed assets</option>
                                        <option value="Bank">Bank</option>
                                    </select>
                                    <!-- <input type="text" class="form-control" v-model="item.account_type" name="account_type"> -->
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offset Company Accounts <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control" v-model="item.offset_company_accounts" name="offset_company_accounts">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offset Account Type <b class="text-danger">*</b></label>
                                    <select v-model="item.offset_account_type" class="form-control" name="offset_account_type">
                                        <option value="Ledger">Ledger</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Vendor">Vendor</option>
                                        <option value="Project">Project</option>
                                        <option value="Fixed assets">Fixed assets</option>
                                        <option value="Bank">Bank</option>
                                    </select>
                                    <!-- <input type="text" class="form-control" v-model="item.offset_account_type" name="offset_account_type"> -->
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offset Account <b class="text-danger">*</b></label>
                                    <!-- <input type="text" class="form-control" v-model="item.offset_account" name="offset_account"> -->
                                    <select class="form-control" v-model="item.offset_account" name="offset_account">
                                        <option v-for="main_account in client_main_accounts" :value="main_account.id">{{ main_account.main_account_name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offset Transaction Text <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control" v-model="item.offset_transaction_text" name="offset_transaction_text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-info-circle"></i> Others</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Journal Entry Number <b class="text-danger">*</b></label>
                                    <input type="number" class="form-control" min="0" v-model="item.entry_pair_number" name="entry_pair_number">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Transaction Date<b class="text-danger">*</b></label>
                                    <input type="text" class="form-control calendar-form" id="transaction_date" v-model="item.transaction_date" name="transaction_date">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment Due Date</label>
                                    <input type="text" class="form-control calendar-form" id="due_date" v-model="item[dueDateColumnName]" name="dueDateColumnName">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice Date <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control calendar-form" id="invoice_date" v-model="item.invoice_date" name="invoice_date">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Charge Percentage</label>
                                    <input type="text" class="form-control" v-model="item.charges_percentage" name="charges_percentage">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Cash Discount Code</label>
                                    <input type="text" class="form-control" v-model="item.cash_discount_code" name="cash_discount_code">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Cash Discount Date</label>
                                    <input type="text" class="form-control calendar-form" id="cash_discount_date" v-model="item.cash_discount_date" name="cash_discount_date">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Cash Discount Amount</label>
                                    <input type="number" class="form-control" v-model="item.cash_discount_amount" name="cash_discount_amount">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Release Date Comment</label>
                                    <input type="text" class="form-control calendar-form" id="release_date_comment" v-model="item.release_date_comment" name="release_date_comment">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group col-md-12">
                                <h4><i class="fas fa-info-circle"></i> Sales Tax</h4><hr>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Tax Exempt Number</label>
                                <input type="text" class="form-control" v-model="item.tax_exempt_number" name="tax_exempt_number">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Calculated Sales Tax Amount</label>
                                <input type="text" class="form-control" v-model="item.calculated_sales_tax_amount" name="calculated_sales_tax_amount">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Sales Tax Code</label>
                                <input type="text" class="form-control" v-model="item.sales_tax_code" name="sales_tax_code">
                            </div>
                            <!-- <div class="form-group col-md-12">
                                <label>Sales Tax Direction</label>
                                <input type="text" class="form-control" v-model="item.sales_tax_direction" name="sales_tax_direction">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Sales Tax Group</label>
                                <input type="text" class="form-control" v-model="item.sales_tax_group" name="sales_tax_group">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Item Sales Tax Group</label>
                                <input type="text" class="form-control" v-model="item.item_sales_tax_group" name="item_sales_tax_group">
                            </div> -->
                            <div class="form-group col-md-12">
                                <label>Actual Tax Amount</label>
                                <input type="text" class="form-control" v-model="item.actual_tax_amount" name="actual_tax_amount">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	import { bus }from 'Root/bus.js';
    import ResponseMixin from 'Mixins/response.js';
    import { ModelListSelect } from 'vue-search-select'
    import Vselect from "vue-select";

	export default {
		props: {
            modalId: {
                default: 'new-voucher',
                type: String
            },
            clientId: {
                default: null,
                type: Number
            },
            btnLabel: {
                default: 'Save Changes',
                type: String
            },

	        submitUrl: String,

            modalTitle: String,

            journal: Object,

            customerOrVendorList: Array,
            customerOrVendorValue: String,
            customerOrVendorDisplay: String,

            isVendor: {
                type: Boolean,
                default: true
            },

            payment_methods: Array,
            terms_of_payments: Array,
            voucherIdentification: String,
            journalIdentification: String,

            depositType: {
                default: 'payment_deposit_slip',
                type: String
            },

            dueDateColumnName: {
                default: 'due_date',
                type: String
            },

            invoice_number: {
                default: 'customer_invoice_number',
                type: String
            },

            main_accounts: Array,
            clients: Array,
            invoices: Array,
		},
        
        components: {
            ModelListSelect,
            'v-select' : Vselect,
        },

		data() {
			return {
                item: {
                    debit_amount: 0,
                    credit_amount: 0,
                },

                selectedCustOrVend: {},
                selectedInvoiceNumber: {},
                selectedPaymentId: {},
                selectedClient: {},
                client_main_accounts: [],

                option : {
					altInput: true,
					altFormat: "m/d/Y",
					dateFormat: "Y-m-d",
				}
			}
		},

        created() {
            bus.$on('create-voucher', data => {
                setTimeout(() => {
                    this.generateLineCode();
                }, 1000)

                flatpickr('#due_date', {
                    dateFormat: "m/d/Y",
                })
                flatpickr('#invoice_date', {
                    dateFormat: "m/d/Y",
                })
                flatpickr('#release_date_comment', {
                    dateFormat: "m/d/Y",
                })
                flatpickr('#cash_discount_date', {
                    dateFormat: "m/d/Y",
                })
                flatpickr('#transaction_date', {
                    dateFormat: "m/d/Y",
                })
            });
        },

        mounted() {
            if(this.client_id) {
                this.item = {
                    client_id : this.client_id,
                }
            }
        },

        watch: {
            selectedCustOrVend(val) {
                this.item[this.customerOrVendorDisplay] = val.fullname;
                this.item[this.customerOrVendorValue] = val[this.customerOrVendorValue]
            },
            selectedInvoiceNumber(val) {
                this.item.invoice_number = val[this.invoice_number];
                this.item.invoice_date = val.invoice_date;
            },
            selectedPaymentId(val) {
                this.item.payment_id = val[this.invoice_number];
                this.item[this.dueDateColumnName] = val.invoice_date;
            },
            selectedClient(val) {
                this.item.client_id = val.id;
                this.client_main_accounts = _.filter(this.main_accounts, { 'client_id': val.id })
            }
        },

        mixins: [ ResponseMixin ],

        methods: {
        	addVoucher() {
                swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to continue this process?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        this.$loading.show(true);

                        axios.post(this.submitUrl, this.item)
                            .then(response => {
                                var data = response.data;

                                this.$loading.show(false);
                                this.$emit('success');
                                this.parseSuccess(data.message, 'Successfully created!')
                                if(!this.edit) {
                                    this.item = {
                                        debit_amount: 0,
                                        credit_amount: 0,
                                    };
                                    this.selectedCustOrVend = {}
                                }

                                this.$emit('success')
                            }).catch(error => {
                                this.$loading.show(false);
                                this.parseError(error, null);
                            })
                    }
                })
        		
        	},

            generateLineCode() {
                var date = new Date();
                var time = Math.round(date.getTime() / 1000);   
                this.item[this.voucherIdentification] = date.getDate().toString() + date.getMonth().toString() + date.getFullYear().toString() +'-'+ time.toString();
                this.item[this.voucherIdentification] += "-" + Math.random().toString(36).substring(2, 6);
            },
        }
	}
</script>