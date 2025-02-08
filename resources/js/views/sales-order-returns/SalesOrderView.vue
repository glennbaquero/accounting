<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
			<card>
				<template v-slot:header>
					Sales Order Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn-primary btn-sm">Save Changes</action-button>
						<button type="button" class="btn btn-success btn-sm" @click="confirmedSO" :disabled="disableConfirmButton">Confirm SO</button>
						<a :href="item.customerInvoiceUrl" class="btn btn-success btn-sm" :class="disableGenerateInvoiceButton ? 'disabled' : ''">Generate Invoice</a>
					</div>
				</template>
				<div class="row mb-3">
					<div class="col-md-12">
						<template v-if="item.id">
							<a :href='printUrl' type='button'  class="btn btn-success" target="_blank">Print Sales Order</a>
						</template>
					</div>
				</div>
				<div class="card">
				    <div class="card-header">
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<!-- <li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab">General</a></li> -->
									<li class="nav-item"><a class="nav-link active" href="#sales_order" data-toggle="tab">Sales Order</a></li>
									<!-- <li class="nav-item"><a class="nav-link" href="#customer" data-toggle="tab">Customer Information</a></li> -->
									<li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
									<li class="nav-item"><a class="nav-link" href="#delivery" data-toggle="tab">Delivery and Charges</a></li>
									<!-- <li class="nav-item"><a class="nav-link" href="#tax" data-toggle="tab">VAT</a></li> -->
									<!-- <li class="nav-item"><a class="nav-link" href="#price_discount" data-toggle="tab">Totals</a></li> -->
									<li class="nav-item"><a class="nav-link" href="#sales_order_lines" data-toggle="tab">Sales Order Lines</a></li>
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
				        	<div class="tab-pane show active" id="sales_order">
				        		<div class="row">
				        			<div class="col-md-4">
			        		    		<div class="form-group">
											<h4 class="mb-2"><i class="fas fa-tags"></i> Sales Order</h4><hr>
			        		    			<label>Sales Order Return Number</label>
			        		    			<input type="text" name="sales_order_return_number" v-model="item.sales_order_return_number" class="form-control mb-2" readonly>
			        		    			<label>Invoice Number</label>
			        		    			<input type="text" v-model="item.customer_invoice_number" class="form-control mb-2" readonly>
											<label>Sales Order Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="sales_order_date" type="text" class="form-control calendar-form" name="sales_order_date" v-model="item.sales_order_date" readonly>
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
			        		    			
											<label>Sold By</label>
						                	<input name="sold_by" v-model="item.sold_by" class="form-control">	
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
											<label>Sales Type <b class="text-danger">*</b></label>
											<v-select v-model="item.sales_type" :options="sale_types" placeholder="Select Sales Type" class="mb-2"></v-select>
			        		    			<label>Sales Order Status <b class="text-danger">*</b></label>
											<v-select v-model="item.sales_order_status" :options="sale_order_statuses" placeholder="Select Sales Order Status" class="mb-2"></v-select>
											<label>Confirmed By</label>
	 										<input readonly v-model="item.confirmed_user" type="text" class="form-control mb-2">
											<label>Confirmed Date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input readonly name="approval_status_date" v-model="item.approval_status_date" type="text" class="form-control">
											</div>
											<!-- <label>Confirmed By</label>
											<div class="input-group mb-2">
			        		    				<input type="text" class="form-control" v-model="item.confirmed_date" readonly>
											</div>
											<label>Confirmed Date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input type="text" class="form-control" v-model="item.confirmed_date" readonly>
											</div> -->
											<label>Accounting date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
			        		    				<input type="text" class="form-control" v-model="item.accounting_date" readonly>
											</div>
			        		    		</div>
				        			</div>
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-user"></i> Customer</h4><hr>
			        		    			<label>Customer Account <b class="text-danger">*</b></label>
											<v-select class="mb-2" v-model="item.customer_account" :reduce="item => item.customer_account" label="company" placeholder="Select Customer" :options="customers"></v-select>
											<label>Invoice Account</label>
											<input class="form-control mb-2" readonly v-model="item.invoice_account">
							    			<label>Customer Number</label>
							                <input name="customer_name" readonly v-model="item.customer_account" type="text" class="form-control mb-2">
							    			<label>Customer Contact ID</label>
							                <input name="customer_contact_id" v-model="item.customer_contact_id" type="text" class="form-control mb-2" >
											<label>Customer Address</label>
							                <textarea name="customer_address" v-model="item.customer_address" class="form-control mb-2" rows="3"></textarea>
										</div>
									</div>
		        				</div>
				        	</div>

				        	<div class="tab-pane" id="financial">
		        				<div class="row">
		        		    		<div class="form-group col-sm-6">
										<h4><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
		        		    			<label>Cost Center <b class="text-danger">*</b></label>
										<v-select v-model="item.cost_center_id" :options="cost_centers" label="dimension_name" placeholder="Select Cost Center" :reduce="item => item.id" class="mb-2"></v-select>	        		    	
		        		    			<label>Department <b class="text-danger">*</b></label>
										<v-select v-model="item.department_id" :options="departments" label="dimension_name" placeholder="Select Department" :reduce="item => item.id" class="mb-2"></v-select>
										<label>Expense Purpose <b class="text-danger">*</b></label>
										<v-select v-model="item.expense_purpose_id" :options="expense_purposes" label="dimension_name" placeholder="Select Expense Purpose" :reduce="item => item.id" class="mb-2"></v-select>
		        		    			<label>Posting Profile</label>
		        		    			<v-select v-model="item.posting_profile" :reduce="item => item.id" label="posting_profile" :options="posting_profiles"></v-select>
		        		                <input name="posting_profile" v-model="item.posting_profile" type="hidden" class="form-control mb-2">
		        		    	
		        		    			<label>Accouting Distribution</label>
		        		                <input name="accouting_distribution" v-model="item.accounting_distribution" type="text" class="form-control mb-2">
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
											<v-select v-model="item.settlement_type" :options="settlement_types"  placeholder="Select Settlement Type" class="mb-2"></v-select>
							    			<label>Payment Method <b class="text-danger">*</b></label>
											<v-select v-model="item.method_of_payment" :options="payment_methods" label="name" placeholder="Select Payment Method" :reduce="item => item.id" class="mb-2"></v-select>							    		
							    			<label>Terms of Payment <b class="text-danger">*</b></label>
											<v-select v-model="item.terms_of_payment" :options="terms_of_payments" label="terms_of_payment" placeholder="Select Terms of Payment" :reduce="item => item.id" class="mb-2"></v-select>
							    			<label>Payment Specification</label>
											<textarea
											name="payment_specification"f
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
																				
											<!-- <label>Delivery Contact</label>
											<input name="delivery_contact" v-model="item.delivery_contact" class="form-control mb-2">

											<label>Delivery Address</label>
											<input  name="delivery_address" v-model="item.delivery_address" class="form-control mb-2"> -->

											<label>Delivery Terms</label>
											<input name="delivery_terms" v-model="item.delivery_terms" class="form-control mb-2">
		        		    	
							    			<label>Mode of Delivery</label>
											<input v-model="item.mode_of_delivery" name="mode_of_delivery" class="form-control mb-2">

											<label>Charges Group</label>
			        		                <input name="charges_group" v-model="item.charges_group" type="text" class="form-control mb-2">
							    	
										</div>
									</div>
		        				</div>
				        	</div>
				        	<!-- <div class="tab-pane" id="tax">
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

				        	<div class="tab-pane" id="sales_order_lines">
								<sales-order-lines
									@newLines="getNewlines"
									:so="item"
									:lines="sales_order_lines"
									:products="products"
									:variants="variants"
									:clients="clients"
									:cost_center_value_code="item.cost_center_id"
									:department_value_code="item.department_id"
									:expense_purpose_value_code="item.expense_purpose_id"
									:departments="departments"
									:expense_purposes="expense_purposes"
									:cost_centers="cost_centers"
									:specifications="specifications"
									:services="services"
									:procurements="procurements"
									:charges_on_lines="filtered_charges_on_lines"
									:discount_on_lines="filtered_discount_on_lines"
									>
								</sales-order-lines>
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
						                            <customer-invoice-table 
						                            :clients="clients"
						                            ref="table-1"
						                            :fetch-url="customerInvoicesApproval"
						                            ></customer-invoice-table>
						                        </div>
						                        <div class="tab-pane" id="approved">
						                            <customer-invoice-table 
						                            :clients="clients"
						                            ref="table-2"
						                            :fetch-url="customerInvoicesApproved"
						                            ></customer-invoice-table>
						                        </div>
						                        <div class="tab-pane" id="posted">
						                            <customer-invoice-table 
						                            :clients="clients"
						                            ref="table-3"
						                            :fetch-url="customerInvoicesPosted"
						                            ></customer-invoice-table>
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
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-4"
						                                :fetch-url="customerPaymentsApproval"
						                            ></customer-payment-table>
						                        </div>
						                        <div class="tab-pane" id="approved_payment">
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-5"
						                                :fetch-url="customerPaymentsApproved"
						                                :is-approved="true"
						                            ></customer-payment-table>
						                        </div>
						                        <div class="tab-pane" id="posted_payment">
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-6"
						                                :fetch-url="customerPaymentsPosted"
						                                :is-posted="true"
						                            ></customer-payment-table>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </div>
				        	</div>
							
				        </div>
				    </div>

				</div>

				<loader 
				:loading="loading">
				</loader>
			</card>
			
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';

	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
    import DataTable from 'Components/tables/StaticDataTable.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SalesOrderLineView from './SalesOrderLineView.vue';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';
	import { ModelListSelect } from 'vue-search-select'
	import Vselect from "vue-select";

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

			customerInvoicesApproval: String,
			customerInvoicesApproved: String,
			customerInvoicesPosted: String,
			customerPaymentsApproval: String,
			customerPaymentsApproved: String,
			customerPaymentsPosted: String,
			printUrl: String,
		},

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
            'data-table': DataTable,
			'action-button': ActionButton,
			'sales-order-lines' : SalesOrderLineView,
			ModelListSelect,
			'v-select' : Vselect,
		},

		data() {
			return {
				created_by : '',
				updated_by : '',
				client_id : '',
				params : [],
				item: {
					sales_order_number: null,
					sales_order_status: 'Open Order',
					document_status: 'None',
					approval_status: 'Draft',
					sales_type: 'Standard SO',
					settlement_type: 'None',
					is_already_confirmed: false,
					hasExistingInvoice: false,
					total_sales_vat_exclusive: 0,
					less_discount: 0,
					add_charge: 0,
					add_vat: 0,
					total_sales_vat_inclusive: 0,
					amount_due: 0,
				},
				users: [],
				customers: [],
				products: [],
				variants: [],
				payment_methods: [],
				terms_of_payments: [],
				cost_centers: [],
				departments: [],
				expense_purposes: [],

				sales_order_line: [],
				sales_order_lines: [],
                clients: [],
                posting_profiles: [],
                specifications: [],
                services: [],
                procurements: [],
				charges_on_lines : [],
				charges_on_header : [],
				discount_on_lines : [],
				discount_on_header : [],

				sale_types : ['Standard SO', 'Contract SO', 'Blanket SO', 'Planned SO'],
				sale_order_statuses : ['Open Order', 'Delivered', 'Invoiced', 'Canceled'],
				settlement_types : ['None', 'Open Transactions', 'Designated Transactions'],
			}
		},


        computed: {
            disableConfirmButton() {
            	if(this.showConfirmButton) {
	            	return this.item.is_already_confirmed;
            	}

            	return true;
            },

            disableGenerateInvoiceButton() {
            	if(this.showConfirmButton) {
            		if(this.item.is_already_confirmed) {
		            	return this.item.hasExistingInvoice;
            		}
            	}

            	return true;
            },
            total_quantity() {
                let result = 0;
                let itemLines = this.sales_order_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        result += parseInt(line.quantity);
                    });
                }

                return result;
            },

            total_vattable_sales_vat_exclusive() {
                let result = 0;
                let itemLines = this.sales_order_lines;

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
                let itemLines = this.sales_order_lines;

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
                let itemLines = this.sales_order_lines;

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
                let itemLines = this.sales_order_lines;

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
                let itemLines = this.sales_order_lines;

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

            filtered_charges_on_lines() {
            	return _.filter(this.charges_on_lines, (charge) => { return moment(this.item.sales_order_date).isBetween(charge.valid_from, charge.valid_to) });
            },

            filtered_discount_on_lines() {
            	return _.filter(this.discount_on_lines, (discount) => { return moment(this.item.sales_order_date).isBetween(discount.valid_from, discount.valid_to) });
            },

            filtered_charges_on_header() {
            	return _.filter(this.charges_on_header, (charge) => { return moment(this.item.sales_order_date).isBetween(charge.valid_from, charge.valid_to) });
            },

            filtered_discount_on_header() {
            	return _.filter(this.discount_on_header, (discount) => { return moment(this.item.sales_order_date).isBetween(discount.valid_from, discount.valid_to) });
            },
        },

		mixins: [ CrudMixin ],


		watch: {
			'item.customer_account'(val) {
				_.each(this.customers, (customer) => {
					if(customer.customer_account == val) {
						this.item.invoice_account = customer.customer_account;
						this.item.customer_name = customer.fullname;
						this.item.customer_address = `${customer.shipping_street}, ${customer.shipping_city}, ${customer.shipping_province}, ${customer.shipping_postal_code}, ${customer.shipping_country}`;
						this.item.customer_contact_id = customer.fullname;
					} else {
						this.item.invoice_account = null;
						this.item.customer_name = null;
						this.item.customer_address = null;
						this.item.customer_contact_id = null;
					}
				})
			},

			'client_id'(value){
				this.item.client_id = value;
				this.getActiveCode();
			},

			'item.created_by'(val) {
				this.created_by = val.fullname;
			},

		},
		mounted() {

			if(!this.item.sales_order_return_number) {
				this.generateID();
			}

			flatpickr(this.$refs.sales_order_date)
			flatpickr(this.$refs.delivery_date_pickr)
			flatpickr(this.$refs.due_date)
			// flatpickr(this.$refs.confirmed_date)
			// flatpickr(this.$refs.accounting_date)
			// flatpickr(this.$refs.delivery_date_tbl)
			// flatpickr(this.$refs.approval_status_date)
		},

		methods: {
			fetchSuccess(data) {


				this.item = data.item ? data.item : this.item;
				this.users = data.users ? data.users : this.users;
				this.customers = data.customers ? data.customers : this.customers;
				this.products = data.products ? data.products : this.products;
				this.variants = data.variants ?? this.variants;
				this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.sales_order_lines = data.sales_order_lines ? data.sales_order_lines : this.sales_order_lines;
				
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

				if(!this.item.id) {
					this.client_id = this.clients[0].id;	
				}
			},

			addLine() {
				var data = {
					sales_category: this.$refs.sales_category.value,
					line_number: this.sales_order_line.length + 1,
					item_number: this.item.item_number.item_number,
					product_name: this.item.item_number.name,
					size: this.$refs.size.value,
					color: this.$refs.color.value,
					quantity: this.item.quantity,
					sales_unit: this.item.item_number.unit_price * this.$refs.quantity.value,
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

				this.sales_order_line.push(data);	
				this.sales_order_lines = JSON.stringify(this.sales_order_line);
			},

			removeLine(key) {
				this.sales_order_line.splice(key, 1);
				this.sales_order_lines = JSON.stringify(this.sales_order_line);
			},

			confirmedSO() {
				var $this = this;

				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you want to confirm this SO?',
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

			getNewlines(value) {
				this.item.sales_order_lines = value;
			},

			getActiveCode() {
				let id = this.item.client_id;
				if(id) {
					let client = this.clients.filter(item => item.id == id)[0];
					if(client.code) {	
						this.item.sales_order_return_number = client.code;
					}else {
						this.generateID();
					}	
				}
			},

			generateID() {
				var date = new Date();
				var time = Math.round(date.getTime() / 1000);	
				this.item.sales_order_return_number = date.getDate().toString() + date.getMonth().toString() + date.getFullYear().toString() +'-'+ time.toString();
				this.item.sales_order_return_number += "-" + Math.random().toString(36).substring(2, 6);
			}
		}
	}
</script>