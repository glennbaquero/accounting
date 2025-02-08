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
		    		<div class="form-group col-sm-12">
		    			<p><input name="name" type="checkbox" class=""> This is a credit card</p>
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