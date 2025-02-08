<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Term Information</template>

				<div class="row">
		    		<div class="form-group col-sm-12">
		    			<label>Name</label>
		                <input name="name" type="text" v-model="item.name" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-12">
		    			<p><input name="is_credit_card" v-model="item.is_credit_card" type="checkbox" class=""> This is a credit card</p>
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
				item: {}
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