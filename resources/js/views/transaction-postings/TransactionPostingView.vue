<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Transaction Posting Line Information</template>
				<div class="row">
		    		<div class="form-group col-sm-3">
		    			<label>Client</label>
            			<v-select disabled v-model="item.client_id" :options="clients" :reduce="client => client.id" label="name"></v-select>
			    		<input type="hidden" name="client_id" :value="item.client_id">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Transaction Posting Header</label>
		                <input readonly v-model="item.posting_profile_header" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
		    			<label>Transaction Posting Line</label>
		                <input name="posting_profile" v-model="item.posting_profile" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
						<label>Module</label>
						<input readonly name="module" v-model="item.module" type="text" class="form-control">
		    		</div>

					<input readonly hidden name="posting_header_id" v-model="item.posting_header_id" type="text" class="form-control">
		    	</div>
				
		    	<div class="row">
					<div class="form-group col-sm-4">
		    			<label>Types Of Account</label>
		               	<v-select v-model="item.type_of_account" placeholder="Select Type Of Account" :options="types_of_account"></v-select>
						<input readonly hidden name="type_of_account" v-model="item.type_of_account" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Debit Account Description</label>
		                <v-select v-model="item.debit_account_description" placeholder="Select Debit Account Description" :options="debit_accounts"></v-select>
						<input readonly hidden name="debit_account_description" v-model="item.debit_account_description" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Credit Account Description</label>
		                <v-select v-model="item.credit_account_description" placeholder="Select Credit Account Description" :options="credit_accounts"></v-select>
						<input readonly hidden name="credit_account_description" v-model="item.credit_account_description" type="text" class="form-control">
		    		</div>
		    	</div>

				<div class="row">
					<div class="form-group col-sm-4">
		    			<label>Procurement Posting</label>
		               	<v-select v-model="item.procurement_posting" :reduce="item => item.id" label="procurement" placeholder="Select Procurement Posting" :options="procurement_postings"></v-select>
						<input readonly hidden name="procurement_posting" v-model="item.procurement_posting" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Methods of Payments Vendor</label>
		                <v-select v-model="item.method_of_payment_vendor" :reduce="item => item.id" label="method_of_payment" placeholder="Select Payment Vendor" :options="method_of_payment_vendors"></v-select>
						<input readonly hidden name="method_of_payment_vendor" v-model="item.method_of_payment_vendor" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Methods of Payments Customer</label>
		                <v-select v-model="item.method_of_payment_customer" :reduce="item => item.id" label="method_of_payment" placeholder="Select Method of Payment" :options="method_of_payment_customers"></v-select>
						<input readonly hidden name="method_of_payment_customer" v-model="item.method_of_payment_customer" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-4">
		    			<label>Settlement Type</label>
		                <v-select v-model="item.settlement_type" placeholder="Select Settlement settlement_type" :options="settlement_types"></v-select>
						<input readonly hidden name="settlement_type" v-model="item.settlement_type" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-4">
		    			<label>Bank Posting</label>
		                <v-select v-model="item.bank_posting" :reduce="item => item.id" label="bank_posting" placeholder="Select Bank Posting" :options="bank_postings"></v-select>
						<input readonly hidden name="bank_posting" v-model="item.bank_posting" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-4">									
						<label>Status</label>
						<div class="custom-control custom-switch mb-3 mt-2">
						<input type="checkbox" name="status" class="custom-control-input" id="status" v-model="item.status">
						<label class="custom-control-label" for="status">
							<span class="badge" :class="item.status ? 'badge-success' : 'badge-danger'">
								{{ item.status ? 'Enabled' : 'Disabled'  }}
							</span>
						</label>	
						</div>
					</div>
		    	</div>

		    	<div class="row">
					<div class="form-group col-sm-4">
		    			<label>Document</label>
		                <input readonly name="document" v-model="item.document" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Document Attribute</label>
		                <input name="document_attribute" v-model="item.document_attribute" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Document Values</label>
		                <input name="document_values" v-model="item.document_values" type="text" class="form-control">
		    		</div>
		    	</div>

				<div class="row">
		    		<div class="form-group col-sm-3">
		    			<label>Journal</label>
            			<v-select v-model="item.journal" :options="journals" placeholder="Select Journal"></v-select>
			    		<input type="hidden" name="journal" :value="item.journal">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Priority</label>
		                <input v-model="item.priority" type="number" name="priority" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
		    			<label>Match Account Number</label>
		                <input readonly name="match_account_number" v-model="item.match_account_number" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
						<label>Match Account</label>
						<v-select ref="ledger-credit" v-model="item.match_account" :reduce="item => item.id" label="main_account_code" placeholder="Select Match Account" :options="filtered_main_accounts">
							<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
								<b>Type</b> : {{ main_account_type }} - 
								<b>Category</b> : {{ main_account_category }} - 
								<b>Code</b> : {{ main_account_code }} - 
								<b>Name</b> : {{ main_account_name }}
								<b>Balance Control</b> : {{ balance_control }}
							</template>
						</v-select>
						<input hidden name="match_account" class="form-control" v-model="item.match_account">
		    		</div>
		    	</div>

	    		<div class="row mt-3">

					<div class="form-group col-sm-4">
		    			<label>Offset Account Type</label>
						<v-select v-model="item.offset_account_type" :options="main_account_types" placeholder="Select Offset Account Type"></v-select>
						<input hidden name="offset_account_type" v-model="item.offset_account_type" type="text" class="form-control" readonly>
		    		</div>

		    		<div class="form-group col-sm-4">
		    			<label>Offset Account Code</label>
		                <input name="offset_account_code" v-model="item.offset_account_code" type="text" class="form-control" readonly>
		    		</div>

		    		<div class="form-group col-sm-4">
		    			<label>Offset Account</label>
						<v-select ref="ledger-credit" v-model="item.offset_account" :reduce="item => item.id" label="main_account_code" placeholder="Select Match Account" :options="filtered_main_accounts">
							<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
								<b>Type</b> : {{ main_account_type }} - 
								<b>Category</b> : {{ main_account_category }} - 
								<b>Code</b> : {{ main_account_code }} - 
								<b>Name</b> : {{ main_account_name }}
								<b>Balance Control</b> : {{ balance_control }}
							</template>
						</v-select>
						<input hidden name="offset_account" class="form-control" v-model="item.offset_account">
		    		</div>

					<div class="form-group col-sm-4">
		    			<label>Main Account Type</label>
						<v-select v-model="item.main_account_type" :options="main_account_types" placeholder="Select Main Account Type"></v-select>
						<input hidden name="main_account_type" v-model="item.main_account_type" type="text" class="form-control">
		    		</div>

		    		<div class="form-group col-sm-4">
		    			<label>Main Account Code</label>
		                <input name="main_account_number" v-model="item.main_account_number" type="text" class="form-control" readonly>
		    		</div>

		    		<div class="form-group col-sm-4">
						<label>Main Account</label>
						<v-select ref="ledger-credit" v-model="item.main_account" :reduce="item => item.id" label="main_account_code" placeholder="Select Match Account" :options="filtered_main_accounts">
							<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
								<b>Type</b> : {{ main_account_type }} - 
								<b>Category</b> : {{ main_account_category }} - 
								<b>Code</b> : {{ main_account_code }} - 
								<b>Name</b> : {{ main_account_name }}
								<b>Balance Control</b> : {{ balance_control }}
							</template>
						</v-select>
						<input hidden name="main_account" class="form-control" v-model="item.main_account">
		    		</div>

		    		<div class="form-group col-sm-6">
		    			<label>Link Account Number</label>
		                <input name="link_account_number" v-model="item.link_account_number" type="text" class="form-control" readonly>
		    		</div>

		    		<div class="form-group col-sm-6">
		    			<label>Link Account</label>
						<v-select ref="ledger-credit" v-model="item.link_account" :reduce="item => item.id" label="main_account_code" placeholder="Select Match Account" :options="filtered_main_accounts">
							<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
								<b>Type</b> : {{ main_account_type }} - 
								<b>Category</b> : {{ main_account_category }} - 
								<b>Code</b> : {{ main_account_code }} - 
								<b>Name</b> : {{ main_account_name }}
								<b>Balance Control</b> : {{ balance_control }}
							</template>
						</v-select>
						<input hidden name="link_account" class="form-control" v-model="item.link_account">
		    		</div>

					<div class="form-group col-sm-12">
						<text-editor
							v-model="item.description"
							label="Description"
							name="description"
							row="5"
						></text-editor>
					</div>
				</div>

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</template>
			</card>
				
			<card>
				<template v-slot:header>Audit Trail</template>
				<div class="row">
					<div class="form-group col-sm-3">
		    			<label>Created By</label>
		                <input readonly v-model="item.created_by" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
		    			<label>Created On</label>
		                <input readonly v-model="item.created_on" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
		    			<label>Update By</label>
		                <input readonly v-model="item.updated_by" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
		    			<label>Update On</label>
		                <input readonly v-model="item.updated_on" type="text" class="form-control">
		    		</div>
		    	</div>
			</card>
			
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import Card from 'Components/containers/Card.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";
	import TextEditor from 'Components/inputs/TextEditor.vue';

	export default {
		components: {
			Card,
			'v-select' : Vselect,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'text-editor' : TextEditor,
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
				vendors: [],
				types_of_account: [],
				debit_accounts: [],
				credit_accounts: [],

				procurement_postings : [],
				method_of_payment_vendors : [],
				method_of_payment_customers : [],
				settlement_types : [],
				bank_postings : [],
				journals : [],

				main_accounts: [],
				filtered_main_accounts: [],
				main_account_types: [],
			}
		},

		watch: {
			'item.client_id'(value) {
				this.filtered_main_accounts = this.main_accounts.filter(item => item.client_id == value);
			},

			'item.main_account'(value) {
				let main_account = this.main_accounts.filter(item => item.id == value)[0];

				if(main_account) {
					this.item.main_account_number = main_account.main_account_code;
				}else {
					this.item.main_account_number = null;
				}
			},

			'item.offset_account'(value) {
				let main_account = this.main_accounts.filter(item => item.id == value)[0];

				if(main_account) {
					this.item.offset_account_code = main_account.main_account_code;
				}else {
					this.item.offset_account_code = null;
				}
			},

			'item.match_account'(value) {
				let main_account = this.main_accounts.filter(item => item.id == value)[0];

				if(main_account) {
					this.item.match_account_number = main_account.main_account_code;
				}else {
					this.item.match_account_number = null;
				}
			},

			'item.link_account'(value) {
				let main_account = this.main_accounts.filter(item => item.id == value)[0];

				if(main_account) {
					this.item.link_account_number = main_account.main_account_code;
				}else {
					this.item.link_account_number = null;
				}
			},

		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
				this.vendors = data.vendors ? data.vendors : this.vendors;
				this.types_of_account = data.types_of_account ? data.types_of_account : this.types_of_account;
				this.debit_accounts = data.debit_accounts ? data.debit_accounts : this.debit_accounts;
				this.credit_accounts = data.credit_accounts ? data.credit_accounts : this.credit_accounts;
				this.procurement_postings = data.procurement_postings ? data.procurement_postings : this.procurement_postings;
				this.method_of_payment_vendors = data.method_of_payment_vendors ? data.method_of_payment_vendors : this.method_of_payment_vendors;
				this.method_of_payment_customers = data.method_of_payment_customers ? data.method_of_payment_customers : this.method_of_payment_customers;
				this.settlement_types = data.settlement_types ? data.settlement_types : this.settlement_types;
				this.bank_postings = data.bank_postings ? data.bank_postings : this.bank_postings;
				this.journals = data.journals ? data.journals : this.journals;
				this.main_account_types = data.main_account_types ? data.main_account_types : this.main_account_types;

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
					this.item.module = this.postingHeader.module;
					
				}
			},
		},

		mixins: [ CrudMixin ],
	}
</script>