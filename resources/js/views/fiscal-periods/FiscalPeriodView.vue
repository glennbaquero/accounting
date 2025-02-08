<template>
 <div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Fiscal Calendar</b></h3>

			            <div class="card-tools">
<!-- 			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> -->
				        </div>
				    </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
				    			<label>Fiscal Calendar code </label>
				                <input type="hidden" name="fiscal_calendar_id" v-model="fcId.fiscal_calendar_id" class="form-control" readonly>				    			
				                <input type="text" name="fiscal_calendar_code" v-model="fcId.fiscal_calendar_code" class="form-control" readonly>		                
				    		</div>

							<div class="form-group col-md-6">
								<label>Client</label>		
								<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
								<input type="hidden" name="client_id" v-model="item.client_id"> 	
							</div>

				    		<div class="form-group col-sm-6">
								<label>Fiscal year Start date</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
				    				<input type="text" class="form-control calendar-form" name="fiscal_year_start_date" v-model="fcId.fiscal_year_start_date" readonly>
								</div>
							</div>

				    		<div class="form-group col-sm-6">					
								<label>Fiscal year End date</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
									<input type="text" class="form-control calendar-form" name="fiscal_year_end_date" v-model="fcId.fiscal_year_end_date" readonly>
								</div>
							</div>

				    		<div class="form-group col-sm-6">
				    			<label>Fiscal Period id </label>
				                <input type="text" name="fiscal_period_id" v-model="item.fiscal_period_id" class="form-control" readonly>
				    		</div>

				    		<div class="form-group col-sm-6">
				    			<label>Fiscal Period code </label>
				                <input type="text" name="fiscal_period_code" v-model="item.fiscal_period_code" class="form-control">
				    		</div>				    									

				    		<div class="form-group col-sm-6">
				    			<label>Fiscal Period name </label>
				                <input type="text" name="fiscal_period_name" v-model="item.fiscal_period_name" class="form-control">
				    		</div>

				    		<div class="form-group col-sm-6">
				    			<label>Period Type</label>
				    			<select name="fiscal_period_type" v-model="item.fiscal_period_type" class="form-control">
				    			    <option v-for="from in fiscal_period_type" :value="from.label">{{ from.label }}</option>
				    			</select>
				    		</div>		  

				    		<div class="form-group col-sm-6">
								<label>Period Start date</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
				    				<input ref="fiscal_period_start_date" type="text" class="form-control calendar-form" name="fiscal_period_start_date" v-model="item.fiscal_period_start_date" readonly>
								</div>
							</div>

				    		<div class="form-group col-sm-6">					
								<label>Period End date</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
									<input ref="fiscal_period_end_date" type="text" class="form-control calendar-form" name="fiscal_period_end_date" v-model="item.fiscal_period_end_date" readonly>
								</div>
							</div>				    		

				    		<div class="form-group col-sm-6">
				    			<label>Month</label>
				                <input type="text" name="fiscal_month" v-model="item.fiscal_month" class="form-control">
				    		</div>				    									

				    		<div class="form-group col-sm-6">
				    			<label>Quarter</label>
				                <input type="text" name="fiscal_quarter" v-model="item.fiscal_quarter" class="form-control">
				    		</div>				    													    		
				    		<div class="form-group col-sm-6">
				    			<label>Fiscal Period Status</label>
				    			<select name="fiscal_period_status" v-model="item.fiscal_period_status" class="form-control">
				    			    <option v-for="from in fiscal_period_status" :value="from.label">{{ from.label }}</option>
				    			</select>
				    		</div>		 


				    		<div class="form-group col-sm-12">
				    			<label>Comments</label>
				                <textarea type="text" name="comments" v-model="item.comments" class="form-control" />
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
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
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
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";


	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {
		props: {
			fcId: Object,	
			fpId: Number,	
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
			if (this.fpId) {
				item['fiscal_period_id'] = this.fpId; 
			}			

			return {
				item: item,
				created_by : null,
				updated_by : null,		
				fiscal_period_type: [
					{
						label: 'Opening',
					},
					{
						label: 'Operating',
					},																				
					{
						label: 'Closing',
					},																									
				],
				fiscal_period_status: [
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
				clients: [],												
			}
		},

		mounted() {
            let vm = this;

			flatpickr(this.$refs.fiscal_period_start_date)
			flatpickr(this.$refs.fiscal_period_end_date)			
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;			
			},

			buttonClick(params) {
				console.log(params)	
			}
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
			
		},				

	}
</script>