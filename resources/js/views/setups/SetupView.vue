<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Setup Information</template>

				<div class="row">
					<div class="form-group col-sm-6">
						<label>Close</label>
			            <select name="close" class="form-control">
			            	<option></option>
			            </select>
					</div>
		    		<div class="form-group col-sm-6">
		    			<label>Posting profile</label>
		                <input name="posting_profile" type="text" class="form-control">
		    		</div>
				</div>

				<div class="row">
		    		<div class="form-group col-sm-4">
		    			<label>Account code</label>
			            <select name="account_code" v-model="item.account_code" class="form-control">
			            	<option value="All">All</option>
			            	<option value="Group">Group</option>
			            	<option value="Table">Table</option>
			            </select>
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Account/Group number</label>
			            <select name="group_number" v-model="item.group_number" class="form-control" :disabled="!activate.group_number">
			            	<option value="All">All</option>
			            </select>
		    		</div>
		    		<div class="form-group col-sm-4">
		    			<label>Settle account</label>
			            <select name="settle_account" v-model="item.settle_account" class="form-control">
			            	<option value="All">All</option>
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
					<button type="submit" class="btn btn-sm btn-primary">SAVE</button>
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
		props: {
			submitUrl: String
		},

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		},

		data() {
			return {
				item: {
					account_code: 'All',
				},
				activate: {
					group_number: false
				}
			}
		},

		watch: {
			/**
			 * @todo If Table is selected in the Account code field, select the account number of the customer who is associated with the posting profile. If Group is selected, select the customer group. If All is selected, leave this field blank.
			 */
			'item.account_code'(val) {
				switch(val) {
					case 'All':
						this.activate.group_number = false;
						break;
					case 'Group':
						this.activate.group_number = true;
						break;
					case 'Table':
						this.activate.group_number = true;
						break;
				}
			}
		},

		methods: {
			buttonClick(params) {
				console.log(params)	
			}
		}
	}
</script>