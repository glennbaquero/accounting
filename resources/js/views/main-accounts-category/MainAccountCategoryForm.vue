<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
			        <div class="card-header">
				        <h3 class="card-title"><b>Main Account Category Information</b></h3>
				    </div>				

				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
				    			<label>Reference ID</label>
				                <input name="main_account_category_reference" id="reference_id" type="number" 
								oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
				                maxlength="3" min = "1" max = "999" v-model="item.main_account_category_reference" class="form-control" />
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Main account category</label>
				                <input name="main_account_category" type="text" v-model="item.main_account_category" class="form-control">
				    		</div>
							<div class="form-group col-md-6">
			    				<label>Client</label>		
								<v-select  v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
								<input hidden  v-model="item.client_id" name="client_id">
							</div>				    		
				    		<div class="form-group col-sm-6">
				    			<label>Main account type</label>
				    			<select name="main_account_type" v-model="item.main_account_type" class="form-control">
				    			    <option v-for="from in values_from" :value="from.label">{{ from.label }}</option>
				    			</select>
				    		</div>
							<text-editor
								v-model="item.description"
								class="form-group col-md-12"
								label="Description"
								name="description"
								row="5"
							></text-editor>					    		
				    		<div class="form-group col-sm-12">
				    			<p><label style="margin-right: 0.5em;">Closed</label><input name="closed_checkbox" v-model="item.closed_checkbox" type="checkbox" class="" :true-value="1" :false-value="0"></p>
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
        :loading="true">
        </loader>		
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

	export default {

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select' : Vselect,

		},

		data() {
			return {
				item: [],
				created_by : null,
				updated_by : null,	
				clients : [],
				values_from: [
					{
						label: 'Profit and loss',
					},
					{
						label: 'Revenue',
					},
					{
						label: 'Expense',
					},
					{
						label: 'Balance sheet',
					},
					{
						label: 'Assets',
					},															
					{
						label: 'Liability',
					},															
					{
						label: 'Equity',
					},																									

				]
			}
		},

		mounted() {
            let vm = this;
	

		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;

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
		},				
	}
</script>