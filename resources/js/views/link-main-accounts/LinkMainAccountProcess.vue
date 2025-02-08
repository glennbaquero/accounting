<template>
	<div>
		<form-request :submit-url="submitUrl">
			<div class="card">
		        <div class="card-header">
					<div class="row">
						<div class="col-md-9">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Link Main Account Details</a></li>
								<li v-if="item.id" class="nav-item"><a class="nav-link" href="#main_accounts" data-toggle="tab">Main Accounts</a></li>
							</ul>
						</div>
					</div>
			    </div>				
			    <div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="details">
							<div class="row">
								<div class="form-group col-md-4">
									<label>Client</label>
									<v-select v-model="item.client_id" :options="clients" placeholder="Select Client" label="name" :reduce="item => item.id"></v-select>
									<input type="hidden" name="client_id" v-model="item.client_id"> 
								</div>

								<div class="form-group col-md-4">
									<label>Main Account</label>
									<v-select v-model="item.main_account" :options="filtered_main_accounts" placeholder="Select Main Account" label="main_account_name" :reduce="item => item.main_account_id" :resetOnOptionsChange="loaded"></v-select>
									<input type="hidden" name="main_account" v-model="item.main_account"> 
								</div>

								<div class="form-group col-md-4">
									<label>Chart Of Account</label>
									<v-select v-model="item.chart_of_accounts_code" :options="filtered_chart_of_accounts" placeholder="Select Chart Of Account" label="coa_name" :reduce="item => item.coa_id" :resetOnOptionsChange="loaded"></v-select>
									<input type="hidden" name="chart_of_accounts_code" v-model="item.chart_of_accounts_code">
								</div>

								<text-editor
									v-model="item.description"
									class="col-sm-12"
									label="Description"
									name="description"
									row="5"
								></text-editor>
							</div>
						</div>
						<div class="tab-pane show" id="main_accounts">
							<div class="row">
								<div class="col-md-6">
									<div class="card">
										<div class="card-header"><b>Attach Main Accounts</b></div>
										<div class="card-body">
											<main-account-table
											pivot
											:fetch-url="mainAccountsSelectedFetchUrl"
											ref="attach"
											@success="$refs.detach.fetch()"
											detach
											:account="item.id"	
											></main-account-table>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card">
										<div class="card-header"><b>Main Accounts List</b></div>
										<div class="card-body">
											<main-account-table
											pivot
											:fetch-url="mainAccountsFetchUrl"
											ref="detach"
											@success="$refs.attach.fetch()"
											attach
											:account="item.id"
											></main-account-table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</div>	
			</div>
	
			<div class="card card-default">
		        <div class="card-header">
			        <h3 class="card-title"><b>Trail Audit</b></h3>
			    </div>
			    <div class="card-body">
			    	<div class="row">
				    		<div class="form-group col-sm-6">
    		    			<label>Created By</label>
								<input readonly v-model="item.creator" type="text" class="form-control">
						</div>
				    		<div class="form-group col-sm-6">
    		    			<label>Created On</label>
							<input readonly v-model="item.formatted_created_at" type="text" class="form-control">
						</div>
		    		
				    		<div class="form-group col-sm-6">
    		    			<label>Updated By</label>
							<input readonly v-model="item.updator" type="text" class="form-control">
						</div>    		    	
				    		<div class="form-group col-sm-6">
    		    			<label>Updated on</label>
    		                <input readonly v-model="item.formatted_updated_at" type="text" class="form-control">
    		            </div>				    		
			    	</div>
			    </div>					
			</div>				
			
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import { ModelListSelect } from 'vue-search-select';
	import Vselect from 'vue-select';

	export default {
		props: {
			coaId: String,
			submitUrl: String,
			mainAccountsFetchUrl : String,
			mainAccountsSelectedFetchUrl : String,
		},

		components: {
			ModelListSelect,						
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select' : Vselect,
		},

		data() {
			var item = {};

			if (this.coaId) {
				item['coa_id'] = this.coaId; 
			}

			return {
				item: item,					
				created_by : null,
				updated_by : null,		
				client: {},
				clients: [],				
				chart_of_accounts: [],
				main_accounts: [],
				filtered_main_accounts : [],
				filtered_chart_of_accounts: [],
				loaded: false,
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;			
				this.chart_of_accounts = data.chart_of_accounts ? data.chart_of_accounts : this.chart_of_accounts;			
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
				
				setTimeout(()=>{
					this.loaded = true;
				}, 1000);
			},
		},

		watch: {
			'item.client_id'(value) {
				this.filtered_main_accounts = this.main_accounts.filter(item => item.client_id == value);
				this.filtered_chart_of_accounts = this.chart_of_accounts.filter(item => item.client_id == value);
			},
		},		

		mixins: [ CrudMixin ]
	}
</script>