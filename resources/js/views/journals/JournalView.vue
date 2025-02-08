<template>
	<div>
		<!-- <form-request :submit-url="submitUrl"> -->
			<card>
				<template v-slot:header>Journal Information</template>

				<div class="row">
					<div class="col-md-3 row">
						<div class="col-md-12">
							<label>Balance</label>
						</div>
						<div class="col-md-12">
							Journal <input type="text" name="" :value="balanceJournal" class="form-control" disabled>
						</div>
						<div class="col-md-12">
							Per voucher <input type="text" name="" :value="balancePerVoucher" class="form-control" disabled>
						</div>
					</div>
					<div class="col-md-3 row">
						<div class="col-md-12">
							<label>Total debit</label>
						</div>
						<div class="col-md-12">
							Journal <input type="text" name="" value="0.00"  :value="debitJournal" class="form-control" disabled>
						</div>
						<div class="col-md-12">
							Per voucher <input type="text" name="" value="0.00" :value="debitPerVoucher" class="form-control" disabled>
						</div>
					</div>
					<div class="col-md-3 row">
						<div class="col-md-12">
							<label>Total credit</label>
						</div>
						<div class="col-md-12">
							Journal <input type="text" name="" value="0.00" :value="creditJournal" class="form-control" disabled>
						</div>
						<div class="col-md-12">
							Per voucher <input type="text" name="" value="0.00" :value="creditPerVoucher" class="form-control" disabled>
						</div>
					</div>

				</div>
				<div class="card mt-4">
				    <div class="card-header p-2">
				        <ul class="nav nav-pills">
				            <li class="nav-item"><a class="nav-link active" href="#overview" data-toggle="tab">Overview</a></li>
				            <li class="nav-item"><a class="nav-link" href="#general" data-toggle="tab">General</a></li>
				            <li class="nav-item"><a class="nav-link" href="#cash_discount" data-toggle="tab">Cash discount</a></li>
				            <li class="nav-item"><a class="nav-link" href="#invoice" data-toggle="tab">Invoice</a></li>
				            <li class="nav-item"><a class="nav-link" href="#history" data-toggle="tab">History</a></li>
				        </ul>
				    </div>
				    <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="overview">
				        		<div class="row">
						    		<div class="col-sm-12">
						    			<div class="table-responsive">
						    			    <table id="journalTable" class="dataTable table table-striped text-center" style="white-space: nowrap">
						    			    	<tr>
						    			    		<th v-for="header in headers">{{ header.text }}</th>
						    			    	</tr>
				                                <tr v-for="item in items" @click="selectedLine(item)" :class="item.selected ? 'selected-table' : ''">
				                                    <td>
				                                    	<input type="checkbox" name="" @change="dataSelected(item)">
				                                    </td>
				                                    <td @dblclick="showOnlyData.account_type = false">
				                                    	<template v-if="showOnlyData.account_type">
				                                    		{{ item.account_type }}
				                                    	</template>
				                                    	<template v-else>
				                                    		<select class="form-control" v-model="item.account_type" name="account_type" @change="showOnlyData.account_type = true">
				                                    		    <option value="Ledger">Ledger</option>
				                                    		    <option value="Vendor">Vendor</option>
				                                    		    <option value="Customer">Customer</option>
				                                    		    <option value="Vendor">Vendor</option>
				                                    		    <option value="Project">Project</option>
				                                    		    <option value="Fixed Assets">Fixed Assets</option>
				                                    		    <option value="Bank">Bank</option>
				                                    		</select>
				                                    	</template>
				                                    	
				                                    </td>
				                                    <td>{{ item.account }}</td>
				                                    <td>{{ item.invoice }}</td>
				                                    <td @dblclick="showOnlyData.description = false">
				                                    	<template v-if="showOnlyData.description">
					                                    	{{ item.description }}
					                                    </template>
					                                    <template v-else>
					                                    	<textarea name="description" @keyup="onEnter($event, 'description')" class="form-control" v-model="item.description">{{ item.description }}</textarea>
					                                    	<!-- <input type="number" name="debit" v-model="item.debit" class="form-control" @keyup="onEnter($event, 'debit')"> -->
					                                    </template>
				                                    	
				                                    </td>
				                                    <td @dblclick="showOnlyData.debit = false">
				                                    	<template v-if="showOnlyData.debit">
					                                    	{{ item.debit }}
					                                    </template>
					                                    <template v-else>
					                                    	<input type="number" name="debit" v-model="item.debit" class="form-control" @keyup="onEnter($event, 'debit')">
					                                    </template>
				                                    </td>
				                                    <td @dblclick="showOnlyData.credit = false">
				                                    	<template v-if="showOnlyData.credit">
					                                    	{{ item.credit }}
					                                    </template>
					                                    <template v-else>
					                                    	<input type="number" name="credit" v-model="item.credit" class="form-control" @keyup="onEnter($event, 'credit')">
					                                    </template>
				                                    </td>
				                                    <td @dblclick="showOnlyData.offset_account_type = false">
				                                    	<template v-if="showOnlyData.offset_account_type">
				                                    		{{ item.offset_account_type }}
				                                    	</template>
				                                    	<template v-else>
				                                    		<select class="form-control" v-model="item.offset_account_type" name="offset_account_type" @change="showOnlyData.offset_account_type = true">
				                                    		    <option value="Ledger">Ledger</option>
				                                    		    <option value="Ledger">Ledger</option>
				                                    		    <option value="Ledger">Ledger</option>
				                                    		</select>
				                                    	</template>
				                                    </td>
				                                    <td @dblclick="showOnlyData.offset_account = false">
				                                    	<template v-if="showOnlyData.offset_account">
					                                    	{{ item.offset_account }}
					                                    </template>
					                                    <template v-else>
					                                    	<input type="text" name="offset_account" v-model="item.offset_account" class="form-control" @keyup="onEnter($event, 'offset_account')">
					                                    </template>
				                                    </td>
				                                    <td>
				                                        <button type="button" class="btn btn-flat btn-sm">
				                                            <i class="fas fa-trash"></i>
				                                        </button>
				                                    </td>
				                                </tr>
				                        	</table>
					                    </div>
						    		</div>
						    		<div class="col-sm-4 row mt-4">
						    			<div class="col-sm-12">
						    				<label>Invoice</label>
						    			</div>
						    			<div class="col-sm-12">
						    				Terms of payment: 

						    				<select class="form-control input-sm" v-model="selected.invoice_data.terms_of_payment">
						    					<option>Net 30</option>
						    				</select>
						    			</div>
						    			<div class="col-sm-12 mt-2">
						    				Due Date: <datepicker format="M/dd/yyyy"  v-model="selected.invoice_data.due_date" input-class="form-control input-sm"></datepicker>
						    			</div>
						    			<div class="col-sm-12 mt-2">
						    				Payment ID: <input type="text" v-model="selected.invoice_data.payment_id" class="form-control input-sm">
						    			</div>
						    			<div class="col-sm-12 mt-2">
						    				Tax exempt number: 
						    				<select class="form-control input-sm" v-model="selected.invoice_data.tax_exempt_number">
						    					<option>sample</option>
						    				</select>
						    			</div>
						    		</div>
						    		<div class="col-sm-4 row mt-4">
						    			<div class="col-sm-12">
						    				<label>Sales tax</label>
						    			</div>
						    			<div class="col-sm-12">
						    				Sales tax group
						    				<select class="form-control input-sm" v-model="selected.sales_tax.sales_tax_group">
						    					<option></option>
						    					<option>sample</option>
						    				</select>
						    			</div>
						    			<div class="col-sm-12">
						    				Item sales tax group
						    				<select class="form-control input-sm" v-model="selected.sales_tax.item_sales_tax_group">
						    					<option></option>
						    					<option>sample</option>
						    				</select>
						    			</div>
						    			<div class="col-sm-12">
						    				Calculated sales tax amount
						    				<input class="form-control input-sm" value="0.00" disabled v-model="selected.sales_tax.calculated_sales_tax_amount">
						    			</div>
						    			<div class="col-sm-12">
						    				Actual sales tax amount
						    				<input class="form-control input-sm" value="0.00" disabled v-model="selected.sales_tax.actual_sales_tax_amount">
						    			</div>
						    		</div>
						    		<div class="col-sm-4 row mt-4">
						    			<div class="col-sm-12">
						    				<label>Document</label>
						    			</div>
						    			<div class="col-sm-12 mt-3">
						    				Document
						    				<input type="text" class="form-control input-sm" v-model="selected.document.document">
						    			</div>
						    			<div class="col-sm-12 mt-3">
						    				Document Date
						    				<datepicker format="M/dd/yyyy" input-class="form-control input-sm" v-model="selected.document.document_date"></datepicker>
						    			</div>
						    			<div class="col-sm-12 mt-4">
						    				<label>Account name</label>
						    			</div>
						    			<div class="col-sm-12">
						    				Account name
						    				<input type="text" class="form-control input-sm" disabled v-model="selected.account_name.account_name">
						    			</div>
						    			<div class="col-sm-12">
						    				Offset Account name
						    				<input type="text" class="form-control input-sm" disabled v-model="selected.account_name.offset_account_name">
						    			</div>
						    		</div>
								</div>
								<div class="row">
									<div class="col-sm-4 row mt-4">
									</div>
									<div class="col-sm-4 row mt-4">
										<div class="col-sm-12">
											<label>Cash discount</label>
										</div>
										<div class="col-sm-12 mt-2">
											Cash discount
											<select class="form-control input-sm" v-model="selected.cash_discount.cash_discount">
												<option></option>
												<option>sample</option>
											</select>
										</div>
										<div class="col-sm-12">
											Cash discount date
											<datepicker format="M/dd/yyyy" input-class="form-control input-sm" v-model="selected.cash_discount.cash_discount_date"></datepicker>
										</div>
									</div>
								</div>
				        	</div>
				        </div>
				    </div>
				</div>
				<template v-slot:footer>
					<button type="button" class="btn btn-sm btn-primary">SAVE</button>
				</template>
			</card>
			
		<!-- </form-request> -->
	</div>
