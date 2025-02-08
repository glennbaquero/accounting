<template>
	<div>
		<form-request :submit-url="submitUrl">
			<div class="card">
				<div class="card-header">	
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">User Details</a></li>
						<li v-if="company" class="nav-item"><a class="nav-link" :class="item.id ? '' : 'disabled'" href="#client" data-toggle="tab">User Clients</a></li>
						<li class="nav-item"><a class="nav-link" :class="item.id ? '' : 'disabled'" href="#permission" data-toggle="tab">User Permission</a></li>
					</ul>
				</div>

				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="details">
							<div class="card">
								<div class="card-header">
									<b>User Details</b>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="form-group col-sm-3">
											<span v-if="item.id" class="badge" :class="item.email_verified_at ? 'badge-success' : 'badge-danger'">
												{{ item.email_verified_at ? 'email verified' : 'email not verified'}}
											</span>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-sm-3">
											<label>Status</label>
											<select class="form-control" name="status" v-model="item.status">
												<option value="Active">Active</option>
												<option value="Inactive">Inactive</option>
											</select>
										</div>

										<div class="form-group col-sm-3">
											<label>Company</label>
											<model-list-select :list="companies"
											:is-disabled="company ? true : false"
											v-model="item.company_id"
											option-value="id" m
											option-text="name"
											placeholder="Select Company"
											class="form-control">
											</model-list-select>
											<input name="company_id" hidden v-model="item.company_id"> 
											<input v-if="company" name="designated_company" hidden :value="true"> 
										</div>

										<div class="form-group col-sm-3">
											<label>Department</label>
											<model-list-select :list="filtered_department"
											v-model="item.department_id"
											option-value="id"
											option-text="name"
											placeholder="Select Department"
											class="form-control">
											</model-list-select>
											<input name="department_id" hidden v-model="item.department_id"> 
										</div>

										<div class="form-group col-sm-3">
											<label>Position</label>
											<model-list-select :list="filtered_position"
											v-model="item.position_id"
											option-value="id"
											option-text="name"
											placeholder="Select Position"
											class="form-control">
											</model-list-select>
											<input name="position_id" hidden v-model="item.position_id"> 
										</div>

										<!-- <div class="form-group col-sm-4">
											<label>User ID</label>
											<input name="user_id" v-model="item.user_id" type="text" class="form-control">
										</div> -->
									</div>
									
									<div class="row">
										<div class="form-group col-sm-3">
											<label>First name</label>
											<input name="first_name" v-model="item.first_name" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-3">
											<label>Last name</label>
											<input name="last_name" v-model="item.last_name" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-3">
											<label>Middle name</label>
											<input name="middle_name" v-model="item.middle_name" type="text" class="form-control">
										</div>
										<div class="form-group col-sm-3">
											<label>Email</label>
											<input name="email" v-model="item.email" class="form-control">
										</div>

										<input name="user_id" hidden v-model="item.user_id"> 
										<div class="form-group col-sm-3">
											<label>Active from</label>
											<input ref="active_from" type="text" class="form-control" name="active_from" v-model="item.active_from" readonly>
										</div>
										<div class="form-group col-sm-3">
											<label>Active to</label>
											<input ref="active_to" type="text" class="form-control" name="active_to" v-model="item.active_to" readonly>
										</div>
									</div>
								</div>
								<div class="card-footer">															
									<action-button type="submit" :disabled="loading" class="btn-primary float-right">Save Changes</action-button>
								</div>
							</div>
						</div>

						<div class="tab-pane show" id="client">
							<div class="row">
								<div class="col-md-6">
									<div class="card">
										<div class="card-header">User Registered Client</div>
										<div class="card-body">
											<client-table
											ref="attach"
											@success="$refs.detach.fetch()"
											detach
											:user="item.id"
											:fetch-url="clientSelectedFetchUrl"
											>
											</client-table>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card">
										<div class="card-header">Available Client(s)</div>
										<div class="card-body">
											<client-table
											ref="detach"
										  	@success="$refs.attach.fetch()"
											attach
											:user="item.id"
											:fetch-url="clientFetchUrl"
											>
											</client-table>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="tab-pane show" id="permission">
							<user-permissions-view 
							:fetch-url="permissionsFetchUrl"
							:submit-url="permissionsSubmitUrl">
							</user-permissions-view>
						</div>
					</div>
				</div>
			</div>
		</form-request>

		<loader 
        :loading="loading">
        </loader>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
	import { ModelListSelect } from 'vue-search-select'
	import UserPermissionView from './UserPermissionView.vue';
	
	export default {

		mixins: [ CrudMixin ],

		props: {
			permissionsFetchUrl : {
                default : null,
                type : String,
            },
			permissionsSubmitUrl : {
                default : null,
                type : String,
            },
			clientSelectedFetchUrl : {
                default : null,
                type : String,
            },
			clientFetchUrl : {
                default : null,
                type : String,
            },
			company : {
				type : String,
				default : null,
			}
		},
		
		components: {
			'user-permissions-view' : UserPermissionView,
			ModelListSelect,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
		},

		data() {
			return {
				item: {},

				companies: [],
				departments: [],
				positions: [],

				filtered_department: [],
				filtered_position: [],

			}
		},

		watch: {
			'item.company_id'(value) {
				this.filtered_department = this.departments.filter(department => department.company_id == value);
			},

			'item.department_id'(value) {
				this.filtered_position = this.positions.filter(position => position.department_id == value);
			}
		},

		mounted() {
			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)
		},

		methods: {
			fetchSuccess(data) {
				this.companies = data.companies ? data.companies : this.companies;
				this.departments = data.departments ? data.departments : this.departments;
				this.positions = data.positions ? data.positions : this.positions;
				this.item = data.item ? data.item : this.item;

				if(this.company) {
					this.item.company_id = parseInt(this.company);
				}
			},

		}
	}
</script>