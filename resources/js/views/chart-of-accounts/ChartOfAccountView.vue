<template>
	<div>
		<form-request :submit-url="submitUrl">
			<div class="card">
		        <div class="card-header">
			        <h3 class="card-title"><b>Chart of Account Information</b></h3>
			    </div>				
			    <div class="card-body">
					<div class="row">
			    		<div class="form-group col-sm-6">
			    			<label>Chart of Accounts ID</label>
			                <input type="text"  name="coa_id" v-model="item.coa_id" class="form-control" readonly>
			    		</div>
			    		<div class="form-group col-sm-6">
			    			<label>Chart of Accounts Code</label>
			                <input type="text"  name="coa_code" v-model="item.coa_code" class="form-control">
			    		</div>
			    		<div class="form-group col-sm-6">
			    			<label>Chart of accounts</label>
			                <input type="text"  name="coa_name" v-model="item.coa_name" class="form-control">
			    		</div>
						<div class="form-group col-md-6">
		    				<label>Client</label>		
							<v-select  v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
							<input hidden  v-model="item.client_id" name="client_id">
						</div>
						<text-editor
							v-model="item.description"
							class="form-group col-md-12"
							label="Description"
							name="description"
							row="5"
						></text-editor>						
					</div>
				</div>
				<div class="card-footer text-right">
					<action-button type="submit" :disabled="loading" class="btn-primary pull-right">Save Changes</action-button>
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
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {
		props: {
			coaId: String,
			submitUrl: String
		},

		components: {
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select' : Vselect
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
				clients : [],
			}
		},

		methods: {
			fetchSuccess(data) {
				// console.log(data, 'data');
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
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
		},		

		mixins: [ CrudMixin ]
	}
</script>