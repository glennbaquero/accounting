<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Department Information</template>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Status</label>
						<select class="form-control" name="status" v-model="item.status">
							<option value="Active">Active</option>
							<option value="Inactive">Inactive</option>
						</select>
					</div>

					<div class="form-group col-sm-4">
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
				</div>
				
				<div class="row">
		    		<div class="form-group col-sm-4">
		    			<label>Code</label>
		                <input name="code" v-model="item.code"  type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Name</label>
		                <input name="name" v-model="item.name"  type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Head</label>
						<model-list-select :list="users"
						v-model="item.user_id"
						option-value="id"
						option-text="fullname"
						placeholder="Select User"
						class="form-control">
						</model-list-select>
		    		</div>

					<input name="user_id" hidden v-model="item.user_id"> 
				</div>

				<div class="row">
		    		<div class="form-group col-sm-6">
		    			<label>Active from</label>
		    			<input ref="active_from" type="text" class="form-control" name="active_from" v-model="item.active_from" readonly>
		    		</div>
		    		<div class="form-group col-sm-6">
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
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import { ModelListSelect } from 'vue-search-select'
	import 'vue-search-select/dist/VueSearchSelect.css';

	export default {

		mixins: [ CrudMixin ],

		props : {
			company : {
				type : String,
				default : false,
			}
		},

		components: {
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			 ModelListSelect,
		},

		data() {
			return {
				item: {},
				users : [],
				companies : [],
			}
		},
	
		mounted() {
			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)


		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.users = data.users ? data.users : this.users;
				this.companies = data.companies ? data.companies : this.companies;

				if(this.company) {
					this.item.company_id = parseInt(this.company);
				}
			},
		},
	}
</script>