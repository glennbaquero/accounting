<template>
 <div>
	<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card card-default">
				<div class="card-header">
					<h3 class="card-title"><b>Fiscal Calendar</b></h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="form-group col-md-6">
							<label>Client</label>		
							<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
							<input type="hidden" name="client_id" v-model="item.client_id"> 	
						</div>
						
						<div class="form-group col-sm-6">
							<label>Fiscal Calendar ID </label>
							<input type="text" name="fiscal_calendar_id" v-model="item.fiscal_calendar_id" class="form-control" readonly>
						</div>

						<div class="form-group col-sm-6">
							<label>Fiscal Calendar Code </label>
							<input type="text" name="fiscal_calendar_code" v-model="item.fiscal_calendar_code" class="form-control" readonly>		                
						</div>

						<div class="form-group col-sm-6">
							<label>Fiscal Calendar Code Number</label>
							<input type="text" name="fiscal_calendar_code_number" v-model="item.fiscal_calendar_code_number" class="form-control" readonly>
						</div>

						<div class="form-group col-sm-6">
							<label>Fiscal Calendar Name</label>
							<input type="text" name="fiscal_calendar_name" v-model="item.fiscal_calendar_name" class="form-control">
						</div>

						<div class="form-group col-sm-6">
							<label>Length of Period</label>
							<input type="number" name="length_of_period" min="1" max="12" v-model="item.length_of_period" class="form-control">
						</div>	    		

						<div class="form-group col-sm-6">
							<label>Fiscal Year Start Date</label>
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
								<input ref="fiscal_year_start_date" type="text" class="form-control calendar-form" name="fiscal_year_start_date" v-model="item.fiscal_year_start_date" readonly>
							</div>
						</div>

						<div class="form-group col-sm-6">					
							<label>Fiscal year End date</label>
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
								<input ref="fiscal_year_end_date" type="text" class="form-control calendar-form" name="fiscal_year_end_date" v-model="item.fiscal_year_end_date" readonly>
							</div>
						</div>

						<div class="form-group col-sm-6">
							<label>Unit</label>
							<select name="unit" v-model="item.unit" class="form-control">
								<option v-for="from in units" :value="from.label">{{ from.label }}</option>
							</select>
						</div>		  

						<div class="form-group col-sm-6">
							<label>Fiscal Year Status</label>
							<select name="fiscal_year_status" v-model="item.fiscal_year_status" class="form-control">
								<option v-for="from in fiscal_year_status" :value="from.label">{{ from.label }}</option>
							</select>
						</div>		  				    		

						<div class="form-group col-sm-12">
							<label>Description</label>
							<textarea type="text" name="description" v-model="item.description" class="form-control" />
						</div>		    							
					</div>
				</div>
				<div class="card-footer text-right">
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
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
	
	<loader 
	:loading="loading">
	</loader>
 </div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';

	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	
	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {
		props: {
			fcId: Number,		
			submitUrl: String,
		},

		components: {				
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select' : Vselect,			
		},

		data() {
			var item = {};
			
			if (this.fcId) {
				item['fiscal_calendar_id'] = this.fcId; 
			}
			
			return {
				item: item,
				created_by : null,
				updated_by : null,		
				clients: [],				
				units: [
					{
						label: 'Months',
					},
					{
						label: 'Quarter',
					},																				
				],
				fiscal_year_status: [
					{
						label: 'Open',
					},
					{
						label: 'On hold',
					},																				
					{
						label: 'Closed',
					},												
				],												
			}
		},

		mounted() {
            let vm = this;

			flatpickr(this.$refs.fiscal_year_start_date);
			flatpickr(this.$refs.fiscal_year_end_date);

			if (this.fcId && !this.item.id) {
				this.item.fiscal_calendar_code_number = 'FC-' + this.fcId; 
			}
		},

		methods: {
			fetchSuccess(data) {
				this.clients = data.clients ? data.clients : this.clients;			
				this.item = data.item ? data.item : this.item;
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

			'item.unit'(val) {
				this.item.fiscal_calendar_code = 'FC-' + this.item.fiscal_calendar_id  + '-' + val;
			},

			'item.length_of_period'(val) {
				if(val < 1) {
					this.item.length_of_period = 1;
				}

				if(val > 12) {
					this.item.length_of_period = 12
				}
			}
									
		},				

	}
</script>