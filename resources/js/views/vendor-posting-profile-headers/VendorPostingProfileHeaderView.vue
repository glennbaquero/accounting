<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">						
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#vendor_posting_profile_header" data-toggle="tab">Vendor Posting Profile Header</a></li>
						<li class="nav-item" v-show="item.id"><a class="nav-link" @click="initList('table-1')" href="#vendor_posting_profile_lines" data-toggle="tab">Vendor Posting Profile Lines</a></li>
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="vendor_posting_profile_header">
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Client</label>
									<v-select v-model="item.client_id" placeholder="Select Client" :options="clients" :reduce="client => client.id" label="name"></v-select>
									<input type="hidden" name="client_id" :value="item.client_id">
								</div>
								<div class="form-group col-sm-4">
									<label>Vendor Posting Profile</label>
									<input name="posting_profile" v-model="item.posting_profile" type="text" class="form-control">
								</div>
								<div class="form-group col-sm-4">
									<label>Document</label>
									<v-select v-model="item.document" placeholder="Select Document" :options="documents" :reduce="client => client.value" label="name"></v-select>
									<input type="hidden" name="document" :value="item.document">
								</div>
								<div class="form-group col-sm-12">
									<label>Description</label>
									<text-editor v-model="item.description" name="description"></text-editor>
								</div>
							</div>
						</div>
						<div class="tab-pane show" id="vendor_posting_profile_lines">
							<a class="btn btn-success mb-2" :href="createPostingLineUrl"><i class="fa fa-plus"></i> Create Posting Profile Line</a>
							<vendor-posting-profile-table
							ref="table-1"
							:fetch-url="postingLinesFetchUrl">
							</vendor-posting-profile-table>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</div>
			</div>
	
			<card>
				<template v-slot:header>Audit Trail</template>
				<div class="row">
					<div class="form-group col-sm-3">
		    			<label>Created By</label>
		                <input readonly v-model="item.created_by" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
		    			<label>Created On</label>
		                <input readonly v-model="item.created_on" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
		    			<label>Update By</label>
		                <input readonly v-model="item.updated_by" type="text" class="form-control">
		    		</div>
					<div class="form-group col-sm-3">
		    			<label>Update On</label>
		                <input readonly v-model="item.updated_on" type="text" class="form-control">
		    		</div>
		    	</div>
			</card>
			<loader :loading="loading"></loader>
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import SetupMixin from 'Mixins/setup.js'
	import Card from 'Components/containers/Card.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {
		components: {
			Card,
			'v-select' : Vselect,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
		},

		props : {
			createPostingLineUrl : String,
			postingLinesFetchUrl : String
		},

		data() {
			return {
				item: {
					account_code: 'Group',
					offset_account_type: 'Ledger'
				},
				clients: [],
				documents: [],

			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
				this.documents = data.documents ? data.documents : this.documents;
			},
		},

		mixins: [ CrudMixin, SetupMixin],
	}
</script>