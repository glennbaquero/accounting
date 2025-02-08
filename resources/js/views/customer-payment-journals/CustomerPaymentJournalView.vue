<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetchData" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>
					Journal Information
					<div class="float-right">
						<!-- <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button> -->
						<button type="button" class="btn btn-danger" @click="updateVoucher" :disabled="!hasSelectedData">Update Voucher</button>
						<!-- <button type="button" class="btn btn-primary" @click="openCreateVouchers" :disabled="journalItemData.posted_on && journalItemData.posted_by">Add Voucher</button> -->
						<button type="button" class="btn btn-primary" @click="openCreateVouchers">Add Voucher</button>
						<button type="button" class="btn btn-success" @click="post">POST</button>
						<!-- <button type="button" class="btn btn-success" @click="post" :disabled="journalItemData.posted_on && journalItemData.posted_by">POST</button> -->
						<button type="button" class="btn btn-warning" @click="validateVoucher">Validate</button>
					</div>
				</template>

				<div class="row">
					<div class="form-group col-md-4">
					    <label>Client</label>
					    <model-list-select :list="clients"
					    v-model="journalItem.client_id"
					    option-value="id"
					    option-text="name"
					    placeholder="Select Client"
					    class="form-control pull-right">
					    </model-list-select>
					    <input name="client_id" hidden v-model="journalItem.client_id"> 
					</div>
				</div>

				<div class="row">
					<div class="col-md-3 row">
						<div class="col-md-12">
							<label>Balance</label>
						</div>
						<div class="col-md-12">
							Journal <input type="text" name="balance_journal" :value="balanceJournal" class="form-control" disabled>
						</div>
						<div class="col-md-12">
							Per voucher <input type="text" name="" :value="balancePerVoucher" class="form-control" disabled>
						</div>
					</div>
					<div class="col-md-3 row">
						<div class="col-md-12">
							<label>Total debit</label>
						</div>
						<div class="col-md-12">
							Journal <input type="text" name="total_debit_journal" value="0.00"  :value="debitJournal" class="form-control" disabled>
						</div>
						<div class="col-md-12">
							Per voucher <input type="text" name="" value="0.00" :value="debitPerVoucher" class="form-control" disabled>
						</div>
					</div>
					<div class="col-md-3 row">
						<div class="col-md-12">
							<label>Total credit</label>
						</div>
						<div class="col-md-12">
							Journal <input type="text" name="total_credit_journal" value="0.00" :value="creditJournal" class="form-control" disabled>
						</div>
						<div class="col-md-12">
							Per voucher <input type="text" name="" value="0.00" :value="creditPerVoucher" class="form-control" disabled>
						</div>
					</div>

				</div>
				<div class="card mt-4">
				    <div class="card-header p-2">
				        <div class="row">
				        	<div class="col-md-9">
				        		<ul class="nav nav-pills">
				        		    <li class="nav-item"><a class="nav-link active" href="#overview" data-toggle="tab">Overview</a></li>
				        		    <li class="nav-item"><a class="nav-link" href="#journal-header" data-toggle="tab">Journal Header</a></li>
				        		    <li class="nav-item"><a class="nav-link" href="#invoice" data-toggle="tab">Invoice</a></li>
				        		    <li class="nav-item"><a class="nav-link" href="#cash_discount" data-toggle="tab">Cash discount</a></li>
				        		    <li class="nav-item"><a class="nav-link" href="#history" data-toggle="tab">History</a></li>
				        		    
				        		</ul>
				        	</div>
				        	<div class="col-md-3">
				        		<div class="row">
				        			<div class="col-md-12">
				        				<div class="float-right">
				        					<button type="button" class="btn btn-danger" @click="updateVoucherStatus('Rejected')" :disabled="!hasVoucherSelected">Reject</button>
				        					<button type="button" class="btn btn-success" @click="updateVoucherStatus('Approved')" :disabled="!hasVoucherSelected">Approve</button>
				        				</div>
				        			</div>
				        		</div>
				        	</div>
				        </div>
				    </div>
				    <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="overview">

				        		<filter-box @refresh="fetch">
				        		    <template v-slot:left>
				        		        <date-range
				        		        :options="filterColumns"
				        		        class="mr-2 mt-4"
				        		        @change="filter($event)"
				        		        ></date-range>
				        		    </template>
				        		</filter-box>
				        		
				        		<div class="row">
						    		<div class="col-sm-12">
						    			    
					    			    <data-table 
					    			    	ref="data-table"
					    			    	:headers="headers" 
					    			    	:items="items"
					    			    	:striped="false"
					    			    	showSelect
					    			    	@selectAll="selectAll(...arguments)"

					    			    	:filters="filters"
					    			    	:fetch-url="fetchVoucherUrl"
					    			    	order-by="id"
					    			    	order-asc
					    			    	@load="load"
					    			    	noAction
					    			    >
					    			    	<!-- <tr>
					    			    		<th v-for="header in headers">{{ header.text }}</th>
					    			    	</tr> -->
					    			    	<template v-slot:body="{ items }">
					    			    		<tr v-for="$item in items" @click="selectedLine($item)" :class="$item.selected ? 'selected-table' : ''">
					    			    		    <td>
					    			    		    	<input type="checkbox" :checked="$item.alreadyInSelectedItem" @change="dataSelected($item)">
					    			    		    </td>
													<td>{{ $item.client }}</td>
					    			    		    <td>{{ $item.entry_pair_number }}</td>
					    			    		    <td>{{ $item.voucher_number }}</td>
					    			    		    <td>{{ $item.voucher_date }}</td>
					    			    		    <td>{{ $item.transaction_date }}</td>
					    			    		    <td>{{ $item.payee }}</td>
					    			    		    <td>{{ $item.main_account_name }}</td>
					    			    		    <td>{{ $item.account_type }}</td>
					    			    		    <td>{{ $item.debit_amount }}</td>
					    			    		    <td>{{ $item.credit_amount }}</td>
					    			    		    <td>{{ $item.balance }}</td>
					    			    		    <td>{{ $item.offset_account_name }}</td>
					    			    		    <td>{{ $item.offset_account_type }}</td>
					    			    		    <td>{{ $item.description }}</td>
					    			    		    <td>{{ $item.posted_on }}</td>
					    			    		    <td>{{ $item.ledger_line_no }}</td>
					    			    		    <td>{{ $item.approved_date }}</td>
					    			    		    <td>{{ $item.log_date }}</td>
					    			    		    <td>{{ $item.log_message }}</td>
					    			    		    <td>{{ $item.invoice_number }}</td>
					    			    		    <!-- <td>{{ $item.invoice_date }}</td> -->
					    			    		    <td>{{ $item.payment_id }}</td>
					    			    		    <!-- <td>{{ $item.payment_due_date }}</td> -->
					    			    		</tr>
					    			    	</template>
			                        	</data-table>
						    		</div>
						
								</div>
	    						<card-drop-down
	    							icon="fas fa-thumbs-up"
	    							title="Logs"
	    							:inputs="log_fields"
	    							:selected="selected"
	    						></card-drop-down>
	    						<card-drop-down
	    							:selected="selected"
	    							icon="fas fa-thumbs-up"
	    							title="Posting & Approvals"
	    							:inputs="posting_approval_fields"
	    						></card-drop-down>
	    						<card-drop-down
	    							:selected="selected"
	    							icon="fas fa-clipboard-list"
	    							title="Voucher"
	    							:inputs="voucher_fields"
	    						></card-drop-down>
	    						<card-drop-down
	    							icon="fas fa-clipboard-list"
	    							title="Increase & Decrease Rule"
	    							:inputs="ledger_rules_fields"
	    							:selected="selected"
	    						></card-drop-down>
	    						<!-- <card-drop-down
	    								:selected="selected"
	    							icon="fas fa-chart-line"
	    							title="Purchase order"
	    							:inputs="sales_order_fields"
	    						></card-drop-down> -->
	    						<card-drop-down
	    							:selected="selected"
	    							icon="fas fa-money-bill-wave"
	    							title="Payment"
	    							:inputs="payment_fields"
	    						></card-drop-down>
	    						<card-drop-down
	    							:selected="selected"
	    							icon="fas fa-hand-holding-usd"
	    							title="Sales tax"
	    							:inputs="sales_tax_fields"
	    						></card-drop-down>
	    						<card-drop-down
	    							:selected="selected"
	    							icon="fas fa-user-edit"
	    							title="Audit"
	    							:inputs="audit_fields"
	    						></card-drop-down>
				        	</div>
				        	<div class="tab-pane" id="journal-header">
				        		<journal-header
				        			:item="journalItem"
						            invoice-journal-number="customer_payment_journal_number"
						            invoice-header-title="Customer Payment Journal Number"
						            :cost_centers="cost_centers"
						            :departments="departments"
						            :expense_purposes="expense_purposes"
								    :clients="clients"
								    :client-banks="client_banks"
								    :payment-methods="payment_methods"
				        		></journal-header>
				        	</div>
				        	<div class="tab-pane" id="invoice">
				        		<div class="row">
				        			<div class="form-group col-md-4">
				        			    <label>Client</label>
				        			    <model-list-select :list="clients"
				        			    v-model="selected.client_id"
				        			    option-value="id"
				        			    option-text="name"
				        			    placeholder="Select Client"
				        			    class="form-control pull-right">
				        			    </model-list-select>
				        			    <input name="client_id" hidden v-model="selected.client_id"> 
				        			</div>
				        			
		        			        <div class="col-sm-4">
		        			            <label>Invoice date</label>
		        			            <input type="text" class="form-control calendar-form" v-model="selected.invoice_date" id="invoice_date">
		        			        </div>
		        			        <div class="col-sm-4">
		        			            <label>Customer invoice number</label>
		        			            <input type="text" class="form-control" v-model="selected.customer_invoice_number" readonly>
		        			        </div>
		        			        <div class="col-sm-4">
		        			            <label>Invoice Number</label>
		        			            <input type="text" class="form-control" v-model="selected.invoice_number">
		        			        </div>
		        			        <div class="col-sm-4">
		        			            <label>Customer account</label>
		        			            <input type="text" class="form-control" v-model="selected.customer_account" readonly>
		        			        </div>
		        			        <div class="col-sm-4">
		        			            <label>Customer Name</label>
		        			            <input type="text" class="form-control" v-model="selected.customer_name" readonly>
		        			        </div>

		        			        <div class="col-sm-4">
		        			            <label>Due date</label>
		        			            <input type="text" class="form-control" v-model="selected.payment_due_date" readonly>
		        			        </div>
				        		</div>
				        	</div>
				        	<div class="tab-pane" id="cash_discount">
				        		<div class="row">
				        			<div class="col-sm-4">
				        			    <label>Cash discount code</label>
				        			    <input type="text" class="form-control" v-model="selected.cash_discount_code" readonly>
				        			</div>
				        			<div class="col-sm-4">
				        			    <label>Cash discount date</label>
				        			    <input type="text" class="form-control" v-model="selected.cash_discount_date" readonly>
				        			</div>
				        			<div class="col-sm-4">
				        			    <label>Cash discount amount</label>
				        			    <input type="text" class="form-control" v-model="selected.cash_discount_amount" readonly>
				        			</div>
				        		</div>
				        	</div>
				        </div>
				    </div>
				</div>
			</card>
			

			<new-voucher
			    :submit-url="voucherSubmitUrl"
			    @success="fetch"
			    modal-title="Create Voucher"
			    modal-id="new-voucher"
			    :journal="journalItem"
			    :customerOrVendorList="customers"
			    customerOrVendorValue="customer_account"
			    customerOrVendorDisplay="customer_name"
			    :terms_of_payments="terms_of_payments"
			    :payment_methods="payment_methods"
			    voucher-identification="voucher_number"
			    journal-identification="customer_payment_journal_number"
			    :isVendor="false"
			    deposit-type="deposit_slip_number"
			    due-date-column-name="payment_due_date"
			    :main_accounts="main_accounts"
			    :clients="clients"
			    :invoices="invoices"
			    :clientId="journalItem.client_id"
			    :client-banks="client_banks"
			></new-voucher>

		</form-request>
	</div>
