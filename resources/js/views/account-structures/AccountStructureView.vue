
<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
				
			<div class="card card-default">
				<div class="card-header">
					<h3 class="card-title"><b>Account Structure</b></h3>
				</div>
				<div class="card-body">
					<div class="row">
		    			<div class="form-group col-sm-6">
		    				<label>Client</label>
							<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
							<input type="hidden" name="client_id" v-model="item.client_id"> 	
		    			</div>
					</div>
					<div class="row">

						<div class="form-group col-sm-6">
							<label>Ledger</label>
							<model-list-select :list="filtered_ledgers"
							class="form-control"
							label=""		
							name="ledger_id"					
							v-model="item.ledger_id"
							option-value="ledger_id"
							option-text="ledger_name"
							placeholder="Please select a Ledger"
							>
							</model-list-select>	
							<input type="hidden" name="ledger_id" v-model="item.ledger_id"> 
							<input type="hidden" name="ledger_code" v-model="ledger.ledger_code"> 	
							<input type="hidden" name="ledger_name" v-model="ledger.ledger_name">
							<input type="hidden" name="company_id" v-model="ledger.company_id">											
						</div>

						<div class="form-group col-sm-6">
							<label>Chart of Account Code</label>			    				
							<model-list-select :list="filtered_coas"
							class="form-control"
							label=""		
							name="chart_of_accounts_id"					
							v-model="item.chart_of_accounts_id"
							option-value="coa_id"
							option-text="coa_name"
							placeholder="Please select a Ledger"
							>
							</model-list-select>	
							<input type="hidden" name="chart_of_accounts_id" v-model="item.chart_of_accounts_id"> 
							<input type="hidden" name="ledger_chart_of_accounts" v-model="coa.coa_name"> 
						</div>

						<div class="form-group col-sm-6">
							<label>Account Structure Id</label>
							<input name="ledger_account_structure_id" v-model="item.ledger_account_structure_id" class="form-control" readonly >
						</div>
						<div class="form-group col-sm-6">
							<label>Account Structure Code Number</label>
							<input name="ledger_account_structure_code_number" type="text" v-model="item.ledger_account_structure_code_number" class="form-control">
						</div>
						<div class="form-group col-sm-6">
							<label>Account Structure Code</label>
							<input name="ledger_account_structure_code" type="text" v-model="item.ledger_account_structure_code" class="form-control">
						</div>
						<div class="form-group col-sm-6">
							<label>Account Structure Name</label>
							<input name="ledger_account_structure_name" type="text" v-model="item.ledger_account_structure_name" class="form-control">
						</div>

							<text-editor
								v-model="item.description"
								class="col-sm-12"
								label="Description"
								name="description"
								row="5"
							></text-editor>

						<div class="form-group col-sm-6">
							<label>Main Account From</label>
							<input name="main_account_from" type="text" v-model="item.main_account_from" class="form-control">
						</div>

						<div class="form-group col-sm-6">
							<label>Main Account To</label>
							<input name="main_account_to" type="text" v-model="item.main_account_to" class="form-control">
						</div>

						<div class="form-group col-sm-6">
							<label>Active From</label>
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
								<input ref="active_from" type="text" class="form-control calendar-form" name="active_from" v-model="item.active_from" readonly>
							</div>
						</div>

						<div class="form-group col-sm-6">					
							<label>Active To</label>
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
								<input ref="active_to" type="text" class="form-control calendar-form" name="active_to" v-model="item.active_to" readonly>
							</div>
						</div>
									
						<div class="row mb-2 ml-1">
							<div class="form-group col-md-12">
								<div class="custom-control custom-switch">
									<input
									v-model="item.ledger_status"
									name="ledger_status" :checked="item.ledger_status" type="checkbox" class="custom-control-input" id="ledger_status" :true-value="1" :false-value="0">
									<label class="custom-control-label" for="ledger_status">Ledger Status</label>
								</div>
							</div>
						</div>							
					</div>
				</div>
				<div class="card-footer text-right">
					<action-button type="submit" :disabled="loading" class="btn btn-sm btn-primary">Save Changes</action-button>
				</div>
			</div>

			<div class="card card-default">
				<div class="card-header">
					<h3 class="card-title"><b>Audit Trail</b></h3>
				</div>
				<div class="card-body">
					<div class="row">
							<div class="form-group col-sm-6">
							<label>Created By</label>
								<input readonly v-model="created_by" type="text" class="form-control">
						</div>
							<div class="form-group col-sm-6">
							<label>Created On</label>
							<input readonly v-model="item.formatted_created_at" type="text" class="form-control">
						</div>
					
							<div class="form-group col-sm-6">
							<label>Updated By</label>
							<input readonly v-model="updated_by" type="text" class="form-control">
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

	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import { ModelListSelect } from 'vue-search-select'

	import Datepicker from 'vuejs-datepicker';
	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {
		props: {
			asId: String,
			submitUrl: String
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.ledgers = data.ledgers ? data.ledgers : this.ledgers;			
				this.coas = data.coas ? data.coas : this.coas;				
				this.clients = data.clients ? data.clients : this.clients;				
			},

		},

		data() {
			var item = {};
			if (this.asId) {
				item['ledger_account_structure_id'] = this.asId; 
			}			
			return {
				item: item,
				ledger: {},
				ledgers: [],
				coa: {},
				coas: [],
				clients: [],
				created_by : null,
				updated_by : null,				
				main_account_categories: [],			

			}
		},

		components: {
			ModelListSelect,			
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton,
			'v-select' : Vselect,
		},

		mounted() {
            let vm = this;
			
			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)
		},

		computed: {
			filtered_ledgers() {
				var client_id = this.item.client_id;
				return this.ledgers.filter((ledger) => parseInt(ledger.client_id) == client_id);
			},

			filtered_coas() {
				var client_id = this.item.client_id;
				return this.coas.filter((coa) => parseInt(coa.client_id) == client_id);
			},
		},

		mixins: [ CrudMixin ],

		watch: {

			'item.created_by'(val) {
				this.created_by = val.fullname;
			},

			'item.updated_by'(val) {
				this.updated_by = val.fullname;
			},

			'item.confirm_by'(val) {
				if(val) {
					this.confirm_by = val.fullname ? val.fullname : null;
				}
			},

			'item.ledger_id'(val) {
				this.ledger = this.ledgers.filter(ledger => ledger.ledger_id == val)[0] ? this.ledgers.filter(ledger => ledger.ledger_id == val)[0]	: '-'; 
			},

			'ledger.chart_of_accounts_id'(val) {
				this.coa = this.coas.filter(coa => coa.coa_id == val)[0] ? this.coas.filter(coa => coa.coa_id == val)[0] : '-';
				// console.log(this.coa);
			},			

			
		},		

	}
</script>
<style>


</style>