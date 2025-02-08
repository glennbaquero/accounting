<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Linked Main Account Information</template>
				<div class="row">
		    		<div class="form-group col-sm-6">
		    			<label>Linked Main Account Code</label>
		                <input type="text"  name="linked_main_account_code" v-model="item.linked_main_account_code" class="form-control" readonly>
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label id="code_coa">Chart of Accounts Code</label>
		                <input id="code_coa" name="chart_of_accounts_code" type="text" v-model="item.chart_of_accounts_code" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Chart of Accounts</label>
		    			<select name="chart_of_accounts_name" v-model="item.chart_of_accounts_name" class="form-control" id="coa">
		    			    <option v-for="from in values_from_coa" :value="from.coa_code">
		    			    {{ from.coa_title }}</option>
		    			</select>
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Main Account Code</label>
		                <input name="main_account_code" type="text" v-model="item.main_account_code" class="form-control">
		    		</div>		    		
		    		<div class="form-group col-sm-6">
		    			<label>Main Account</label>
		    			<select name="main_account" v-model="item.main_account" class="form-control">
		    			    <option v-for="from in values_from_ma" :value="from.label">{{ from.label }}</option>
		    			</select>
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Main account type</label>
		                <input name="main_account_type" type="text" v-model="item.main_account_type" class="form-control">
		    		</div>		    				    		
		    		<div class="form-group col-sm-6">
		    			<label>Main Account Category</label>
		    			<select name="main_account" v-model="item.main_account_category" class="form-control">
		    			    <option v-for="from in values_from_mac" :value="from.label">{{ from.label }}</option>
		    			</select>
		    		</div>

		    		<div class="form-group col-sm-6">
		    			<label>Description</label>
		                <input name="description" type="text" v-model="item.description" class="form-control">
		    		</div>

		    		<div class="form-group col-sm-6">
						<div class="custom-control custom-switch">
							<input
							v-model="item.linked_checkbox"
							name="linked_checkbox" :checked="item.linked_checkbox" type="checkbox" class="custom-control-input" id="linked_checkbox">
							<label class="custom-control-label" for="linked_checkbox">Linked</label>
						</div>
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
				item: {
					linked_main_account_code: null,
				},

				values_from_coa: [
					{
						coa_code: '101',
						coa_title: 'Cash',						
					},
					{
						coa_code: '120',
						coa_title: 'Accounts Recievable',						
					},
					{
						coa_code: '140',
						coa_title: 'Merchandise Inventory',						
					},										

				],

				values_from_ma: [
					{
						label: '',
					},
				],

				values_from_mac: [
					{
						label: '',
					},
				]								

			}
		},

		mounted() {
            let vm = this;
            $('#coa').change(function(e) {
            	
            	document.getElementById('code_coa').val = e.target.value;
            	alert(e.target.value);
            });		

			if(!this.item.linked_main_account_code) {
				var date = new Date();
				var time = Math.round(date.getTime() / 1000);	
				this.item.linked_main_account_code = date.getDate().toString() + (date.getMonth() + 1).toString() + date.getFullYear().toString() +'-'+ time.toString();
				this.item.linked_main_account_code += "-" + Math.random().toString(36).substring(2, 6);
			}			

		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
			},
		},

		mixins: [ CrudMixin ],
	}
</script>