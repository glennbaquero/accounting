<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
			<card>
				<template v-slot:header>
					Purchase Order Return Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
						<button type="button" class="btn btn-success btn-sm" @click="confirmedPO" :disabled="disableConfirmButton  || item.is_cancelled">Confirm Return PO</button>
						<button type="button" class="btn btn-danger btn-sm" @click="cancelPO" :disabled="disableConfirmButton || item.is_cancelled">Cancel PO Return</button>
						<a :href="item.vendorInvoiceUrl" class="btn btn-success btn-sm" :class="item.vendor_invoice || !item.confirm_by	 ? 'disabled' : '' ">Generate Invoice</a>
					</div>
				</template>
				<div v-if="item.id ? true : false" class="row mb-3">
					<div class="col-md-12">
						<a :href='printUrl' type='button' class="btn btn-success" target="_blank">Print Purchase Order Return</a>
					</div>
				</div>
				<div class="card">
				    <div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#purchase_order" data-toggle="tab">Purchase Order Return</a></li>
									<li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
									<li class="nav-item"><a class="nav-link" href="#delivery" data-toggle="tab">Delivery and Charges</a></li>
									<!-- <li class="nav-item"><a class="nav-link" href="#tax" data-toggle="tab">VAT</a></li> -->
									<li class="nav-item"><a class="nav-link" href="#purchase_order_lines" data-toggle="tab">Purchase Order Return Lines</a></li>
									<li class="nav-item"><a class="nav-link" href="#invoice" data-toggle="tab">Invoice</a></li>
									<li class="nav-item"><a class="nav-link" href="#payment" data-toggle="tab">Payment</a></li>
								</ul>
							</div>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-3 mt-2">
										<label>Client <template v-if="item.id">:</template></label>
									</div>
									<div class="col-md-9">
										<template v-if="!item.id">
											<v-select ref="client_select" :disabled="item.id ? true : false" v-model="client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</template>
										<template v-else>
											<input readonly class="form-control" :value="item.client ? item.client.name : '---'">
										</template>
									</div>
								</div>
							</div>
						</div>
				    </div>

				    <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="purchase_order">
				        		<div class="row">
				        			<div class="col-md-4">
			        		    		<div class="form-group">
											<h4 class="mb-2"><i class="fas fa-tags"></i> Purchase Order Return</h4><hr>
											
											<label>Purchase Order Return Number</label>
											<div class="input-group mb-2">
												<input type="text" name="purchase_order_return_number" v-model="item.purchase_order_return_number" class="form-control mb-2" readonly>
											</div>
											<label>Purchase Order Return Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="purchase_order_date" type="text" class="form-control calendar-form" name="purchase_order_date" v-model="item.purchase_order_date" readonly>
											</div>
											<label>Delivery Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
			        		    				<input ref="delivery_date_pickr" type="text" class="form-control calendar-form" name="delivery_date" v-model="item.delivery_date" readonly>
											</div>
											<label>Due Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="due_date" type="text" class="form-control calendar-form" name="due_date" v-model="item.due_date" readonly>
											</div>
											<label>Ordered By <b class="text-danger">*</b></label>
						                	<input name="ordered_by" v-model="item.ordered_by" class="form-control mb-2">	
											<label>Sales Order</label>
											<v-select :disabled="item.id ? true : false" v-model='item.sales_order_number' @input="selectSO" :reduce="item => item.sales_order_number" label="sales_order_number" placeholder="Select Sales Order" :options="sale_orders" class="mb-2"></v-select>
											<div class="form-group mt-4">
												<h4><i class="fas fa-dollar-sign"></i> Sales Tax</h4><hr>
												<label>Sales Tax Group</label>
												<input name="sales_tax_group" v-model="item.sales_tax_group" type="text" class="form-control mb-2">
												<label>Prices Include Sales Tax</label>
												<input name="prices_include_sales_tax" v-model="item.prices_include_sales_tax" type="text" class="form-control mb-2">
												<label>Tax Exempt Number</label>
												<input name="tax_exempt_number" v-model="item.tax_exempt_number" type="text" class="form-control mb-2">
											</div>
			        		    		</div>				
			        		    	</div>
				        			<div class="col-md-4">
										<div class="form-group">
			        		    			<h4><i class="far fa-question-circle"></i> Status</h4><hr>
											<label>Purchase Type <b class="text-danger">*</b></label>
											<v-select v-model='item.purchase_type' :options="purchase_types" class="mb-2"></v-select>
			        		    			<label>Purchase Order Status</label>
											<v-select v-model='item.purchase_order_status' :options="purchase_order_statuses" class="mb-2"></v-select>
											<label>Confirmed By</label>
	 										<input readonly v-model="item.confirmed_user" type="text" class="form-control mb-2">
											<label>Confirmed Status Date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input readonly v-model="item.confirmed_date" type="text" class="form-control">
											</div>
											<label>Accounting date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
			        		    				<input type="text" class="form-control" v-model="item.accounting_date" readonly>
											</div>
											<label>Cancelled By</label>
	 										<input readonly v-model="item.cancelled_user_name" type="text" class="form-control mb-2">
											<label>Cancelled Date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input readonly v-model="item.cancelled_date" type="text" class="form-control">
											</div>
			        		    		</div>
				        			</div>
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-store"></i> Vendor</h4><hr>

											<label>Vendor</label>
											<v-select v-model="item.vendor_account" :reduce="item => item.vendor_account" label="company_name" class="mb-2" :options="vendors"></v-select>
											
			        		    			<label>Vendor Account <b class="text-danger">*</b></label>
 										 	<input readonly name="vendor_account" v-model="item.vendor_account" type="text" class="form-control mb-2">

 										 	<input hidden name="vendor_name" v-model="item.vendor_name" type="text" class="form-control mb-2">			

							    			<label>Vendor Contact ID <b class="text-danger">*</b></label>
							                <input name="vendor_contact_id" readonly v-model="item.vendor_contact_id" type="text" class="form-control mb-2" >
											<label>Vendor Address</label>
							                <textarea readonly name="vendor_address" v-model="item.vendor_address" class="form-control mb-2" rows="3">{{ item.vendor_address }}</textarea>

											<label>Invoice Account</label>
											<v-select v-model="item.invoice_account" :reduce="item => item.vendor_account" label="fullname" :options="vendors"></v-select>

											<input class="mt-2 mr-2" name="one_time_supplier_checkbox" :checked="checked(item.one_time_supplier_checkbox)" type="checkbox"> <label>One Time Supplier</label>
										</div>
									</div>
		        				</div>
				        	</div>

				        	<div class="tab-pane" id="financial">
		        				<div class="row">
		        		    		<div class="form-group col-sm-6">
										<h4><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
										<label>Cost Center <b class="text-danger">*</b></label>
										<v-select class="mb-2" v-model="item.cost_center" :reduce="item => item.id" label="dimension_name" placeholder="Select Cost Center" :options="cost_centers"></v-select>
										<label>Department <b class="text-danger">*</b></label>
										<v-select class="mb-2" v-model="item.department" :reduce="item => item.id" label="dimension_name" placeholder="Select Department" :options="departments"></v-select>
										<label>Expense Purpose <b class="text-danger">*</b></label>
										<v-select class="mb-2" v-model="item.expense_purpose" :reduce="item => item.id" label="dimension_name" placeholder="Select Expense Purpose" :options="expense_purposes"></v-select>
		        		    			<label>Posting Profile</label>
		        		    			<v-select class="mb-2" v-model="item.posting_profile" :reduce="item => item.id" label="posting_profile" :options="posting_profiles"></v-select>
		        		                <input name="posting_profile" v-model="item.posting_profile" type="hidden" class="form-control mb-2">
		        		    			<label>Accouting Distribution</label>
		        		                <input name="accouting_distribution" v-model="item.accouting_distribution" type="text" class="form-control mb-2">
		        		    		</div>
									<div class="form-group col-sm-6">
										<h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
		        		    			<label>Created By</label>
 										<input readonly v-model="created_by" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Created On</label>
										<input readonly v-model="item.formatted_created_at" type="text" class="form-control mb-2">
		        		    		
		        		    			<label>Updated By</label>
										<input readonly v-model="updated_by" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Updated on</label>
		        		                <input readonly v-model="item.formatted_updated_at" type="text" class="form-control">
		        		    		</div>
		        				</div>
				        	</div>	

				        	<div class="tab-pane" id="delivery">
		        				<div class="row">
									<div class="col-md-6">
										<div class="form-group">
							    			<h4><i class="fas fa-file-invoice-dollar"></i> Payments</h4> <hr>										
											<label>Settlement Type</label>
											<v-select v-model='item.settlement_type' :options="settlement_types" placeholder="Select Settlement Type" class="mb-2"></v-select>

							    			<label>Payment Method <b class="text-danger">*</b></label>
											<v-select v-model='item.method_of_payment' :reduce='item => item.id' :options="payment_methods" label="method_of_payment" placeholder="Payment Method Type" class="mb-2"></v-select>
																	    		
							    			<label>Terms of payment <b class="text-danger">*</b></label>
											<v-select  v-model='item.terms_of_payment' :reduce='item => item.id' :options="terms_of_payments" label="terms_of_payment" placeholder="Term of Payment" class="mb-2"></v-select>

							    			<label>Payment Specification</label>
											<textarea
											name="payment_specification"
											v-model="item.payment_specification"
											class="form-control mb-2"
											row="5"
											></textarea>
											
											<label>Cash Amount</label>
											<input type="number" min="0" step="any" name="cash_amount" v-model="item.cash_amount" class="form-control mb-2">
											<label>Check Amount</label>
											<input type="number" min="0" step="any" name="check_amount" v-model="item.check_amount" class="form-control mb-2">
											<label>Deposit Amount</label>
											<input type="number" min="0" step="any" name="deposit_amount" v-model="item.deposit_amount" class="form-control mb-2">
											<label>Other Amount</label>
											<input type="number" min="0" step="any" name="other_amount" v-model="item.other_amount" class="form-control mb-2">
											<label>Total Amount Received</label>
											<input type="number" min="0" step="any" name="total_amount_received" v-model="item.total_amount_received" class="form-control mb-2">
							    	
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
							    			<h4><i class="fas fa-truck"></i> Delivery</h4><hr>
																				
											<label>Delivery Contact</label>
											<input name="delivery_contact" v-model="item.delivery_contact" class="form-control mb-2">

											<label>Delivery Address</label>
											<input  name="delivery_address" v-model="item.delivery_address" class="form-control mb-2">

											<label>Delivery Terms</label>
											<input name="delivery_terms_type" v-model="item.delivery_terms_type" class="form-control mb-2">
		        		    	
							    			<label>Mode of Delivery</label>
											<input v-model="item.mode_of_delivery_type" name="mode_of_delivery_type" class="form-control mb-2">

											<label>Charges Group</label>
			        		                <input name="charges_group" v-model="item.charges_group" type="text" class="form-control mb-2">
							    	
										</div>
										<div class="form-group">

											<h4><i class="fas fa-cash-register"></i> Charges</h4><hr>
											<label>Charges</label>
											<v-select class="mb-3" v-model="item.charge_id" placeholder="Select Charges" :options="filtered_charges_on_header"  :reduce="item => item.id" label="name"></v-select>
											<h4><i class="fas fa-percentage"></i> Discount</h4><hr>
											<label>Discount</label>
											<v-select class="mb-3" v-model="item.discount_id" placeholder="Select Discount" :options="filtered_discount_on_header"  :reduce="item => item.id" label="name"></v-select>


										</div>
									</div>
		        				</div>
				        	</div>

