<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Service Task Overview</template>
				
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Service #</label>
						<input readonly class="form-control" :value="service.service_number">
						<input type="hidden" class="form-control" :value="service.id" name="service_id">
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Service</label>
						<input name="service" class="form-control" v-model="item.service">
					</div>
					<div class="form-group col-sm-4">
						<label>Service Task</label>
						<input name="service_task" class="form-control" v-model="item.service_task">
					</div>
					<div class="form-group col-sm-4">
						<label>RPM Method</label>
						<select class="form-control" name="rpm_method" v-model="item.rpm_method">
							<option value="Repair">Repair</option>
							<option value="Preventive">Preventive</option>
							<option value="Modify">Modify</option>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Service Responsible</label>
						<input name="service_responsible" class="form-control" v-model="item.service_responsible">
					</div>
					<div class="form-group col-sm-4">
						<label>Period</label>
						<select class="form-control" name="period" v-model="item.period">
							<option value="Year">Year</option>
							<option value="Months">Months</option>
							<option value="Days">Days</option>
							<option value="Hours">Hours</option>
						</select>
					</div>
					<div class="form-group col-sm-4">
						<label>Base Hour</label>
						<input type="number" min="0" step="any" name="base_hour" class="form-control" v-model="item.base_hour">
					</div>
					<div class="form-group col-sm-4">
						<label>Unit Price</label>
						<input type="number" min="0" step="any" name="unit_price" class="form-control" v-model="item.unit_price">
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
import Vselect from 'vue-select';

export default {

	components: {
		'text-editor': TextEditor,
		'form-request': FormRequest,
		'action-button': ActionButton,
		'v-select' : Vselect
	},

	props : {
		service: Object
	},

	data() {
		return {
			item: {
				base_hour: 0,
				unit_price: 0
			},
		}
	},

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;

			if(!this.item.id) {
				var date = new Date();
				var time = (Math.random() + Math.random()).toString(36).substr(2, 7);
				this.item.service_task = 'SRVCTSK-' + ("0" + (date.getMonth() + 1)).slice(-2) + date.getFullYear().toString() + '-' + time;
			}
		},
	},

	mixins: [ CrudMixin ],
}
</script>