</template>

<script>

	import Selectize from 'vue2-selectize';
	import selectizecss from 'selectize/dist/css/selectize.css';

	import Datepicker from 'vuejs-datepicker';
    import DataTable from 'Components/tables/DataTable.vue';

	import Card from 'Components/containers/Card.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';

	export default {
		props: {
			submitUrl: String
		},

        computed: {
            headers() {
                let array = [
                    { text: '', value: '' },
                    { text: 'Account type', value: null },
                    { text: 'Account', value: null },
                    { text: 'Invoice', value: null },
                    { text: 'Description', value: null },
                    { text: 'Debit', value: null },
                    { text: 'Credit', value: null },
                    { text: 'Offset account type', value: null },
                    { text: 'Offset account', value: null },
                    { text: 'Action', value: null },
                ];

                return array;
            },

            balanceJournal() {
            	var credit = 0;
            	var debit = 0;

            	credit = _.sumBy(this.items, (item) => {
            		return parseFloat(item.credit);
            	});

            	debit = _.sumBy(this.items, (item) => {
            		return parseFloat(item.debit);
            	});

            	return parseFloat(credit - debit);
            },

            balancePerVoucher() {
            	var credit = 0;
            	var debit = 0;

            	credit = _.sumBy(this.items, (item) => {
            		if(item.alreadyInSelectedItem) {
	            		return parseFloat(item.credit);
            		} else {
            			return 0;
            		}
            	});

            	debit = _.sumBy(this.items, (item) => {
            		if(item.alreadyInSelectedItem) {
	            		return parseFloat(item.debit);
            		} else {
            			return 0;
            		}
            	});

            	return parseFloat(credit - debit);
            },

            debitJournal() {
            	return _.sumBy(this.items, (item) => {
            		return parseFloat(item.debit);
            	});
            },

            debitPerVoucher() {
            	return _.sumBy(this.items, (item) => {
            		if(item.alreadyInSelectedItem) {
	            		return parseFloat(item.debit);
            		} else {
            			return 0;
            		}
            	});
            },


            creditJournal() {
            	return _.sumBy(this.items, (item) => {
            		return parseFloat(item.credit);
            	});
            },

            creditPerVoucher() {
            	return _.sumBy(this.items, (item) => {
            		if(item.alreadyInSelectedItem) {
	            		return parseFloat(item.credit);
            		} else {
            			return 0;
            		}
            	});
            },
        },

		data() {
			return {
                items: [
                    {
                        id: 1,
                        account_type: 'Vendor',
                        account: 'chap-000000',
                        invoice: '10001',
                        description: 'Landscaping',
                        debit: 0,
                        credit: 1275.00,
                        offset_account_type: 'Ledger',
                        offset_account: '7000-001-201-101',
                        selected: false,
                        alreadyInSelectedItem: false,

                        invoice_data: {
                        	terms_of_payment: 'Net 30',
                        	due_date: '11/11/2020',
                        	payment_id: null,
                        	tax_exempt_number: 'sample'
                        },

                        sales_tax: {
                        	sales_tax_group: 'sample',
                        	item_sales_tax_group: 'sample',
                        	calculated_sales_tax_amount: 0,
                        	actual_sales_tax_amount: 0
                        },

                        document: {
                        	document: null,
                        	document_date: '11/11/2020'
                        },

                        account_name: {
                        	account_name: 'East County Lands',
                        	offset_account_name: null
                        },

                        cash_discount: {
                        	cash_discount: null,
                        	cash_discount_date: '11/11/2020'
                        }
                    },
                    {
                        id: 2,
                        account_type: 'Vendor',
                        account: 'chap-000000',
                        invoice: '10001',
                        description: 'Landscaping',
                        debit: 500,
                        credit: 0,
                        offset_account_type: 'Ledger',
                        offset_account: '7000-001-201-101',
                        selected: false,
                        alreadyInSelectedItem: false,

                        invoice_data: {
                        	terms_of_payment: 'Net 30',
                        	due_date: '11/12/2020',
                        	payment_id: null,
                        	tax_exempt_number: 'sample'
                        },

                        sales_tax: {
                        	sales_tax_group: 'sample',
                        	item_sales_tax_group: 'sample',
                        	calculated_sales_tax_amount: 0,
                        	actual_sales_tax_amount: 0
                        },

                        document: {
                        	document: null,
                        	document_date: '11/16/2020'
                        },

                        account_name: {
                        	account_name: 'East County Lands',
                        	offset_account_name: null
                        },

                        cash_discount: {
                        	cash_discount: null,
                        	cash_discount_date: '11/11/2020'
                        }
                    },
                    {
                        id: 2,
                        account_type: 'Vendor',
                        account: 'chap-000000',
                        invoice: '10001',
                        description: 'Landscaping',
                        debit: 0,
                        credit: 1250,
                        offset_account_type: 'Ledger',
                        offset_account: '7000-001-201-101',
                        selected: false,
                        alreadyInSelectedItem: false,

                        invoice_data: {
                        	terms_of_payment: 'Net 30',
                        	due_date: '11/21/2020',
                        	payment_id: null,
                        	tax_exempt_number: 'sample'
                        },

                        sales_tax: {
                        	sales_tax_group: 'sample',
                        	item_sales_tax_group: 'sample',
                        	calculated_sales_tax_amount: 0,
                        	actual_sales_tax_amount: 0
                        },

                        document: {
                        	document: null,
                        	document_date: '11/30/2020'
                        },

                        account_name: {
                        	account_name: 'East County Lands',
                        	offset_account_name: null
                        },

                        cash_discount: {
                        	cash_discount: null,
                        	cash_discount_date: '11/21/2020'
                        }
                    },
                ],

                showOnlyData: {
                	account_type: true,
                	credit: true,
                	debit: true,
                	description: true,
                	offset_account: true,
                	offset_account_type: true
                },

                selected: {
                	invoice_data: {
                		terms_of_payment: null,
                		due_date: null,
                		payment_id: null,
                		tax_exempt_number: null
                	},

                	sales_tax: {
                		sales_tax_group: null,
                		item_sales_tax_group: null,
                		calculated_sales_tax_amount: 0,
                		actual_sales_tax_amount: 0
                	},

                	document: {
                		document: null,
                		document_date: null
                	},

                	account_name: {
                		account_name: null,
                		offset_account_name: null
                	},

                	cash_discount: {
                		cash_discount: null,
                		cash_discount_date: null
                	}
                },
	      	}
		},

		components: {
			'card': Card,
            'data-table': DataTable,
		    'selectize': Selectize,
		    'datepicker': Datepicker,
			'form-request': FormRequest,
		},

		watch: {
			items(val) {
				this.init();
			}
		},

		mounted() {
			this.init();
		},

		methods: {
			init() {
				//
			},

			getTotalCredit() {

			},

			onEnter(e, input) {
				if(e.keyCode === 13) {
					this.showOnlyData[input] = true; 
				}
			},

			selectedLine(item) {
				_.each(this.items, (item) => {
					item.selected = false;	
				})
				item.selected = true;
				this.selected = item;
			},

			dataSelected(item) {
				item.alreadyInSelectedItem = !item.alreadyInSelectedItem;
			}

		}
	}
</script>

<style type="text/css">
	tr {
		cursor: hand;
	}

	.selected-table {
		background: #C1C1C1;
	}
</style>