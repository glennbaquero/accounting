<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#procurement" data-toggle="tab">Procurement Details</a></li>
					</ul>
				</div>							
				
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="procurement">
							<div class="card">
								<div class="card-header">
									<b>Procurement Overview</b>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="form-group col-sm-6">
											<label>Client</label>
											<v-select v-model="item.client_id" :options="clients" :reduce="client => client.id" label="name"/></v-select>
								    		<input type="hidden" name="client_id" :value="item.client_id">
										</div>
									</div>

									<div class="row">
										<div class="form-group col-sm-6">
											<label>Procurement #</label>
											<input name="procurement" v-model="item.procurement" type="text" class="form-control">
										</div>
									</div>
									<div class="row">
										
							    		<div class="form-group col-sm-6">
							    			<label>Main Account Code</label>
							    			<v-select v-model="item.main_account_code" :options="main_accounts" :reduce="main_account => main_account.main_account_id" label="main_account_name"/></v-select>
								    		<input type="hidden" name="main_account_code" :value="item.main_account_code">
							    		</div>
							    		<div class="form-group col-sm-6">
							    			<label>Main Account Name</label>
							    			<v-select v-model="item.main_account_name" :options="main_accounts" :reduce="main_account => main_account.main_account_name" label="main_account_name"/></v-select>
								    		<input type="hidden" name="main_account_name" :value="item.main_account_name">
							    		</div>

									</div>
								</div>
								<div class="card-footer text-right">
									<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
								</div>
							</div>
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
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {

		props : {
			showUrl : {
				default : false,
				type : String,
			},
			product : {
				default : {},
				type : Object,
			}
		},

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select' : Vselect
		},

		data() {
			return {
				item: {},
				main_accounts: [],
				clients: [],
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;

				if(!data.item) {
					this.generateCode('create','PRCRMNT');
				}
			},


			generateCode(value, prefix) {
				if(value == 'create') {
					var date = new Date();
					var time = Math.round(date.getTime() / 1000);	
					this.item.procurement = prefix + '-' + date.getFullYear().toString() + ("0" + (date.getMonth() + 1)).slice(-2) + date.getDate().toString()   +'-'+ time.toString();
				}
			},
		},

		mixins: [ CrudMixin ],
	}
</script>