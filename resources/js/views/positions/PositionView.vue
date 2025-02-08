<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Position Information</template>

				<div class="row">
					<div class="form-group col-sm-6">
						<label>Status</label>
						<select class="form-control" name="status" v-model="item.status">
							<option value="Active">Active</option>
							<option value="Inactive">Inactive</option>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label>Type</label>
						<select class="form-control" name="type" v-model="item.type">
							<option value="Full Time">Full Time</option>
							<option value="Part Time">Part Time</option>
						</select>
					</div>
				</div>
				
				<div class="row">
		    		<div class="form-group col-sm-6">
		    			<label>Code</label>
		                <input name="code" v-model="item.code"  type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Name</label>
		                <input name="name" v-model="item.name"  type="text" class="form-control">
		    		</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-3">
		    			<label>Company</label>
						<model-list-select :list="companies"
						:is-disabled="company ? true : false"
						v-model="item.company_id"
						option-value="id"
						option-text="name"
						placeholder="Select Company"
						class="form-control">
						</model-list-select>
						<input name="company_id" hidden v-model="item.company_id"> 
						<input v-if="company" name="designated_company" hidden :value="true"> 
		    		</div>

					<div class="form-group col-sm-3">
		    			<label>Department</label>
						<model-list-select :list="filtered_departments"
						v-model="item.department_id"
						option-value="id"
						option-text="name"
						placeholder="Select Department"
						class="form-control">
						</model-list-select>
						<input name="department_id" hidden v-model="item.department_id"> 
		    		</div>

		    		<div class="form-group col-sm-3">
		    			<label>Active from</label>
		    			<input ref="active_from" type="text" class="form-control" name="active_from" v-model="item.active_from" readonly>
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Active to</label>
		    			<input ref="active_to" type="text" class="form-control" name="active_to" v-model="item.active_to" readonly>
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
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import { ModelListSelect } from 'vue-search-select'

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {

		props : {
			company : {
				type : String,
				default : null,
			}
		},

		components: {
			Card,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton,
			ModelListSelect,
		},

		data() {
			return {
				item: {},
				departments: [],
				filtered_departments : [],
				companies: [],
			}
		},

		mounted() {
			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item
				this.departments = data.departments ? data.departments : this.departments
				this.companies = data.companies ? data.companies : this.companies

				if(this.company) {
					this.item.company_id = parseInt(this.company);
				}
			},
		},

		watch : {
			'item.company_id'(value) {
				this.filtered_departments = this.departments.filter(department => department.company_id == value);
			}
		},

		mixins: [ CrudMixin ],
	}
</script>