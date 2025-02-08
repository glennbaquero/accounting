<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
		
			<div class="card card-default">
		        <div class="card-header">
			        <h3 class="card-title"><b>Ledger Information</b></h3>
			    </div>				
			    <div class="card-body">
					<div class="row">			    
							<div class="form-group col-md-12">
								<div class="custom-control custom-switch">
									<input
									v-model="item.ledger_status"
									name="ledger_status" :checked="item.ledger_status" type="checkbox" class="custom-control-input" id="ledger_status" :true-value="1" :false-value="0">
									<label class="custom-control-label" for="ledger_status">Ledger Status</label>
								</div>
							</div>

							<div class="form-group col-md-6">
			    				<label>Client</label>
								<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
								<input type="hidden" name="client_id" v-model="item.client_id"> 	
							</div>

				    		<div class="form-group col-sm-6">
				    			<label>Ledger ID</label>
				                <input type="text"  name="ledger_id" v-model="item.ledger_id" class="form-control" readonly>
				    		</div>

				    		<div class="form-group col-sm-6">
				    			<label>Ledger Code</label>
				                <input type="text"  name="ledger_code" v-model="item.ledger_code" class="form-control">
				    		</div>		    		

				    		<div class="form-group col-sm-6">
				    			<label>Ledger Name</label>
				                <input type="text"  name="ledger_name" v-model="item.ledger_name" class="form-control">
				    		</div>		    				   

			    			<div class="form-group col-sm-6">
								<label>Chart Of Account</label>			    				
								<v-select :options="filtered_chart_of_accounts" :reduce="item => item.id" :resetOnOptionsChange="data_loaded" v-model="item.chart_of_account_id" label="coa_name">
								<template #option="{ coa_name, coa_code }">
									<b>Name</b> : {{ coa_name }} - <b>Code</b> : {{ coa_code }}
								</template>
								</v-select>
							
								<input type="hidden" name="chart_of_account_id" v-model="item.chart_of_account_id"> 				
			    			</div>	    		

				    		<div class="form-group col-sm-6">
				    			<label>Ledger Calendar</label>
								<v-select  :options="filtered_ledger_calendars" :reduce="item => item.id"  :resetOnOptionsChange="data_loaded"  v-model="item.ledger_calendar_id" label="ledger_calendar_name" :value="item.id">
									<template #option="{ ledger_calendar_name, ledger_calendar_code }">
										<b>Name</b> : {{ ledger_calendar_name }} - <b>Code</b> : {{ ledger_calendar_code }}
									</template>
								</v-select>
								<input type="hidden" name="ledger_calendar_id" v-model="item.ledger_calendar_id"> 				
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

							<text-editor
								v-model="item.description"
								class="form-group col-sm-12"
								label="Description"
								name="description"
								row="5"
							></text-editor>
			        </div>
			     </div>
				<div class="card-footer text-right">
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</div>
			</div>

			<div class="card card-default">
				<div class="card-header">
					<h3 class="card-title"><b>Audit Trail</b></h3>

					<div class="card-tools">

					</div>
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
	import DataTable from 'Components/tables/DataTable.vue';
	import Datepicker from 'vuejs-datepicker';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {
		
		props: {
			ledgerId: String,
			submitUrl: String
		},

		mixins: [ CrudMixin ],
		
		components: {	
			'text-editor': TextEditor,
			'form-request': FormRequest,	
			'data-table': DataTable,
			'datepicker': Datepicker,
			'v-select' : Vselect,
			Card,
		},

		data() {
			var item = {};
			if (this.ledgerId) {
				item['ledger_id'] = 'ledger-' + this.ledgerId; 
			}			
			return {
				item: item,
				data_loaded: false,
				chart_of_accounts: [],
				ledger_calendars: [],	
				filtered_chart_of_accounts: [],
				filtered_ledger_calendars: [],
				coa: {},
				clients: [],
				items: [],
				las: [],
				created_by : null,
				updated_by : null,	
				selected_ledger_fiscal_calendar: {},	
				selected_chart_of_accounts: {},	
				selected_client: {},	
				values_from_coa: [
					{
						label: 'Cash and Cash Equivalents',
					},
					{
						label: 'Cash in Bank-Local Currency',
					},																				
				],				

				values_from_lfc: [
					{
						label: 'Period 1',
					},
					{
						label: 'Period 2',
					},																				
				]								
			}
		},

		mounted() {
			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)
		},

		computed : {
            headers() {
                let array = [
                    { text: 'Ledger Account Structure Code', value: 'ledger_account_structure_code' },
                    { text: 'Account Structure', value: 'ledger_account_structure_name' },
                    { text: 'Ledger Code', value: 'ledger_code' },
                    { text: 'Chart of accounts', value: 'ledger_chart_of_accounts' },
                    { text: 'Fiscal Calendar', value: 'ledger_fiscal_calendar' },
                    { text: 'Action', value: null },
                ];

                return array;
            },
		},

		methods: {
			fetchSuccess(data) {
				this.clients = data.clients ? data.clients : this.clients;
				this.item = data.item ? data.item : this.item;
				this.ledger_calendars = data.ledger_calendars ? data.ledger_calendars : this.ledger_calendars;					
				this.chart_of_accounts = data.chart_of_accounts ? data.chart_of_accounts : this.chart_of_accounts;


				setTimeout(()=>{
					this.loaded();
				}, 1000);
			},

			loaded() {
				this.data_loaded = true;
			},
		},

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
			'item.client_id'(val) {
				this.filtered_chart_of_accounts = this.chart_of_accounts.filter( item => item.client_id == val);
				this.filtered_ledger_calendars = this.ledger_calendars.filter( item => item.client_id == val);
			},
		},


	}
</script>