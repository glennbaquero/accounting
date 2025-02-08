import { bus }from 'Root/bus.js';
import DateRange from 'Components/datepickers/DateRange.vue';

export default {
	props: {
		voucherValidateUrl: String,
		submitUrl: String,
		journalItem: Object,
		voucherSubmitUrl: String,
		statusUpdateUrl: String,
		journalValidateUrl: String,
		fetchVoucherUrl: String,
		fetchVoucherPendingUrl: String,
		fetchVoucherApprovedUrl: String,
		fetchVoucherDeniedUrl: String,
		fetchUrl: String,
		postUrl: String,
		generateAccrualUrl : String,
	},

	data() {
		return {
			statuses : [
				{'name' : 'Pending', value : 'pending' },
				{'name' : 'Approved', value : 'approved' },
				{'name' : 'Rejected', value : 'rejected' },
			],
			status : 'pending',
		}
	},

	computed: {
		hasVoucherSelected() {
			return !_.isEmpty(_.filter(this.voucher_lines, { 'alreadyInSelectedItem': true }));
		},

		
		filterColumns() {
		    let array = [
		        { text: 'Created At', value: 'created_at' },
		        { text: 'Voucher Date', value: 'voucher_date' },
		        { text: 'Posted On', value: 'posted_on' },
		        { text: 'Approved Date', value: 'approved_date' },
		        { text: 'Log Date', value: 'log_date' },
		    ];

		    return array;
		},

		ledger_rules_fields() {

			let array = [
			    { label: 'Normal Balance', value: this.selected.normal_balance, editable: false, model: 'normal_balance' },
			    { label: 'Increase Rule', value: this.selected.increase_rule, editable: false, model: 'increase_rule' },
			    { label: 'Decrease Rule', value: this.selected.decrease_rule, editable: false, model: 'decrease_rule' },
			];

			return array;
		},
	},

	
	components: {
	    'date-range' : DateRange,
	},

	methods: {

		updateVoucherStatus(status) {
			var lines = this.voucher_lines.filter(function (item) {
							return item.alreadyInSelectedItem === true;
						}, 'id');
			var ids = _.map(lines, 'id');

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
			    	axios.post(this.statusUpdateUrl, payloads)
			    		.then(response => {
			                this.$loading.show(false);
			                this.$emit('success');
			                this.parseSuccess(response.data.message, 'Status updated successfully!')
			                this.fetch();
			                this.selected = {};

							this.init();
							this.$refs['data-table'].selected = false;

			    		}).catch(error => {
			                this.$loading.show(false);
			    		})
			    }
			});

			
		},

		validateJournal() {
            this.$loading.show(true);
			axios.post(this.journalValidateUrl)
				.then(response => {
		            this.$loading.show(false);
		            this.parseSuccess(response.data.message, 'Validation success!')
				}).catch(error => {
		            this.$loading.show(false);
		            this.parseError(error, 'Validation error!')
				})
		},

		validateVoucher() {
            this.$loading.show(true);
			var lines = this.voucher_lines.filter(function (item) {
							return item.alreadyInSelectedItem === true;
						}, 'id');
			var ids = _.map(lines, 'id');

			var payloads = {
				ids: ids,
			}

	    	axios.post(this.voucherValidateUrl, payloads)
	    		.then(response => {
	                this.$loading.show(false);
	                this.fetch();
	                this.selected = {};
					this.$refs['data-table'].selected = false;
	                this.parseSuccess(response.data.message, 'Validation success!')
	    		}).catch(error => {
	                this.$loading.show(false);
	                this.fetch();
	                this.selected = {};
					this.$refs['data-table'].selected = false;
		            this.parseError(error, 'Validation error!')
	    		})
		},

		post() {
            this.$loading.show(true);
			axios.post(this.postUrl)
				.then(response => {
		            this.$loading.show(false);
		            this.parseSuccess(response.data.message, 'Posting success!')
	                this.fetch();
				}).catch(error => {
		            this.$loading.show(false);
		            this.parseError(error, 'Posting error!')
				})	
		},

		selectAll(selected) {
            this.$loading.show(true);
			_.map(this.$refs['data-table'].items, (line) => {
				line.alreadyInSelectedItem = selected;

				return line;
			});
			this.voucher_lines = this.$refs['data-table'].items;
            this.$loading.show(false);
		},

		updateVoucher() {
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

			        axios.post(this.selected.updateUrl, this.selected)
			            .then(response => {
			                var data = response.data;

			                this.$loading.show(false);
			                this.$emit('success');
			                this.parseSuccess(data.message, 'Successfully created!')
			                this.fetch();
			                this.selected = {};
			            }).catch(error => {
			                this.$loading.show(false);
			                this.parseError(error, null);
			            })
			    }
			})
		},

		selectedLine(item) {
			_.each(this.voucher_lines, (item) => {
				item.selected = false;	
			})
			item.selected = true;
			this.selected = item;
		},

		dataSelected(item) {
			item.alreadyInSelectedItem = !item.alreadyInSelectedItem;
			this.voucher_lines = this.$refs['data-table'].items;
		},

		openCreateVouchers() {
			bus.$emit('create-voucher');
			this.$nextTick(()=> {
				$('#new-voucher').modal('toggle');
			}, 500)
		},
	}
}