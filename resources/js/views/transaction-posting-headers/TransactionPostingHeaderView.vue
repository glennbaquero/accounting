<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">						
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#vendor_posting_profile_header" data-toggle="tab">Transaction Posting Header</a></li>
						<li class="nav-item" v-show="item.id"><a class="nav-link" @click="initList('table-1')" href="#vendor_posting_profile_lines" data-toggle="tab">Transaction Posting Lines</a></li>
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="vendor_posting_profile_header">
							<div class="row">
								<div class="form-group col-sm-3">
									<label>Client</label>
									<v-select v-model="item.client_id" @input="changeClient" placeholder="Select Client" :options="clients" :reduce="client => client.id" label="name"></v-select>
									<input type="hidden" name="client_id" :value="item.client_id">
								</div>
								<div class="form-group col-sm-6">
									<label>Transaction Posting</label>
									<input name="posting_profile" v-model="item.posting_profile" type="text" class="form-control">
								</div>

								<div class="form-group col-sm-2">									
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
								<div class="form-group col-sm-3">
									<label>Effective Date</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input ref="effective_date" type="text" class="form-control calendar-form" name="effective_date" v-model="item.effective_date">
									</div>
								</div>

								<div class="form-group col-sm-3">
									<label>Expiration Date</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input ref="expiration_date" type="text" class="form-control calendar-form" name="expiration_date" v-model="item.expiration_date">
									</div>
								</div>

								<div class="form-group col-sm-3">
									<label>Module</label>
									<v-select v-model="item.module" placeholder="Select Document" :options="modules" :reduce="client => client.value" label="name"></v-select>
									<input type="hidden" name="module" :value="item.module">
								</div>

								<div class="form-group col-sm-3">
									<label>Document</label>
									<v-select v-model="item.document" placeholder="Select Document" :options="documents" :reduce="client => client.value" label="name"></v-select>
									<input type="hidden" name="document" :value="item.document">
								</div>
							
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label>Closing Debit Account</label>
									<v-select ref="debit_account" v-model="item.closing_debit_account" :reduce="item => item.id" label="main_account_name" placeholder="Select Closing Debit Account" :options="filtered_main_accounts">
										<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
											<b>Type</b> : {{ main_account_type }} - 
											<b>Category</b> : {{ main_account_category }} - 
											<b>Code</b> : {{ main_account_code }} - 
											<b>Name</b> : {{ main_account_name }}
											<b>Balance Control</b> : {{ balance_control }}
										</template>
									</v-select>
									<input hidden name="closing_debit_account" v-model="item.closing_debit_account">
								</div>
								<div class="form-group col-sm-6">
									<label>Closing Debit Account Code Number</label>
									<input readonly name="closing_debit_account_code_number"  v-model="item.closing_debit_account_code_number" class="form-control">
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label>Closing Credit Account</label>
									<v-select ref="credit_account"  v-model="item.closing_credit_account" :reduce="item => item.id" label="main_account_name" placeholder="Select Closing Credit Account" :options="filtered_main_accounts">
										<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
											<b>Type</b> : {{ main_account_type }} - 
											<b>Category</b> : {{ main_account_category }} - 
											<b>Code</b> : {{ main_account_code }} - 
											<b>Name</b> : {{ main_account_name }}
											<b>Balance Control</b> : {{ balance_control }}
										</template>
									</v-select>
									<input hidden name="closing_credit_account" v-model="item.closing_credit_account">
								</div>
								<div class="form-group col-sm-6">
									<label>Closing Credit Account Code Number</label>
									<input readonly name="closing_credit_account_code_number" v-model="item.closing_credit_account_code_number" class="form-control">
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-12">
									<label>Description</label>
									<text-editor v-model="item.description" name="description"></text-editor>
								</div>
							</div>
						</div>
						<div class="tab-pane show" id="vendor_posting_profile_lines">
							<a class="btn btn-success mb-2" :href="createPostingLineUrl"><i class="fa fa-plus"></i> Create Posting Profile Line</a>
							<vendor-posting-profile-table
							ref="table-1"
							:fetch-url="postingLinesFetchUrl">
							</vendor-posting-profile-table>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</div>
			</div>
	
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
			<loader :loading="loading"></loader>
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import SetupMixin from 'Mixins/setup.js'
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
			'action-button': ActionButton
		},

		props : {
			createPostingLineUrl : String,
			postingLinesFetchUrl : String
		},

		data() {
			return {
				item: {
					account_code: 'Group',
					offset_account_type: 'Ledger'
				},
				clients: [],
				documents: [],
				modules : [],

				filtered_main_accounts : [],
				main_accounts : [],
			}
		},

		
		watch : {
			'item.client_id'(value) {
				this.filtered_main_accounts = this.main_accounts.filter(item => item.client_id == value);
			},

			'item.closing_debit_account'(value) {
				let main_account = this.main_accounts.filter(item => item.id == value)[0];

				if(main_account) {
					this.item.closing_debit_account_code_number = main_account.main_account_code;
				}else {
					this.item.closing_debit_account_code_number = null;
				}
			},

			'item.closing_credit_account'(value) {
				let main_account = this.main_accounts.filter(item => item.id == value)[0];

				if(main_account) {
					this.item.closing_credit_account_code_number = main_account.main_account_code;
				}else {
					this.item.closing_credit_account_code_number = null;
				}
			},
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
				this.documents = data.documents ? data.documents : this.documents;
				this.modules = data.modules ? data.modules : this.modules;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
			},

			changeClient() {
				this.$refs.credit_account.clearSelection();
				this.$refs.debit_account.clearSelection();
			},

		},

		mounted() {
			flatpickr(this.$refs.effective_date);
			flatpickr(this.$refs.expiration_date);
		},

		mixins: [ CrudMixin, SetupMixin],
	}
</script>