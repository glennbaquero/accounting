<template>
	<div>
		<a href="javascript:void(0)" class="btn btn-primary text-white" @click="modal">
            <i class="fa fa-plus"></i>
            Create
        </a>

        <div ref="modal" class="modal fade"  id="br-lines-modal" tabindex="-1" role="dialog" aria-labelledby="payment_cancellation_label" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="payment_cancellation_label">Create Payment Schedule Line</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <payment-schedule-line-view
                        	ref="form"
	                        :submit-url="submitUrl"
	                        :fetch-url="fetchUrl"
	                        :parent="parent"
	                        :clients="clients"
	                        @submit-success="submitSuccess"
                        ></payment-schedule-line-view>
                    </div>
                </div>
            </div>
        </div>

	</div>
</template>

<script>
	
	export default {
		methods: {
			modal(action = 'show') {
				this.$refs.form.fetch();
				$(this.$refs.modal).modal(action);
			},

			submitSuccess() {
				this.modal('hide');
			},
		},

		props: {
			submitUrl: String,
			fetchUrl: String,
			clients: {
				type: Array,
				default: () => [],
			},
			parent: {
				type: Object,
				default: () => {},
			},
		},
	}

</script>