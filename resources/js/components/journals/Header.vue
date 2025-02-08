<template>
    <div>
        <div class="card mt-4">
            <div class="card-header">

                <div class="row float-right">
                    <div class="col-sm-12">
                        <a :href="createUrl" class="btn btn-primary">Create</a>
                        <button class="btn btn-warning" :disabled="!hasSelectedItem" @click="updateJournalStatus('Approved')">Approve</button>
                        <button class="btn btn-danger" :disabled="!hasSelectedItem" @click="updateJournalStatus('Rejected')">Reject</button>
                        <button class="btn btn-secondary" :disabled="!hasSelectedItem" @click="validateJournal">Validate</button>
                        <button class="btn btn-success" :disabled="!hasSelectedItem" >Post</button>
                    </div>
                </div>

                <div class="row">
                    <filter-box @refresh="fetch">
                        <template v-slot:left>
                            <div class="col-sm-2 text-right">Show</div>
                            <div class="col-sm-3">  
                                <filter-select
                                    @change="filter($event, 'show')"
                                ></filter-select>
                            </div>
                            <div class="col-sm-4">
                                <v-select class="mr-4 select-size" :reduce="client => client.id"  v-model="selected_client" label="name" placeholder="Select Client" :options="clients"></v-select> 
                            </div>
                        </template>
                    </filter-box>
                </div>
            </div>

            <div class="card-body">                
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#overview" data-toggle="tab">Overview</a></li>
                        <li class="nav-item"><a class="nav-link" href="#general" data-toggle="tab">General</a></li>
                        <li class="nav-item"><a class="nav-link" href="#setup" data-toggle="tab">Setup</a></li>
                        <li class="nav-item"><a class="nav-link" href="#financial_dimension" data-toggle="tab">Financial dimensions</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="overview">

                        <div class="row">
                            <div class="col-sm-12">
		        		        <filter-box @refresh="fetch">
				        		    <template v-slot:left>
				        		        <date-range
				        		        :options="filterColumns"
				        		        class="mr-2"
				        		        @change="filter($event)"
				        		        ></date-range>
				        		    </template>
									<template v-slot:right>
										<search-form
										@search="filter($event, 'search')">
										</search-form>
									</template>
				        		</filter-box>
                                <data-table 
                                    ref="data-table"
                                    :headers="headers" 
                                    :items="items"

                                    :filters="filters"
                                    :fetch-url="fetchUrl"
                                    order-by="id"
                                    order-desc
                                    @load="load">

                                    <template v-slot:body="{ items }">
                                        <tr v-for="(item, key) in items">
                                            <td><input type="checkbox" v-model="item.is_selected" @click="selectThisItem(item, key)"></td>
                                            <td> {{ item.client }} </td>
                                            <td> {{ item[invoiceJournalNumber] }} </td>
                                            <td> {{ item.invoice_journal_batch_number }} </td>
                                            <td> {{ item.journal_name_number }} </td>
                                            <td> {{ item.journal_name }} </td>
                                            <td> {{ item.journal_status }} </td>
                                            <td> {{ item.department_fd }} </td>
                                            <td> {{ item.total_vouchers }} </td>
                                            <td> {{ item.total_log_errors }} </td>
                                            <td> {{ item.totalBalance }} </td>
                                            <td> {{ item.totalDebit }} </td>
                                            <td> {{ item.totalCredit }} </td>
                                            <td> {{ item.posted_on }} </td>
                                            <td> {{ item.approved_date }} </td>
                                            <td> {{ item.rejected_by_journal ? item.rejected_by_journal : '---' }} </td>
                                            <td> {{ item.reversing_date }} </td>
                                            <td> {{ item.log_date }} </td>
                                            <!-- <td> <input type="checkbox" :checked="item.log" disabled></td> -->
                                            <td> <input type="checkbox" :checked="item.in_use_checkbox" disabled> </td>
                                            <!-- <td> {{ item.used_by_user }} </td> -->
                                            <!-- <td> {{ item.posted_on ? item.posted_on : '---' }} </td> -->
                                            <!-- <td> {{ item.posted_by ? item.posted_by : '---' }} </td> -->
                                            <!-- <td> {{ item.approved_by_journal ? item.approved_by_journal : '---' }} </td> -->
                                            <!-- <td> {{ item.journal_status }} </td> -->
                                            <td>
                                                <a :href="item.showUrl" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a> 
                                                <a :href="item.editUrl" class="btn btn-secondary btn-sm"><i class="fas fa-pencil-alt"></i></a> 
                                                <!-- <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#edit-journal" @click="selectThisItem(item, key, true)"><i class="fas fa-pencil-alt"></i></button>  -->
                                                <!-- <view-button :href="item.showUrl"></view-button> -->
                                                <action-button
                                                small 
                                                color="btn-danger"
                                                alt-color="btn-warning"
                                                :show-alt="item.deleted_at"
                                                :action-url="item.archiveUrl"
                                                :alt-action-url="item.restoreUrl"
                                                icon="fas fa-trash"
                                                alt-icon="fas fa-trash-restore-alt"
                                                confirm-dialog
                                                :disabled="loading"
                                                title="Archive Item"
                                                alt-title="Restore Item"
                                                :message="'Are you sure you want to archive '+ invoiceHeaderTitle + ' ' + item[invoiceJournalNumber] + '?'"
                                                :alt-message="'Are you sure you want to restore '+ invoiceHeaderTitle + ' ' + item[invoiceJournalNumber] + '?'"
                                                @load="init"
                                                @success="init"
                                                ></action-button>
                                            </td>
                                        </tr>
                                    </template>         
                                </data-table>

                                <loader 
                                :loading="loading">
                                </loader>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="general">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>{{ invoiceHeaderTitle }}</label>
                                        <input type="text" class="form-control" v-model="selected[invoiceJournalNumber]" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Journal Batch number</label>
                                        <input type="text" class="form-control" v-model="selected.invoice_journal_batch_number" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Journal Name number</label>
                                        <input type="text" class="form-control" v-model="selected.journal_name_number" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Journal name</label>
                                        <input type="text" class="form-control" v-model="selected.journal_name" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Description</label>
                                        <input type="text" class="form-control" v-model="selected.description" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>(Balance) Journal</label>
                                        <input type="text" class="form-control" v-model="selected.totalBalance" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>(Total debit) Journal</label>
                                        <input type="text" class="form-control" v-model="selected.totalDebit" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>(Total credit) Journal</label>
                                        <input type="text" class="form-control" v-model="selected.totalCredit" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Reported as ready by</label>
                                        <input type="text" class="form-control" v-model="selected.reported_as_ready_by_journal" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Approved by</label>
                                        <input type="text" class="form-control" v-model="selected.approved_by_journal" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Approved date</label>
                                        <input type="text" class="form-control" v-model="selected.approved_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Rejected by</label>
                                        <input type="text" class="form-control" v-model="selected.rejected_by_journal" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Posted</label>
                                        <input type="checkbox" v-model="selected.posted_checkbox" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Posted on</label>
                                        <input type="text" class="form-control" v-model="selected.posted_on" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Posted by</label>
                                        <input type="text" class="form-control" v-model="selected.posted_by" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Log</label>
                                        <input type="checkbox" v-model="selected.log_in_checkbox" disabled>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Log Message</label>
                                        <input type="text" class="form-control" v-model="selected.log_message" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Reversing Entry</label>
                                        <input type="checkbox"  v-model="selected.reversing_entry_checkbox" disabled>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Reversing date</label>
                                        <input type="text" class="form-control" v-model="selected.reversing_date" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Original Journal number</label>
                                        <input type="text" class="form-control" v-model="selected.original_journal_number" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Show user-created only</label>
                                        <input type="checkbox" v-model="selected.show_user_created_only" disabled>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Journal type</label>
                                        <input type="text" class="form-control" v-model="selected.journal_type" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Account type</label>
                                        <!-- <input type="text" class="form-control" v-model="selected.account_type" readonly> -->
                                        <select v-model="selected.account_type" class="form-control" name="account_type" readonly>
                                            <option value="Ledger">Ledger</option>
                                            <option value="Customer">Customer</option>
                                            <option value="Vendor">Vendor</option>
                                            <option value="Project">Project</option>
                                            <option value="Fixed assets">Fixed assets</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Offset account</label>

                                        <select v-model="selected.offset_account" class="form-control" name="offset_account" readonly>
                                            <option value="Ledger">Ledger</option>
                                            <option value="Customer">Customer</option>
                                            <option value="Vendor">Vendor</option>
                                            <option value="Project">Project</option>
                                            <option value="Fixed assets">Fixed assets</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                        <!-- <input type="text" class="form-control" v-model="selected.offset_account" readonly> -->
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Document</label>
                                        <input type="text" class="form-control" v-model="selected.document" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Detail level</label>
                                        <input type="text" class="form-control" v-model="selected.detail_level" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Posting layer</label>
                                        <input type="text" class="form-control" v-model="selected.posting_layer" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Number allocation at posting</label>
                                        <input type="text" class="form-control" v-model="selected.number_allocation_at_posting" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Delete lines after posting</label>
                                        <input type="checkbox" v-model="selected.delete_lines_after_posting" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Lines limit</label>
                                        <input type="text" class="form-control" v-model="selected.lines_limit" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Amounts include sales tax</label>
                                        <input type="checkbox" v-model="selected.amounts_include_sales_tax" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Remittance type</label>
                                        <input type="text" class="form-control" v-model="selected.remittance_type" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Bank account</label>
                                        <input type="text" class="form-control" v-model="selected.bank_account" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Cost center</label>
                                        <select name="cost_center" v-model="selected.cost_center" class="form-control" disabled>
                                            <option v-for="cost_center in cost_centers" :value="cost_center.financial_dimension_value_code">{{ cost_center.dimension_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Department</label>
                                        <select name="department" v-model="selected.department" class="form-control" disabled>
                                            <option v-for="department in departments" :value="department.financial_dimension_value_code">{{ department.dimension_name }}</option>
                                        </select>
    
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Expense purpose</label>
                                        <select name="expense_purpose" v-model="selected.expense_purpose" class="form-control" disabled>
                                            <option v-for="expense_purpose in expense_purposes" :value="expense_purpose.financial_dimension_value_code">{{ expense_purpose.dimension_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>In Use</label>
                                        <input type="checkbox" v-model="selected.in_use_checkbox" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Used by user</label>
                                        <input type="text" class="form-control" v-model="selected.used_by_user" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Created by</label>
                                        <input type="text" class="form-control" v-model="selected.created_by" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Created on</label>
                                        <input type="text" class="form-control" v-model="selected.created_at" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Updated by</label>
                                        <input type="text" class="form-control" v-model="selected.updated_by" readonly>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Updated on</label>
                                        <input type="text" class="form-control" v-model="selected.updated_at" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="setup">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>In Use</label>
                                <input type="checkbox" v-model="selected.in_use_checkbox" readonly>
                            </div>
                            <div class="col-sm-12">
                                <label>Created on</label>
                                <input type="checkbox" v-model="selected.created_on" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Used by user</label>
                                <input type="text" class="form-control" v-model="selected.used_by_user" readonly>
                            </div>
                            <div class="col-sm-6">
                                <label>Created by</label>
                                <input type="text" class="form-control" v-model="selected.created_by" readonly>
                            </div>
                            <div class="col-sm-6">
                                <label>Updated by</label>
                                <input type="text" class="form-control" v-model="selected.updated_by" readonly>
                            </div>
                            <div class="col-sm-6">
                                <label>Updated on</label>
                                <input type="text" class="form-control" v-model="selected.updated_at" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="financial_dimension">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Cost center</label>
                                <select name="cost_center" v-model="selected.cost_center" class="form-control">
                                    <option v-for="cost_center in cost_centers" :value="cost_center.financial_dimension_value_code">{{ cost_center.dimension_name }}</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Department</label>
                                <select name="department" v-model="selected.department" class="form-control">
                                    <option v-for="department in departments" :value="department.financial_dimension_value_code">{{ department.dimension_name }}</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Expense purpose</label>
                                <select name="expense_purpose" v-model="selected.expense_purpose" class="form-control">
                                    <option v-for="expense_purpose in expense_purposes" :value="expense_purpose.financial_dimension_value_code">{{ expense_purpose.dimension_name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            </div>
        </div>

        <new-journal
            :cost_centers="cost_centers"
            :departments="departments"
            :expense_purposes="expense_purposes"
            :submit-url="submitUrl"
            @success="init"
            :modal-title="modalTitleCreate"
        ></new-journal>

        <new-journal
            :cost_centers="cost_centers"
            :departments="departments"
            :expense_purposes="expense_purposes"
            :submit-url="selected.updateUrl"
            :edit="true"
            :selected="selected"
            modal-id="edit-journal"
            @success="init"
            :modal-title="modalTitleUpdate"
            btn-label="Update journal"
        ></new-journal>
    </div>
</template>
<script>
    import { bus }from 'Root/bus.js';
    import ListMixin from 'Mixins/list.js';

    import NewJournal from 'Components/dialogs/NewJournal.vue';
    import ActionButton from 'Components/buttons/ActionButton.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import FilterSelect from 'Components/inputs/FilterSelect.vue';
    // import DataTable from 'Components/tables/DataTable.vue';
    import ResponseMixin from 'Mixins/response.js';
    import JournalHeaderMixin from 'Mixins/journal-header.js';

    import { ModelListSelect } from 'vue-search-select'
    import Vselect from "vue-select";
    import DateRange from 'Components/datepickers/DateRange.vue';
    import SearchForm from 'Components/forms/SearchForm.vue';

    export default {
        props: {
            type: String,
            fetchUrl: String,
            submitUrl: String,
            editUrl: String,
            cost_centers: Array,
            departments: Array,
            expense_purposes: Array,

            invoiceJournalNumber: String,
            invoiceHeaderTitle: String,

            modalTitleUpdate: String,
            modalTitleCreate: String,
            createUrl: String,

            statusUpdateUrl: String,
            clients: Array,

        },

        data() {
            return {
                items: [],
                selected: {},
                item: {},

                filterType: 'All',

                loading: false,
                selected_client: null
            }
        },
        
        watch : {
            'selected_client'(value) {
                this.filter(value, 'client');
            }
        },

        computed: {
            headers() {
                let array = [
                    { text: '', value: '' },
                    { text: 'Client', value: 'client' },
                    { text: 'Journal number', value: this.invoiceJournalNumber },
                    { text: 'Journal batch number', value: 'journal_batch_number' },
                    { text: 'Journal name number', value: 'journal_name_number' },
                    { text: 'Journal name', value: 'journal_name' },
                    { text: 'Journal status', value: 'journal_status' },
                    { text: 'Department', value: 'department' },
                    { text: 'Total vouchers', value: '' },
                    { text: 'Total logs (Error)', value: '' },
                    { text: '(Balance) Journal', value: 'balance' },
                    { text: '(Total debit) Journal', value: 'total_debit' },
                    { text: '(Total credit) Journal', value: 'total_credit' },
                    // { text: 'Description', value: 'description' },
                    { text: 'Posted on', value: 'posted_on' },
                    { text: 'Approved date', value: 'approved_by' },
                    { text: 'Rejected By', value: 'rejected_by' },
                    { text: 'Reversing date', value: 'reversing_date' },
                    // { text: 'Posted date', value: 'posted_date' },
                    { text: 'Log Date', value: 'log' },
                    { text: 'In use', value: 'in_use' },
                    // { text: 'Used by user', value: 'used_by_user' },
                    // { text: 'Posted by', value: 'posted_by' },
                    // { text: 'Journal status', value: 'journal_status' },
                ];

                return array;
            },

            hasSelectedItem() {
                return !_.isEmpty(this.selected);
            },
            
            filterColumns() {
                let array = [
                    { text: 'Created At', value: 'created_at' },
                    { text: 'Posted On', value: 'posted_on' },
                ];

                return array;
            },
        },

        components: {
            'new-journal': NewJournal,
            'action-button': ActionButton,
            'view-button': ViewButton,
            FilterSelect,
            ModelListSelect,
            'v-select' : Vselect,
            'date-range' : DateRange,
            'search-form': SearchForm,
        },

        mixins: [ ListMixin, ResponseMixin, JournalHeaderMixin ],

        // mounted() {
        //     this.init()
        // },

        methods: {
            init() {
                this.fetch();
                var payloads = {
                    show: this.filterType.show
                }

                this.$loading.show(true);

                axios.post(this.fetchUrl, payloads)
                    .then(response => {
                        this.items = response.data.items;
                        this.$loading.show(false);
                    }).catch(error => {
                        this.$loading.show(false);
                    })
            },

            selectThisItem(item, key, view=false) {
                if(!view) {
                    if(item.is_selected) {
                        this.selected = {};
                    } else {
                        this.selected = item;
                    }

                    _.each(this.items, (journal, index) => {
                        if(index != key) {
                            journal.is_selected = false;
                        }
                    })
                }
            },

            show() {
                this.init();
            },

            validateJournal() {
                var payloads = {
                    withOffsetAccount: true
                }
                
                this.$loading.show(true);
                axios.post(this.selected.validateUrl, payloads)
                    .then(response => {
                        this.$loading.show(false);
                        this.parseSuccess(response.data.message, 'Validation success!')
                    }).catch(error => {
                        this.$loading.show(false);
                        this.parseError(error, 'Validation error!')
                    })
            }
        }
    }
</script>