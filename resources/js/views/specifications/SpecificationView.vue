<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Product Specification Information</template>

				<div class="row">

					<div class="form-group col-sm-4">
						<label>Client</label>
	                    <v-select class="mb-2" 
	                        :disabled="clientId ? true : false"
	                        :reduce="item => item.id"
	                        v-model="item.client_id" 
	                        :options="clients"
	                        placeholder="Select Client"
	                        label="name">
	                    </v-select>
		                <input name="client_id" type="hidden" v-model="item.client_id" class="form-control">
					</div>

					<div class="form-group col-sm-3">
						<label>Variant</label>
						<v-select v-model="item.variant_id" :options="variants" :reduce="variant => variant.id" label="name"/></v-select>
			    		<input type="hidden" name="variant_id" :value="item.variant_id">
					</div>
				</div>
				<div class="row">
		    		<div class="form-group col-sm-4">
		    			<label>Product Specification</label>
		                <input name="product_specification" type="text" v-model="item.product_specification" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Specification name</label>
		                <input name="specification_name" type="text" v-model="item.specification_name" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Description</label>
		                <input name="description" type="text" v-model="item.description" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Construction</label>
		                <input name="construction" type="text" v-model="item.construction" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Fibre</label>
		                <input name="fibre" type="text" v-model="item.fibre" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Dye Method</label>
		                <input name="dye_method" type="text" v-model="item.dye_method" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Gauge</label>
		                <input name="gauge" type="text" v-model="item.gauge" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Size</label>
		                <input name="size" type="text" v-model="item.size" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Yarn</label>
		                <input name="yarn" type="text" v-model="item.yarn" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Average Density</label>
		                <input name="average_density" type="text" v-model="item.average_density" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Tufted Weight</label>
		                <input name="tufted_weight" type="text" v-model="item.tufted_weight" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Production Weight</label>
		                <input name="production_weight" type="text" v-model="item.production_weight" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Total Thickness</label>
		                <input name="total_thickness" type="text" v-model="item.total_thickness" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Secondary Backing</label>
		                <input name="secondary_backing" type="text" v-model="item.secondary_backing" class="form-control">
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
				clients: [],
				variants: [],
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
				this.variants = data.variants ? data.variants : this.variants;
			},
		},

		mixins: [ CrudMixin ],
	}
</script>