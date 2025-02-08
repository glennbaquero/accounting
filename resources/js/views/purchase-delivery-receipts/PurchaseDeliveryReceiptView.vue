<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
			<card>
				<template v-slot:header>
					Purchase Delivery Receipt Information
					<div class="float-right">
						<!-- :disabled="loading || item.approved_by" -->
						<action-button type="submit"  class="btn-primary btn-sm">Save Changes</action-button>
						<button type="button" class="btn btn-success btn-sm" @click="confirmedThisInvoice" :disabled="item.approved_by || !item.id || !showConfirmButton || !hasApprovedLine">Approve Invoice</button>
						<button type="button" class="btn btn-success btn-sm" :disabled="(!item.approved_by && item.id) || item.posting_date || !item.id || item.is_cancelled" @click="postedThisInvoice">POST</button>
						<button type="button" class="btn btn-danger btn-sm" :disabled="(!item.approved_by && item.id) || item.posting_date || !item.id || item.is_cancelled" @click="cancelThisInvoice">Cancel</button>
						<button type="button" class="btn btn-success btn-sm" :disabled="!item.approved_by"  @click="createVendorInvoicePayment">Generate Payment</button>
						<a :href="item.purchaseOrderShowUrl" class="btn btn-secondary btn-sm" target="_blank">Show Purchase Order</a>
					</div>
				</template>
				<div class="row mb-3">
					<div class="col-md-12">
						<a :href='printUrl' type='button'  class="btn btn-success" target="_blank">Print Purchase Delivery Receipt</a>
					</div>
				</div>

				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#vendor-invoice" data-toggle="tab">Purchase Delivery Receipt</a></li>
									<li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
									<li class="nav-item"><a class="nav-link" href="#delivery" data-toggle="tab">Delivery and Charges</a></li>
									<!-- <li class="nav-item"><a class="nav-link" href="#tax" data-toggle="tab">VAT</a></li> -->
									<li class="nav-item"><a class="nav-link" href="#vendor-invoice-lines" data-toggle="tab">Purchase Delivery Receipt Lines</a></li>
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
				        	<div class="tab-pane show active" id="vendor-invoice">
								<div class="row">	
									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-file-invoice-dollar"></i> Purchase Delivery Receipt</h4><hr>
										</div>
										<label>Purchase Delivery Receipt Number</label>
										<input readonly class="form-control mb-2" v-model="item.purchase_delivery_receipt_number">
									    <!-- <div class="form-group">
									        <label>Transaction Type <b class="text-danger">*</b></label>
											<v-select v-model="item.transaction_type" :options="transaction_types" placeholder="Select Transaction Type"></v-select>
									    </div> -->
                                        <div class="form-group">
                                            <label for="vendor_invoice_id">Purchase Order Number</label>
											<v-select class="mb-2" 
												:disabled="item.created_at ? true : false"
												v-model="item.purchase_order_number" 
												:options="purchase_orders"
												:reduce="item => item.purchase_order_number"
												placeholder="Select Purchase Order"
												label="purchase_order_number">
											</v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="vendor_invoice_id">Purchase Order Return Number</label>
											<v-select class="mb-2" 
												v-model="item.purchase_order_return_number" 
												:disabled="item.created_at ? true : false"
												:options="purchase_order_returns"
												:reduce="item => item.purchase_order_return_number"
												placeholder="Select Purchase Order Return"
												label="purchase_order_return_number">
											</v-select>
                                        </div>

										<!-- Enable this -->
										<label>Invoice Date</label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input ref="invoice_date" type="text" class="form-control calendar-form" v-model="item.invoice_date">
										</div>
										<label>Delivery Date <b class="text-danger">*</b></label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input ref="delivery_date" type="text" class="form-control calendar-form" name="delivery_date" v-model="item.delivery_date">
										</div>
										<label>Payment Due Date <b class="text-danger">*</b></label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input ref="payment_due_date"  type="text" class="form-control calendar-form" name="payment_due_date" v-model="item.payment_due_date">
										</div>
										<label>Payment Release Date</label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<input  type="text" class="form-control" name="invoice_payment_release_date" v-model="item.invoice_payment_release_date" readonly>
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
												<v-select v-model="item.invoice_status" :options="invoice_statuses" :reduce="item => item.value" label="value" placeholder="Select Invoice Status"></v-select>
											</div>

											<label>Approved Invoice</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input disabled type="checkbox" class="custom-control-input" id="approve_invoice" v-model="item.approved_invoice_checkbox">
												<label class="custom-control-label" for="approve_invoice">
													<span class="badge" :class="item.approved_date ? 'badge-success' : 'badge-danger'">
														{{ item.approved_date ? 'Approved' : 'Pending'  }}
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
											<input  type="text" class="form-control mb-2" v-model="item.approved_by_fullname" readonly>
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
												<input readonly type="text" class="form-control" name="purchase_order_date" v-model="item.posting_date" >
											</div>
											
											<label>Posting By</label>
											<input readonly type="text" class="form-control mb-2" name="purchase_order_date" v-model="item.posted_by_fullname" >
											<div>&zwnj;</div>

											<label>Cancelled Invoice</label>
											<div class="custom-control custom-switch mb-3 mt-2">
											<input disabled type="checkbox" class="custom-control-input" id="posted_invoice" v-model="item.is_cancelled">
												<label class="custom-control-label" for="posted_invoice">
													<span class="badge" :class="item.is_cancelled ? 'badge-success' : 'badge-danger'">
														{{ item.is_cancelled ? 'Cancelled' : 'Not Cancelled'  }}
													</span>
												</label>
											</div>
											<label>Cancellation Date</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input readonly type="text" class="form-control" name="purchase_order_date" v-model="item.cancelled_on" >
											</div>
											
											<label>Cancelled By</label>
											<input readonly type="text" class="form-control mb-2" name="purchase_order_date" v-model="item.cancelled_user_name" >
											<div>&zwnj;</div>
										</div>	
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<h4 class="mt-4"><i class="fas fa-dollar-sign"></i> Sales Tax</h4><hr>
											<label>Sale Tax Group</label>
											<input  type="text" class="form-control mb-2" name="sales_tax_group" v-model="item.sales_tax_group" >
											<label>Tax Exempt Group</label>
											<input  type="text" class="form-control mb-2" name="tax_exempt_number" v-model="item.tax_exempt_number" >
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
											<input  type="text" class="form-control mb-2" name="cash_discount" v-model="item.cash_discount" >
											<label>Cash Discount Code</label>
											<input  type="text" class="form-control mb-2" name="cash_discount_code" v-model="item.cash_discount_code" >
										</div>
									</div>
									<div class="col-md-3">
										<h4><i class="fas fa-user"></i> Vendor</h4><hr>
										<!-- Display vendor name instead of vendor number -->
										<label>Vendor <b class="text-danger">*</b></label>
										<v-select class="mb-2" v-model="item.vendor_account" :reduce="item => item.vendor_account" label="company_name" :options="vendors"></v-select>
										<label>Vendor Account <b class="text-danger">*</b></label>
										<input class="form-control" type="text" v-model="item.vendor_account" readonly>
										<label>Invoice Account <b class="text-danger">*</b></label>
										<input class="form-control mb-2" readonly v-model="item.invoice_account">
										<!-- <label>Vendor Name <b class="text-danger">*</b></label> -->
										<input name="vendor_name" v-model="item.vendor_name" type="hidden" class="form-control mb-2">
										<label>Vendor Contact ID <b class="text-danger">*</b></label>
										<input name="vendor_contact_id" v-model="item.vendor_contact_id" type="text" class="form-control mb-2" >
										<label>Vendor Address <b class="text-danger">*</b></label>
										<textarea name="vendor_address" v-model="item.vendor_address" class="form-control mb-2" rows="3"></textarea>
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
		        		    			<v-select v-model="item.posting_profile_id" :reduce="item => item.id" label="posting_profile" placeholder="Select Posting Profile" :options="posting_profiles"></v-select>
		        		                <input name="posting_profile_id" v-model="item.posting_profile_id" type="hidden" class="form-control mb-2">

	        		                    <label>Document</label>
	        		                    <input type="text" class="form-control mb-2" name="document" v-model="item.document">
	        		                    <label>Document Status</label>
	        		                    <input type="text" class="form-control mb-2" name="document_status" v-model="item.document_status">
		        		    	
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
											<label>Payment ID</label>
											<input  type="text" class="form-control mb-2" name="payment_id" v-model="item.payment_id" >									
											<label>Settlement type <b class="text-danger">*</b></label>
											<v-select v-model="item.settlement_type" :options="settlement_types"  placeholder="Select Settlement Type" class="mb-2"></v-select>
							    			<label>Payment Method <b class="text-danger">*</b></label>
											<v-select v-model="item.method_of_payment" :options="payment_methods" :reduce='item => item.id' label="method_of_payment" placeholder="Select Payment Method" class="mb-2"></v-select>
							    			<label>Terms of payment <b class="text-danger">*</b></label>
											<v-select v-model="item.terms_of_payment" :options="terms_of_payments" :reduce='item => item.terms_of_payment' label="terms_of_payment" placeholder="" class="mb-2"></v-select>
							    			<label>Payment Specification</label>
											<textarea
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
																				
											<label>Delivery Contact</label>
											<input name="delivery_contact" v-model="item.delivery_contact" class="form-control mb-2">

											<label>Delivery Address</label>
											<input  name="delivery_address" v-model="item.delivery_address" class="form-control mb-2">

											<label>Delivery Terms</label>
											<input name="delivery_term" v-model="item.delivery_term" class="form-control mb-2">
		        		    	
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
							
				        	<div class="tab-pane" id="vendor-invoice-lines">
			        		   <vendor-invoice-lines 
									@success="fetch"
									@newLines="getNewlines"
									:lines="vendor_invoice_lines"
									:vi="item"
									:products="products"
									:variants="variants"
									:vendors="vendors"
									:show-confirm-button="showConfirmButton"
									:clients="clients"
									:cost_center_value_code="item.cost_center_id"
									:department_value_code="item.department_id"
									:expense_purpose_value_code="item.expense_purpose_id"
									:specifications="specifications"
									:departments="departments"
									:expense_purposes="expense_purposes"
									:services="services"
									:procurements="procurements"
									:cost_centers="cost_centers"
									:charges_on_lines="charges_on_lines"
									:discount_on_lines="discount_on_lines"
									>
							   </vendor-invoice-lines>
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
						                            <vendor-payment-table 
						                                :clients="clients"
						                                ref="table-1"
						                                :fetch-url="vendorPaymentsApproval"
						                            ></vendor-payment-table>
						                        </div>
						                        <div class="tab-pane" id="approved_payment">
						                            <vendor-payment-table 
						                                :clients="clients"
						                                ref="table-2"
						                                :fetch-url="vendorPaymentsApproved"
						                                :is-approved="true"
						                            ></vendor-payment-table>
						                        </div>
						                        <div class="tab-pane" id="posted_payment">
						                            <vendor-payment-table 
						                                :clients="clients"
						                                ref="table-3"
						                                :fetch-url="vendorPaymentsPosted"
						                                :is-posted="true"
						                            ></vendor-payment-table>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </div>
				        	</div>
				        	<div class="tab-pane" id="subsidiary-ledger">
				        		<subsidiary-view
				        			:clients="clients"
					        		:invoice-approval-url="invoiceApprovalUrl"
									:vendor-payment-url="vendorPaymentUrl"
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
			<loader :loading="loading"></loader>
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import SetupMixin from 'Mixins/setup.js';

	import FormRequest from 'Components/forms/FormRequest.vue';
	import InvoiceVendorLines from './PurchaseDeliveryReceiptLineView.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';
	import { ModelListSelect } from 'vue-search-select'
	import Vselect from "vue-select";

	export default {

		props: {
			showConfirmButton: {
				default: false,
				type: Boolean
			},

			generateInvoicePaymentUrl: {
				default: null,
				type: String,
			},

			purchaseOrder : Object,

			vendorPaymentsApproval: String,
			vendorPaymentsApproved: String,
			vendorPaymentsPosted: String,

			invoiceApprovalUrl: String,
			vendorPaymentUrl: String,
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
			'form-request': FormRequest,
			'action-button': ActionButton,
			'vendor-invoice-lines' : InvoiceVendorLines,
			ModelListSelect,
			'v-select' : Vselect,
		},

		watch: {
			'item.vendor_account'(vendor_account) {
				if (vendor_account) {
					let vendor = Object.assign({}, this.vendors.find(vendor => vendor.vendor_account == vendor_account));
					this.item.vendor_name = vendor.fullname;
					this.item.vendor_contact_id = vendor.fullname;
					this.item.vendor_address = vendor.address;
					this.item.invoice_account = vendor_account;
				} else {
					this.item.vendor_name = null;
					this.item.invoice_account = null;
					this.item.vendor_contact_id = null;
					this.item.vendor_address = null;
				}
			},
			
			'client_id'(value){
				this.item.client_id = value;
				this.getActiveCode();
			},

			'item.purchase_order_number'(value) {
				if (value) {
					if (this.original_purchase_order_number != value) {
						let purchase_order = Object.assign({}, this.purchase_orders.find(po => po.purchase_order_number == value));
						if (purchase_order) {
							purchase_order.cost_center_id = 1*purchase_order.cost_center;
							purchase_order.department_id = 1*purchase_order.department;
							purchase_order.expense_purpose_id = 1*purchase_order.expense_purpose;
							let purchase_delivery_receipt_number = this.item.purchase_delivery_receipt_number;
							this.item = Object.assign(this.item, purchase_order);
							this.item.purchase_delivery_receipt_number = purchase_delivery_receipt_number;
							this.item.payment_due_date = this.item.due_date
							this.item.purchase_order_lines.forEach((line, index) => {
								let product = this.products.find(product => line.item_number == product.item_number);
								if (product) {
									line['product'] = product;
								}
								line.client_id = purchase_order.client_id;
								line.is_new = true;
								line.vendor_invoice_line_number = this.generateLineCodeV2('VI', index);
								line.purchase_delivery_receipt_number = this.item.invoice_account;
								line.purchase_delivery_receipt_number = this.item.purchase_delivery_receipt_number;
								line.vendor_account = this.item.vendor_account;
								line.invoice_account = this.item.invoice_account;
								line.vendor_name = this.item.vendor_name;
								delete line.id;
							});
							this.item.vendor_invoice_lines = this.item.purchase_order_lines
							this.vendor_invoice_lines = this.item.purchase_order_lines
							this.item.transaction_type = 'Sales';
							this.item.invoice_status = 'New';
							this.item.created_at = null;
						}
					}
				}else {
					this.item = {};
					this.client_id = null;
					this.generateID();
				}
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

				labelBtn: 'Generate Invoice',

				created_by : null,
				updated_by : null,

				users: [],
				vendors: [],
				products: [],
				variants: [],
				payment_methods: [],
				terms_of_payments: [],
				cost_centers: [],
				departments: [],
				expense_purposes: [],
				purchase_order: {},
				purchase_orders: [],
				purchase_order_returns: [],
				is_creation: true,
				invoice_statuses: [],
				specifications: [],

				vendor_invoice_lines: [],
                clients: [],
                services: [],
                procurements: [],
			
				invoice_by : null,
				copy_vendor_order_lines: false,
				original_purchase_order_number: null,
                posting_profiles: [],
				client_id : null,
				charges_on_lines : [],
				charges_on_header : [],

				transaction_types : ['Sales', 'Purchase', 'Both'],
				settlement_types : ['None', 'Open Transaction', 'Designated Transactions'],

				discount_on_lines : [],
				discount_on_header : [],
				payment_schedules : [],
			}
		},

		mounted() {
			flatpickr(this.$refs.delivery_date);
			flatpickr(this.$refs.payment_due_date);
			flatpickr(this.$refs.invoice_date);

			this.invoice_statuses = [
				{ value: 'New' },
				{ value: 'Pending' },
				{ value: 'Promissory Note' },
				{ value: 'Fully Paid' },
				{ value: 'Delivered' }
			];
		},
		
		computed: {
            headers() {
                let array = [
					{ text: 'Line #', value: 'line_number' },
					{ text: 'Item #', value: 'item_number' },
					{ text: 'Line Status', value: 'line_status' },
					{ text: 'Product', value: 'name' },
					{ text: 'Variant', value: 'variant' },
					{ text: 'Size', value: 'size' },
					{ text: 'Color', value: 'color' },
					{ text: 'Quantity', value: 'quantity' },
					{ text: 'Unit Price', value: 'unit_price' },
					{ text: 'SubTotal', value: 'sub_total' },
					{ text: 'COP', value: 'charge_on_purchase' },
					{ text: 'Discount', value: 'discount' },
					{ text: 'Amount', value: 'amount' },
					{ text: 'Action', value: null },
                ];

                return array;
			},

			hasApprovedLine() {
				return !_.isEmpty(_.find(this.vendor_invoice_lines, { is_approved : true }))
			},

			showCreditTab() {

				let payment_method = this.payment_methods.find(method => {
					return method.id == this.item.method_of_payment;
				});

				if(payment_method) {
					if(payment_method.method_of_payment == "Credit") {
						return true;
					}
				}

				return false;
			},

			total_quantity() {
                let result = 0;
                let itemLines = this.vendor_invoice_lines;

                if(itemLines) {
                    itemLines.forEach((line) => {
                        result += parseInt(line.quantity);
                    });
                }

                return result;
            },

            total_vattable_sales_vat_exclusive() {
                let result = 0;
                let itemLines = this.vendor_invoice_lines;

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
                let itemLines = this.vendor_invoice_lines;

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
                let itemLines = this.vendor_invoice_lines;

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
                let itemLines = this.vendor_invoice_lines;

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
                let itemLines = this.vendor_invoice_lines;

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
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				
				this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;

				if(!data.item) {
					this.is_creation = true;
					this.item = data.purchase_order ?? {};
					if (!this.item.transaction_type) {
						this.item = {
							transaction_type : 'Sales',
							invoice_status : 'New',
							settlement_type : 'None',
							method_of_payment : this.payment_methods[0] ? this.payment_methods[0].method_of_payment_id : null,
							terms_of_payment : this.terms_of_payments[0] ? this.terms_of_payments[0].terms_of_payment : null,
						}
					}

				} else {
					this.is_creation = false;
					this.original_purchase_order_number = this.item.purchase_order_number;
				}

				this.labelBtn = data.item ? 'Save Changes' : 'Generate Invoice'
				this.purchase_order = data.purchase_order ? data.purchase_order : this.purchase_order;
				this.users = data.users ? data.users : this.users;
				this.vendors = data.vendors ? data.vendors : this.vendors;

				this.purchase_orders = data.purchase_orders ?? this.purchase_orders;
				this.purchase_order_returns = data.purchase_order_returns ?? this.purchase_order_returns;
                this.clients = data.clients ?? this.clients;
				
				this.vendor_invoice_lines = data.vendor_invoice_lines ? data.vendor_invoice_lines : this.vendor_invoice_lines;
				this.item.vendor_invoice_lines = data.vendor_invoice_lines;

				// dropdowns
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.departments = data.departments ? data.departments : this.departments;
				this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
				this.products = data.products ? data.products : this.products;
				this.variants = data.variants ?? this.variants;
                this.posting_profiles = data.posting_profiles ?? this.posting_profiles;
                this.specifications = data.specifications ?? this.specifications;
                this.services = data.services ?? this.services;
                this.procurements = data.procurements ? data.procurements : this.procurements;

                this.discount_on_lines = data.discount_on_lines ? data.discount_on_lines : this.discount_on_lines;
                this.discount_on_header = data.discount_on_header ? data.discount_on_header : this.discount_on_header;
                this.payment_schedules = data.payment_schedules ? data.payment_schedules : this.payment_schedules;
				

				if(!this.item.purchase_delivery_receipt_number) {
					this.generateID();
					this.item.payment_due_date = this.purchase_order.due_date;
				}

				if(this.purchaseOrder.id) {
					this.item.purchase_order_number = this.purchaseOrder.purchase_order_number;
				}
			
			
			},

			getNewlines(value) {
				this.item.vendor_invoice_lines = value;
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

			postedThisInvoice() {
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
				    axios.post($this.item.postUrl)
			    	.then(response => {
	    			    $this.$notification.show(response.data.message, 'Success')
	    			    window.location.href = response.data.redirect;
	                    $this.$loading.show(false);
			    	}).catch(error => {
						swal.fire({
							icon: 'error',
							title: 'Unsucessful posting!',
							text: error.response.data.errors.message[0],
						});
	                    $this.$loading.show(false);
			    	})
				  }
				})
			},

			cancelThisInvoice() {
				var $this = this;
				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you want to mark this invoice as cancelled?',
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then((result) => {
				  if (result.value) {
                    $this.$loading.show(true);
				    axios.post($this.item.cancelUrl)
			    	.then(response => {
	    			    $this.$notification.show(response.data.message, 'Success')
	                    $this.$loading.show(false);
			    	}).catch(error => {
						swal.fire({
							icon: 'error',
							title: 'Unsucessful posting!',
							text: error.response.data.errors.message[0],
						});
	                    $this.$loading.show(false);
			    	})
				  }
				})
			},

			createVendorInvoicePayment() {
				var $this = this;
				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you want to create invoice payment?',
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then((result) => {
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
				this.item.purchase_delivery_receipt_number =  'PDR' + '-' + date.getFullYear().toString() + ("0" + (date.getMonth() + 1)).slice(-2) + date.getDate().toString()   +'-'+ time.toString();
			},

			getActiveCode() {
				let id = this.item.client_id;
				if(id) {
					let client = this.clients.filter(item => item.id == id)[0];
					if(client.code) {	
						this.item.purchase_delivery_receipt_number = client.code;
					}else {
						this.generateID();
					}	
				}
			},
		},

		mixins: [ CrudMixin, SetupMixin ],
	}
</script>
