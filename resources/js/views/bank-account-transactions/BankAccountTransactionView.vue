<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Bank Account Transaction Information
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
                                    <li class="nav-item"><a class="nav-link active" href="#deposit" data-toggle="tab">Check</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row" style="margin-right: 10px">
                                    <label class="col-sm-3 col-form-label">Client <b class="text-danger">*</b></label>
                                    <model-list-select :list="clients"
                                        v-model="item.client_id"
                                        option-value="id"
                                        option-text="name"
                                        placeholder="Select a client"
                                        class="form-control col-sm-9">
                                    </model-list-select>
                                    <input name="client_id" hidden v-model="item.client_id"> 
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="deposit">
                                        <div class="row">
                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="fas fa-university"></i> Client Bank Account</h4><hr>

                                                <div class="form-group">
                                                    <label>Bank Account Number <b class="text-danger">*</b></label>
                                                    <v-select 
                                                        v-model="item.client_bank_account_number" 
                                                        :reduce="item => item.bank_account" 
                                                        label="bank_account" 
                                                        placeholder="Select Bank" 
                                                        :options="filteredClientBankAccounts"
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
                                                        :options="customer_bank_accounts"
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

                                                <div class="form-group">
                                                    <label>Payment Method - Customer <b class="text-danger">*</b></label>
                                                    <v-select 
                                                        v-model="item.method_of_payment_customer" 
                                                        :reduce="item => item.method_of_payment_id" 
                                                        label="method_of_payment" 
                                                        placeholder="Select Payment Method" 
                                                        :options="customer_payment_methods"
                                                    ></v-select>
                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="fas fa-store"></i> Vendor Bank Account</h4><hr>

                                                <div class="form-group">
                                                    <label>Vendor Account Number <b class="text-danger">*</b></label>
                                                    <v-select 
                                                        v-model="item.vendor_bank_account_number" 
                                                        :reduce="item => item.bank_account" 
                                                        label="bank_account" 
                                                        placeholder="Select Vendor" 
                                                        :options="vendor_bank_accounts"
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

                                                <div class="form-group">
                                                    <label for="vendor_company">Vendor Company</label>
                                                    <input type="text" class="form-control" v-model="item.vendor_company" id="vendor_company">
                                                </div>

                                                <div class="form-group">
                                                    <label for="vendor_contact">Vendor Contact</label>
                                                    <input type="text" class="form-control" v-model="item.vendor_contact" id="vendor_contact">
                                                </div>

                                                <div class="form-group">
                                                    <label>Payment Method - Vendor <b class="text-danger">*</b></label>
                                                    <v-select 
                                                        v-model="item.method_of_payment_vendor" 
                                                        :reduce="item => item.method_of_payment_id" 
                                                        label="method_of_payment" 
                                                        placeholder="Select Payment Method" 
                                                        :options="vendor_payment_methods"
                                                    ></v-select>
                                                </div>

                                                <hr><h4 class="mb-2"><i class="fas fa-dollar-sign"></i> Transaction</h4><hr>
                                                
                                                <div class="form-group">
                                                    <label for="bank_account_transaction_number">Bank Account Transaction Number</label>
                                                    <input type="text" class="form-control" v-model="item.bank_account_transaction_number" id="bank_account_transaction_number" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_statement">Bank Statement</label>
                                                    <input type="text" class="form-control" v-model="item.bank_statement" id="bank_statement">
                                                </div>

                                                <div class="form-group">
                                                    <label for="deposit_slip_number">Deposit Slip number <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.deposit_slip_number" id="deposit_slip_number">
                                                </div>

                                                <div class="form-group">
                                                    <label for="check_number">Check Number <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.check_number" id="check_number">
                                                </div>

                                                <div class="form-group">
                                                    <label>Voucher Number <b class="text-danger">*</b></label>
                                                    <v-select 
                                                        v-model="item.voucher_number" 
                                                        :reduce="item => item.voucher_number" 
                                                        label="voucher_number" 
                                                        placeholder="Select Voucher" 
                                                        :options="vouchers"
                                                    ></v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="accounting_date">Accounting Date <b class="text-danger">*</b></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="accounting_date" type="text" class="form-control calendar-form" v-model="item.accounting_date" id="accounting_date">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="issued_by">Issued By <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.issued_by" id="issued_by">
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_posting_profile">Bank Posting Profile <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.bank_posting_profile" id="bank_posting_profile">
                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="far fa-question-circle"></i> Status</h4><hr>

                                                <div class="form-group">
                                                    <label for="bank_statement_date">Bank Statement Date <b class="text-danger">*</b></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="bank_statement_date" type="text" class="form-control calendar-form" v-model="item.bank_statement_date" id="bank_statement_date">
                                                    </div>
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

                                                <div class="form-check">
                                                    <input id="cleared_checkbox" name="cleared_checkbox" type="checkbox" class="form-check-input" v-model="item.cleared_checkbox">
                                                    <label for="cleared_checkbox"> Cleared</label>
                                                </div>

                                                <div class="form-check">
                                                    <input id="reconciled_checkbox" name="reconciled_checkbox" type="checkbox" class="form-check-input" v-model="item.reconciled_checkbox">
                                                    <label for="reconciled_checkbox"> Reconciled</label>
                                                </div>

                                                <div class="form-check">
                                                    <input id="pending_cancellation_checkbox" name="pending_cancellation_checkbox" type="checkbox" class="form-check-input" v-model="item.pending_cancellation_checkbox">
                                                    <label for="pending_cancellation_checkbox"> Pending Cancellation</label>
                                                </div>

                                                <hr><h4 class="mb-2"><i class="fas fa-info"></i> Bank Reasons</h4><hr>

                                                <div class="form-group">
                                                    <label>Reason Code</label>
                                                    <v-select 
                                                        v-model="item.reason_code" 
                                                        :reduce="item => item.reason_code" 
                                                        label="reason_code" 
                                                        placeholder="Select a Reason Code" 
                                                        :options="bank_reasons"
                                                    >
                                                        <template #option="{ reason_code, default_comment }">
                                                            <b>Reason Code</b> : {{ reason_code }} - 
                                                            <b>Comment</b> : {{ default_comment }}
                                                        </template>
                                                    </v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="reason_comment">Reason Comment</label>
                                                    <input type="text" class="form-control" v-model="item.reason_comment" id="reason_comment">
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
                                            
                                                <label>Expense Purpose <b class="text-danger">*</b></label>
                                                <select name="expense_purpose" v-model="item.expense_purpose" class="form-control mb-2">
                                                    <option :key="expense_purpose + index" v-for="(expense_purpose, index) in expense_purposes" :value="expense_purpose.id">{{ expense_purpose.dimension_name }}</option>
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
                customer_payment_methods: [],
                vendor_payment_methods: [],
                item: {},
                bank_reasons: [],
                client_bank_accounts: [],
                cost_centers: [],
                customer_bank_accounts: [],
                vendor_bank_accounts: [],
                departments: [],
                vouchers: [],
                expense_purposes: [],
            }
        },

        watch: {
            'item.voucher_no'(value) {
                let item = this.vouchers.find((data) => {
                    return data.voucher_number = value;
                });

                if(item) {
                    this.item.payment_id = item.payment_id;
                }

            },

            'item.reason_code'(value) {
                let item = this.bank_reasons.find((data) => {
                    return data.bank_reason = value;
                });

                if(item) {
                    this.item.reason_comment = item.default_comment;
                }

            },
        },

        methods: {
            fetchSuccess(data) {
                this.item = data.item ? data.item : this.item;
                this.bank_reasons = data.bank_reasons ? data.bank_reasons : this.bank_reasons;
                this.client_bank_accounts = data.client_bank_accounts ? data.client_bank_accounts : this.client_bank_accounts;
                this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
                this.customer_bank_accounts = data.customer_bank_accounts ? data.customer_bank_accounts : this.customer_bank_accounts;
                this.vendor_bank_accounts = data.vendor_bank_accounts ? data.vendor_bank_accounts : this.vendor_bank_accounts;
                this.departments = data.departments ? data.departments : this.departments;
                this.vouchers = data.vouchers ? data.vouchers : this.vouchers;
                this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
                this.customer_payment_methods = data.customer_payment_methods ? data.customer_payment_methods : this.customer_payment_methods;
                this.vendor_payment_methods = data.vendor_payment_methods ? data.vendor_payment_methods : this.vendor_payment_methods;
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },

            mountInputs() {
                let options = {
                    enableTime: true,
                };

                flatpickr(this.$refs.accounting_date, options);
                flatpickr(this.$refs.bank_statement_date, options);
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

            customer_bank_account() {
                let item = this.customer_bank_accounts.find((data) => {
                    return data.bank_account == this.item.customer_bank_account_number;
                });

                return item ? item : {};
            },

            vendor_bank_account() {
                let item = this.vendor_bank_accounts.find((data) => {
                    return data.bank_account == this.item.vendor_bank_account_number;
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