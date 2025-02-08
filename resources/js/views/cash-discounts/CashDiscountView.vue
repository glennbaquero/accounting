<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Cash Discount Information</template>

				<div class="row">
		    		<div class="form-group col-sm-4">
		    			<label>Cash discount</label>
		                <input name="discount_cash" v-model="item.discount_cash" type="number" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Next discount code</label>
		                <input name="next_discount_code" v-model="item.next_discount_code" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Net/Current</label>
		                <select name="net_or_current" v-model="item.net_or_current" class="form-control">
		                	<option value="Net">Net</option>
		                	<option value="Current">Current</option>
		                </select> 
		    		</div>

		    		<div class="form-group col-sm-6">
		    			<label>Months</label>
		                <input name="months" v-model="item.months" type="number" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Days</label>
		                <input name="days" v-model="item.days" type="number" class="form-control">
		    		</div>

		    		<div class="form-group col-sm-6">
		    			<label>Discount percentage</label>
		                <input name="discount_percent" v-model="item.discount_percent" type="number" step="0.01" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Discount offset accounts</label>
		                <input name="discount_offset_accounts" v-model="item.discount_offset_accounts" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Main account for customer discounts</label>
						<model-list-select :list="customers"
							v-model="item.customer_account"
							option-value="id"
							option-text="display_name"
							placeholder="Select Customer"
							class="form-control">
						</model-list-select>
						<input name="customer_account" hidden v-model="item.customer_account"> 
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Main account for vendor discounts</label>
						<model-list-select :list="vendors"
							v-model="item.vendor_account"
							option-value="id"
							option-text="display_name"
							placeholder="Select Vendor"
							class="form-control">
						</model-list-select>
						<input name="vendor_account" hidden v-model="item.vendor_account">
		                <!-- <select name="vendor_account" v-model="item.vendor_account" class="form-control">
		                	<option v-for="vendor in vendors" :key="vendor.id" :value="vendor.id">{{ vendor.display_name }}</option>
		                </select>  -->
		    		</div>
				</div>

				<div class="row">
					<text-editor
					v-model="item.description"
					class="col-sm-12"
					label="Description"
					name="description"
					row="5"
					></text-editor>
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
	import { ModelListSelect } from 'vue-search-select'

	export default {
		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			ModelListSelect,
		},

		data() {
			return {
				item: {},
				vendors: [],
				customers: []
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.vendors = data.vendors ? data.vendors : this.vendors;
				this.customers = data.customers ? data.customers : this.customers;
			},
		},

		mixins: [ CrudMixin ],
	}
</script>