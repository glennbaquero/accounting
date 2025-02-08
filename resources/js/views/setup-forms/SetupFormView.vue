<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Setup Form Information</template>

				<div class="row">
		    		<div class="form-group col-sm-6">
		    			<label>Accounting structure</label>
		                <input name="accounting_structure" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Name</label>
		                <input name="name" type="text" class="form-control">
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