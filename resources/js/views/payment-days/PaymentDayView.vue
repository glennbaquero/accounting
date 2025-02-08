<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Term Information</template>

				<div class="row">
		    		<div class="form-group col-sm-4">
		    			<label>Payment day</label>
		                <input name="payment_day" v-model="item.payment_day" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Week/Month</label>
		    			<select class="form-control" name="week_month" v-model="item.week_month">
		    				<option value="Week">Week</option>
		    				<option value="Month">Month</option>
		    			</select>
		    		</div>
		    		<div class="form-group col-sm-4" v-if="item.week_month == 'Week'">
		    			<label>Day of week</label>
		    			<select class="form-control" name="day_of_week" v-model="item.day_of_week">
		    				<option value="Monday">Monday</option>
		    				<option value="Tuesday">Tuesday</option>
		    				<option value="Wednesday">Wednesday</option>
		    				<option value="Thursday">Thursday</option>
		    				<option value="Friday">Friday</option>
		    				<option value="Saturday">Saturday</option>
		    				<option value="Sunday">Sunday</option>
		    			</select>
		    		</div>
		    		<div class="form-group col-sm-4" v-if="item.week_month == 'Month'">
		    			<label>Day of month</label>
		                <input name="day_of_month" v-model="item.day_of_month" type="number" class="form-control">
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
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	export default {
		components: {
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton
		},

		data() {
			return {
				item: {}
			}
		},

		methods: {
			fetchSuccess(data) {
				console.log(data, 'data');
				this.item = data.item ? data.item : this.item;
			},
		},
		
		mixins: [ CrudMixin ],
	}
</script>