</template>

<script>
	import { bus }from 'Root/bus.js';

	import CrudMixin from 'Mixins/crud.js';

	import Selectize from 'vue2-selectize';
	import selectizecss from 'selectize/dist/css/selectize.css';

	import Datepicker from 'vuejs-datepicker';
    // import DataTable from 'Components/tables/DataTable.vue';

	import Card from 'Components/containers/Card.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	import CardDropDown from 'Components/containers/CardDropDown.vue';
	import JournalHeader from 'Components/journals/JournalHeader.vue';
    import NewVoucher from 'Components/dialogs/vendor-components/NewPaymentVoucher.vue';

    import ResponseMixin from 'Mixins/response.js';
    import HelpersMixin from 'Mixins/helpers.js';
    import VoucherMixin from 'Mixins/voucher.js';
    import ListMixin from 'Mixins/list.js';

    import { ModelListSelect } from 'vue-search-select'

	export default {
		props: {
			vendorInvoice: {
				default: null
			},
		},

        computed: {
            headers() {
                let array = [
                    { text: 'Client', value: 'client' },
                    { text: 'Entry Number', value: 'entry_pair_number' },
                    { text: 'Voucher', value: 'invoice_voucher_number' },
                    { text: 'Voucher date', value: 'voucher_date' },
                    { text: 'Transaction date', value: 'transaction_date' },
                    { text: 'Payee', value: 'payee' },
                    { text: 'Account', value: 'customer_account' },
                    { text: 'Account type', value: 'account_type' },
                    { text: 'Debit', value: 'debit_amount' },
                    { text: 'Credit', value: 'credit_amount' },
                    { text: 'Balance', value: 'balance' },
                    { text: 'Offset account', value: 'offset_account' },
                    { text: 'Offset account type', value: 'offset_account_type' },
                    { text: 'Description', value: 'description' },

                    { text: 'Posted on', value: 'posted_on' },
                    { text: 'Ledger line no', value: 'ledger_line_no' },
                    { text: 'Approved date', value: 'approved_date' },

                    { text: 'Log date', value: 'log_date' },
                    { text: 'Log message', value: 'log_message' },
                    { text: 'Invoice', value: 'invoice_number' },
                    // { text: 'Invoice date', value: 'invoice_date' },
                    { text: 'Payment ID', value: 'payment_id' },
                    // { text: 'Payment Due', value: 'due_date' },
                ];

                return array;
            },

            log_fields() {

            	let array = [
            	    { label: 'Logged date', value: this.selected.log_date, editable: false, model: 'log_date' },
            	    { label: 'Logged by', value: this.selected.logged_by, editable: false, model: 'logged_by' },
            	    { label: 'Logged message', value: this.selected.log_message, editable: false, model: 'log_message' },
            	];

            	return array;
            },

            posting_approval_fields() {

            	let array = [
            	    { label: 'Posted', value: this.selected.posted_by_name, editable: false, model: 'posted_by_name' },
            	    { label: 'Posted on', value: this.selected.posted_on, editable: false, model: 'posted_on' },
            	    { label: 'Posted by', value: this.selected.posted_by_name, editable: false, model: 'posted_by_name' },
            	    { label: 'Adjusted', value: this.selected.adjusted, editable: true, model: 'adjusted', isCheckbox: true },
            	    { label: 'Adjusted on', value: this.selected.adjusted_on, editable: true, model: 'adjusted_on', isDate: true, ref: 'adjusted_on' },
            	    { label: 'Adjusted by', value: this.selected.adjusted_by, editable: true, model: 'adjusted_by' },
            	    { label: 'Approved', value: this.selected.approved_by_journal, editable: false, model: 'approved_by_journal' },
            	    { label: 'Approved date', value: this.selected.approved_date, editable: false, model: 'approved_date' },
            	    { label: 'Approved by', value: this.selected.approved_by_journal, editable: false, model: 'approved_by_journal' },
            	    { label: 'Review date', value: this.selected.review_date, editable: false, model: 'review_date' },
            	    { label: 'Reported as ready by', value: this.selected.reported_as_ready_by_journal, editable: false, model: 'reported_as_ready_by_journal' },
            	    { label: 'Rejected by', value: this.selected.rejected_by_journal, editable: false, model: 'rejected_by_journal' },
            	];

            	return array;
            },

            voucher_fields() {

            	let array = [
            		{ label: 'Client', value: this.selected.client_id, editable: true, model: 'client_id', isDropdownSelection: true, opt_value: 'id', selections: this.clients, value: 'id', display: 'name' },
					{ label: 'Entry #', value: this.selected.entry_pair_number, editable: true, model: 'entry_pair_number' },
            	    { label: 'Voucher', value: this.selected.voucher_number, editable: false, model: 'voucher_number' },
            	    { label: 'Voucher Line number', value: this.selected.voucher_line_number, editable: false, model: 'voucher_line_number' },
            	    { label: 'Voucher date', value: this.selected.voucher_date, editable: false, model: 'voucher_date' },
            	    { label: 'Description', value: this.selected.description, editable: true, model: 'description' },
            	    { label: 'Journal Name', value: this.selected.journal_name, editable: false, model: 'journal_name' },
            	    { label: 'Main Account', value: this.selected.main_account, editable: true, model: 'main_account', isDropdownSelection: true, selections: this.main_accounts, opt_value: 'id', display: 'main_account_name' },
            	    { label: 'Debit', value: this.selected.debit_amount, editable: true, model: 'debit_amount' },
            	    { label: 'Credit', value: this.selected.credit_amount, editable: true, model: 'credit_amount' },
            	    { label: 'Journal Batch number', value: this.selected.invoice_journal_batch_number, editable: false, model: 'invoice_journal_batch_number' },
            	    { label: 'Account type', value: this.selected.account_type, editable: true, model: 'account_type', isDropdownSelection: true, selections: this.account_types, opt_value: 'label', display: 'label' },
            	    { label: 'Offset company accounts', value: this.selected.offset_company_accounts, editable: true, model: 'offset_company_accounts' },
            	    { label: 'Offset account type', value: this.selected.offset_account_type, editable: true, model: 'offset_account_type', isDropdownSelection: true, selections: this.account_types, opt_value: 'label', display: 'label' },
            	    { label: 'Offset account', value: this.selected.offset_account_id, editable: true, model: 'offset_account', isDropdownSelection: true, selections: this.main_accounts, opt_value: 'id', display: 'main_account_name' },
            	    { label: 'Offset-transaction text', value: this.selected.offset_transaction_text, editable: true, model: 'offset_transaction_text' },
            	];

            	return array;
            },

            sales_order_fields() {

            	let array = [
            	    { label: 'Invoice date', value: this.selected.invoice_date, editable: true, model: 'invoice_date' },
            	    { label: 'Customer Invoice number', value: this.selected.customer_invoice_number, editable: true, model: 'customer_invoice_number' },
            	    { label: 'Purchase order', value: this.selected.sales_order, editable: true, model: 'sales_order' },
            	    { label: 'Invoice payment release date', value: this.selected.invoice_payment_release_date, editable: true, model: 'invoice_payment_release_date' },
            	    { label: 'Customer account', value: this.selected.customer_account, editable: true, model: 'customer_account' },
            	    { label: 'Customer Name', value: this.selected.customer_name, editable: true, model: 'customer_name' },
            	    { label: 'Due date', value: this.selected.due_date, editable: true, model: 'due_date' },
            	    { label: 'Pending Customer invoice', value: this.selected.pending_customer_invoice, editable: true, model: 'pending_customer_invoice' },
            	];

            	return array;
            },

            payment_fields() {

            	let array = [
            	    { label: 'Payment ID', value: this.selected.payment_id, editable: true, model: 'payment_id' },
            	    { label: 'Invoice Number', value: this.selected.invoice_number, editable: true, model: 'invoice_number' },
            	    { label: 'Payment Due Date', value: this.selected.payment_due_date, editable: true, model: 'payment_due_date', isDate: true, ref: 'payment_due_date' },
            	    { label: 'Settlement Type', value: this.selected.settlement_type, editable: true, model: 'settlement_type', isDropdownSelection: true, selections: this.settlement_types, value: 'name', display: 'name' },
            	    { label: 'Bank transaction type', value: this.selected.bank_transaction_type, editable: true, model: 'bank_transaction_type' },
            	    { label: 'Method of payment', value: this.selected.method_of_payment, editable: true, model: 'method_of_payment', isDropdownSelection: true, selections: this.payment_methods, value: 'id', display: 'name' },
            	    { label: 'Payment Status', value: this.selected.payment_status, editable: true, model: 'payment_status', isDropdownSelection: true, selections: this.payment_statuses, value: 'name', display: 'name' },
            	    { label: 'Bank account', value: this.selected.bank_account, editable: true, model: 'bank_account' },
            	    { label: 'Terms of payment', value: this.selected.terms_of_payment, editable: true, model: 'terms_of_payment', isDropdownSelection: true, selections: this.terms_of_payments, value: 'terms_of_payment', display: 'terms_of_payment' },
            	    { label: 'Payment specification', value: this.selected.payment_specification, editable: true, model: 'payment_specification' },
            	    { label: 'Payment reference', value: this.selected.payment_reference, editable: true, model: 'payment_reference' },
            	    { label: 'Deposit Slip Number', value: this.selected.deposit_slip_number, editable: true, model: 'deposit_slip_number' },
            	];

            	return array;
            },

            sales_tax_fields() {

            	let array = [
            	    { label: 'Sales tax group', value: this.selected.sales_tax_group, editable: true, model: 'sales_tax_group' },
            	    { label: 'Sales tax direction', value: this.selected.sales_tax_direction, editable: true, model: 'sales_tax_direction' },
            	    { label: 'Item sales tax group', value: this.selected.item_sales_tax_group, editable: true, model: 'item_sales_tax_group' },
            	    { label: 'Terms of payment', value: this.selected.terms_of_payment, editable: true, model: 'terms_of_payment' },
            	    { label: 'Withholding Tax Group', value: this.selected.withholding_tax_group, editable: true, model: 'withholding_tax_group' },
            	    { label: 'Fee Account', value: this.selected.fee_account, editable: true, model: 'fee_account' },
            	    { label: 'Fee ID', value: this.selected.fee_id, editable: true, model: 'fee_id' },
            	    { label: 'Fee Amount', value: this.selected.fee_amount, editable: true, model: 'fee_amount' },
            	];

            	return array;
            },

            audit_fields() {

            	let array = [
            	    { label: 'Created On', value: this.selected.created_date, editable: false, model: 'created_date' },
            	    { label: 'Created By', value: this.selected.created_by, editable: false, model: 'created_by' },
            	    { label: 'Updated On', value: this.selected.updated_date, editable: false, model: 'updated_date' },
            	    { label: 'Updated By', value: this.selected.updated_by, editable: false, model: 'updated_by' },
            	];

            	return array;
            },

            balanceJournal() {
            	var credit = 0;
            	var debit = 0;

            	credit = _.sumBy(this.voucher_lines, (item) => {
            		return parseFloat(item.credit_amount);
            	});

            	debit = _.sumBy(this.voucher_lines, (item) => {
            		return parseFloat(item.debit_amount);
            	});

            	return parseFloat(credit - debit);
            },

            balancePerVoucher() {
            	var credit = 0;
            	var debit = 0;

            	credit = _.sumBy(this.voucher_lines, (item) => {
            		if(item.alreadyInSelectedItem) {
	            		return parseFloat(item.credit_amount);
            		} else {
            			return 0;
            		}
            	});

            	debit = _.sumBy(this.voucher_lines, (item) => {
            		if(item.alreadyInSelectedItem) {
	            		return parseFloat(item.debit_amount);
            		} else {
            			return 0;
            		}
            	});

            	return parseFloat(credit - debit);
            },

            debitJournal() {
            	return _.sumBy(this.voucher_lines, (item) => {
            		return parseFloat(item.debit_amount);
            	});
            },

            debitPerVoucher() {

            	var lines = _.filter(this.voucher_lines, ['alreadyInSelectedItem', true]);
            	var total = 0;

            	if(!_.isEmpty(lines)) {
    		    	total = _.sumBy(lines, (item) => {
    		    		if(lines[0].payment_id == item.payment_id && lines[0].payment_due_date == item.payment_due_date) {
    						return parseFloat(item.debit_amount);
    		    		}
    		    	})
            	} else {
	            	total = 0;
            	}


            	return total;

            },


            creditJournal() {
            	return _.sumBy(this.voucher_lines, (item) => {
            		return parseFloat(item.credit_amount);
            	});
            },

            creditPerVoucher() {
            	var lines = _.filter(this.voucher_lines, ['alreadyInSelectedItem', true]);
            	var total = 0;

            	if(!_.isEmpty(lines)) {
    		    	total = _.sumBy(lines, (item) => {
    		    		if(lines[0].invoice_number == item.invoice_number && lines[0].invoice_date == item.invoice_date) {
    						return parseFloat(item.credit_amount);
    		    		}
    		    	})
            	} else {
	            	total = 0;
            	}


            	return total;
            },

            hasSelectedData() {
            	return !_.isEmpty(this.selected);
            }
        },

		data() {
			return {
				client_banks: [],
                items: [],

                showOnlyData: {
                	account_type: true,
                	credit: true,
                	debit: true,
                	description: true,
                	offset_account: true,
                	offset_account_type: true
                },

                selected: {},


                voucher_lines: [],
                customer_invoice: [],
                terms_of_payments: [],
                payment_methods: [],
                cost_centers: [],
                departments: [],
                expense_purposes: [],
                customers: [],
                main_accounts: [],
                clients: [],
                invoices: [],
                account_types: [
                	{
                		label: 'Ledger'
                	},
                	{
                		label: 'Customer'
                	},
                	{
                		label: 'Vendor'
                	},
                	{
                		label: 'Project'
                	},
                	{
                		label: 'Fixed assets'
                	},
                	{
                		label: 'Bank'
                	},

                ],

                settlement_types: [
                	{
                		name: 'None'
                	},
                	{
                		name: 'Open transactions'
                	},
                	{
                		name: 'Designated transactions'
                	},
                ],

                payment_statuses: [
                	{
                		name: 'None'
                	},
                	{
                		name: 'Sent'
                	},
                	{
                		name: 'Received'
                	},
                	{
                		name: 'Approved'
                	},
                	{
                		name: 'Rejected'
                	},
                ],

                journalItemData: {},
	      	}
		},

		components: {
			'card': Card,
            // 'data-table': DataTable,
		    'selectize': Selectize,
		    'datepicker': Datepicker,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'card-drop-down': CardDropDown,
			'journal-header': JournalHeader,
            'new-voucher': NewVoucher,
            ModelListSelect
		},

		mixins: [ CrudMixin, ResponseMixin, HelpersMixin, VoucherMixin, ListMixin ],

		watch: {
			items(val) {
				this.init();
			}
		},

		mounted() {
			this.fetchData();
		},

		methods: {
			fetchSuccess(data) {
				// this.item = data.item ? data.item : this.item;
				this.voucher_lines = data.voucher_lines ? data.voucher_lines : this.voucher_lines;
				this.customer_invoice = data.customer_invoice ? data.customer_invoice : this.customer_invoice;
				this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
				this.client_banks = data.client_banks ? data.client_banks : this.client_banks;

				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.departments = data.departments ? data.departments : this.departments;
				this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
				this.customers = data.customers ? data.customers : this.customers;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
				this.clients = data.clients ? data.clients : this.clients;
				this.invoices = data.invoices ? data.invoices : this.invoices;
				this.journalItemData = data.item ? data.item : this.journalItem;

				this.checker('Customer Payment Journal', this.journalItem.customer_payment_journal_number)

				flatpickr('#invoice_date')
			},

			init() {
				setTimeout(() => {
					this.voucher_lines = this.$refs['data-table'].items;
				}, 500)
			},

			fetchData() {
				axios.post(this.fetchUrl)
					.then(response => {
						this.fetchSuccess(response.data);
					})
			},

			onEnter(e, input) {
				if(e.keyCode === 13) {
					this.showOnlyData[input] = true; 
				}
			},

		}
	}
</script>

<style type="text/css">
	tr {
		cursor: hand;
	}

	.selected-table {
		background: #C1C1C1;
	}
</style>