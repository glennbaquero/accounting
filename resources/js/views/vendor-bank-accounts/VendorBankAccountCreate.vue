<template>
	<div class="mb-4">
		<button class="btn btn-primary text-white" data-toggle="modal" data-target="#modal-form">
			<i class="fa fa-plus"></i>
			Create
		</button >

		<div class="modal fade" tabindex="-1" role="dialog" id="modal-form">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Select a vendor first</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group col-sm-12">
							<label>Vendors</label>			    				
							<v-select :options="vendors" :reduce="vendor => vendor.createUrl" v-model="createUrl" label="fullname" class="vue-select">
							<template #option="{ fullname, vendor_account }">
								<b>Name</b> : {{ fullname }} - <b>Vendor Account</b> : {{ vendor_account }}
							</template>
							</v-select>
						
							<input type="hidden" name="chart_of_account_id" v-model="createUrl">
		    			</div>
					</div>
					<div class="modal-footer">
						<a :href="createUrl" class="btn btn-success" :class="{'disabled':!createUrl}">Create</a>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>

<script>

	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";
	
	export default {

		components: {
			'v-select' : Vselect,
		},

		methods: {
			create(e) {
				if(!this.createUrl)
					return;

				window.location.href = this.createUrl;
			},
		},

		data() {
			return {
				createUrl: null,
			}
		},

		props: {
			vendors: {
				type: Array,
				default: () => [],
			},
		},
	}

</script>