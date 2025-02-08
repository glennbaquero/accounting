<template>
	<div class="row">
	    <div class="col-sm-3">
	        <div class="row">

				<div class="col-md-12 mb-2">
					<label>Client</label>
					<v-select :disabled="true" v-model="item.client_id" :options="clients" :reduce="item => item.id" label="name" placeholder="Select Client"></v-select>
 					<input name="client_id" hidden v-model="item.client_id"> 
				</div>
			
	            <div class="col-sm-12 mb-2">
	                <label>{{ invoiceHeaderTitle }}</label>
	                <input type="text" class="form-control" v-model="item[invoiceJournalNumber]" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Journal Batch number</label>
	                <input type="text" class="form-control" v-model="item.invoice_journal_batch_number" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Journal Name number</label>
	                <input type="text" class="form-control" v-model="item.journal_name_number" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Journal name</label>
	                <input type="text" class="form-control" v-model="item.journal_name" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Description</label>
	                <input type="text" class="form-control" v-model="item.description" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>(Balance) Journal</label>
	                <input type="text" class="form-control" v-model="item.totalBalance" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>(Total debit) Journal</label>
	                <input type="text" class="form-control" v-model="item.totalDebit" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>(Total credit) Journal</label>
	                <input type="text" class="form-control" v-model="item.totalCredit" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Reported as ready by</label>
	                <input type="text" class="form-control" v-model="item.reported_as_ready_by_journal" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Approved by</label>
	                <input type="text" class="form-control" v-model="item.approved_by_journal" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Approved date</label>
	                <input type="text" class="form-control" v-model="item.approved_date" readonly>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-3">
	        <div class="row">
	            <div class="col-sm-12 mb-2">
	                <label>Rejected by</label>
	                <input type="text" class="form-control" v-model="item.rejected_by_journal" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Posted</label>
	                <input type="checkbox" v-model="item.posted_checkbox" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Posted on</label>
	                <input type="text" class="form-control" v-model="item.posted_on" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Posted by</label>
	                <input type="text" class="form-control" v-model="item.posted_by" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Log</label>
	                <input type="checkbox" v-model="item.log_in_checkbox" disabled>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Log Message</label>
	                <input type="text" class="form-control" v-model="item.log_message" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Reversing Entry</label>
	                <input type="checkbox"  v-model="item.reversing_entry_checkbox" disabled>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Reversing date</label>
	                <input type="text" class="form-control" v-model="item.reversing_date" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Original Journal number</label>
	                <input type="text" class="form-control" v-model="item.original_journal_number" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Show user-created only</label>
	                <input type="checkbox" v-model="item.show_user_created_only" disabled>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Journal type</label>
	                <input type="text" class="form-control" v-model="item.journal_type" readonly>
	            </div>
	            <div class="form-group col-sm-12 mb-2">
                    <label>Method of Payment</label>
                    <v-select 
                        v-model="item.method_of_payment_id" 
                        :reduce="item => item.id" 
                        label="name" 
                        placeholder="Select a Method of Payment" 
                        :options="paymentMethods"
                        disabled
                    ></v-select>
                </div>
	        </div>
	    </div>
	    <div class="col-sm-3">
	        <div class="row">
	            <div class="col-sm-12 mb-2">
	                <label>Account type</label>
	                <select v-model="item.account_type" class="form-control" name="account_type" readonly>
	                    <option value="Ledger">Ledger</option>
	                    <option value="Customer">Customer</option>
	                    <option value="Vendor">Vendor</option>
	                    <option value="Project">Project</option>
	                    <option value="Fixed assets">Fixed assets</option>
	                    <option value="Bank">Bank</option>
	                </select>
	                <!-- <input type="text" class="form-control" v-model="item.account_type" readonly> -->
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Offset account</label>
	                
	                <select v-model="item.offset_account" class="form-control" name="offset_account" readonly>
	                    <option value="Ledger">Ledger</option>
	                    <option value="Customer">Customer</option>
	                    <option value="Vendor">Vendor</option>
	                    <option value="Project">Project</option>
	                    <option value="Fixed assets">Fixed assets</option>
	                    <option value="Bank">Bank</option>
	                </select>
	                <!-- <input type="text" class="form-control" v-model="item.offset_account" readonly> -->
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Document</label>
	                <input type="text" class="form-control" v-model="item.document" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Detail level</label>
	                <input type="text" class="form-control" v-model="item.detail_level" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Posting layer</label>
	                <input type="text" class="form-control" v-model="item.posting_layer" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Number allocation at posting</label>
	                <input type="text" class="form-control" v-model="item.number_allocation_at_posting" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Delete lines after posting</label>
	                <input type="checkbox" v-model="item.delete_lines_after_posting" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Lines limit</label>
	                <input type="text" class="form-control" v-model="item.lines_limit" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Amounts include sales tax</label>
	                <input type="checkbox" v-model="item.amounts_include_sales_tax" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Remittance type</label>
	                <input type="text" class="form-control" v-model="item.remittance_type" readonly>
	            </div>
	            <div class="form-group col-sm-12 mb-2">
                    <label>Bank account</label>
                    <v-select 
                        v-model="item.bank_account" 
                        :reduce="item => item.bank_account" 
                        label="bank_name" 
                        placeholder="Select a bank" 
                        :options="clientBanks"
                        disabled
                    ></v-select>
                </div>
	        </div>
	    </div>
	    <div class="col-sm-3">
	        <div class="row">
	            <div class="col-sm-12 mb-2">
	                <label>Cost center</label>
	                <select name="cost_center" v-model="item.cost_center" class="form-control" disabled>
	                    <option v-for="cost_center in cost_centers" :value="cost_center.financial_dimension_value_code">{{ cost_center.dimension_name }}</option>
	                </select>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Department</label>
	                <select name="department" v-model="item.department" class="form-control" disabled>
	                    <option v-for="department in departments" :value="department.financial_dimension_value_code">{{ department.dimension_name }}</option>
	                </select>
	
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Expense purpose</label>
	                <select name="expense_purpose" v-model="item.expense_purpose" class="form-control" disabled>
	                    <option v-for="expense_purpose in expense_purposes" :value="expense_purpose.financial_dimension_value_code">{{ expense_purpose.dimension_name }}</option>
	                </select>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>In Use</label>
	                <input type="checkbox" v-model="item.in_use_checkbox" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Used by user</label>
	                <input type="text" class="form-control" v-model="item.used_by_user" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Created by</label>
	                <input type="text" class="form-control" v-model="item.created_by" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Created on</label>
	                <input type="text" class="form-control" v-model="item.created_at" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Updated by</label>
	                <input type="text" class="form-control" v-model="item.updated_by" readonly>
	            </div>
	            <div class="col-sm-12 mb-2">
	                <label>Updated on</label>
	                <input type="text" class="form-control" v-model="item.updated_at" readonly>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script type="text/javascript">

	import { ModelListSelect } from 'vue-search-select'
	import Vselect from 'vue-select';

	export default {
		props:{
			item: Object,
			invoiceJournalNumber: String,
			invoiceHeaderTitle: String,
            cost_centers: Array,
            departments: Array,
            expense_purposes: Array,
            clients: Array,
            clientBanks: Array,
            paymentMethods: Array,
		},

		components: {
			'v-select' : Vselect,
            ModelListSelect
		},
	}
</script>