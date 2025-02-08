<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>		
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Fiscal Calendar Information</b></h3>
			            <div class="card-tools">
							<!-- <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> -->
				        </div>
				    </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
			    				<label>Client</label>		
								<v-select  v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
								<input hidden v-model="item.client_id" name="client_id">
			    			</div>
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
			    			</div>

				    		<div class="form-group col-sm-6">
				    			<label>Ledger Calendar Id</label>
				                <input name="ledger_calendar_id" v-model="item.ledger_calendar_id" class="form-control"readonly >
				            </div>
				    		<div class="form-group col-sm-6">
				    			<label>Ledger Calendar code number</label>
				                <input name="ledger_calendar_code_number" type="text" v-model="item.ledger_calendar_code_number" class="form-control">
				    		</div>				            
				    		<div class="form-group col-sm-6">
				    			<label>Ledger Calendar Code</label>
				                <input name="ledger_calendar_code" type="text" v-model="item.ledger_calendar_code" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Ledger Calendar name</label>
				                <input name="ledger_calendar_name" type="text" v-model="item.ledger_calendar_name" class="form-control">
				    		</div>

				    		<div class="form-group col-sm-6">
								<label>Ledger Calendar Year</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
				    				<input ref="ledger_calendar_year" type="text" class="form-control calendar-form" name="ledger_calendar_year" v-model="item.ledger_calendar_year" readonly>
								</div>
							</div>

							<text-editor
								v-model="item.description"
								class="col-sm-12"
								label="Description"
								name="description"
								row="5"
							></text-editor>



			    			<div class="form-group col-sm-6">
			    				<label>Fiscal Calendar Code</label>
									<model-list-select :list="filtered_fiscalcalendars"
									class="form-control"
									label=""		
									name="fiscal_calendar_code"					
									v-model="item.fiscal_calendar_code"
									option-value="fiscal_calendar_code"
									option-text="fiscal_calendar_name"
									placeholder="Please select a Fiscal Calendar Code"
									>
									</model-list-select>	
									<input type="hidden" name="fiscal_calendar_code" v-model="item.fiscal_calendar_code"> 
			    			</div>
			    			<div class="form-group col-sm-6"></div>

					  <!--  <div class="form-group col-sm-6">
				    			<label>Fiscal Calendar code</label>
				                <input name="fiscal_calendar_code" type="text" v-model="item.fiscal_calendar_code" class="form-control">
				    		</div> -->

				    		<div class="form-group col-sm-6">
								<label>Fiscal year Start date</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
				    				<input ref="fiscal_year_start_date" type="text" class="form-control calendar-form" name="fiscal_year_start_date" v-model="fiscalcalendar.fiscal_year_start_date" readonly>
								</div>
							</div>

				    		<div class="form-group col-sm-6">					
								<label>Fiscal year End date</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
									<input ref="fiscal_year_end_date" type="text" class="form-control calendar-form" name="fiscal_year_end_date" v-model="fiscalcalendar.fiscal_year_end_date" readonly>
								</div>
							</div>				    	
										
							<div class="row mb-2 ml-1">
								<div class="form-group col-md-12">
									<div class="custom-control custom-switch">
										<input
										v-model="item.ledger_calendar_status"
										name="ledger_calendar_status" :checked="item.ledger_calendar_status" type="checkbox" class="custom-control-input" id="ledger_calendar_status" :true-value="1" :false-value="0">
										<label class="custom-control-label" for="ledger_calendar_status">Ledger Calendar status</label>
									</div>
								</div>
							</div>							

						</div>
					</div>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Audit Trail</b></h3>

			            <div class="card-tools">
<!-- 			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> -->
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

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn btn-sm btn-primary">Save Changes</action-button>
				</template>		
			</card>

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
			ledgercalendarId: String,
			submitUrl: String
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.ledgers = data.ledgers ? data.ledgers : this.ledgers;			
				this.fiscalcalendars = data.fiscalcalendars ? data.fiscalcalendars : this.fiscalcalendars;				
				this.clients = data.clients ? data.clients : this.clients;
			},

		},

		data() {
			var item = {};
			if (this.ledgercalendarId) {
				item['ledger_calendar_id'] = this.ledgercalendarId; 
			}			
			return {
				item: item,
				ledger: {},								
				ledgers: [],
				fiscalcalendar: {},
				fiscalcalendars: [],
				created_by : null,
				updated_by : null,				
				main_account_categories: [],
				clients : [],

			}
		},

		components: {
			ModelListSelect,			
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton,
			'v-select' : Vselect			
		},

		mounted() {
            let vm = this;
			flatpickr(this.$refs.ledger_calendar_year)
			flatpickr(this.$refs.fiscal_year_start_date)
			flatpickr(this.$refs.fiscal_year_end_date)
		},

		mixins: [ CrudMixin ],

		computed: {
			filtered_ledgers() {
				var client_id = this.item.client_id;
				return this.ledgers.filter((ledger) => parseInt(ledger.client_id) == client_id);
			},

			filtered_fiscalcalendars() {
				var client_id = this.item.client_id;
				return this.fiscalcalendars.filter((fiscalcalendar) => parseInt(fiscalcalendar.client_id) == client_id);
			}
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

			'item.ledger_id'(val) {
				this.ledger = this.ledgers.filter(ledger => ledger.ledger_id == val)[0] ? this.ledgers.filter(ledger => ledger.ledger_id == val)[0] : "";
			},

			'item.fiscal_calendar_code'(val) {
				this.fiscalcalendar = this.fiscalcalendars.filter(fiscalcalendar => fiscalcalendar.fiscal_calendar_code == val)[0] ? this.fiscalcalendars.filter(fiscalcalendar => fiscalcalendar.fiscal_calendar_code == val)[0] : "";
			},			

			
		},		

	}
</script>
<style>


</style>