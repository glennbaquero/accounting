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
                                    <label>Client <b class="text-danger">*</b></label>
                                    <v-select class="mb-2" 
                                        v-model="item.client_id"  
                                        :options="clients"
                                        :reduce="item => item.id"
                                        placeholder="Select Client"
                                        label="name">
                                    </v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice Approval Journal Number </label>
                                    <input type="text" class="form-control" :value="journal[journalIdentification]" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice Approval Journal Batch #</label>
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
                                    <h4><i class="fas fa-info-circle"></i> Invoice</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice Number <b class="text-danger">*</b></label>
                                    <!-- <input type="text" class="form-control" v-model="item.invoice_number" name="invoice_number"> -->
                                    <v-select 
                                        v-model="selectedInvoiceNumber" 
                                        :options="invoices"
                                        :label="invoice_number">
                                    </v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice Date <b class="text-danger">*</b></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control calendar-form" v-model="item.invoice_date" id="invoice_date">
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ isVendor ? 'Vendor Account' : 'Customer Account' }} <b class="text-danger">*</b></label>
                                    <v-select
                                        v-model="selectedCustOrVend"
                                        :options="customerOrVendorList"
                                        :label="customerOrVendorValue">
                                    </v-select>

                                    <input type="text" :name="customerOrVendorDisplay" :value="selectedCustOrVend[customerOrVendorDisplay]" hidden>
                                    <input type="text" :name="customerOrVendorValue" :value="selectedCustOrVend[customerOrVendorValue]" hidden>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Vendor <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control" :value="selectedCustOrVend.fullname" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Due Date </label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control calendar-form" id="due_date" v-model="item[dueDateColumnName]" >
                                    </div>
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
                                    <label>Payment Method</label>
                                    <v-select v-model="item.method_of_payment" label="method_of_payment" :reduce="item => item.id" placeholder="Select Payment Method" :options="payment_methods" ></v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Terms Of Payment</label>
                                    <v-select v-model="item.terms_of_payment" label="terms_of_payment" :reduce="item => item.terms_of_payment" placeholder="Select Terms of Payment" :options="terms_of_payments" ></v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Bank Transaction Type</label>
                                    <input type="text" class="form-control" v-model="item.bank_transaction_type" name="bank_transaction_type">
                                </div>
                                <!-- <div class="form-group col-md-12">
                                    <label>Bank Account</label>
                                    <input type="text" class="form-control" v-model="item.bank_account" name="bank_account">
                                </div> -->
                                <div class="form-group col-md-12">
                                    <label>Bank Account</label>
                                    <v-select 
                                        v-model="item.bank_account" 
                                        :reduce="item => item.bank_account" 
                                        label="bank_name" 
                                        placeholder="Select a bank" 
                                        :options="clientBanks"
                                        name="bank_account"
                                    ></v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Description</label>
                                    <input type="text" class="form-control" v-model="item.description" name="bank_account">
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label>Settlement Type <b class="text-danger">*</b></label>
                                    <select class="form-control" v-model="item.settlement_type" name="settlement_type">
                                        <option value="None">None</option>
                                        <option value="Open transactions">Open transactions</option>
                                        <option value="Designated transactions">Designated transactions</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-file-invoice"></i> Voucher</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Voucher Type <b class="text-danger">*</b></label>
                                    <v-select v-model="item.voucher_type" placeholder="Select Type" :options="voucher_types"></v-select>
                                </div> 
                                <div class="form-group col-md-12">
                                    <label>Journal Entry Number <b class="text-danger">*</b></label>
                                    <input type="number" class="form-control" min="0" v-model="item.entry_pair_number" name="entry_pair_number">
                                </div>                             
                                <div class="form-group col-md-12">
                                    <label>Voucher Number <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control" v-model="item[voucherIdentification]" :name="voucherIdentification" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Voucher Date <b class="text-danger">*</b></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control calendar-form" id="voucher_date" v-model="item.voucher_date" name="voucher_date">
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Transaction Date<b class="text-danger">*</b></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control calendar-form" id="transaction_date" v-model="item.transaction_date" name="transaction_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-money-check"></i> Debit</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Debit <b class="text-danger">*</b></label>
                                    <input :readonly="main_account_disabled" type="number" class="form-control" v-model="item.debit_amount" name="debit_amount">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Main Account <b class="text-danger">*</b></label>
                                    <v-select ref="main_account" :disabled="main_account_disabled" :reduce="item => item.id" v-model="item.main_account" label="main_account_name" placeholder="Select Main Account" :resetOnOptionsChange="true" :options="client_main_accounts">
                                        
                                        <template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
                                            <b>Type</b> : {{ main_account_type }} - 
                                            <b>Category</b> : {{ main_account_category }} - 
                                            <b>Code</b> : {{ main_account_code }} - 
                                            <b>Name</b> : {{ main_account_name }}
                                            <b>Balance Control</b> : {{ balance_control }}
                                        </template>
                                        
                                    </v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Account Type <b class="text-danger">*</b></label>
                                    <v-select ref="account_type" :disabled="main_account_disabled" v-model="item.account_type" placeholder="Select Account Type" :options="account_types"></v-select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h4><i class="fas fa-money-check-alt"></i> Credit</h4><hr>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Credit <b class="text-danger">*</b></label>
                                    <input :readonly="offset_disabled" type="number" class="form-control" v-model="item.credit_amount" name="credit_amount">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offset Account <b class="text-danger">*</b></label>
                                    <v-select ref="offset_account" :disabled="offset_disabled" :key="item.offset_account" :reduce="item => item.id" v-model="item.offset_account" label="main_account_name" placeholder="Select Offset Account" :resetOnOptionsChange="true" :options="client_main_accounts"></v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offset Account Type <b class="text-danger">*</b></label>
                                    <v-select ref="offset_account_type" :disabled="offset_disabled" :key="item.offset_account_type" v-model="item.offset_account_type"  placeholder="Select Account Type" :options="account_types"></v-select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offset Transaction Text <b class="text-danger">*</b></label>
                                    <input  :readonly="offset_disabled" type="text" class="form-control" v-model="item.offset_transaction_text" name="offset_transaction_text">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offset Company Accounts <b class="text-danger">*</b></label>
                                    <input :readonly="offset_disabled" type="text" class="form-control" v-model="item.offset_company_accounts" name="offset_company_accounts">
                                </div>
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
    import ValidatorMixin from './validator.js';
    import { ModelListSelect } from 'vue-search-select'
    import Vselect from "vue-select";

	export default {

        mixins: [ ResponseMixin, ValidatorMixin ],

		props: {
            modalId: {
                default: 'new-voucher',
                type: String
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
                default: 'customer_payment_number',
                type: String
            },

            clientId: {
                default: null,
                type: Number
            },

            main_accounts: Array,
            clients: Array,
            invoices: Array,
            clientBanks: Array,
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

                account_types: ['Ledger', 'Customer', 'Vendor', 'Project', 'Fixed Assets', 'Bank'],
                voucher_types : ['Debit', 'Credit'],

                option : {
					dateFormat: "m/d/Y",
				}
			}
		},

        created() {
            bus.$on('create-voucher', data => {
                setTimeout(() => {
                    this.generateLineCode();
                }, 1000)

                flatpickr('#due_date',this.option)
                flatpickr('#invoice_date', this.option)
                flatpickr('#release_date_comment', this.option)
                flatpickr('#cash_discount_date', this.option)
                flatpickr('#transaction_date', this.option)
                flatpickr('#voucher_date', this.option)
            });
        },

        watch: {
            selectedCustOrVend(val) {
                this.item[this.customerOrVendorDisplay] = val.fullname;
                this.item[this.customerOrVendorValue] = val[this.customerOrVendorValue];
             
            },
            selectedInvoiceNumber(val) {   
                if(val != null) {
                    this.item.invoice_number = val[this.invoice_number];
                    this.item.invoice_date = moment(val.invoice_date).format('MM/DD/YYYY');
                    this.selectedCustOrVend = this.customerOrVendorList.filter(item => item.vendor_account == val.vendor_account)[0];
                    this.item.terms_of_payment = val.terms_of_payment;
                    // this.item.bank_account = val.bank_account;
                    this.item.method_of_payment = parseInt(val.method_of_payment);
                    this.item.bank_transaction_type = val.transaction_type;
                    this.item.due_date = moment(val.payment_due_date).format('MM/DD/YYYY');
                }else {
                    this.item.invoice_number = null;
                    this.item.invoice_date = null;
                    this.selectedCustOrVend = {};
                    this.item.terms_of_payment = null;
                    // this.item.bank_account = null;
                    this.item.method_of_payment = null;
                    this.item.bank_transaction_type = null;
                    this.item.due_date = null;
                }
            },
            selectedPaymentId(val) {
                this.item.payment_id = val[this.invoice_number];
                this.item[this.dueDateColumnName] = val.invoice_date;
            },
            'item.client_id'(val) {
                this.client_main_accounts = _.filter(this.main_accounts, { 'client_id': val })
            },
            'clientId'(val) {
                console.log(val)
                this.client_main_accounts = _.filter(this.main_accounts, { 'client_id': val })
            }
        },

        mounted() {
             if(this.clientId) {
                 this.item = {
                     client_id : this.clientId,
                 }
                 this.client_main_accounts = _.filter(this.main_accounts, { 'client_id': this.clientId })
             }
        },

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

                                $('#'+this.modalId).modal('hide');
                                setTimeout(()=>{
                                    this.$emit('success')
                                },1000)
                               
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