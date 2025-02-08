export default {
	methods: {
		showConfirm(event) {
			swal.fire({
			  title: this.dialog_title,
			  text: this.dialog_message,
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonText: this.okText,
			  cancelButtonText: this.cancelText
			}).then((result) => {
			  if (result.value) {
			    this.onDialogSuccess(event);
			  }
			})
		},

		onDialogSuccess(event, dialog) {

		},

		onDialogCancel(event) {

		},
	},

	computed: {
		dialog_title() {
			return this.title;
		},

		dialog_message() {
			return this.message;
		},
	},
}