<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<!-- <template v-slot:header>Vendor Information</template> -->

				<div class="row">
		    		<div class="form-group col-sm-4">
		    			<label>Client</label>
            			<v-select v-model="item.client_id" :options="clients" :reduce="client => client.id" label="name"  />
        	    		</v-select>
			    		<input type="hidden" name="client_id" :value="item.client_id">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Customer Posting Profile</label>
		                <input name="posting_profile" v-model="item.posting_profile" type="text" class="form-control">
		                <input readonly hidden name="posting_header_id" v-model="item.posting_header_id" type="text" class="form-control">
		    		</div>
		    	</div>
		    	
				<div class="row">
		    		<div class="form-group" :class=" item.account_code != 'All' ? 'col-sm-3' : 'col-sm-4' ">
		    			<label>Summary account code</label>
		                <input name="summary_account_code" v-model="item.summary_account_code" type="text" class="form-control" readonly>
		    		</div>

		    		<div class="form-group" :class=" item.account_code != 'All' ? 'col-sm-3' : 'col-sm-4' ">
		    			<label>Summary account</label>
		    			<select class="form-control" name="summary_account" v-model="item.summary_account">
		    				<option v-for="main_account in main_accounts" :value="main_account.id">{{ main_account.main_account_name }}</option>
		    			</select>
		    		</div>

		    		<div class="form-group" :class=" item.account_code != 'All' ? 'col-sm-3' : 'col-sm-4' ">
		    			<label>Account Code</label>
		    			<select class="form-control" name="account_code" v-model="item.account_code">
		    				<option value="Table">Table</option>
		    				<option value="Group">Group</option>
		    				<option value="All">All</option>
		    			</select>
		    		</div>

		    		<div class="form-group col-sm-3" v-if="item.account_code != 'All'">
		    			<label>{{ item.account_code === 'Group' ? 'Group Number' : 'Account' }}</label>
		    			<v-select multiple v-model="item.group_number" :options="vendors" :reduce="vendor => vendor.id" label="fullname" v-if="item.account_code === 'Group'" />
			    		</v-select>
			    		<input type="hidden" name="group_number" :value="JSON.stringify(item.group_number)" v-if="item.account_code === 'Group'">
		    			<v-select v-model="item.account" :options="vendors" :reduce="vendor => vendor.id" label="fullname" v-if="item.account_code === 'Table'"  />
			    		</v-select>
			    		<input type="hidden" name="account" :value="item.account" v-if="item.account_code === 'Table'">
		    		</div>

		    	</div>

		    	<div class="row mt-3">
		    		<div class="form-group col-sm-12 text-center font-weight-bold">
		    			<h3>Journal Segment</h3>
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Journal name</label>
		                <input name="journal_name" v-model="item.journal_name" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Document Status</label>
		                <input name="document_status" v-model="item.document_status" type="text" class="form-control">
		    		</div>

		    		<div class="form-group col-sm-4">
		    			<label>Document</label>
		                <input name="document" v-model="item.document" type="text" class="form-control">
		    		</div>
		    	</div>
		    	
	    		<div class="row mt-3">

		    		<div class="form-group col-sm-12 text-center font-weight-bold">
		    			<h3>Voucher Segment</h3>
		    		</div>

		    		<div class="form-group col-sm-4">
		    			<label>Offset account code</label>
		                <input name="offset_account_code" v-model="item.offset_account_code" type="text" class="form-control" readonly>

		    		</div>

		    		<div class="form-group col-sm-4">
		    			<label>Offset account</label>
		    			<select class="form-control" name="offset_account" v-model="item.offset_account">
		    				<option v-for="main_account in main_accounts" :value="main_account.id">{{ main_account.main_account_name }}</option>
		    			</select>
		    		</div>

		    		<div class="form-group col-sm-4">
		    			<label>Offset account type</label>
		    			<select v-model="item.offset_account_type" class="form-control" name="offset_account_type">
		    			    <option value="Ledger">Ledger</option>
		    			    <option value="Customer">Customer</option>
		    			    <option value="Vendor">Vendor</option>
		    			    <option value="Project">Project</option>
		    			    <option value="Fixed assets">Fixed assets</option>
		    			    <option value="Bank">Bank</option>
		    			</select>
		    		</div>

		    		<div class="form-group col-sm-6">
		    			<label>Settle account code</label>
		                <input name="settle_account_code" v-model="item.settle_account_code" type="text" class="form-control" readonly>
		    		</div>

		    		<div class="form-group col-sm-6">
		    			<label>Settle account</label>
		    			<select class="form-control" name="settle_account" v-model="item.settle_account">
		    				<option v-for="main_account in main_accounts" :value="main_account.id">{{ main_account.main_account_name }}</option>
		    			</select>
		    		</div>

		    		<div class="form-group col-sm-6">
		    			<label>Sales tax prepayments</label>
		                <input name="sales_tax_prepayments" v-model="item.sales_tax_prepayments" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Arrival</label>
		                <input name="arrival" v-model="item.arrival" type="text" class="form-control">
		    		</div>

				</div>
	
				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</template>
			</card>
			
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import Card from 'Components/containers/Card.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {
		components: {
			Card,
			'v-select' : Vselect,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select' : Vselect
		},
		
		props : {
			postingHeader : {
				type : Object,
				default() {
					return {}
				}
			},
		},

		data() {
			return {
				item: {
					account_code: 'Group',
					offset_account_type: 'Ledger'
				},
				clients: [],
				main_accounts: [],
				vendors: [],
			}
		},

		watch: {
			'item.offset_account'(val) {
				var item = _.find(this.main_accounts, (account) => { return account.id == val });

				this.item.offset_account_code = item.main_account_code_number;
			},

			'item.settle_account'(val) {
				var item = _.find(this.main_accounts, (account) => { return account.id == val });

				this.item.settle_account_code = item.main_account_code_number;
			},

			'item.summary_account'(val) {
				var item = _.find(this.main_accounts, (account) => { return account.id == val });

				this.item.summary_account_code = item.main_account_code_number;
			},
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
				this.vendors = data.vendors ? data.vendors : this.vendors;


				if(!this.item.id) {

					this.item = {
						client_id : this.postingHeader.client_id,
						posting_header_id : this.postingHeader.id,
						posting_profile_header : this.postingHeader.posting_profile,
						document : this.postingHeader.document,
						account_code : 'All'
					};
						
				}else {
					this.item.posting_profile_header = this.postingHeader.posting_profile;
					this.item.document = this.postingHeader.document;
				}
			},
		},

		mixins: [ CrudMixin ],
	}
</script>