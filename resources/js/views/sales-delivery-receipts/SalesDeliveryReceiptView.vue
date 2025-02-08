<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
			<card>
				<template v-slot:header>
					Sales Delivery Receipt Information
					<div class="float-right">
						 <!-- :disabled="loading || item.approved_by" -->
						<action-button type="submit" class="btn-primary">Save Changes</action-button>
						<a :href="item.salesOrderShowUrl" class="btn btn-secondary" target="_blank">Show Sales Order</a>
						<button type="button" class="btn btn-success" @click="confirmedThisInvoice" :disabled="item.approved_by || !item.id || !showConfirmButton || !hasApprovedLine">Approve Invoice</button>
						<button type="button" class="btn btn-success" :disabled="!item.approved_by"  @click="createCustomerInvoicePayment">Generate Payment</button>
						<a href="#" class="btn btn-success" :class="!item.approved_by ? 'disabled' : ''">Bills of Exchange</a>
						<button type="button" class="btn btn-success" :disabled="(!item.approved_by && item.id) || item.posting_date || !item.id" @click="postThisInvoice">POST</button>
					</div>
				</template>

				<div class="row mb-3">
					<div class="col-md-12">
						<a :href='printUrl' type='button'  class="btn btn-success" target="_blank">Print Sales Delivery Receipt</a>
					</div>
				</div>

				<div class="card">
				    <div class="card-header">
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#customer-invoice" data-toggle="tab">Sales Delivery Receipt</a></li>
									<li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
									<li class="nav-item"><a class="nav-link" href="#delivery" data-toggle="tab">Delivery and Charges</a></li>
									<!-- <li class="nav-item"><a class="nav-link" href="#tax" data-toggle="tab">VAT</a></li> -->
									<li class="nav-item"><a class="nav-link" href="#customer-invoice-lines" data-toggle="tab">Sales Delivery Receipt Lines</a></li>
									<li class="nav-item"><a class="nav-link" href="#payment" data-toggle="tab">Payment</a></li>
									<li class="nav-item"><a class="nav-link" href="#subsidiary-ledger" data-toggle="tab">Subsidiary Ledger</a></li>
									<li v-if="showCreditTab" class="nav-item"><a class="nav-link" href="#credit" data-toggle="tab">Credit</a></li>
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
				        	<div class="tab-pane show active" id="customer-invoice">
								<div class="row">				
									<div class="col-md-3">
										<h4><i class="fas fa-file-invoice-dollar"></i> Sales Delivery</h4><hr>
										<label>Delivery Number</label>
										<input readonly class="form-control mb-2" v-model="item.sales_delivery_receipt_number" >

										<!-- <div class="form-group">
										    <label>Transaction Type <b class="text-danger">*</b></label>
										    <select class="form-control" v-model="item.transaction_type" name="transaction_type">
										    	<option value="Sales" >Sales</option>
										    	<option value="Purchase">Purchase</option>
										    	<option value="Both">Both</option>
										    </select>
										</div> -->
                                        <div class="form-group">
                                            <label for="customer_invoice_id">Sales Order Return Number</label>
											<v-select class="mb-2" 
												v-model="item.sales_order_return_number" 
												:options="sales_order_returns"
												:reduce="item => item.sales_order_return_number"
												label="sales_order_return_number"></v-select>
                                        </div>
										<label>Invoice Date <b class="text-danger">*</b></label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input ref="invoice_date" type="text" class="form-control calendar-form" v-model="item.invoice_date">
										</div>
										
										<label>Due Date <b class="text-danger">*</b></label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input ref="payment_due_date" type="text" class="form-control calendar-form" name="payment_due_date" v-model="item.payment_due_date" :disabled="item.approved_by">
										</div>
										<label>Payment Release Date  </label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input ref="invoice_payment_release_date" type="text" class="form-control calendar-form" name="invoice_payment_release_date" v-model="item.invoice_payment_release_date" :disabled="item.approved_by">
										</div>
										<label>Invoice By <b class="text-danger">*</b></label>
										<input type="text" class="form-control mb-2" v-model="item.invoiced_by">
			
										<label>Invoice Onhold</label>
										<div class="custom-control custom-switch mb-3 mt-2">
										<input type="checkbox" name="invoice_onhold_checkbox" class="custom-control-input" id="invoice_onhold" v-model="item.invoice_onhold_checkbox">
											<label class="custom-control-label" for="invoice_onhold">
												<span class="badge" :class="item.invoice_onhold_checkbox ? 'badge-success' : 'badge-danger'">
													{{ item.invoice_onhold_checkbox ? 'Yes' : 'No'  }}
												</span>
											</label>
										</div>

										<div class="form-group">
											<label>Payment Schedule</label>
											<v-select 
												v-model="item.payment_schedule_id" 
												:reduce="item => item.payment_schedule_id" 
												label="payment_schedule_name" 
												placeholder="Select A Payment Schedule" 
												:options="payment_schedules"
											></v-select>
										</div>
												
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="far fa-question-circle"></i> Status</h4><hr>
											<div class="form-group">
												<label for="item.invoice_status">Invoice Status <b class="text-danger">*</b></label>
												<v-select v-model="item.invoice_status" :options="invoice_statuses" label="value" placeholder="value" :reduce="item => item.id" class="mb-2"></v-select>
											</div>
											<label>Approved Invoice</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input disabled type="checkbox" class="custom-control-input" id="approve_invoice" v-model="item.approved_invoice_checkbox">
												<label class="custom-control-label" for="approve_invoice">
													<span class="badge" :class="item.approved_invoice_checkbox ? 'badge-success' : 'badge-danger'">
														{{ item.approved_invoice_checkbox ? 'Approved' : 'Pending'  }}
													</span>
												</label>
											</div>
											<label>Approved Date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input  type="text" class="form-control" v-model="item.approved_date" readonly>
											</div>
											<label>Approved By</label>
											<input  type="text" class="form-control mb-2" v-model="item.approver" readonly>
											<label>Posted Invoice</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input disabled type="checkbox" class="custom-control-input" id="posted_invoice" v-model="item.posted_invoice_checkbox">
												<label class="custom-control-label" for="posted_invoice">
													<span class="badge" :class="item.posted_invoice_checkbox ? 'badge-success' : 'badge-danger'">
														{{ item.posted_invoice_checkbox ? 'Posted' : 'Not Posted'  }}
													</span>
												</label>
											</div>
											<label>Posting Date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input readonly type="text" class="form-control" name="sales_order_date" v-model="item.posting_date" >
											</div>
											
											<label>Posting By</label>
											<input readonly type="text" class="form-control mb-2" name="sales_order_date" v-model="item.poster" >
											<div>&zwnj;</div>
										</div>	
									</div>
									<div class="col-md-3">
										<div class="form-group">
						
											<h4 class="mt-4"><i class="fas fa-dollar-sign"></i> Sales Tax</h4><hr>
											<label>Sale Tax Group</label>
											<input  type="text" class="form-control mb-2" name="sales_tax_group" v-model="sales_order.sales_tax_group" :disabled="item.approved_by">
											<label>Tax Exempt Group</label>
											<input  type="text" class="form-control mb-2" name="tax_exempt_number" v-model="sales_order.tax_exempt_number" :disabled="item.approved_by">
											<label>Prices Include Sale Tax</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input type="checkbox" class="custom-control-input" id="sales_tax" name="prices_include_sales_tax_checkbox" v-model="item.prices_include_sales_tax_checkbox">
												<label class="custom-control-label" for="sales_tax">
													<span class="badge" :class="item.prices_include_sales_tax_checkbox ? 'badge-success' : 'badge-danger'">
														{{ item.prices_include_sales_tax_checkbox ? 'Yes' : 'No'  }}
													</span>
												</label>
											</div>
											<label>Ignore Calculated Sale Tax</label>
											<div class="custom-control custom-switch mb-2 mt-1">
											<input type="checkbox" class="custom-control-input" id="ignore_calculated_sales_tax" name="ignore_calculated_sales_tax_checkbox" v-model="item.ignore_calculated_sales_tax_checkbox">
												<label class="custom-control-label" for="ignore_calculated_sales_tax">
													<span class="badge" :class="item.ignore_calculated_sales_tax_checkbox ? 'badge-success' : 'badge-danger'">
														{{ item.ignore_calculated_sales_tax_checkbox ? 'Yes' : 'No'  }}
													</span>
												</label>
											</div>

											<h4 class="mt-3"><i class="fas fa-percentage"></i> Cash Discount</h4><hr>
											<label>Cash Discount</label>
											<input  type="number" class="form-control mb-2" name="cash_discount" v-model="item.cash_discount" :disabled="item.approved_by" >
											<label>Cash Discount Code</label>
											<input  type="text" class="form-control mb-2" name="cash_discount_code" v-model="item.cash_discount_code" :disabled="item.approved_by" >
										</div>
									</div>
									<div class="col-md-3">
										<h4><i class="fas fa-user"></i> Customer</h4><hr>
										<label>Customer <b class="text-danger">*</b></label>
										<v-select class="mb-2" v-model="item.customer_account" :reduce="item => item.customer_account" label="company" :options="customers"></v-select>
										<label>Invoice Account</label>
										<input class="form-control mb-2" readonly v-model="item.invoice_account">
										<label>Customer Number</label>
										<input name="customer_name" readonly v-model="item.customer_account" type="text" class="form-control mb-2">
										<label>Customer Contact ID <b class="text-danger">*</b></label>
										<input name="customer_contact_id" v-model="item.customer_contact_id" type="text" class="form-control mb-2" >
										<label>Customer Address <b class="text-danger">*</b></label>
										<textarea name="customer_address" v-model="item.customer_address" class="form-control mb-2" rows="3"></textarea>
									</div>
								</div>
							</div>

							<div class="tab-pane" id="financial">
		        				<div class="row">
		        		    		<div class="form-group col-sm-6">
										<h4><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
										<div class="form-group">
											<label>Cost Center <b class="text-danger">*</b></label>
											<v-select v-model="item.cost_center_id" :options="cost_centers" label="dimension_name" placeholder="Select Cost Center" :reduce="item => item.id" class="mb-2"></v-select> 
										</div>
										<div class="form-group">
											<label>Department <b class="text-danger">*</b></label>
											<v-select v-model="item.department_id" :options="departments" label="dimension_name" placeholder="Select Department" :reduce="item => item.id" class="mb-2"></v-select>
										</div>
										<div class="form-group">
											<label>Expense Purpose <b class="text-danger">*</b></label>
											<v-select v-model="item.expense_purpose_id" :options="expense_purposes" label="dimension_name" placeholder="Select Expense Purpose" :reduce="item => item.id" class="mb-2"></v-select>
										</div>
		        		    			<label>Posting Profile</label> 
		        		    			<v-select v-model="item.posting_profile" :reduce="item => item.id" label="posting_profile" :options="posting_profiles"></v-select>
		        		                <input name="posting_profile" v-model="item.posting_profile" type="hidden" class="form-control mb-2" :disabled="item.approved_by">
		        		    			
		        		    			<label>Document</label>
		        		    			<input type="text" class="form-control mb-2" name="document" v-model="item.document">
		        		    			<label>Document Status</label>
		        		    			<input type="text" class="form-control mb-2" name="document_status" v-model="item.document_status">

		        		    			<label>Accouting Distribution</label>
		        		                <input name="accouting_distribution" v-model="item.accouting_distribution" type="text" class="form-control mb-2" :disabled="item.approved_by">
		        		    		</div>
									<div class="form-group col-sm-6">
										<h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
		        		    			<label>Created By</label>
 										<input readonly v-model="item.creator" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Created On</label>
										<input readonly v-model="item.created_date" type="text" class="form-control mb-2">
		        		    		
		        		    			<label>Updated By</label>
										<input readonly v-model="item.updater" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Updated on</label>
		        		                <input readonly v-model="item.updated_date" type="text" class="form-control">
		        		    		</div>
		        				</div>
				        	</div>	

				        	<div class="tab-pane" id="delivery">
		        				<div class="row">
									<div class="col-md-6">
										<div class="form-group">
							    			<h4><i class="fas fa-file-invoice-dollar"></i> Payments</h4> <hr>
											<label>Payment ID</label>
											<input  type="text" class="form-control mb-2" name="payment_id" v-model="item.payment_id" :disabled="item.approved_by">									
											<label>Settlement Type <b class="text-danger">*</b></label>
											<v-select :disabled="item.approved_by ? true : false" v-model="item.settlement_type" :options="settlement_types" placeholder="Select Settlement Type" class="mb-2"></v-select>
							    			<label>Payment Method <b class="text-danger">*</b></label>
											<v-select v-model="item.method_of_payment" :options="payment_methods" :reduce='item => item.method_of_payment_id' label="method_of_payment" placeholder="Select Payment Method" class="mb-2"></v-select>
							    			<label>Terms of payment <b class="text-danger">*</b></label>
											<v-select :disabled="item.approved_by ? true : false" v-model="item.terms_of_payment" :options="terms_of_payments" :reduce='item => item.terms_of_payment' label="terms_of_payment" placeholder="Select Terms of Payment" class="mb-2"></v-select>

							    			<label>Payment Specification</label>
											<textarea
											:disabled="item.approved_by"
											name="payment_specification"
											v-model="item.payment_specification"
											class="form-control mb-2"
											row="5"
											></textarea>

											<label>Bank Account</label>
											<input name="bank_account" v-model="item.bank_account" class="form-control mb-2">

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
																				
											<label>Delivery Terms</label>
											<input name="delivery_term" v-model="item.delivery_terms" class="form-control mb-2" :disabled="item.approved_by">
		        		    	
							    			<label>Mode of Delivery</label>
											<input v-model="item.mode_of_delivery" name="mode_of_delivery" class="form-control mb-2" :disabled="item.approved_by">

											<label>Charges Group</label>
			        		                <input name="charges_group" v-model="item.charges_group" type="text" class="form-control mb-2" :disabled="item.approved_by">
							    	
										</div>
									</div>
		        				</div>
				        	</div>
    			        	<!-- <div class="tab-pane" id="tax">
    	        				<div class="row">
    								<div class="col-md-3">
    									<label>Total Sales (VAT Exclusive)</label>
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
    									<label>VATTable Sales</label>
    									<input type="number" min="0" step="any" name="vattable_sales" v-model="item.vattable_sales" class="form-control">
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
    									<label>Outstanding</label>
    									<input type="number" min="0" step="any" name="outstanding" v-model="item.outstanding" class="form-control">
    								</div>
    							</div>
    						</div> -->
				        	<div class="tab-pane" id="customer-invoice-lines">
			        		   <customer-invoice-lines 
							   	@newLines="getNewlines"
								:lines="customer_invoice_lines"
								:ci="item"
								:customers="customers"
								:products="products"
								:show-confirm-button="showConfirmButton"
								@success="fetch"
								:variants="variants"
								:clients="clients"
								:cost_center_value_code="item.cost_center_id"
								:department_value_code="item.department_id"
								:expense_purpose_value_code="item.expense_purpose_id"
								:departments="departments"
								:expense_purposes="expense_purposes"
								:specifications="specifications"
								:services="services"
								:procurements="procurements"
								:cost_centers="cost_centers"
								:charges_on_lines="charges_on_lines"
								:discount_on_lines="discount_on_lines"
								>
							   </customer-invoice-lines>

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
						                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#for_approval_payment" data-toggle="tab">For Approval</a></li>
						                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#approved_payment" data-toggle="tab">Approved</a></li>
						                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#posted_payment" data-toggle="tab">Posted</a></li>
						                    </ul>
						                </div>
						                <div class="card-body">
						                    <div class="tab-content">
						                        <div class="tab-pane show active" id="for_approval_payment">
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-1"
						                                :fetch-url="customerPaymentsApproval"
						                            ></customer-payment-table>
						                        </div>
						                        <div class="tab-pane" id="approved_payment">
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-2"
						                                :fetch-url="customerPaymentsApproved"
						                                :is-approved="true"
						                            ></customer-payment-table>
						                        </div>
						                        <div class="tab-pane" id="posted_payment">
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-3"
						                                :fetch-url="customerPaymentsPosted"
						                                :is-posted="true"
						                            ></customer-payment-table>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </div>
				        	</div>
				        	<div class="tab-pane" id="subsidiary-ledger">
				        		<subsidiary-view
				        			first-tab-name="Sales Delivery Receipt Subsidiary"
				        			second-tab-name="Customer Payment Subsidiary"
				        			:clients="clients"
					        		:invoice-approval-url="invoiceApprovalUrl"
									:customer-payment-url="customerPaymentUrl"
									:general-journal-url="generalJournalUrl"
				        		></subsidiary-view>
				        	</div>
				        	<div class="tab-pane" id="credit">
			        			<div class="col-12">
						            <div class="card">
						                <div class="card-header p-2">
						                    <ul class="nav nav-pills">
						                        <li class="nav-item"><a class="nav-link active" href="#payment-schedules" data-toggle="tab">Payment Schedule</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#interest-calculations" data-toggle="tab">Interest Calculation</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#interest-notes" data-toggle="tab">Interest Note</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#interest-setups" data-toggle="tab">Interest Setup</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#interest-adjustments" data-toggle="tab">Interest Adjustments</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#collections" data-toggle="tab">Collections</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#bills-of-exchange" data-toggle="tab">Bills Of Exchange</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#bills-of-exchange-adjustment" data-toggle="tab">Bills Of Exchange Adjustment</a></li>
						                    </ul>
						                </div>
						                <div class="card-body">
						                    <div class="tab-content">
						                        <div class="tab-pane show active" id="payment-schedules">
						                           	<payment-schedule-table
						                           		:clients="clients"
						                           		:fetch-url="paymentScheduleUrl"
						                           	></payment-schedule-table>
						                        </div>
						                        <div class="tab-pane" id="interest-calculations">
						                           	<interest-calculation-table
						                           		:clients="clients"
						                           		:fetch-url="interestCalculationUrl"
						                           	></interest-calculation-table>
						                        </div>
						                        <div class="tab-pane" id="interest-notes">
						                           	<interest-note-table
						                           		:clients="clients"
						                           		:fetch-url="interestNoteUrl"
						                           	></interest-note-table>
						                        </div>
						                        <div class="tab-pane" id="interest-setups">
						                           	<interest-setup-table
						                           		:clients="clients"
						                           		:fetch-url="interestSetupUrl"
						                           	></interest-setup-table>
						                        </div>
						                        <div class="tab-pane" id="interest-adjustments">
						                           	<interest-adjustment-table
						                           		:clients="clients"
						                           		:fetch-url="interestAdjustmentUrl"
						                           	></interest-adjustment-table>
						                        </div>
						                        <div class="tab-pane" id="collections">
						                           	<collection-table
						                           		:clients="clients"
						                           		:fetch-url="collectionUrl"
						                           	></collection-table>
						                        </div>
						                        <div class="tab-pane" id="bills-of-exchange">
						                           	<bills-exchange-table
						                           		:clients="clients"
						                           		:fetch-url="boeUrl"
						                           	></bills-exchange-table>
						                        </div>
						                        <div class="tab-pane" id="bills-of-exchange-adjustment">
						                           	<bills-exchange-table
						                           		:clients="clients"
						                           		:fetch-url="boeAdjustmentUrl"
						                           	></bills-exchange-table>
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
			
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';

	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	import Datepicker from 'Components/inputs/Datepicker.vue';
	import Input from 'Components/inputs/Input.vue';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';
	import { ModelListSelect } from 'vue-search-select'

	import SalesOrderTemplate from './sales-orders/SalesOrder.vue';
    import DataTable from 'Components/tables/StaticDataTable.vue';

	import InvoiceCustomerLines from './SalesDeliveryReceiptLineView.vue';
	import Vselect from "vue-select";

	export default {

		props: {
			salesOrder: {
				default() { return {} },
				type: Object
			},

			showConfirmButton: {
				default: false,
				type: Boolean
			},
			generateInvoicePaymentUrl: {
				default: null,
				type: String
			},

			customerPaymentsApproval: String,
			customerPaymentsApproved: String,
			customerPaymentsPosted: String,

			invoiceApprovalUrl: String,
			customerPaymentUrl: String,
			generalJournalUrl: String,
			printUrl: String,

			paymentScheduleUrl: String,
			interestSetupUrl: String,
			interestCalculationUrl: String,
			interestNoteUrl: String,
			interestScheduleUrl: String,
			interestAdjustmentUrl: String,

			collectionUrl: String,
			boeUrl: String,
			boeAdjustmentUrl: String,
		},

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
            'sales-order-template': SalesOrderTemplate,
            'data-table': DataTable,
            'date-picker' : Datepicker,
            'input-type' : Input,
			'customer-invoice-lines' : InvoiceCustomerLines,
			ModelListSelect,
			'v-select' : Vselect,
		},

		watch: {
			'item.customer_account'(customer_account) {
				if (customer_account) {
					let customer = Object.assign({}, this.customers.find(customer => customer.customer_account == customer_account));
					this.item.customer_name = customer.fullname;
					this.item.customer_contact_id = customer.fullname;
					this.item.customer_address = `${customer.shipping_street}, ${customer.shipping_city}, ${customer.shipping_province}, ${customer.shipping_postal_code}, ${customer.shipping_country}`;
					this.item.invoice_account = customer_account;
				} else {
					this.item.customer_name = null;
					this.item.invoice_account = null;
					this.item.customer_contact_id = null;
					this.item.customer_address = null;
				}
			},
			'item.sales_order_number'(value) {
				if (value) {
					if (this.original_sales_order_number != value) {
						let sales_order = Object.assign({}, this.sales_orders.find(po => po.sales_order_number == value));
						if (sales_order) {
							let sales_delivery_receipt_number = this.item.sales_delivery_receipt_number;
							this.item = Object.assign(this.item, sales_order);
							this.item.sales_delivery_receipt_number = sales_delivery_receipt_number;
							this.item.payment_due_date = this.item.due_date
							this.item.sales_order_lines.forEach((line, index) => {
								let product = this.products.find(product => line.item_number == product.item_number);
								if (product) {
									line['product'] = product;
								}
								line.is_new = true;
								line.customer_invoice_line_number = this.generateLineCodeV2('CI', index);
								line.sales_delivery_receipt_number = this.item.invoice_account;
								line.sales_delivery_receipt_number = this.item.sales_delivery_receipt_number;
								line.customer_account = this.item.customer_account;
								line.invoice_account = this.item.invoice_account;
								line.customer_name = this.item.customer_name;
								delete line.id;
							});
							this.customer_invoice_lines = this.item.sales_order_lines;
							this.item.transaction_type = 'Sales';
							this.item.invoice_status = 'New';
						}
					}
				}else {
					this.item = {};
					this.generateID();
				}
				this.getActiveCode();
			},
			'client_id'(value){
				this.item.client_id = value;
				this.getActiveCode();
			},
		},

		data() {
			return {
				
				item: {
					total_sales_vat_exclusive: 0,
					less_discount: 0,
					add_charge: 0,
					add_vat: 0,
					total_sales_vat_inclusive: 0,
					amount_due: 0,
				},
				client_id : null,
				labelBtn: 'Save Changes',

				created_by : null,
				updated_by : null,
				approved_by: null,

				users: [],
				customers: [],
				products: [],
				variants: [],
				payment_methods: [],
				terms_of_payments: [],
				cost_centers: [],
				departments: [],
				expense_purposes: [],
				specifications: [],
				sales_order: {},
				sales_orders: [],
				sales_order_returns: [],
				is_creation: true,
				invoice_statuses: [],
				procurements: [],
				services: [],

				customer_invoice_lines: [],

				charges_on_lines : [],
				charges_on_header : [],
				discount_on_lines : [],
				discount_on_header : [],
			
				showOnlyData: {
					account_type: true,
					account: true,
					credit: true,
					debit: true,
					description: true,
					offset_account: true,
					offset_account_type: true,
					invoice: true,
				},

				clients: [],
				posting_profiles: [],

				invoice_by : null,
				clients: [],
				settlement_types : ['None', 'Open Transaction', 'Designated Transactions'],
				payment_schedules: [],
			}
		},

		computed: {
            headers() {
                let array = [
                    { text: 'Invoice', value: null },
                    { text: 'Payment Release Date', value: null },
                    { text: 'Payment ID', value: null },
                    { text: 'Payment Status', value: null },
                    { text: 'Method of payment', value: null },
                    { text: 'Terms of payment', value: null },
                    { text: 'Bank Account', value: null },
                    // { text: 'TOTAL QUANTITY', value: null },
                    // { text: 'LINE AMOUNT', value: null },
                    // { text: 'PURCHASE ORDER NUMBER', value: null },
                    { text: 'ACTION', value: null },
                ];

                return array;

            },

            total_quantity() {
                let result = 0;
                let itemLines = this.customer_invoice_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        result += parseInt(line.quantity);
                    });
                }

                return result;
            },

            total_vattable_sales_vat_exclusive() {
                let result = 0;
                let itemLines = this.customer_invoice_lines;

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
                let itemLines = this.customer_invoice_lines;

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
                let itemLines = this.customer_invoice_lines;

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
                let itemLines = this.customer_invoice_lines;

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
                let itemLines = this.customer_invoice_lines;

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

            hasApprovedLine() {
            	return !_.isEmpty(_.find(this.item.customer_invoice_lines, { is_approved : true }))
            },

            showCreditTab() {

				let payment_method = this.payment_methods.find(method => {
					return method.method_of_payment_id == this.item.method_of_payment;
				});

				if(payment_method) {
					if(payment_method.method_of_payment == "Credit") {
						return true;
					}
				}

				return false;
			},
		},


		mounted() {

			flatpickr(this.$refs.payment_due_date)
			flatpickr(this.$refs.invoice_date);
			flatpickr(this.$refs.invoice_payment_release_date)

			this.invoice_statuses = [
				{ value: 'New' },
				{ value: 'Pending' },
				{ value: 'Promissory Note' },
				{ value: 'Fully Paid' },
				{ value: 'Delivered' }
			];
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;

				if(!data.item) {
					this.is_creation = true;
					this.item = data.sales_order ?? {};
					this.item.customer_invoice_lines = [];
					if (!this.item.transaction_type) {
						this.item.transaction_type = 'Sales';
					}
					this.item.invoice_status = 'New';
				} else {
					this.is_creation = false;
				}

				this.original_sales_order_number = this.item.sales_order_number;

				if(!this.item.sales_delivery_receipt_number) {
					this.generateID();
				}

				this.customers = data.customers ? data.customers : this.customers;
				this.sales_orders = data.sales_orders ?? this.sales_orders;
				this.sales_order_returns = data.sales_order_returns ?? this.sales_order_returns;
				this.clients = data.clients ?? this.clients;

				this.sales_order = data.sales_order ? data.sales_order : this.sales_order;
				this.users = data.users ? data.users : this.users;
				this.products = data.products ? data.products : this.products;
				this.variants = data.variants ?? this.variants;
				this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
			
				this.customer_invoice_lines = data.customer_invoice_lines ? data.customer_invoice_lines : this.item.customer_invoice_lines;
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.departments = data.departments ? data.departments : this.departments;
				this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
				this.posting_profiles = data.posting_profiles ? data.posting_profiles : this.posting_profiles;
				this.specifications = data.specifications ? data.specifications : this.specifications;
				this.services = data.services ? data.services : this.services;
				this.procurements = data.procurements ? data.procurements : this.procurements;

				this.charges_on_lines = data.charges_on_lines ? data.charges_on_lines : this.charges_on_lines;
				this.charges_on_header = data.charges_on_header ? data.charges_on_header : this.charges_on_header;
				this.discount_on_lines = data.discount_on_lines ? data.discount_on_lines : this.discount_on_lines;
				this.discount_on_header = data.discount_on_header ? data.discount_on_header : this.discount_on_header;

				this.clients = data.clients ?? this.clients;
				this.payment_schedules = data.payment_schedules ?? this.payment_schedules;
			},

			getNewlines(value) {
				this.item.customer_invoice_lines = value;
			}, 

			confirmedThisInvoice() {
				var $this = this;
				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you want to approved this Invoice?',
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then((result) => {
				  if (result.value) {
				    axios.post($this.item.confirmUrl)
			    	.then(response => {
					    $this.$notification.show(response.data.message, 'Success')
					    $this.fetch();
			    	}).catch(error => {
			    		$this.$notification.show(error.response.data.errors['On hold'][0], 'On Hold', 'error');
			    	})
				  }
				})
			},

			postThisInvoice() {
				var $this = this;
				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you want to mark this invoice as posted?',
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then((result) => {
				  if (result.value) {
	                $this.$loading.show(true);
				    axios.post($this.item.postCustomerInvoiceUrl)
			    	.then(response => {
					    $this.$notification.show(response.data.message, 'Success')
					    window.location.href = response.data.redirect;
		                $this.$loading.show(false);
			    	}).catch(error => {
			    		$this.$loading.show(false);
			    	})
				  }
				})
			},

			createCustomerInvoicePayment() {
				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you to create invoice payment?',
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then(_ => {
					window.location.href = this.generateInvoicePaymentUrl;
				})
			},
			
			generateLineCodeV2(prefix, index = 1) {
				var date = new Date();
				var time = Math.round(date.getTime() / 1000) + index;
				return prefix + '-' + date.getFullYear().toString() + ("0" + (date.getMonth() + 1)).slice(-2) + date.getDate().toString()   +'-'+ time.toString();
			},

			generateID() {
				var date = new Date();
				var time = Math.round(date.getTime() / 1000);	
				this.item.sales_delivery_receipt_number = date.getDate().toString() + date.getMonth().toString() + date.getFullYear().toString() +'-'+ time.toString();
				this.item.sales_delivery_receipt_number += "-" + Math.random().toString(36).substring(2, 6);
			},

			getActiveCode() {
				let id = this.item.client_id;
				if(id) {
					let client = this.clients.filter(item => item.id == id)[0];
					if(client.code) {	
						this.item.sales_delivery_receipt_number = client.code;
					}else {
						this.generateID();
					}	
				}else {
					this.generateID();
				}
			}
			
		},

		mixins: [ CrudMixin ],
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