<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Term Information</template>

				<div class="row">
		    		<div class="form-group col-sm-12">
		    			<label>Name</label>
		                <input name="name" type="text" class="form-control">
		    		</div>
				</div>


				<div class="row">
					<text-editor
					v-model="item.description"
					class="col-sm-12"
					label="Description"
					name="Description"
					row="5"
					></text-editor>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>General</b></h3>

			            <div class="card-tools">
			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
				    		<div class="col-sm-6 row">
				    			<div class="form-group col-sm-3">
					    			<p>Journal Type</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<select class="form-control">
					    				<option v-for="type in types" :value="type.label">{{ type.label }}</option>
					    			</select>
				    			</div>
				    			
				    			<!-- Approvavl -->
				    			<div class="form-group col-sm-12">
					    			<label>Approval</label>
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>Active</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<input type="checkbox" >
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>Workflow</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<select class="form-control">
					    				<option value="sample">sample</option>
					    			</select>
				    			</div>

				    			<!-- Approval Workflow -->
				    			<div class="form-group col-sm-12">
					    			<label>Approval Workflow</label>
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>Approval Workflow</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<input type="checkbox" >
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>Workflow</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<select class="form-control">
					    				<option value="sample">sample</option>
					    			</select>
				    			</div>

				    			<!-- Blocking -->
				    			<div class="form-group col-sm-12">
					    			<label>Blocking</label>
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>Private user group</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<select class="form-control">
					    				<option value="sample">sample</option>
					    			</select>
				    			</div>

				    			<!-- Blocking -->
				    			<div class="form-group col-sm-12">
					    			<label>Voucher number</label>
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>Voucher series</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<select class="form-control">
					    				<option value="sample">sample</option>
					    			</select>
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>New voucher</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<select class="form-control">
					    				<option value="sample">sample</option>
					    			</select>
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>Number allocation at posting</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<input type="checkbox" >
				    			</div>
				    			<div class="form-group col-sm-3">
					    			<p>Document</p>
				    			</div>
				    			<div class="form-group col-sm-9">
					    			<input type="text" class="form-control">
				    			</div>

				    		</div>
				    		<div class="col-sm-6">
				    			<div class="row">
				    				<!-- Offset account proposal -->
					    			<div class="form-group col-sm-12">
						    			<label>Offset account proposal</label>
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Fixed Offset account</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<input type="checkbox" >
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Account type</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<select class="form-control">
						    				<option value="sample">sample</option>
						    			</select>
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Offset account</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<select class="form-control">
						    				<option value="sample">sample</option>
						    			</select>
					    			</div>

					    			<!-- Posting -->
					    			<div class="form-group col-sm-12">
						    			<label>Posting</label>
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Delete lines after posting</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<input type="checkbox" >
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Posting layer</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<select class="form-control">
						    				<option value="Current">Current</option>
						    			</select>
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Details Level</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<select class="form-control">
						    				<option value="Details">Details</option>
						    			</select>
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Fees posting</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<select class="form-control">
						    				<option value="Keep fees in journal">Keep fees in journal</option>
						    			</select>
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Lines limit</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<input type="number" name="" class="form-control">
					    			</div>
					    			
				    				<!-- Currency -->
					    			<div class="form-group col-sm-12">
						    			<label>Currency</label>
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Fixed rate</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<input type="checkbox" >
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Currency</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<select class="form-control">
						    				<option value="USD">USD</option>
						    				<option value="PHP">PHP</option>
						    			</select>
					    			</div>

				    				<!-- GST -->
					    			<div class="form-group col-sm-12">
						    			<label>GST</label>
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Amounts include GST</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<input type="checkbox" >
					    			</div>
					    			<div class="form-group col-sm-3">
						    			<p>Hide GST field in journal entry forms</p>
					    			</div>
					    			<div class="form-group col-sm-9">
						    			<input type="checkbox" >
					    			</div>
				    			</div>

			    				
				    		</div>
				    	</div>
				    </div>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Financial dimensions</b></h3>

			            <div class="card-tools">
			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
				    		<div class="form-group col-md-12">
				    			<label>Default financial dimensions</label>
				    		</div>
			    			<div class="form-group col-sm-12 text-nowrap">
			    				<label>Business Unit</label>
			    				<select name="business_unit" class="form-control w-25 d-inline">
			    					<option></option>
			    				</select>
			    				<input type="text" name="" value="No default" class="form-control w-25 d-inline" disabled>
			    				<select name="business_unit" class="form-control w-25 d-inline">
			    					<option>Not fixed</option>
			    				</select>
			    			</div>
			    			<div class="form-group col-sm-12 text-nowrap">
			    				<label>Cost center</label>
			    				<select name="cost_center" class="form-control w-25 d-inline">
			    					<option></option>
			    				</select>
			    				<input type="text" name="" value="No default" class="form-control w-25 d-inline" disabled>
			    				<select name="cost_center" class="form-control w-25 d-inline">
			    					<option>Not fixed</option>
			    				</select>
			    			</div>
			    			<div class="form-group col-sm-12 text-nowrap">
			    				<label>Department</label>
			    				<select name="cost_center" class="form-control w-25 d-inline">
			    					<option></option>
			    				</select>
			    				<input type="text" name="" value="No default" class="form-control w-25 d-inline" disabled>
			    				<select name="cost_center" class="form-control w-25 d-inline">
			    					<option>Not fixed</option>
			    				</select>
			    			</div>
			    			<div class="form-group col-sm-12 text-nowrap">
			    				<label>Item Group</label>
			    				<select name="item_group" class="form-control w-25 d-inline">
			    					<option></option>
			    				</select>
			    				<input type="text" name="" value="No default" class="form-control w-25 d-inline" disabled>
			    				<select name="item_group" class="form-control w-25 d-inline">
			    					<option>Not fixed</option>
			    				</select>
			    			</div>
			    			<div class="form-group col-sm-12 text-nowrap">
			    				<label>Project</label>
			    				<select name="project" class="form-control w-25 d-inline">
			    					<option></option>
			    				</select>
			    				<input type="text" name="" value="No default" class="form-control w-25 d-inline" disabled>
			    				<select name="project" class="form-control w-25 d-inline">
			    					<option>Not fixed</option>
			    				</select>
			    			</div>
				    	</div>
				    </div>
				</div>

				<template v-slot:footer>
					<button type="button" class="btn btn-sm btn-primary">SAVE</button>
				</template>
			</card>
			
		</form-request>
	</div>
