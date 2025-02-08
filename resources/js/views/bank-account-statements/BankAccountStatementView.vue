<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Bank Account Statement Information
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
                                    <li class="nav-item"><a class="nav-link active" href="#deposit" data-toggle="tab">Bank Account Statement</a></li>
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

                                                <h4 class="mb-2"><i class="far fa-question-circle"></i> Status</h4><hr>

                                                <div class="form-check">
                                                    <input id="approved" name="approved" type="checkbox" class="form-check-input" :checked="item.approved" disabled>
                                                    <label for="approved"> Is Approved</label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="approved_date">Approved Date</label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="approved_date" type="text" class="form-control calendar-form" :value="item.approved_date" id="approved_date" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="approved_by">Approved By</label>
                                                    <input type="text" class="form-control" v-model="item.approved_by" id="approved_by" disabled>
                                                </div>

                                                <div class="form-check">
                                                    <input id="canceled" name="canceled" type="checkbox" class="form-check-input" :checked="item.canceled" disabled>
                                                    <label for="canceled"> Is Cancelled</label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="canceled_date">Bank statement To Date</label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="canceled_date" type="text" class="form-control calendar-form" :value="item.canceled_date" id="canceled_date" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="canceled_by">Cancelled By</label>
                                                    <input type="text" class="form-control" v-model="item.canceled_by" id="canceled_by" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="currency">Currency <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.currency" id="currency">
                                                </div>

                                                <div class="form-group">
                                                    <label for="opening_balance">Opening Balance <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.opening_balance" id="opening_balance">
                                                </div>

                                                <div class="form-group">
                                                    <label for="ending_balance">Ending Balance <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.ending_balance" id="ending_balance">
                                                </div>

                                                <div class="form-group">
                                                    <label for="total_reconciled">Total Reconciled</label>
                                                    <input type="text" class="form-control" v-model="item.total_reconciled" id="total_reconciled" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="total_adjustmments">Total Adjustments</label>
                                                    <input type="text" class="form-control" v-model="item.total_adjustmments" id="total_adjustmments" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label for="total_matched">Total Matched</label>
                                                    <input type="text" class="form-control" v-model="item.total_matched" id="total_matched" disabled>
                                                </div>

                                                <div class="form-check">
                                                    <input id="reconciled" name="reconciled" type="checkbox" class="form-check-input" :checked="item.reconciled" disabled>
                                                    <label for="reconciled"> Reconciled</label>
                                                </div>

                                                <div class="form-check">
                                                    <input id="adjustment" name="adjustment" type="checkbox" class="form-check-input" :checked="item.adjustment" disabled>
                                                    <label for="adjustment"> Adjustment</label>
                                                </div>
                                            </div>
                                            
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
                                            </div>

                                            <div class="col-md-4">

                                                <h4 class="mb-2"><i class="far fa-file-alt"></i> Statement</h4><hr>

                                                <div class="form-group">
                                                    <label for="bank_statement">Bank Statement</label>
                                                    <input type="text" class="form-control" v-model="item.bank_statement" id="bank_statement">
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_statement_id">Bank Statement ID</label>
                                                    <input type="text" class="form-control" :value="item.bank_statement_id" id="bank_statement_id" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>Bank Account Transaction <b class="text-danger">*</b></label>
                                                    <v-select 
                                                        v-model="item.bank_account_transaction_number" 
                                                        :reduce="item => item.bank_account_transaction_number" 
                                                        label="bank_account_transaction_number" 
                                                        placeholder="Select Bank Transaction" 
                                                        :options="bank_account_transactions"
                                                    ></v-select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_statement_issue_date">Bank statement Issue Date <b class="text-danger">*</b></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="bank_statement_issue_date" type="text" class="form-control calendar-form" v-model="item.bank_statement_issue_date" id="bank_statement_issue_date">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_statement_from_date">Bank statement From Date <b class="text-danger">*</b></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="bank_statement_from_date" type="text" class="form-control calendar-form" v-model="item.bank_statement_from_date" id="bank_statement_from_date">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_statement_to_date">Bank statement To Date <b class="text-danger">*</b></label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input ref="bank_statement_to_date" type="text" class="form-control calendar-form" v-model="item.bank_statement_to_date" id="bank_statement_to_date">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="prepared_by">Prepared By <b class="text-danger">*</b></label>
                                                    <input type="text" class="form-control" v-model="item.prepared_by" id="prepared_by">
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
                item: {},
            }
        },

        methods: {
            fetchSuccess(data) {
                this.bank_account_transactions = data.bank_account_transactions ? data.bank_account_transactions : this.bank_account_transactions;
                this.client_bank_accounts = data.client_bank_accounts ? data.client_bank_accounts : this.client_bank_accounts;
                this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
                this.departments = data.departments ? data.departments : this.departments;
                this.item = data.item ? data.item : this.item;
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },

            mountInputs() {
                let options = {
                    enableTime: true,
                };

                // flatpickr(this.$refs.accounting_date, options);
                // flatpickr(this.$refs.bank_statement_date, options);
                // flatpickr(this.$refs.transaction_date, options);
                flatpickr(this.$refs.bank_statement_issue_date, options);
                flatpickr(this.$refs.bank_statement_from_date, options);
                flatpickr(this.$refs.bank_statement_to_date, options);
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
        },
    }

</script>