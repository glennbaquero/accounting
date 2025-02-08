<template>
	<div class="modal fade" :id="modalId">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ modalTitle }}</h5>
                    <div class="float-right">
                        <button type="button" class="btn btn-warning" v-if="edit">Validate</button>
                        <button type="button" class="btn btn-success" v-if="edit">Approval</button>
                        <button type="button" class="btn btn-secondary" v-if="edit">Post</button>
                        <button type="button" class="btn btn-danger" v-if="edit">Generate payment</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Journal Batch Number</label>
                                    <input type="text" class="form-control" v-model="item.invoice_journal_batch_number" name="invoice_journal_batch_number">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Journal Name Number</label>
                                    <input type="text" class="form-control" v-model="item.journal_name_number" name="journal_name_number">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Journal Name</label>
                                    <input type="text" class="form-control" v-model="item.journal_name" name="journal_name">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Description</label>
                                    <input type="text" class="form-control" v-model="item.description" name="description">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Reported as ready by</label>
                                    <input type="text" class="form-control" v-model="item.reported_as_ready_by_journal" name="reported_as_ready_by_journal">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Approved by</label>
                                    <input type="text" class="form-control" :value="item.approved_by_journal" name="approved_by_journal" disabled>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Approved Date</label>
                                    <input type="text" class="form-control" ref="approved_date" :value="item.approved_date" name="approved_date" disabled>
                                </div>

                                <div class="col-sm-12 mt-2">
                                    <label>Rejected By</label>
                                    <input type="text" class="form-control" :value="item.rejected_by_journal" name="rejected_by_journal" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Posted</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                        <input disabled type="checkbox" class="custom-control-input" id="posted_checkbox" :value="item.posted_checkbox">
                                            <label class="custom-control-label" for="posted_checkbox">
                                                <span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
                                                    {{ item.posted_checkbox ? 'Posted' : 'Not Posted'  }}
                                                </span>
                                            </label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label>Posted On</label>
                                    <input type="text" class="form-control" ref="posted_on"  :value="item.posted_on" name="posted_on" disabled>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Posted by</label>
                                    <input type="text" class="form-control" :value="item.posted_by" name="posted_by" disabled>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Reversing Entry</label>
                                    <div class="custom-control custom-switch mb-3 mt-1">
                                        <input type="checkbox" class="custom-control-input" id="reversing_entry_checkbox" v-model="item.reversing_entry_checkbox">
                                            <label class="custom-control-label" for="reversing_entry_checkbox">
                                                <span class="badge" :class="item.reversing_entry_checkbox ? 'badge-success' : 'badge-danger'">
                                                    {{ item.reversing_entry_checkbox ? 'Yes' : 'No'  }}
                                                </span>
                                            </label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label>Reversing date</label>
                                    <input type="text" class="form-control calendar-form" ref="reversing_date" v-model="item.reversing_date" name="reversing_date">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Original Journal number</label>
                                    <input type="text" class="form-control" v-model="item.original_journal_number" name="original_journal_number">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Show User-Created Only</label>
                                    <div class="custom-control custom-switch mb-3 mt-1">
                                        <input type="checkbox" class="custom-control-input" id="show_user_created_only" v-model="item.show_user_created_only">
                                            <label class="custom-control-label" for="show_user_created_only">
                                                <span class="badge" :class="item.show_user_created_only ? 'badge-success' : 'badge-danger'">
                                                    {{ item.show_user_created_only ? 'Yes' : 'No'  }}
                                                </span>
                                            </label>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-1">
                                    <label>Journal Type</label>
                                    <input type="text" class="form-control" v-model="item.journal_type" name="journal_type">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Account type</label>
                                    <!-- <input type="text" class="form-control" v-model="item.account_type" name="account_type"> -->
                                    <select v-model="item.account_type" class="form-control" name="account_type">
                                        <option value="Ledger">Ledger</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Vendor">Vendor</option>
                                        <option value="Project">Project</option>
                                        <option value="Fixed assets">Fixed assets</option>
                                        <option value="Bank">Bank</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Offset account</label>
                                    <input type="text" class="form-control" v-model="item.offset_account" name="offset_account">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Document</label>
                                    <input type="text" class="form-control" v-model="item.document" name="document">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Detail level</label>
                                    <input type="text" class="form-control" v-model="item.detail_level" name="detail_level">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Posting layer</label>
                                    <input type="text" class="form-control" v-model="item.posting_layer" name="posting_layer">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Number Allocation At Posting</label>
                                    <input type="text" class="form-control" v-model="item.number_allocation_at_posting" name="number_allocation_at_posting">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Delete Lines After Posting</label>                                    
                                        <div class="custom-control custom-switch mb-3 mt-1">
                                        <input type="checkbox" class="custom-control-input" id="delete_lines_after_posting" v-model="item.delete_lines_after_posting">
                                            <label class="custom-control-label" for="delete_lines_after_posting">
                                                <span class="badge" :class="item.delete_lines_after_posting ? 'badge-success' : 'badge-danger'">
                                                    {{ item.delete_lines_after_posting ? 'Yes' : 'No'  }}
                                                </span>
                                            </label>
                                        </div>
                                </div>
                                <div class="col-sm-12 mt-1">
                                    <label>Lines limit</label>
                                    <input type="text" class="form-control" v-model="item.lines_limit" name="lines_limit">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Amounts Include Sales Tax</label>
                                       <div class="custom-control custom-switch mb-3 mt-1">
                                        <input type="checkbox" class="custom-control-input" id="amounts_include_sales_tax" v-model="item.amounts_include_sales_tax">
                                            <label class="custom-control-label" for="amounts_include_sales_tax">
                                                <span class="badge" :class="item.amounts_include_sales_tax ? 'badge-success' : 'badge-danger'">
                                                    {{ item.amounts_include_sales_tax ? 'Yes' : 'No'  }}
                                                </span>
                                            </label>
                                        </div>
                                </div>
                                <div class="col-sm-12">
                                    <label>Remittance type</label>
                                    <input type="text" class="form-control" v-model="item.remittance_type" name="remittance_type">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Bank account</label>
                                    <input type="text" class="form-control" v-model="item.bank_account" name="bank_account">
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Cost center</label>
                                    <select name="cost_center" v-model="item.cost_center" class="form-control">
                                        <option v-for="cost_center in cost_centers" :value="cost_center.financial_dimension_value_code">{{ cost_center.dimension_name }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Department</label>
                                    <select name="department" v-model="item.department" class="form-control">
                                        <option v-for="department in departments" :value="department.financial_dimension_value_code">{{ department.dimension_name }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Expense purpose</label>
                                    <select name="expense_purpose" v-model="item.expense_purpose" class="form-control">
                                        <option v-for="expense_purpose in expense_purposes" :value="expense_purpose.financial_dimension_value_code">{{ expense_purpose.dimension_name }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>In Use</label>
                                    <div class="custom-control custom-switch mb-3 mt-1">
                                        <input type="checkbox" class="custom-control-input" id="in_use_checkbox" v-model="item.in_use_checkbox">
                                        <label class="custom-control-label" for="in_use_checkbox">
                                            <span class="badge" :class="item.in_use_checkbox ? 'badge-success' : 'badge-danger'">
                                                {{ item.in_use_checkbox ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <label>Used by user</label>
                                    <input type="text" class="form-control" v-model="item.used_by_user" name="used_by_user">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="col-sm-12">
                                <label>Save Activity Logs</label>
                                    <div class="custom-control custom-switch mb-3 mt-1">
                                        <input type="checkbox" class="custom-control-input" id="log_in_checkbox" v-model="item.log_in_checkbox">
                                        <label class="custom-control-label" for="log_in_checkbox">
                                            <span class="badge" :class="item.log_in_checkbox ? 'badge-success' : 'badge-danger'">
                                                {{ item.log_in_checkbox ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Log Message</label>
                                <input type="text" class="form-control" v-model="item.log_message" name="log_message">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="addJournal">{{ btnLabel }}</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	import { bus }from 'Root/bus.js';
    import NewJournal from 'Components/dialogs/NewJournal.vue';
    import ResponseMixin from 'Mixins/response.js';

	export default {
		props: {
            modalId: {
                default: 'new-journal',
                type: String
            },
            btnLabel: {
                default: 'Save Changes',
                type: String
            },

	        cost_centers: Array,
	        departments: Array,
	        expense_purposes: Array,

	        submitUrl: String,

            modalTitle: String,
            
            edit: {
                default: false,
                type: Boolean
            },
            selected: Object,
		},

		data() {
			return {
                item: {},
			}
		},

        watch: {
            selected(val) {
                if(this.edit) {
                    this.item = val;
                }
            }
        },

        mixins: [ ResponseMixin ],

        mounted() {
            flatpickr(this.$refs.reversing_date)
            flatpickr(this.$refs.posted_on)
            flatpickr(this.$refs.approved_date)
        },

        methods: {
        	addJournal() {
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
                                    this.item = {};
                                }

                            }).catch(error => {
                                this.$loading.show(false);
                                this.parseError(error, null);
                            })
                    }
                })
        		
        	}
        }
	}
</script>