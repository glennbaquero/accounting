<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Product Information</template>

				<div class="row mb-2">
					<div class="form-group col-md-12">
						<div class="custom-control custom-switch">
							<input
							v-model="item.is_ledger"
							name="is_ledger" :checked="item.is_ledger" type="checkbox" class="custom-control-input" id="is_ledger">
							<label class="custom-control-label" for="is_ledger">Ledger</label>
						</div>
					</div>
				</div>

				<div class="row">
		    		<div class="form-group col-sm-6">
		    			<label>Reason code</label>
		                <input name="reason_code" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Default comment</label>
		                <input name="default_comment" type="text" class="form-control">
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