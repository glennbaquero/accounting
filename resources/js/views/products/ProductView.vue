<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#product" data-toggle="tab">Product</a></li>
						<template v-if="item.id">
							<li class="nav-item"><a  @click="$refs['table-2'].fetch()" class="nav-link" href="#variants" data-toggle="tab">Variants</a></li>
						</template> 
					</ul>
				</div>							

				<!-- <div class="row mb-2">
					<div class="form-group col-md-12">
						<div class="custom-control custom-switch">
							<input
							v-model="item.is_available"
							name="is_available" :checked="item.is_available" type="checkbox" class="custom-control-input" id="is_available">
							<label class="custom-control-label" for="is_available">available</label>
						</div>
					</div>
				</div> -->
				
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="product">
							<div class="card">
								<div class="card-header">
									<b>Product Overview</b>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="form-group col-sm-4">
											<label>Product #</label>
											<input name="product_number" v-model="item.product_number" type="text" class="form-control">
										</div>

										<div class="form-group col-sm-4">
											<label>Client</label>
											<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
											<input type="hidden" name="client_id" v-model="item.client_id"> 			
										</div>
									</div>
									<div class="row">
										<div class="form-group col-sm-4">
											<label>Batch Number</label>
											<input name="batch_number" v-model="item.batch_number" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-4">
											<label>Serial Number</label>
											<input name="serial_number" v-model="item.serial_number" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-4">
											<label>Name</label>
											<input name="name" v-model="item.name" type="text" class="form-control">
										</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
								</div>
							</div>
						</div>
						<div class="tab-pane show" id="variants">
							<div class="card">
								<div class="card-header">
									<b>Variants</b>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="form-group col-sm-4">
											<a class="btn btn-primary" :href="item.createVariantUrl"><i class="fa fa-plus"></i> Create Variants</a>
										</div>
									</div>
									<variant-table
									 ref="table-2"
									:fetch-url="item.variantUrl"
									></variant-table>
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
				item: {},
				clients : [],
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
			},
		},

		mixins: [ CrudMixin ],
	}
</script>