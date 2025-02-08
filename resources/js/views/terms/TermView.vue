<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Term Information</template>

				<div class="row">
		    		<!-- <div class="form-group col-sm-12">
		    			<label><input name="cash_payment_checkbox" v-model="item.cash_payment_checkbox" type="checkbox"> Cash payment</label>
		    		</div> -->
		    		<div class="form-group col-sm-6">
		    			<label>Terms of payment</label>
		                <input name="terms_of_payment" v-model="item.terms_of_payment" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Payment method</label>
		                <select name="payment_method_id" v-model="item.payment_method_id" class="form-control">
		                	<option v-for="method in payment_methods" :value="method.id">{{ method.method_of_payment }}</option>
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
		    			<label>Payment schedule</label>
		                <select name="payment_schedule" v-model="item.payment_schedule" class="form-control">
		                	<option v-for="schedules in payment_schedules" :value="schedules.id">{{ schedules.payment_schedule_name }}</option>
		                </select> 
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Payment day</label>
		                <select name="payment_day" v-model="item.payment_day" class="form-control">
		                	<option v-for="day in payment_days" :value="day.id">{{ day.payment_day }}</option>
		                </select> 
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Cutoff day</label>
		                <input name="cutoff_day" v-model="item.cutoff_day" type="number" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Ledger posting profile</label>
		                <select name="ledger_posting_profile" v-model="item.ledger_posting_profile" class="form-control">
		                	<option v-for="ledger_posting_profile in ledger_postings" :value="ledger_posting_profile.id">{{ ledger_posting_profile.posting_profile }}</option>
		                </select> 
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

	export default {
		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
		},

		data() {
			return {
				item: {},

				ledger_postings: [],
				payment_methods: [],
				payment_days: [],
				payment_schedules: [],
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
				this.payment_days = data.payment_days ? data.payment_days : this.payment_days;
				this.ledger_postings = data.ledger_postings ? data.ledger_postings : this.ledger_postings;
				this.payment_schedules = data.payment_schedules ? data.payment_schedules : this.payment_schedules;
			},
		},

		mixins: [ CrudMixin ],
	}
</script>