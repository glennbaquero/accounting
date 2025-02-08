<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Deposit Slip Information
                    <div class="float-right">
                        <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                        <action-button @success="fetch" type="button" class="btn btn-success" :class="{'disabled':(!item.approveUrl || item.approved_date || item.canceled)}" :action-url="item.approveUrl">Approve</action-button>
                        <action-button @success="fetch" href="javascript:void(0)" class="btn btn-danger" :class="{'disabled':(!item.cancelUrl || item.canceled || item.approved_date)}" :action-url="item.cancelUrl">Cancel</action-button>
                    </div>
                </template>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#deposit-slip" data-toggle="tab">Deposit Slip</a></li>
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
                                    <div class="tab-pane show active" id="deposit-slip">
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

                                                <hr><h4 class="mb-2"><i class="fas fa-store"></i> Vendor Bank Account</h4><hr>

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
                                            
                                            </div>

                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="fas fa-money-check"></i> Deposit</h4><hr>

                                                <div class="form-group">
                                                    <label for="deposit_slip_id">Deposit Slip Id</label>
                                                    <input type="text" class="form-control" :value="item.deposit_slip_id" id="deposit_slip_id" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="deposit_slip_number">Deposit Slip Number  <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.deposit_slip_number" id="deposit_slip_number">
                                                </div>

                                                <div class="form-group">
                                                    <label for="issue_date">Issue Date <b class="text-danger">*</b></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="issue_date" type="text" class="form-control calendar-form" v-model="item.issue_date" id="issue_date">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="deposit_amount">Deposit Amount  <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.deposit_amount" id="deposit_amount">
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_posting_profile">Bank Posting Profile</label>
                                                    <input type="text" class="form-control" v-model="item.bank_posting_profile" id="bank_posting_profile">
                                                </div>

                                                <div class="form-group">
                                                    <label for="payment_reference">Payment Reference <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.payment_reference" id="payment_reference">
                                                </div>

                                                <div class="form-group">
                                                    <label>Voucher Number <b class="text-danger">*</b></label>
                                                    <v-select 
                                                        v-model="item.voucher_no" 
                                                        :reduce="item => item.voucher_number" 
                                                        label="voucher_number" 
                                                        placeholder="Select a Voucher" 
                                                        :options="vouchers"
                                                    ></v-select>
                                                </div>

                                                <hr><h4 class="mb-2"><i class="fas fa-dollar-sign"></i> Payment - Vendor</h4><hr>

                                                <!-- <div class="form-group">
                                                    <label for="payment_id">Payment Id  <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.payment_id" id="payment_id">
                                                </div> -->

                                                <!-- <div class="form-group">
                                                    <label for="method_of_payment_vendor">Method of Payment - Vendor <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.method_of_payment_vendor" id="method_of_payment_vendor">
                                                </div> -->

                                                <div class="form-group">
                                                    <label>Method of Payment - Vendor</label>
                                                    <v-select 
                                                        v-model="item.method_of_payment_vendor" 
                                                        :reduce="item => item.method_of_payment_id" 
                                                        label="method_of_payment" 
                                                        placeholder="Select a method of payment" 
                                                        :options="vendor_payment_methods"
                                                    ></v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="vendor_payment_status">Deposit Status - Vendor</label>
                                                    <v-select v-model="item.vendor_payment_status" :options="payment_statuses" label="value" placeholder="Select Payment Status" :reduce="item => item.value" class="mb-2"></v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Vendor Payment</label>
                                                    <v-select 
                                                        v-model="item.vendor_payment_id" 
                                                        :reduce="item => item.vendor_payment_number" 
                                                        label="vendor_payment_number" 
                                                        placeholder="Select a method of payment" 
                                                        :options="vendor_payments"
                                                    ></v-select>
                                                </div>
                                                
                                                <hr><h4 class="mb-2"><i class="fas fa-dollar-sign"></i> Payment - Customer</h4><hr>

                                                <div class="form-group">
                                                    <label>Method of Payment - Customer</label>
                                                    <v-select 
                                                        v-model="item.method_of_payment_customer" 
                                                        :reduce="item => item.method_of_payment_id" 
                                                        label="method_of_payment" 
                                                        placeholder="Select a method of payment" 
                                                        :options="customer_payment_methods"
                                                    ></v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="customer_payment_status">Deposit Status - Customer</label>
                                                    <v-select v-model="item.customer_payment_status" :options="payment_statuses" label="value" placeholder="Select Payment Status" :reduce="item => item.value" class="mb-2"></v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Customer Payment</label>
                                                    <v-select 
                                                        v-model="item.customer_payment_id" 
                                                        :reduce="item => item.customer_payment_number" 
                                                        label="customer_payment_number" 
                                                        placeholder="Select a method of payment" 
                                                        :options="customer_payments"
                                                    ></v-select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="far fa-question-circle"></i> Status</h4><hr>

                                                <div class="form-group">
                                                    <label>Postdated Check Status</label>
                                                    <v-select 
                                                        v-model="item.postdated_check_status"  
                                                        placeholder="Select a Postdated Status" 
                                                        :options="statuses"
                                                    ></v-select>
                                                </div>

                                                <div class="form-check">
                                                    <input id="reconciled_checkbox" name="reconciled_checkbox" type="checkbox" class="form-check-input" :checked="item.reconciled_checkbox" disabled>
                                                    <label for="reconciled_checkbox"> Reconciled</label>
                                                </div>


                                                <div class="form-group">
                                                    <label for="approved_date">Approved Date</label>
                                                    <input type="text" class="form-control" :value="formatDate(item.approved_date)" id="approved_date" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="approved_by">Approved By</label>
                                                    <input type="text" class="form-control" :value="item.approved_by" id="approved_by" readonly>
                                                </div>

                                                <div class="form-check">
                                                    <input id="posted_invoice" name="posted_invoice" type="checkbox" class="form-check-input" :checked="item.posted_invoice_checkbox" disabled>
                                                    <label for="posted_invoice"> Posted Deposit</label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="posting_date">Posting Date</label>
                                                    <input type="text" class="form-control" :value="formatDate(item.posting_date)" id="posting_date" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="posted_by">Posted by</label>
                                                    <input type="text" class="form-control" :value="item.posted_by" id="posted_by" readonly>
                                                </div>

                                                <div class="form-check">
                                                    <input id="canceled" name="canceled" type="checkbox" class="form-check-input" :checked="item.canceled" disabled>
                                                    <label for="canceled"> Canceled</label>
                                                </div>

                                                <div class="form-check">
                                                    <input id="pending_cancellation" name="pending_cancellation" type="checkbox" class="form-check-input" v-model="item.pending_cancellation">
                                                    <label for="pending_cancellation"> Pending Cancellation</label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="canceled_by">Canceled By</label>
                                                    <input type="text" class="form-control" :value="item.canceled_by" id="canceled_by" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="canceled_date">Canceled Date</label>
                                                    <input type="text" class="form-control" :value="item.canceled_date" id="canceled_date" readonly>
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

                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <input type="text" class="form-control" v-model="item.description" id="description">
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
                item: {
                    check_currency: 'P',
                },
                bank_reasons: [],
                customers: [],
                client_bank_accounts: [],
                cost_centers: [],
                customer_bank_accounts: [],
                vendor_bank_accounts: [],
                departments: [],
                vouchers: [],
                expense_purposes: [],
                customer: {},
                statuses: [
                    'Open',
                    'On Hold',
                    'Void',
                    'Paid',
                    'Posted',
                    'Cancelled',
                    'Pending Cancellation',
                ],
                payment_statuses: [
                    { value: 'None' },
                    { value: 'Sent' },
                    { value: 'Received' },
                    { value: 'Approved' },
                    { value: 'Rejected' }
                ],
                vendor_payment_methods: [],
                customer_payment_methods: [],

                vendor_payments: [],
                customer_payments: [],
            }
        },

        watch: {
            'item.reason_code'(value) {
                let item = this.bank_reasons.find((data) => {
                    return data.bank_reason = value;
                });

                if(item) {
                    this.item.reason_comment = item.default_comment;
                }

            },

            'item.customer_account'(value) {
                let item = this.customers.find((data) => {
                    return data.customer_account = value;
                });

                if(item) {
                    this.customer = item;
                }
            },
        },

        methods: {
            fetchSuccess(data) {
                this.item = data.item ? data.item : this.item;
                this.bank_reasons = data.bank_reasons ? data.bank_reasons : this.bank_reasons;
                this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
                this.departments = data.departments ? data.departments : this.departments;
                this.vouchers = data.vouchers ? data.vouchers : this.vouchers;
                this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
                this.customers = data.customers ? data.customers : this.customers;

                this.client_bank_accounts = data.client_bank_accounts ? data.client_bank_accounts : this.client_bank_accounts;
                this.customer_bank_accounts = data.customer_bank_accounts ? data.customer_bank_accounts : this.customer_bank_accounts;
                this.vendor_bank_accounts = data.vendor_bank_accounts ? data.vendor_bank_accounts : this.vendor_bank_accounts;

                this.vendor_payment_methods = data.vendor_payment_methods ? data.vendor_payment_methods : this.vendor_payment_methods;
                this.customer_payment_methods = data.customer_payment_methods ? data.customer_payment_methods : this.customer_payment_methods;
                this.vendor_payments = data.vendor_payments ? data.vendor_payments : this.vendor_payments;
                this.customer_payments = data.customer_payments ? data.customer_payments : this.customer_payments;

            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },

            mountInputs() {
                let options = {
                    dateFormat: 'Y-m-d H:i',
                    enableTime: true,
                };

                flatpickr(this.$refs.issue_date, options);
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