<!-- 				        	<div class="tab-pane" id="tax">
		        				<div class="row">
									<div class="col-md-3">
										<label>Total VATTable Sales (VAT Exclusive)</label>
										<input type="number" min="0" step="any" name="total_sales_vat_exclusive" v-model="item.total_sales_vat_exclusive" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Less Discount</label>
										<input type="number" min="0" step="any" name="less_discount" v-model="item.less_discount" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Add Charge</label>
										<input type="number" min="0" step="any" name="add_charge" v-model="item.add_charge" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Add 12% VAT</label>
										<input type="number" min="0" step="any" name="add_vat" v-model="item.add_vat" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Total Sales (VAT Inclusive)</label>
										<input type="number" min="0" step="any" name="total_sales_vat_inclusive" v-model="item.total_sales_vat_inclusive" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Less Withholding Tax</label>
										<input type="number" min="0" step="any" name="less_withholding_tax" v-model="item.less_withholding_tax" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Amount Due</label>
										<input type="number" min="0" step="any" name="amount_due" v-model="item.amount_due" class="form-control">
									</div>
									<div class="col-md-3">
										<label>VAT-Exempt Sale</label>
										<input type="number" min="0" step="any" name="vat_exempt_sale" v-model="item.vat_exempt_sale" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Zero-Rated Sales</label>
										<input type="number" min="0" step="any" name="zero_rated_sales" v-model="item.zero_rated_sales" class="form-control">
									</div>
									<div class="col-md-3">
										<label>VAT Amount</label>
										<input type="number" min="0" step="any" name="vat_amount" v-model="item.vat_amount" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Total Amount Due</label>
										<input type="number" min="0" step="any" name="total_amount_due" v-model="item.total_amount_due" class="form-control">
									</div>
									<div class="col-md-3">
										<label>Round Off</label>
										<input type="number" min="0" step="any" name="round_off" v-model="item.round_off" class="form-control">
									</div>
								</div>
							</div> -->

				        	<div class="tab-pane" id="purchase_order_lines">
								<purchase-order-lines
									@newLines="getNewlines"
									:po="item"
									:lines="purchase_order_lines"
									:products="products"
									:variants="variants"
									:expense_purposes="expense_purposes"
									:departments="departments"
									:cost_centers="cost_centers"
									:specifications="specifications"
									:services="services"
									:procurements="procurements"
									:charges_on_lines="filtered_charges_on_lines"
									:discount_on_lines="filtered_discount_on_lines"
									>
								</purchase-order-lines>

								<div class="row mt-4">
                                    <div class="col-4">
                                        <div class="form-group col-12">
                                            <label for="total_quantity">Total Quantity</label>
                                            <input type="number" class="form-control" id="total_quantity" name="total_quantity" :value="total_quantity" readonly="readonly">
                                        </div>  

                                        <div class="form-group col-12">
                                            <label for="total_sales_vat_exclusive">Total VATTable Sales (VAT Exclusive)</label>
                                            <input type="text" class="form-control" name="total_sales_vat_exclusive" :value="total_vattable_sales_vat_exclusive" id="total_sales_vat_exclusive" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <hr><h4><i class="fa fa-info-circle" aria-hidden="true"></i> Discount</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="less_discount">Less Discount</label>
                                            <input type="text" class="form-control" name="less_discount" :value="less_discount" id="less_discount" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="cash_discount">Cash Discount</label>
                                            <input type="text" class="form-control" name="cash_discount" :value="cash_discount" id="cash_discount" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="line_discount">Line Discount</label>
                                            <input type="text" class="form-control" name="line_discount" :value="line_discount" id="line_discount" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <hr><h4><i class="fa fa-info-circle" aria-hidden="true"></i> Charges</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="add_charge">Add Charge</label>
                                            <input type="text" class="form-control" name="add_charge" :value="add_charge" id="add_charge" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="charge">Charge</label>
                                            <input type="text" class="form-control" name="charge" :value="charge" id="charge" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="line_charge">Line Charge</label>
                                            <input type="text" class="form-control" name="line_charge" :value="line_charge" id="line_charge" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <hr><h4><i class="fa fa-info-circle" aria-hidden="true"></i> Fee</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="add_fee">Add Fee</label>
                                            <input type="text" class="form-control" name="add_fee" readonly :value="add_fee" id="add_fee">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="fee">Fee</label>
                                            <input type="text" class="form-control" name="fee" readonly :value="fee" id="fee">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="line_fee">Line Fee</label>
                                            <input type="text" class="form-control" name="line_fee" :value="line_fee" id="line_fee" readonly>
                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <div class="form-group col-12">
                                            <h4><i class="fa fa-info-circle" aria-hidden="true"></i> VAT</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="add_12_vat">Add 12% VAT</label>
                                            <input type="text" class="form-control" name="add_12_vat" v-model="item.add_vat" id="add_12_vat">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="additional_tax">Additional Tax</label>
                                            <input type="text" class="form-control" name="additional_tax" v-model="item.additional_tax" id="additional_tax">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="vat_amount">VAT Amount</label>
                                            <input type="text" class="form-control" name="vat_amount" v-model="item.vat_amount" id="vat_amount">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="line_vat">Line VAT</label>
                                            <input type="text" class="form-control" name="line_vat" v-model="item.line_vat" id="line_vat">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="total_sales_vat_exclusive">Total Sales (VAT Exclusive)</label>
                                            <input type="text" class="form-control" name="total_sales_vat_exclusive" v-model="item.total_sales_vat_exclusive" id="total_sales_vat_exclusive">
                                        </div>

                                        <div class="form-group col-12">
                                            <div class="form-group col-12">
                                                <hr><h4><i class="fa fa-info-circle" aria-hidden="true"></i> Withholding Tax</h4><hr>
                                            </div>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="less_withholding_tax">Less Withholding Tax</label>
                                            <input type="text" class="form-control" name="less_withholding_tax" v-model="item.less_withholding_tax" id="less_withholding_tax">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="withholding_tax">Withholding Tax</label>
                                            <input type="text" class="form-control" name="withholding_tax" v-model="item.withholding_tax" id="withholding_tax">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="amount_due">Amount Due</label>
                                            <input type="text" class="form-control" name="amount_due" v-model="item.amount_due" id="amount_due">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="vatexempt_sales">VAT-Exempt Sale</label>
                                            <input type="text" class="form-control" name="vatexempt_sales" v-model="item.vatexempt_sales" id="vatexempt_sales">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="zero_rated_sales">Zero-Rated Sales</label>
                                            <input type="text" class="form-control" name="zero_rated_sales" v-model="item.zero_rated_sales" id="zero_rated_sales">
                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <div class="form-group col-12">
                                            <h4><i class="fa fa-info-circle" aria-hidden="true"></i> Payment</h4><hr>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="total_round_off">Round Off</label>
                                            <input type="number" class="form-control" id="total_round_off" v-model="item.total_round_off" name="total_round_off" readonly="readonly">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="total_amount_due">Total Amount Due</label>
                                            <input type="text" class="form-control" name="total_amount_due" v-model="item.total_amount" id="total_amount_due">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="cash_payment">Cash Payment</label>
                                            <input type="text" class="form-control" name="cash_payment" v-model="item.cash_payment" id="cash_payment">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="check_payment">Check Payment</label>
                                            <input type="text" class="form-control" name="check_payment" v-model="item.check_payment" id="check_payment">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="deposit_amount">Deposit Amount</label>
                                            <input id="deposit_amount" name="deposit_amount" type="text" class="form-control" v-model="item.deposit_amount">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="other_payment">Other Payment</label>
                                            <input type="text" class="form-control" name="other_payment" v-model="item.other_payment" id="other_payment">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="total_amount_paid">Total Amount Paid</label>
                                            <input type="text" class="form-control" name="total_amount_paid" v-model="item.total_amount_paid" id="total_amount_paid">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="outstanding">Outstanding</label>
                                            <input type="text" class="form-control" name="outstanding" v-model="item.outstanding" id="outstanding">
                                        </div>
                                    </div>
                                    
                                </div>
				        	</div>

				        	<div class="tab-pane" id="invoice">
					            <!-- <a href="javascript:void(0)" class="btn btn-primary text-white">
					                <i class="fa fa-plus"></i>
					                Create
					            </a> -->
						        <div class="col-xs-12">
						            <div class="card">
						                <div class="card-header p-2">
						                    <ul class="nav nav-pills">
						                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#for_approval" data-toggle="tab">For Approval</a></li>
						                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#approved" data-toggle="tab">Approved</a></li>
						                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#posted" data-toggle="tab">Posted</a></li>
						                    </ul>
						                </div>
						                <div class="card-body">
						                    <div class="tab-content">
						                        <div class="tab-pane show active" id="for_approval">
						                            <vendor-invoice-table 
						                            :clients="clients"
						                            ref="table-1"
						                            :fetch-url="vendorInvoicesApproval"
						                            ></vendor-invoice-table>
						                        </div>
						                        <div class="tab-pane" id="approved">
						                            <vendor-invoice-table 
						                            :clients="clients"
						                            ref="table-2"
						                            :fetch-url="vendorInvoicesApproved"
						                            ></vendor-invoice-table>
						                        </div>
						                        <div class="tab-pane" id="posted">
						                            <vendor-invoice-table 
						                            :clients="clients"
						                            ref="table-3"
						                            :fetch-url="vendorInvoicesPosted"
						                            ></vendor-invoice-table>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </div>
				        	</div>

				        	<div class="tab-pane" id="payment">
				        		<!-- <div class="mb-4">
						            <a href="javascript:void(0)" class="btn btn-primary text-white">
						                <i class="fa fa-plus"></i>
						                Create
						            </a>
						        </div> -->
				        		<div class="col-xs-12">
						            <div class="card">
						                <div class="card-header p-2">
						                    <ul class="nav nav-pills">
						                        <li class="nav-item"><a @click="initList('table-4')" class="nav-link active" href="#for_approval_payment" data-toggle="tab">For Approval</a></li>
						                        <li class="nav-item"><a @click="initList('table-5')" class="nav-link" href="#approved_payment" data-toggle="tab">Approved</a></li>
						                        <li class="nav-item"><a @click="initList('table-6')" class="nav-link" href="#posted_payment" data-toggle="tab">Posted</a></li>
						                    </ul>
						                </div>
						                <div class="card-body">
						                    <div class="tab-content">
						                        <div class="tab-pane show active" id="for_approval_payment">
						                            <vendor-payment-table 
						                                :clients="clients"
						                                ref="table-4"
						                                :fetch-url="vendorPaymentsApproval"
						                            ></vendor-payment-table>
						                        </div>
						                        <div class="tab-pane" id="approved_payment">
						                            <vendor-payment-table 
						                                :clients="clients"
						                                ref="table-5"
						                                :fetch-url="vendorPaymentsApproved"
						                                :is-approved="true"
						                            ></vendor-payment-table>
						                        </div>
						                        <div class="tab-pane" id="posted_payment">
						                            <vendor-payment-table 
						                                :clients="clients"
						                                ref="table-6"
						                                :fetch-url="vendorPaymentsPosted"
						                                :is-posted="true"
						                            ></vendor-payment-table>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </div>
				        	</div>
				        </div>
				    </div>
				</div>
			</card>
        <loader 
        :loading="loading">
        </loader>
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import POMixin from './purchase-order.js';
	import SetupMixin from 'Mixins/setup.js';

	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
    import DataTable from 'Components/tables/StaticDataTable.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import PurchaseOrderLineView from './PurchaseOrderLineView.vue';
	import { ModelListSelect } from 'vue-search-select';
	import Vselect from 'vue-select';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {
		props: {
			showConfirmButton: {
				default: true,
				type: Boolean
			},

			orderNumber: {
				default: null,
				type: String
			},

			vendorInvoicesApproval: String,
			vendorInvoicesApproved: String,
			vendorInvoicesPosted: String,
			vendorPaymentsApproval: String,
			vendorPaymentsApproved: String,
			vendorPaymentsPosted: String,
			printUrl : String,

		},
		
		components: {
			Card,
			'purchase-order-lines' : PurchaseOrderLineView,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
            'data-table': DataTable,
			'action-button': ActionButton,
			ModelListSelect,
			'v-select' : Vselect
		},

		data() {
			return {
				key : null,
				created_by : null,
				updated_by : null,
				confirm_by : null,
				client_id : null,

				params : [],
				item: {
					total_sales_vat_exclusive: 0,
					less_discount: 0,
					add_charge: 0,
					add_vat: 0,
					total_sales_vat_inclusive: 0,
					amount_due: 0,
				},

				users: [],
				vendors: [],
				products: [],
				variants: [],
				payment_methods: [],
				terms_of_payments: [],
				cost_centers: [],
				departments: [],
				expense_purposes: [],
				clients: [],

				purchase_order_line: [],
				purchase_order_lines: [],
				posting_profiles: [],
				sale_orders : [],
				specifications : [],
				services : [],
				procurements : [],
				charges_on_lines : [],
				charges_on_header : [],
				discount_on_lines : [],
				discount_on_header : [],
			}
		},


        computed: {
            headers() {
                let array = [
                    { text: 'Line #', value: null },
                    { text: 'Item #', value: null },
                    { text: 'Product name', value: null },
                    { text: 'Procurement category', value: null },
                    { text: 'Size', value: null },
                    { text: 'Color', value: null },
                    { text: 'Total quantity', value: null },
                    { text: 'Purchase unit', value: null },
                    { text: 'Cash discount', value: null },
                    { text: 'Discount percentage', value: null },
                    { text: 'Line amount', value: null },
                    { text: 'Action', value: null },
                ];

                return array;
            },


            disableConfirmButton() {
            	if(this.showConfirmButton) {
	            	return this.item.is_already_confirmed;
            	}

            	return true;
            },

            disableGenerateInvoiceButton() {
            	if(this.showConfirmButton) {
	            	 if(!this.item.has_invoice && !this.item.confirm_by) {
						 return true;
					 }
					
					return false;
            	}

            	return true;
            },

            total_quantity() {
                let result = 0;
                let itemLines = this.purchase_order_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        result += parseInt(line.quantity);
                    });
                }

                return result;
            },

            total_vattable_sales_vat_exclusive() {
                let result = 0;
                let itemLines = this.purchase_order_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.amount);
                        result += amount;
                    });
                }

                return result;
            },

            less_discount() {
                let result = 0;
                let itemLines = this.purchase_order_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.less_discount);
                        result += amount;
                    });
                }

                return result;
            },

            cash_discount() {
                let result = 0;

                return result;
            },

            line_discount() {
                let result = 0;
                let itemLines = this.purchase_order_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.discount);
                        result += amount;
                    });
                }

                return result;
            },

            add_charge() {
                let result = 0;
                let itemLines = this.purchase_order_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.add_charge);
                        result += amount;
                    });
                }

                return result;
            },

            charge() {
                let result = 0;

                return result;
            },

            line_charge() {
                let result = 0;
                let itemLines = this.purchase_order_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        let amount = parseFloat(line.charge_on_purchase);
                        result += amount;
                    });
                }

                return result;
            },

            add_fee() {
                let result = 0;

                return result;
            },

            fee() {
                let result = 0;

                return result;
            },

            line_fee() {
                let result = 0;

                return result;
            },


            filtered_charges_on_lines() {
            	return _.filter(this.charges_on_lines, (charge) => { return moment(this.purchase_order_date).isBetween(charge.valid_from, charge.valid_to) });
            },

            filtered_discount_on_lines() {
            	return _.filter(this.discount_on_lines, (discount) => { return moment(this.purchase_order_date).isBetween(discount.valid_from, discount.valid_to) });
            },

            filtered_charges_on_header() {
            	return _.filter(this.charges_on_header, (charge) => { return moment(this.purchase_order_date).isBetween(charge.valid_from, charge.valid_to) });
            },

            filtered_discount_on_header() {
            	return _.filter(this.discount_on_header, (discount) => { return moment(this.purchase_order_date).isBetween(discount.valid_from, discount.valid_to) });
            },

            totalVat() {

            	var gross_amount = parseFloat(this.item.total_sales_vat_exclusive);
            	var vat = 12 / 100;
            	this.item.add_vat = gross_amount * vat;
            	return gross_amount * vat;

            },

            totalSales() {
            	var gross_amount = parseFloat(this.item.total_sales_vat_exclusive);
            	var less_amount = parseFloat(this.item.less_discount);
            	var charge = parseFloat(this.item.add_charge);
            	var vat = parseFloat(this.item.add_vat);
            	this.item.total_sales_vat_inclusive = (gross_amount - less_amount) + charge + vat;
            	this.item.round_off = Math.round(gross_amount)
            	// this.item.amount_due = (gross_amount - less_amount);
            	return (gross_amount - less_amount) + charge + vat;
            },
        },

		mixins: [CrudMixin, SetupMixin, POMixin],

		watch: {
			'item.vendor_account'(val) {
				let vendor = this.vendors.filter(item => item.vendor_account == val)[0];
				if(vendor) {
					this.item.vendor_account = vendor.vendor_account;
					this.item.vendor_address = vendor.address;
					this.item.vendor_contact_id = vendor.fullname;
					this.item.vendor_name = vendor.fullname;
				} else {
					this.item.vendor_account = null;
					this.item.vendor_address = null;
					this.item.vendor_contact_id = null;
					this.item.vendor_name = null;
				}
			},

			'item.created_by'(val) {
				this.created_by = val.fullname;
			},

			'item.updated_by'(val) {
				this.updated_by = val.fullname;
			},

			'item.confirm_by'(val) {
				if(val) {
					this.confirm_by = val.fullname ? val.fullname : null;
				}
			},

			'client_id'(value) {
				this.item.client_id = value;
				this.getActiveCode();
			},
		},

		mounted() {

			if(!this.item.purchase_order_return_number) {
				this.generateCode('create','PO');
			}

			flatpickr(this.$refs.purchase_order_date)
			flatpickr(this.$refs.delivery_date_pickr)
			flatpickr(this.$refs.due_date)

			// flatpickr(this.$refs.approval_status_date)
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.users = data.users ? data.users : this.users;
				this.vendors = data.vendors ? data.vendors : this.vendors;
				this.products = data.products ? data.products : this.products;
				this.variants = data.variants ? data.variants : this.variants;
				
				this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.purchase_order_lines = data.purchase_order_lines ? data.purchase_order_lines : this.purchase_order_lines;

				this.departments = data.departments ? data.departments : this.departments;
				this.clients = data.clients ? data.clients : this.clients;
				this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
				this.posting_profiles = data.posting_profiles ? data.posting_profiles : this.posting_profiles;
				this.sale_orders = data.sale_orders ? data.sale_orders : this.sale_orders;
				this.specifications = data.specifications ? data.specifications : this.specifications;
				this.services = data.services ? data.services : this.services;
				this.procurements = data.procurements ? data.procurements : this.procurements;

				this.charges_on_lines = data.charges_on_lines ? data.charges_on_lines : this.charges_on_lines;
				this.charges_on_header = data.charges_on_header ? data.charges_on_header : this.charges_on_header;

				this.discount_on_lines = data.discount_on_lines ? data.discount_on_lines : this.discount_on_lines;
				this.discount_on_header = data.discount_on_header ? data.discount_on_header : this.discount_on_header;



				if(!this.item.id) {
					this.defaultValue();
					this.client_id = this.clients[0].id;
				}
			},

			addLine() {
				var data = {
					procurement_category: this.$refs.procurement_category.value,
					line_number: this.purchase_order_line.length + 1,
					item_number: this.item.item_number.item_number,
					product_name: this.item.item_number.name,
					size: this.$refs.size.value,
					color: this.$refs.color.value,
					quantity: this.item.quantity,
					purchase_unit: this.item.item_number.unit_price * this.$refs.quantity.value,
					line_status: this.$refs.line_status.value,
					line_net_amount: this.item.item_number.unit_price * this.$refs.quantity.value,
					delivery_date: this.$refs.delivery_date_tbl.value,

					// computation in totals
					
					discount: this.item.cash_discount,
					discount_percentage: this.item.discount_percentage,

					// for backend purpose
					product_obj: this.item.item_number,
					is_new: true
				}

				this.purchase_order_line.push(data);	
				this.purchase_order_lines = JSON.stringify(this.purchase_order_line);
			},

			removeLine(key) {
				this.purchase_order_line.splice(key, 1);
				this.purchase_order_lines = JSON.stringify(this.purchase_order_line);
			},

			confirmedPO() {
				var $this = this;
				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you want to approved this PO?',
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then((result) => {
				  if (result.value) {
				    axios.post($this.item.confirmationUrl)
			    	.then(response => {
					    $this.$notification.show(response.data.message, 'Success')
					    $this.fetch();
			    	}).catch(error => {

			    	})
				  }
				})
			},

			cancelPO() {
				var $this = this;
				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you want to cancel this PO?',
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then((result) => {
				  if (result.value) {
				    axios.post($this.item.confirmationUrl)
			    	.then(response => {
					    $this.$notification.show(response.data.message, 'Success')
					    $this.fetch();
			    	}).catch(error => {

			    	})
				  }
				})
			},

			checked(value) {
				if(value == 1) {
					return true;
				}
				return false;
			},

			generateCode(value, prefix) {
				if(value == 'create') {
					var date = new Date();
					var time = Math.round(date.getTime() / 1000);	
					this.item.purchase_order_return_number = prefix + '-' + date.getFullYear().toString() + ("0" + (date.getMonth() + 1)).slice(-2) + date.getDate().toString()   +'-'+ time.toString();
				}
			},

			getActiveCode() {
				let id = this.item.client_id;
				if(id) {
					let client = this.clients.filter(item => item.id == id)[0];
					if(client.code) {	
						this.item.purchase_order_return_number = client.code;
					}else {
						this.generateCode('create','POReturn');
					}	
				}else {
					this.generateCode('create','POReturn');
				}
			},

			getNewlines(value) {
				this.item.purchase_order_lines = value;
			},

			selectSO(value) {
				if(value) {
					let so = this.sale_orders.filter(item => item.sales_order_number == value)[0];
					if(so) {
						this.client_id = so.client_id;

						let lines = so.sales_order_lines.map(item => { 
							item.is_new = true;
							item.procurement_category = item.sales_category;
							item.purchase_order_line_number = this.generateLineCode('create','POL');
							console.log(this.generateLineCode('create','POL'));
							return item;
						});

						this.purchase_order_lines = lines;

						this.item = {
							sales_order_number: value,
							purchase_order_status: 'Open Order',
							document_status: 'None',
							approval_status: 'Draft',
							purchase_type: 'Standard PO',
							settlement_type: 'None',
							client_id : so.client_id,
							cost_center: so.cost_center_id,
							department: so.department_id,
							expense_purpose: so.expense_purpose_id,
							due_date: so.due_date,
							delivery_date: so.delivery_date,
							method_of_payment : so.method_of_payment,
							terms_of_payment : so.terms_of_payment,
							purchase_order_lines : lines,
						}

					}else {
						this.purchase_order_lines = [];
						this.defaultValue();
					}
				}else {
					this.purchase_order_lines = [];
					this.defaultValue();
				}
				this.getActiveCode();
			},

			defaultValue() {
				this.client_id = null;
				this.item = {
					purchase_order_return_number: null,
					purchase_order_status: 'Open Order',
					document_status: 'None',
					approval_status: 'Draft',
					purchase_type: 'Standard PO',
					settlement_type: 'None',
					expense_purpose : this.expense_purposes[0] ? this.expense_purposes[0].id : null,
					department : this.departments[0] ? this.departments[0].id : null,
					cost_center : this.cost_centers[0] ? this.cost_centers[0].id : null,
					method_of_payment : this.payment_methods[0] ? this.payment_methods[0].id : null,
					terms_of_payment : this.terms_of_payments[0] ? this.terms_of_payments[0].id : null,
				};
			},
				
			generateLineCode(value, prefix) {
				if(value == 'create') {
					var date = new Date();
					var time = (Math.random() + Math.random()).toString(36).substr(2, 7);
					return prefix + '-' + ("0" + (date.getMonth() + 1)).slice(-2) + date.getFullYear().toString() + '-' + time;
				}
			},

		}
	}
</script>