</template>

<script>
	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';

	export default {
		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		},

		data() {
			return {
				item: {},

				types: [
					{
						label: 'General tax adjustment/benefit/incentive'
					},
					{
						label: 'Invoice register'
					},
					{
						label: 'Land tax'
					},
					{
						label: 'Payroll'
					},
					{
						label: 'Payroll disbursement'
					},
					{
						label: 'Periodic'
					},
					{
						label: 'Post fixed assets'
					},
					{
						label: 'Project - expenses'
					},
					{
						label: 'Salary transactions'
					},
					{
						label: 'Statistic transactions'
					},
					{
						label: 'Tax cost price'
					},
					{
						label: 'Tax differences'
					},
					{
						label: 'Transactions of FA budgets'
					},
					{
						label: 'Transport tax'
					},
					{
						label: 'Vendor bank remittance'
					},
					{
						label: 'Vendor disbursement'
					},
					{
						label: 'Vendor draw promissory note'
					},
					{
						label: 'Vendor invoice pool'
					},
					{
						label: 'Vendor invoice pool excl. posting'
					},
					{
						label: 'Vendor invoice recording'
					},
					{
						label: 'Vendor redraw promissory note'
					},
					{
						label: 'Vendor settle promissory note'
					},
				]
			}
		},

		methods: {
			buttonClick(params) {
				console.log(params)	
			}
		}
	}
</script>