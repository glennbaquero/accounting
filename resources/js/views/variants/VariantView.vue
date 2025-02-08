<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<!-- <div class="card"> -->
				<!-- <div class="card-header">
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#product" data-toggle="tab">Variant Details</a></li>
						<li class="nav-item"><a class="nav-link" href="#variants" data-toggle="tab">Parent Product Details</a></li>
					</ul>
				</div>	 -->						
				
				<!-- <div class="card-body"> -->
					<!-- <div class="tab-content"> -->
						<!-- <div class="tab-pane show active" id="product"> -->
							<div class="card">
								<div class="card-header">
									<b>Variant Overview</b>
								</div>
								<div class="card-body">
									<div class="row mb-2">
										<div class="form-group col-md-12">
											<div class="custom-control custom-switch">
												<input
												v-model="item.is_available"
												name="is_available" :checked="item.is_available" type="checkbox" class="custom-control-input" id="is_available">
												<label class="custom-control-label" for="is_available">Available</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-sm-3">
											<label>Variant #</label>
											<input name="variant_number" v-model="item.variant_number" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-3">
											<label>SKU <small>(optional)</small></label>
											<input name="sku" v-model="item.sku" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-3">
											<label>Batch Number <small>(optional)</small></label>
											<input name="batch_number" v-model="item.batch_number" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-3">
											<label>Serial Number <small>(optional)</small></label>
											<input name="serial_number" v-model="item.serial_number" type="text" class="form-control">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-sm-6">
											<label>Name</label>
											<input name="name" v-model="item.name" type="text" class="form-control">
										</div>

										<div class="form-group col-sm-6">
											<label>Unit of Measurement</label>
											<input name="unit_of_measurement" v-model="item.unit_of_measurement" type="text" class="form-control">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-sm-6">
											<label>Size</label>
											<input name="size" v-model="item.size" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-6">
											<label>Color</label>
											<input name="color" v-model="item.color" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-6">
											<label>Unit Price</label>
											<input name="unit_price" v-model="item.unit_price" type="number" step="any" class="form-control">
										</div>

										<div class="form-group col-sm-6">
											<label>Procurement</label>
											<v-select v-model="item.procurement_id" :options="procurements" :reduce="procurement => procurement.id" label="procurement"/></v-select>
								    		<input type="hidden" name="procurement_id" :value="item.procurement_id">
										</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
								</div>
							</div>
						<!-- </div> -->
						<!-- <div class="tab-pane show" id="variants">
							<div class="card">
								<div class="card-header">
									<b>Parent Product Overview</b>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="form-group col-sm-4">
											<label>Name</label>
											<input readonly  v-model="product.name" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-4">
											<label>Product #</label>
											<input readonly  v-model="product.product_number" type="text" class="form-control">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-sm-4">
											<label>Batch Number</label>
											<input readonly v-model="product.batch_number" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-4">
											<label>Serial Number</label>
											<input readonly v-model="product.serial_number" type="text" class="form-control">
										</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<a class="btn btn-primary" :href="showUrl" target="_blank"><i class="fas fa-external-link-alt"></i> Go to Parent Product</a>
								</div>
							</div>
						</div> -->
					<!-- </div> -->
				<!-- </div> -->
			<!-- </div> -->
			
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
				procurements: []
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.procurements = data.procurements ? data.procurements : this.procurements;
			},
		},

		mixins: [ CrudMixin ],
	}
</script>