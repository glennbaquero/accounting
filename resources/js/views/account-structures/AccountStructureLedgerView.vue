<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>		
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Account Structure</b></h3>

			            <div class="card-tools">
<!-- 			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> -->
				        </div>
				    </div>
				    <div class="card-body">

						<div class="row">
				    		<div class="form-group col-sm-3">
				    			<label>Ledger Id</label>
				                <input name="ledger_id" v-model="ledgerId.ledger_id" class="form-control"readonly >
				            </div>
				    		<div class="form-group col-sm-3">
				    			<label>Ledger Code</label>
				                <input name="ledger_code" v-model="ledgerId.ledger_code" class="form-control"readonly >
				            </div>
				    		<div class="form-group col-sm-3">
				    			<label>Ledger Name</label>
				                <input name="ledger_name" v-model="ledgerId.ledger_name" class="form-control"readonly >
				            </div>
				    		<div class="form-group col-sm-3">
				    			<label>Company Name</label>
				                <input type="hidden" name="company_id" v-model="ledgerId.company_id" class="form-control">				    			
				                <input name="company_name" v-model="ledgerId.company_name" class="form-control" readonly>
				            </div>

				    		<div class="form-group col-sm-6">
				    			<label>Account Structure Id</label>
				                <input name="ledger_account_structure_id" v-model="item.ledger_account_structure_id" class="form-control"readonly >
				            </div>
				    		<div class="form-group col-sm-6">
				    			<label>Account Structure code number</label>
				                <input name="ledger_account_structure_code_number" type="text" v-model="item.ledger_account_structure_code_number" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Account Structure code</label>
				                <input name="ledger_account_structure_code" type="text" v-model="item.ledger_account_structure_code" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Account Structure name</label>
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
					    		<div class="form-group col-sm-12">
	    		    			<label>Created By</label>
									<input readonly v-model="created_by" type="text" class="form-control">
							</div>
					    		<div class="form-group col-sm-12">
	    		    			<label>Created On</label>
								<input readonly v-model="item.formatted_created_at" type="text" class="form-control">
							</div>
    		    		
					    		<div class="form-group col-sm-12">
	    		    			<label>Updated By</label>
								<input readonly v-model="updated_by" type="text" class="form-control">
							</div>    		    	
					    		<div class="form-group col-sm-12">
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

	export default {
		props: {
			asId: String,
			ledgerId: Object,
			submitUrl: String
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;

			},

		},

		data() {
			var item = {};
			if (this.asId) {
				item['ledger_account_structure_id'] = this.asId; 
			}			
			return {
				item: item,
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
		},

		mounted() {
            let vm = this;
			
			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)
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
<style>


</style>