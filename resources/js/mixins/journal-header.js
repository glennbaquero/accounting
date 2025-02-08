export default {
	methods: {

		updateJournalStatus(status) {
			var ids = _.map(_.filter(this.$refs['data-table'].items, (item) => {
						    return item.is_selected === true;
						}), 'id')

			var payloads = {
				selectedIds: ids,
				status: status
			}

			swal.fire({
			    title: 'Are you sure?',
			    text: 'Do you want to continue this process?',
			    icon: 'warning',
			    showCancelButton: true,
			    confirmButtonText: 'OK',
			    cancelButtonText: 'Cancel'
			}).then((result) => {
			    if (result.value) {
	                this.$loading.show(true);
			    	console.log(payloads);
			    	axios.post(this.statusUpdateUrl, payloads)
			    		.then(response => {
			                this.$loading.show(false);
			                this.$emit('success');
			                this.parseSuccess(response.data.message, 'Status updated successfully!')
			                this.fetch();
			                this.selected = {};

			    		}).catch(error => {
			                this.$loading.show(false);
			    		})
			    }
			});

			
		}
	}
}