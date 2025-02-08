<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="submitSuccess" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Bank Account Statement Line Information
                    <div class="float-right">
                        <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                        <!-- <action-button @success="fetch" type="button" class="btn btn-success" :class="{'disabled':(!item.approveUrl || item.approved_date || item.canceled)}" :action-url="item.approveUrl">Approve</action-button> -->
                        <!-- <action-button @success="fetch" href="javascript:void(0)" class="btn btn-danger" :class="{'disabled':(!item.cancelUrl || item.canceled || item.approved_date)}" :action-url="item.cancelUrl">Cancel</action-button> -->
                    </div>
                </template>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#deposit" data-toggle="tab">Bank Account Statement Line</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#details" data-toggle="tab">Details</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
                                </ul>
                            </div>
               
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="deposit">
                                        <div class="row">
                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="fas fa-info-circle"></i> General Info</h4><hr>

                                                <div class="form-group">
                                                    <label for="statement_id">Statement ID</label>
                                                    <input type="text" class="form-control" :value="statementId ? statementId : item.statement_id" id="statement_id" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="statement_line_id">Statement Line ID</label>
                                                    <input type="text" class="form-control" v-model="item.statement_line_id" id="statement_line_id" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="transaction_date">Transaction Date <b class="text-danger">*</b></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="transaction_date" type="text" class="form-control calendar-form" v-model="item.transaction_date" id="transaction_date">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="line_number">Line Number <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.line_number" id="line_number">
                                                </div>

                                                <div class="form-group">
                                                    <label for="payment_reference">Payment Reference <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.payment_reference" id="payment_reference">
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_transaction_code">Bank Transaction Code <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.bank_transaction_code" id="bank_transaction_code">
                                                </div>

                                                <div class="form-group">
                                                    <label for="withdrawal_debit_amount">Withdrawal Debit Amount <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.withdrawal_debit_amount" id="withdrawal_debit_amount">
                                                </div>

                                                <div class="form-group">
                                                    <label for="deposit_credit_amount">Deposit Credit Amount <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.deposit_credit_amount" id="deposit_credit_amount">
                                                </div>

                                            </div>
                                            
                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="far fa-question-circle"></i> Status</h4><hr>

                                                <div class="form-check">
                                                    <input id="reconciled_checkbox" name="reconciled_checkbox" type="checkbox" class="form-check-input" :checked="item.reconciled_checkbox" disabled>
                                                    <label for="reconciled_checkbox"> Reconciled</label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="reconciled_date">Reconciled Date</label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="reconciled_date" type="text" class="form-control calendar-form" v-model="item.reconciled_date" id="reconciled_date" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="reconciled_by">Reconciled By</label>
                                                    <input type="text" class="form-control" v-model="item.reconciled_by" id="reconciled_by" disabled>
                                                </div>

                                                <div class="form-check">
                                                    <input id="adjustment_checkbox" name="adjustment_checkbox" type="checkbox" class="form-check-input" :checked="item.adjustment_checkbox" disabled>
                                                    <label for="adjustment_checkbox"> Adjustment</label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="adjustment_date">Adjustment Date</label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="adjustment_date" type="text" class="form-control calendar-form" v-model="item.adjustment_date" id="adjustment_date" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="adjusted_by">Adjusted By</label>
                                                    <input type="text" class="form-control" v-model="item.adjusted_by" id="adjusted_by" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <input type="text" class="form-control" v-model="item.description" id="description">
                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="fas fa-info"></i> Bank Reasons</h4><hr>

                                                <div class="form-group">
                                                    <label>Reason Code</label>
                                                    <v-select 
                                                        v-model="item.bank_reason" 
                                                        :reduce="item => item.reason_code" 
                                                        label="reason_code" 
                                                        placeholder="Select a Bank Reason" 
                                                        :options="bank_reasons"
                                                    >
                                                        <template #option="{ reason_code, default_comment }">
                                                            <b>Reason Code</b> : {{ reason_code }} - 
                                                            <b>Comment</b> : {{ default_comment }}
                                                        </template>
                                                    </v-select>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="financial">
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <h4><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
                                                <label>Cost Center <b class="text-danger">*</b></label>
                                                <select name="cost_center" v-model="item.cost_center" class="form-control mb-2">
                                                    <option :key="cost_center + index" v-for="(cost_center, index) in cost_centers" :value="cost_center.id">{{ cost_center.dimension_name }}</option>
                                                </select>
                                        
                                                <label>Department <b class="text-danger">*</b></label>
                                                <select name="department" v-model="item.department" class="form-control mb-2">
                                                    <option :key="department + index" v-for="(department, index) in departments" :value="department.id">{{ department.dimension_name }}</option>
                                                </select>
                                           
                                            </div>
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
                                    <div class="tab-pane" id="details">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h4 class="mb-2"><i class="fas fa-info-circle"></i> General Info</h4><hr>

                                                <div class="form-group">
                                                    <label for="bank_reconciliation_id">Bank Reconciliation Id</label>
                                                    <input type="text" class="form-control" v-model="item.bank_reconciliation_id" id="bank_reconciliation_id" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="cashflow_adjustment_id">Cashflow Adjustment Id</label>
                                                    <input type="text" class="form-control" v-model="item.cashflow_adjustment_id" id="cashflow_adjustment_id" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_account_transaction_id">Bank Account Transaction Id</label>
                                                    <input type="text" class="form-control" v-model="item.bank_account_transaction_id" id="bank_account_transaction_id" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="deposit_id">Deposit Id</label>
                                                    <input type="text" class="form-control" v-model="item.deposit_id" id="deposit_id" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="check_id">Check Id</label>
                                                    <input type="text" class="form-control" v-model="item.check_id" id="check_id" disabled>
                                                </div>

                                                <!-- <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <input type="text" class="form-control" v-model="item.description" id="description" disabled>
                                                </div> -->

                                                <div class="form-group">
                                                    <label for="settlement_type">Settlement Type</label>
                                                    <input type="text" class="form-control" v-model="item.settlement_type" id="settlement_type" disabled>
                                                </div>

                                                <div class="form-check">
                                                    <input id="matched_checkbox" name="matched_checkbox" type="checkbox" class="form-check-input" :checked="item.matched_checkbox" disabled>
                                                    <label for="matched_checkbox"> Matched Checkbox</label>
                                                </div>

                                            </div>

                                            <div class="col-md-4">
                                                <h4 class="mb-2"><i class="fas fa-store"></i> Vendor</h4><hr>

                                                <div class="form-group">
                                                    <label>Vendor Payment Journal Voucher</label>
                                                    <v-select 
                                                        v-model="item.vendor_payment_journal_voucher" 
                                                        :reduce="item => item.voucher_number" 
                                                        label="voucher_number" 
                                                        placeholder="Select a Vendor Payment Journal Voucher" 
                                                        :options="vendor_payment_journal_vouchers"
                                                        disabled
                                                    ></v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="vendor_payment_id">Vendor Payment ID</label>
                                                    <input type="text" class="form-control" v-model="item.vendor_payment_id" id="vendor_payment_id" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="vendor_account">Vendor Account</label>
                                                    <input type="text" class="form-control" v-model="item.vendor_account" id="vendor_account" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="vendor_name">Vendor Name</label>
                                                    <input type="text" class="form-control" v-model="item.vendor_name" id="vendor_name" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label>Method of Payment - Vendor</label>
                                                    <v-select 
                                                        v-model="item.method_of_payment_vendor" 
                                                        :reduce="item => item.method_of_payment_id" 
                                                        label="method_of_payment" 
                                                        placeholder="Select a Vendor Payment Method" 
                                                        :options="vendor_payment_methods"
                                                        disabled
                                                    ></v-select>
                                                </div>

                                            </div>

                                            <div class="col-md-4">
                                                <h4 class="mb-2"><i class="fas fa-user"></i> Customer</h4><hr>

                                                <div class="form-group">
                                                    <label>Customer Payment Journal Voucher</label>
                                                    <v-select 
                                                        v-model="item.customer_payment_journal_voucher" 
                                                        :reduce="item => item.voucher_number" 
                                                        label="voucher_number" 
                                                        placeholder="Select a Customer Payment Journal Voucher" 
                                                        :options="customer_payment_journal_vouchers"
                                                        disabled
                                                    ></v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="customer_payment_id">Customer Payment ID</label>
                                                    <input type="text" class="form-control" v-model="item.customer_payment_id" id="customer_payment_id" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="customer_account">Customer Account</label>
                                                    <input type="text" class="form-control" v-model="item.customer_account" id="customer_account" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="customer_name">Customer Name</label>
                                                    <input type="text" class="form-control" v-model="item.customer_name" id="customer_name" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label>Method of Payment - Customer</label>
                                                    <v-select 
                                                        v-model="item.method_of_payment_customer" 
                                                        :reduce="item => item.method_of_payment_id" 
                                                        label="method_of_payment" 
                                                        placeholder="Select a Customer Payment Method" 
                                                        :options="customer_payment_methods"
                                                        disabled
                                                    ></v-select>
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
                bank_account_transactions: [],
                client_bank_accounts: [],
                cost_centers: [],
                departments: [],
                bank_reasons: [],
                item: {},

                vendor_payment_methods: [],
                customer_payment_methods: [],

                vendor_payment_journal_vouchers: [],
                customer_payment_journal_vouchers: [],
            }
        },

        methods: {
            fetchSuccess(data) {
                this.bank_account_transactions = data.bank_account_transactions ? data.bank_account_transactions : this.bank_account_transactions;
                this.client_bank_accounts = data.client_bank_accounts ? data.client_bank_accounts : this.client_bank_accounts;
                this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
                this.departments = data.departments ? data.departments : this.departments;
                this.item = data.item ? data.item : this.item;
                this.bank_reasons = data.bank_reasons ? data.bank_reasons : this.bank_reasons;

                this.vendor_payment_methods = data.vendor_payment_methods ? data.vendor_payment_methods : this.vendor_payment_methods;
                this.customer_payment_methods = data.customer_payment_methods ? data.customer_payment_methods : this.customer_payment_methods;

                this.vendor_payment_journal_vouchers = data.vendor_payment_journal_vouchers ? data.vendor_payment_journal_vouchers : this.vendor_payment_journal_vouchers;
                this.customer_payment_journal_vouchers = data.customer_payment_journal_vouchers ? data.customer_payment_journal_vouchers : this.customer_payment_journal_vouchers;
            },

            submitSuccess() {
                this.fetch();
                this.$emit('submit-success');
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },

            mountInputs() {
                let options = {
                    enableTime: true,
                };

                flatpickr(this.$refs.transaction_date, options);
            },
        },

        computed: {
            client_bank_account() {
                let item = this.client_bank_accounts.find((data) => {
                    return data.bank_account == this.item.client_bank_account_number;
                });

                return item ? item : {};
            },

            bankStatement() {
                let result;
                result = `Statement from ${this.client_bank_account.bank_name} `;
                result += `of ${this.client_bank_account.bank_account_number} `;
                result += `For the Period of ${this.item.bank_statement_from_date} - ${this.item.bank_statement_to_date}`;

                return result;
            },

            filteredClientBankAccounts() {
                let items = this.client_bank_accounts.filter((data) => {
                    return data.client_id == this.item.client_id;
                });

                return items;
            },

        },

        watch: {
            item: {
                handler: function (val, oldVal) {
                    let result = this.client_bank_account.bank_name && this.client_bank_account.bank_account_number && this.item.bank_statement_from_date && this.item.bank_statement_to_date;
                    if(result && !this.item.id) {
                        this.item.bank_statement = this.bankStatement;
                    }
                },
                deep: true,
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

            statementId: String,
        },
    }

</script>