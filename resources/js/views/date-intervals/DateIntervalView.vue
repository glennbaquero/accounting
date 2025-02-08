<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Product Information</template>

				<div class="row">
		    		<div class="form-group col-sm-4">
		    			<label>Date interval</label>
		                <input name="date-interval" type="text" class="form-control">
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
				    		<div class="col-md-6">
				    			<div class="form-group col-md-12">
				    				<h4>From date</h4>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Before date</label>
				    				<select class="form-control">
				    					<option></option>
				    				</select>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Start date</label>
				    				<select class="form-control">
				    					<option></option>
				    				</select>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>+/-</label>
				    				<input type="text" name="" class="form-control">
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Days, months, years or periods</label>
				    				<select class="form-control">
				    					<option value="Days">Days</option>
				    					<option value="Months">Months</option>
				    					<option value="Years">Years</option>
				    					<option value="Periods">Periods</option>
				    				</select>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Start/End</label>
				    				<select class="form-control">
				    					<option></option>
				    				</select>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Locked start</label>
				    				<datepicker format="M/dd/yyyy"  v-model="item.locked_start" input-class="form-control input-sm"></datepicker>
				    			</div>
				    		</div>
				    		<div class="col-md-6">
				    			<div class="form-group col-md-12">
				    				<h4>To date</h4>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Before date</label>
				    				<select class="form-control">
				    					<option></option>
				    				</select>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Start date</label>
				    				<select class="form-control">
				    					<option></option>
				    				</select>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>+/-</label>
				    				<input type="text" name="" class="form-control">
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Days, months, years or periods</label>
				    				<select class="form-control">
				    					<option value="Days">Days</option>
				    					<option value="Months">Months</option>
				    					<option value="Years">Years</option>
				    					<option value="Periods">Periods</option>
				    				</select>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Start/End</label>
				    				<select class="form-control">
				    					<option></option>
				    				</select>
				    			</div>
				    			<div class="form-group col-md-12">
				    				<label>Locked start</label>
				    				<datepicker format="M/dd/yyyy"  v-model="item.locked_start" input-class="form-control input-sm"></datepicker>
				    			</div>
				    		</div>
				    	</div>
				    </div>
				</div>

				<template v-slot:footer>
					<button type="submit" class="btn btn-sm btn-primary">SAVE</button>
				</template>
			</card>
			
		</form-request>
	</div>
</template>

<script>
	import Card from 'Components/containers/Card.vue';
	import ShowHideCard from 'Components/containers/ShowHideCard.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';

	export default {
		props: {
			submitUrl: String
		},

		components: {
			Card,
			ShowHideCard,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
		},

		data() {
			return {
				item: {}
			}
		},

		methods: {
			buttonClick(params) {
				console.log(params)	
			}
		}
	}
</script>