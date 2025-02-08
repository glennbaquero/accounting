<template>
	<div>
		<div class="card">
		    <div class="card-header p-2">
		        <ul class="nav nav-pills">
		            <!-- <li class="nav-item"><a class="nav-link " href="#general" data-toggle="tab">General</a></li> -->
		            <li class="nav-item"><a class="nav-link active" href="#sales_order" data-toggle="tab">Sales Order</a></li>
		            <!-- <li class="nav-item"><a class="nav-link" href="#customer" data-toggle="tab">Customer Information</a></li> -->
		            <li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial dimensions</a></li>
		            <li class="nav-item"><a class="nav-link" href="#delivery" data-toggle="tab">Delivery</a></li>
		            <li class="nav-item"><a class="nav-link" href="#price_discount" data-toggle="tab">Totals</a></li>
		            <li class="nav-item"><a class="nav-link" href="#sales_order_lines" data-toggle="tab">Sales order lines</a></li>
		        </ul>
		    </div>

		    <div class="card-body">
		        <div class="tab-content">
		        	<div class="tab-pane " id="general">
	    				<div class="row">
	    		    		<div class="form-group col-sm-6">
	    		    			<label>Customer Account</label>
	    		                <select v-model="sales_order.customer_account" class="form-control">
	    		                	<option v-for="customer in customers" :value="customer.customer_account">{{ customer.fullname }}</option>
	    		                </select>
	    		    		</div>
	    		    		<div class="form-group col-sm-6">
	    		    			<label>Invoice Account</label>
	    		                <select v-model="sales_order.invoice_account" class="form-control">
	    		                	<option v-for="customer in customers" :value="customer.customer_account">{{ customer.fullname }}</option>
	    		                </select>
	    		    		</div>
	    				</div>
		        	</div>

		        	<div class="tab-pane show active" id="sales_order">
	    				<div class="row">
	    					<div class="col-md-6">
	    						<div class="form-group">
	    							<label>Sales order</label><br>
	    							<p>Sales order number</p>
	    							<input type="text" v-model="sales_order.sales_order_number" class="form-control">
	    						</div>
	    						<div class="form-group">
	    							<label>Customer Account</label><br>
	        		    			<p>Customer Account</p>
	        		                <select v-model="sales_order.customer_account" class="form-control">
	        		                	<option v-for="customer in customers" :value="customer.customer_account">{{ customer.fullname }}</option>
	        		                </select>
	        		    			<p>Invoice Account</p>
	        		                <select v-model="sales_order.invoice_account" class="form-control">
	        		                	<option v-for="customer in customers" :value="customer.customer_account">{{ customer.fullname }}</option>
	        		                </select>
	        		    		</div>	
	    						<div class="form-group">
	        		    			<p>Sales order date</p>
	        		    			<input ref="sales_order_date" type="text" class="form-control" v-model="sales_order.sales_order_date" readonly>
	        		    			<p>Delivery date</p>
	        		    			<input ref="delivery_date_pickr" type="text" class="form-control" v-model="sales_order.delivery_date" readonly>
	        		    			<p>Due date</p>
	        		    			<input ref="due_date" type="text" class="form-control" v-model="sales_order.due_date" readonly>
	        		    			<p>Confirmed date</p>
	        		    			<input ref="confirmed_date" type="text" class="form-control" v-model="sales_order.confirmed_date" readonly>
	        		    			<p>Accounting date</p>
	        		    			<input ref="accounting_date" type="text" class="form-control" v-model="sales_order.accounting_date" readonly>
	        		    		</div>
	    					</div>

	    					<div class="col-md-6">
	    						<div class="form-group">
	        		    			<label>Customer Details</label><br>
					    			<p>Customer Name</p>
					                <input v-model="sales_order.customer_name" type="text" class="form-control">
					    			<p>Customer Address</p>
					                <textarea v-model="sales_order.customer_address" class="form-control">{{ sales_order.customer_name }}</textarea>
					    			<p>Customer Contact ID</p>
					                <input v-model="sales_order.customer_contact_id" type="text" class="form-control">
					    			<p>Sold By</p>
					    			<select v-model="sales_order.sold_by" class="form-control">
					    				<option v-for="user in users" :value="user.id">{{ user.fullname }}</option>
					    			</select>
	    						</div>
	    						<div class="form-group">
	        		    			<label>Status</label><br>
	        		    			<p>Sales order status</p>
	        		                <select v-model="sales_order.sales_order_status" class="form-control">
	        		                	<option value="Open Order" selected>Open Order</option>
	        		                	<option value="Delivered">Delivered</option>
	        		                	<option value="Invoiced">Invoiced</option>
	        		                	<option value="Canceled">Canceled</option>
	        		                </select>
	        		    			<p>Document status</p>
	        		                <select v-model="sales_order.document_status" class="form-control">
	        		                	<option value="None" selected>None</option>
	        		                	<option value="Invoice">Invoice</option>
	        		                	<option value="Product Receipt">Product Receipt</option>
	        		                </select>
	        		    			<p>Approval status</p>
	        		                <select v-model="sales_order.approval_status" class="form-control">
	        		                	<option value="Draft" selected>Draft</option>
	        		                	<option value="Confirmed">Confirmed</option>
	        		                	<option value="Finalized">Finalized</option>
	        		                </select>
	        		    		</div>
	        		    		<div class="form-group">
					    			<label>Payments</label>
					    			<p>Payment methods</p>
					                <select v-model="sales_order.method_of_payment" class="form-control">
					    				<option v-for="method in payment_methods" :value="method.id">{{ method.name }}</option>
					                </select>
					    			<p>Terms of payment</p>
					                <select v-model="sales_order.terms_of_payment" class="form-control">
					    				<option v-for="payment in terms_of_payments" :value="payment.terms_of_payment">{{ payment.terms_of_payment }}</option>
					                </select>
					    			<p>Payment specification</p>
					                <textarea v-model="sales_order.payment_specification" class="form-control">{{ sales_order.payment_specification }}</textarea>
					    			<p>Sales tax group</p>
					                <input v-model="sales_order.sales_tax_group" type="text" class="form-control">
					    			<p>Tax exempt number</p>
					                <input v-model="sales_order.tax_exempt_number" type="text" class="form-control">
								</div>
	    					</div>
	    				</div>
		        	</div>

		        	<div class="tab-pane" id="financial">
	    				<div class="row">
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Cost center</label>
				    			<select v-model="sales_order.cost_center" class="form-control">
				    				<option v-for="cost_center in cost_centers" :value="cost_center.code">{{ cost_center.name }}</option>
				    			</select>
	    		    		</div>
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Department</label>
	    		                <input v-model="sales_order.department" type="text" class="form-control">
	    		    		</div>
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Expense purpose</label>
	    		                <input v-model="sales_order.expense_purpose" type="text" class="form-control">
	    		    		</div>
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Posting profile</label>
	    		                <input v-model="sales_order.posting_profile" type="text" class="form-control">
	    		    		</div>
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Accouting distribution</label>
	    		                <input v-model="sales_order.accouting_distribution" type="text" class="form-control">
	    		    		</div>
	    				</div>
		        	</div>	

		        	<div class="tab-pane" id="delivery">
	    				<div class="row">
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Sales type</label>
	    		                <select v-model="sales_order.sales_type" class="form-control">
	    		                	<option value="Standard SO" selected>Standard SO</option>
	    		                	<option value="Contract SO">Contract SO</option>
	    		                	<option value="Blanket SO">Blanket SO</option>
	    		                	<option value="Planned SO">Planned SO</option>
	    		                </select>
	    		    		</div>
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Settlement type</label>
	    		                <select v-model="sales_order.settlement_type" class="form-control">
	    		                	<option value="None" selected>None</option>
	    		                	<option value="Open Transactions">Open Transactions</option>
	    		                	<option value="Designated Transactions">Designated Transactions</option>
	    		                </select>
	    		    		</div>
	    				</div>

	    				<hr>

	    				<div class="row">
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Prices Include Sales Tax</label>
	    		                <input v-model="sales_order.prices_include_sales_tax" type="text" class="form-control">
	    		    		</div>
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Delivery Terms</label>
	    		                <select v-model="sales_order.delivery_terms" class="form-control">
	    		                	<option value="None" selected>None</option>
	    		                </select>
	    		    		</div>
	    		    		<div class="form-group col-sm-4">
	    		    			<label>Mode of delivery</label>
	    		                <select v-model="sales_order.mode_of_delivery" class="form-control">
	    		                	<option value="Air" selected>Air</option>
	    		                	<option value="Sea">Sea</option>
	    		                	<option value="Land">Land</option>
	    		                </select>
	    		    		</div>
	    				</div>
		        	</div>

		        	<div class="tab-pane" id="price_discount">
	    				<div class="row">
	    					<div class="col-md-6">
	    						<div class="form-group">
	    							<label>Data</label>
	    							<p>Total Quantity</p>
	        		                <input v-model="sales_order.total_data_quantity" type="number" class="form-control">
	    						</div>
	    						<div class="form-group">
	        		                <label>Totals</label><br>
									<p>Total Line Discount</p>
						            <input v-model="sales_order.total_line_discount" type="number" class="form-control">
			            			<p>Subtotal Amount</p>
			                        <input v-model="sales_order.subtotal_amount" type="number" class="form-control">
			                        <p>Total Discount</p>
	        		                <input v-model="sales_order.total_discount" type="number" class="form-control">
	        		                <p>Total Charges</p>
	        		                <input v-model="sales_order.total_charges" type="number" class="form-control">
	        		                <p>Total Sales Tax</p>
	        		                <input v-model="sales_order.total_sales_tax" type="number" class="form-control">
	        		                <p>Total Round Off</p>
	        		                <input v-model="sales_order.total_round_off" type="number" class="form-control">
	        		                <p>Total Amount</p>
	        		                <input v-model="sales_order.total_amount" type="number" class="form-control">
	        		                <p>Cash discount</p>
	        		                <input v-model="sales_order.cash_discount" type="number" class="form-control">
	    						</div>
	        		    		<div class="form-group">
	        		    			<p>Charges group</p>
	        		                <input v-model="sales_order.charges_group" type="text" class="form-control">
	        		                <p>Total Discount Percentage</p>
	        		                <input v-model="sales_order.total_discount_percentage" type="number" class="form-control">
	        		                <p>Total Cash Discount</p>
	        		                <input v-model="sales_order.total_cash_discount" type="number" class="form-control">
	        		    		</div>
	    					</div>
	    				</div>
		        	</div>

		        	<div class="tab-pane" id="sales_order_lines">
	        		    <div class="row">
	        		        <div class="col-md-6">
	        		          <!--   <div class="col-md-12">
	        		                <button type="button" class="btn btn-outline-secondary btn-sm w-10" data-toggle="modal" data-target="#add-line-modal">Add line</button>
	        		            </div> -->
	        		        </div>
	        		    </div>
	        		    <div class="row">
	        		        <div class="col-md-12">
	        					<data-table :is-default="false" :headers="headersSOLines" :items="sales_order_lines">
	        		                 <template v-slot:body="{ items }">
	        		                    <tr v-for="(item, key) in items">
	        		                        <td>{{ key + 1 }}</td>
	        		                        <td>{{ item.item_number }}</td>
	        		                        <td>{{ item.product_name }}</td>
	        		                        <td>{{ item.sales_category }}</td>
	        		                        <td>{{ item.size }}</td>
	        		                        <td>{{ item.color }}</td>
	        		                        <td>{{ item.quantity }}</td>
	        		                        <td>{{ item.sales_unit }}</td>
	        		                        <td>50</td>
	        		                        <td>50%</td>
	        		                        <td>{{ item.line_net_amount }}</td>
	        		                        <td>
	        		                        	<action-button
	        		                        	v-if="item.existing_data"
	        		                        	small 
	        		                        	color="btn-danger"
	        		                        	alt-color="btn-warning"
	        		                        	:show-alt="item.deleted_at"
	        		                        	:action-url="item.removeUrl"
	        		                        	icon="fas fa-trash"
	        		                        	alt-icon="fas fa-trash-restore-alt"
	        		                        	confirm-dialog
	        		                        	:disabled="loading"
	        		                        	title="Archive Item"
	        		                        	alt-title="Restore Item"
	        		                        	:message="'Are you sure you want to archive sales order line ' + item.sales_order_line_number + '?'"
	        		                        	:alt-message="'Are you sure you want to restore sales order line ' + item.sales_order_line_number + '?'"
	        		                        	@load="load"
	        		                        	@success="fetch"
	        		                        	></action-button>
	        		                        	<template v-else>
	        		                        		<button type="button" class="btn btn-flat btn-sm" @click="removeLine(key)">
	        		                        		    <i class="fas fa-trash"></i>
	        		                        		</button>
	        		                        	</template>
	        		                        </td>
	        		                    </tr>
	        		                 </template>         
	        		            </data-table>
	        		        </div>

	        		        <textarea v-show="false">{{ sales_order_lines }}</textarea>
	        		    </div>
		        	</div>
		        </div>
		    </div>

		</div>
	</div>
</template>
<script type="text/javascript">
    import DataTable from 'Components/tables/DataTable.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
            
	export default {
		props: {
			sales_order: Object,
			item: Object,
			users: Array,
			customers: Array,
			products: Array,
			terms_of_payments: Array,
			payment_methods: Array,
			cost_centers: Array,
			sales_order_lines: Array,
		},

		computed: {
			headersSOLines() {
                let array = [
                    { text: 'LINE #', value: null },
                    { text: 'ITEM #', value: null },
                    { text: 'PRODUCT NAME', value: null },
                    { text: 'PROCUREMENT CATEGORY', value: null },
                    { text: 'SIZE', value: null },
                    { text: 'COLOR', value: null },
                    { text: 'QUANTITY', value: null },
                    { text: 'PURCHASE UNIT', value: null },
                    { text: 'CASH DISCOUNT', value: null },
                    { text: 'DISCOUNT PERCENTAGE', value: null },
                    { text: 'TOTAL AMOUNT', value: null },
                    { text: 'ACTION', value: null },
                ];

                return array;
            },
		},

		components: {
			'data-table': DataTable,
			'action-button': ActionButton,
		}
	}
</script>