<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Letter of Credit Information
                    <div class="float-right">
                        <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                        <action-button @success="fetch" type="button" class="btn btn-success" :class="{'disabled':(!item.confirmUrl || item.confirmed || item.close)}" :action-url="item.confirmUrl">Confirm</action-button>
                        <action-button @success="fetch" href="javascript:void(0)" class="btn btn-danger" :class="{'disabled':(!item.closeUrl || item.confirmed || item.close)}" :action-url="item.closeUrl">Close</action-button>
                        <action-button @success="fetch" href="javascript:void(0)" class="btn btn-warning" :class="{'disabled':(!item.closeUrl || item.confirmed || item.close)}" :action-url="item.amendmentUrl">Amendment</action-button>
                    </div>
                </template>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#active" data-toggle="tab">Letter of Credit</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#status" data-toggle="tab">Status</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#customer" data-toggle="tab">Customer</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#bank" data-toggle="tab">Bank Document Tab</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#shipment" data-toggle="tab">Shipment & Terms</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#lines" data-toggle="tab">Lines</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row" style="margin-right: 10px">
                                    <label class="col-sm-3 col-form-label">Client <b class="text-danger">*</b></label>
                                    <model-list-select :list="clients"
                                        v-model="item.client_id"
                                        option-value="id"
                                        option-text="name"
                                        placeholder="Select a client"
                                        class="form-control col-sm-9">
                                    </model-list-select>
                                    <input name="client_id" hidden v-model="item.client_id"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="active">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="">Sales Order</label>
                                        <model-list-select :list="sales_orders"
                                            v-model="item.sales_order_id"
                                            option-value="id"
                                            option-text="sales_order_number"
                                            placeholder="Select sales order"
                                            class="form-control">
                                        </model-list-select>
                                        <input name="sales_order_id" hidden v-model="item.sales_order_id"> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="client_bank_name">Bank Document Number</label>
                                        <input type="text" class="form-control" v-model="item.bank_document_number">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="client_bank_name">Date Issue</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input ref="issue_date" type="text" class="form-control calendar-form" name="issue_date" v-model="item.issue_date" readonly>
                                        </div>
                                     </div>
                                    <div class="form-group col-md-4">
                                        <label for="issue_by">Issue By</label>
                                         <model-list-select :list="users"
                                             v-model="item.issue_by"
                                             option-value="id"
                                             option-text="fullname"
                                             placeholder="Select a user"
                                             class="form-control">
                                         </model-list-select>
                                         <input name="issue_by" hidden v-model="item.issue_by"> 
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="client_bank_name">Application Date</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input ref="application_date" type="text" class="form-control calendar-form" name="application_date" v-model="item.application_date" readonly>
                                        </div>
                                     </div>
                                    <div class="form-group col-md-4">
                                        <label for="client_bank_name">Receipt Date</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input ref="receipt_date" type="text" class="form-control calendar-form" name="receipt_date" v-model="item.receipt_date" readonly>
                                        </div>
                                     </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="amendment_number">Amendment Number</label>
                                        <input type="text" class="form-control" v-model="item.amendment_number">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="client_bank_name">Amendment Date</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input ref="amendment_on" type="text" class="form-control calendar-form" name="amendment_on" v-model="item.amendment_on" readonly>
                                        </div>
                                     </div>

                                     <div class="form-group col-md-4">
                                         <label class="">Amendment By</label>
                                         <model-list-select :list="users"
                                             v-model="item.amendment_by"
                                             option-value="id"
                                             option-text="fullname"
                                             placeholder="Select a user"
                                             class="form-control">
                                         </model-list-select>
                                         <input name="amendment_by" hidden v-model="item.amendment_by"> 
                                     </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="status">

                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <h4><i class="fas fa-question-circle"></i> Status</h4><hr>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>LC Sales Status</label>
                                        <select class="form-control" name="sales_status" v-model="item.sales_status">
                                            <option value="Prepared">Prepared</option>
                                            <option value="Confirmed">Confirmed</option>
                                            <option value="Closed">Closed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Confirmation Instruction</label>
                                        <select class="form-control" name="confirmation_instruction" v-model="item.confirmation_instruction">
                                            <option value="Unconfirmed">Unconfirmed</option>
                                            <option value="Confirmed">Confirmed</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Confirmed By</label>
                                        <input readonly :value="item.confirmed_by" type="text" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Confirmed On</label>
                                        <input readonly :value="item.confirmed_date" type="text" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Closed By</label>
                                        <input readonly :value="item.closed_by" type="text" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Closed on</label>
                                        <input readonly :value="item.closed_date" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="form-group col-sm-12">
                                        <h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Created By</label>
                                        <input readonly :value="item.created_by" type="text" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Created On</label>
                                        <input readonly :value="item.created_date" type="text" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Updated By</label>
                                        <input readonly :value="item.updated_by" type="text" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Updated on</label>
                                        <input readonly :value="item.updated_date" type="text" class="form-control">
                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane" id="customer">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Customer</label>
                                            <!-- <v-select v-model="item.customer_account" :reduce="item => item.customer_account" label="company_name" class="mb-2" :options="customers"></v-select> -->

                                            <model-list-select :list="customers"
                                                v-model="item.customer_account"
                                                option-value="customer_account"
                                                option-text="fullname"
                                                placeholder="Select customer"
                                                class="form-control">
                                            </model-list-select>
                                            <input name="customer_account" hidden v-model="item.customer_account"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Customer Account <b class="text-danger">*</b></label>
                                            <input readonly name="customer_account" v-model="item.customer_account" type="text" class="form-control mb-2">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Customer Contact ID <b class="text-danger">*</b></label>
                                            <input name="customer_contact_id" readonly v-model="item.customer_contact_id" type="text" class="form-control mb-2" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Customer Address</label>
                                            <textarea readonly name="customer_address" v-model="item.customer_address" class="form-control mb-2" rows="3">{{ item.customer_address }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="bank">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h4><i class="fas fa-question-circle"></i> Bank Facility Agreement</h4><hr>
                                            <label>Issuing Bank</label>
                                            <v-select v-model="item.issuing_bank" :reduce="item => item.issuing_bank" label="bank_account" :options="banks"></v-select>
                                            <label>Advising Bank</label>
                                            <v-select v-model="item.advising_bank" :reduce="item => item.advising_bank" label="bank_account" :options="banks"></v-select>
                                            <label>Available With</label>
                                            <input name="available_with" v-model="item.available_with" type="text" class="form-control">
                                            <label>Vendor Bank Account</label>
                                            <v-select v-model="item.vendor_bank_account_id" :reduce="item => item.vendor_bank_account_id" label="bank_account" :options="banks"></v-select>
                                            <label>Bank Document</label>
                                            <v-select v-model="item.bank_document_id" :reduce="item => item.bank_document_id" label="bank_facility_agreement_number" :options="documents"></v-select>
                                            <label>Bank Document Type</label>
                                            <input name="bank_document_type_id" readonly v-model="item.bank_document_type_id" type="text" class="form-control mb-2" >
                                            <label>Bank Facility Agreement</label>
                                            <v-select v-model="item.bank_facility_agreement_number" :reduce="item => item.bank_facility_agreement_number" label="bank_facility_agreement_number" :options="documents"></v-select>
                                            <label>Bank Facility Type</label>
                                            <v-select v-model="item.bank_facility_type_id" :reduce="item => item.bank_facility_type_id" label="bank_facility_type_code" :options="types"></v-select>
                                            <label>Bank Facility Balance</label>
                                            <input name="bank_facility_amount" readonly v-model="item.bank_facility_amount" type="number" class="form-control mb-2" >
                                            <label>LC/IC Amount</label>
                                            <input name="lc_ic_amount" v-model="item.lc_ic_amount" type="number" class="form-control" >
                                            <label>Balance Amount</label>
                                            <input name="balance_amount" v-model="item.balance_amount" type="number" class="form-control" >
                                            <label>SWIFT Code</label>
                                            <input name="swift_code" v-model="item.swift_code" type="text" class="form-control" >
                                            <label>Advising bank notice number</label>
                                            <input name="advising_bank_notice_number" v-model="item.advising_bank_notice_number" type="text" class="form-control" >
                                            <label>Notice Date</label>
                                            <input ref="notice_date" name="notice_date" v-model="item.notice_date" type="text" class="form-control" >
                                        </div>
                                            
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h4><i class="fas fa-question-circle"></i> Bank Document</h4><hr>

                                            <label>Documentary Credit Type</label>
                                            <select class="form-control" name="documentary_credit_type" v-model="item.documentary_credit_type">
                                                <option value="Revocable">Revocable</option>
                                                <option value="Irrevocable">Irrevocable</option>
                                            </select>
                                            <label>Documentary Credit Nature</label>
                                            <select class="form-control" name="documentary_credit_nature" v-model="item.documentary_credit_nature">
                                                <option value="Transferable">Transferable</option>
                                                <option value="Non-transferable">Non-transferable</option>
                                                <option value="Revolving">Revolving</option>
                                            </select>
                                            <label>Beneficiary</label>
                                            <input name="beneficiary" v-model="item.beneficiary" type="text" class="form-control" >
                                            <label>LC/IC Amount</label>
                                            <input name="lc_ic_amount" v-model="item.lc_ic_amount" type="number" class="form-control" >
                                            <label>LC Tolerance Amount</label>
                                            <input name="lc_tolerance_amount" v-model="item.lc_tolerance_amount" type="number" class="form-control" >
                                            <label>Tolerance Percentage</label>
                                            <input name="tolerance_percentage" v-model="item.tolerance_percentage" type="number" class="form-control" >
                                            <label>Tolerance Type</label>
                                            <select class="form-control" name="documentary_credit_nature" v-model="item.documentary_credit_nature">
                                                <option value="Bank">Bank</option>
                                                <option value="Plus">Plus</option>
                                                <option value="Minus">Minus</option>
                                                <option value="Plus/Minus">Plus/Minus</option>
                                            </select>
                                            <label>Currency</label>
                                            <input name="currency" v-model="item.currency" type="text" class="form-control" >
                                            <label>Expiration Date</label>
                                            <input ref="expiration_date" name="expiration_date" v-model="item.expiration_date" type="text" class="form-control" >
                                            <label>Place of Expiration</label>
                                            <input name="place_of_expiration" v-model="item.place_of_expiration" type="text" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="shipment">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h4><i class="fas fa-question-circle"></i> Shipment</h4><hr>
                                            <label>Partial Shipment</label>
                                            <select class="form-control" name="partial_shipment" v-model="item.partial_shipment">
                                                <option value="Not Allowed">Not Allowed</option>
                                                <option value="Allowed">Allowed</option>
                                            </select>
                                            <label>Transshipment</label>
                                            <select class="form-control" name="partial_shipment" v-model="item.partial_shipment">
                                                <option value="Not Allowed">Not Allowed</option>
                                                <option value="Allowed">Allowed</option>
                                            </select>
                                            <label>Port of loading</label>
                                            <input name="port_loading" v-model="item.port_loading" type="text" class="form-control" >
                                            <label>Latest shipment date</label>
                                            <input ref="latest_shipment_date" name="latest_shipment_date" v-model="item.latest_shipment_date" type="text" class="form-control" >
                                            <label>Destination Port</label>
                                            <input name="destination_port" v-model="item.destination_port" type="text" class="form-control" >
                                            <label>Description of Goods</label>
                                            <input name="description_goods" v-model="item.description_goods" type="text" class="form-control" >
                                            <label>Incoterms</label>
                                            <select class="form-control" name="incoterms" v-model="item.incoterms">
                                                <option value="Blank">Blank</option>
                                                <option value="FOB">FOB</option>
                                                <option value="CFR">CFR</option>
                                                <option value="CIF">CIF</option>
                                                <option value="C&F">C&F</option>
                                                <option value="Ex-factory">Ex-factory</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h4><i class="fas fa-question-circle"></i> Bank Document Terms</h4><hr>
                                            <label>Document Required</label>
                                            <input name="document_required" v-model="item.document_required" type="text" class="form-control" >
                                            <label>Special instructions</label>
                                            <input name="special_instructions" v-model="item.special_instructions" type="text" class="form-control" >
                                            <label>Bank charges</label>
                                            <select class="form-control" name="bank_charges" v-model="item.bank_charges">
                                                <option value="Blank">Blank</option>
                                                <option value="Beneficiary">Beneficiary</option>
                                                <option value="Applicant">Applicant</option>
                                                <option value="Both">Both</option>
                                            </select>
                                            <label>Draft</label>
                                            <select class="form-control" name="draft" v-model="item.draft">
                                                <option value="At sight">At sight</option>
                                                <option value="Acceptance">Acceptance</option>
                                            </select>
                                            <label>Deferred days</label>
                                            <input name="deferred_days" v-model="item.deferred_days" type="number" class="form-control" >
                                            <label>Period of presentation</label>
                                            <input name="period_of_presentation" v-model="item.period_of_presentation" type="number" class="form-control" >
                                            <label>Description</label>
                                            <input name="description" v-model="item.description" type="text" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h4><i class="fas fa-question-circle"></i> Insurance</h4><hr>
                                            <label>Insurance Number</label>
                                            <input name="insurance_number" v-model="item.insurance_number" type="text" class="form-control" >
                                            <label>Insurance status</label>
                                            <select class="form-control" name="insurance_status" v-model="item.insurance_status">
                                                <option value="No insurance">No insurance</option>
                                                <option value="Not yet submitted">Not yet submitted</option>
                                                <option value="Submitted">Submitted</option>
                                                <option value="Advice collected">Advice collected</option>
                                            </select>
                                            <label>Insurance vendor number</label>
                                            <input name="insurance_vendor_number" v-model="item.insurance_vendor_number" type="text" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="lines">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h4><i class="fas fa-question-circle"></i> Shipment Margin</h4><hr>
                                            <label>Shipment Number</label>
                                            <input name="shipment_number" v-model="item.shipment_number" type="text" class="form-control" >
                                            <label>Shipment Date</label>
                                            <input ref="shipment_date" name="shipment_date" v-model="item.shipment_date" type="text" class="form-control" >
                                            <label>Shipment Date To</label>
                                            <input ref="shipment_date_to" name="shipment_date_to" v-model="item.shipment_date_to" type="text" class="form-control" >
                                            <label>Port Loading</label>
                                            <input name="port_loading" v-model="item.port_loading" type="text" class="form-control" >
                                            <label>Port of Discharge</label>
                                            <input name="port_discharge" v-model="item.port_discharge" type="text" class="form-control" >
                                            <label>Purchase Delivery Receipt</label>
                                            <v-select v-model="item.purchase_delivery_receipt_id" :reduce="item => item.purchase_delivery_receipt_id" label="purchase_delivery_receipt_number" :options="delivery_receipts"></v-select>
                                            <label>Purchase Delivery Receipt Date</label>
                                            <input name="purchase_delivery_receipt_date" v-model="item.purchase_delivery_receipt_date" readonly type="text" class="form-control" >
                                            <label>Actual maturity date</label>
                                            <input ref="actual_maturity_date" name="actual_maturity_date" v-model="item.actual_maturity_date" type="text" class="form-control" >
                                            <label>Margin Amount</label>
                                            <input name="margin_amount" v-model="item.margin_amount" type="number" class="form-control" >
                                            <label>Allocated</label>
                                            <input name="allocated" v-model="item.allocated" type="number" class="form-control" >
                                            <label>Settled</label>
                                            <input name="settled" v-model="item.settled" type="number" class="form-control" >
                                            <label>Shipping Document status</label>
                                            <select class="form-control" name="shipping_document_status" v-model="item.shipping_document_status">
                                                <option value="Received">Received</option>
                                                <option value="Not received">Not received</option>
                                                <option value="Shipping Guarantee">Shipping Guarantee</option>
                                            </select>
                                            <label>Shipment status</label>
                                            <select class="form-control" name="shipment_status" v-model="item.shipment_status">
                                                <option value="Created">Created</option>
                                                <option value="Open">Open</option>
                                                <option value="Purchase Delivery Receipt updated">Purchase Delivery Receipt updated</option>
                                                <option value="Invoiced">Invoiced</option>
                                                <option value="Paid">Paid</option>
                                            </select>
                                            <label>Voucher Number</label>
                                            <input name="voucher_number" v-model="item.voucher_number" type="number" class="form-control" >
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
    import SetupMixin from 'Mixins/setup.js';

    import FormRequest from 'Components/forms/FormRequest.vue';
    import ActionButton from 'Components/buttons/ActionButton.vue';
    import Datepicker from 'vuejs-datepicker';

    import flatpickr from 'flatpickr';
    import 'flatpickr/dist/flatpickr.css';
    import { ModelListSelect } from 'vue-search-select'
    import Vselect from "vue-select";

    export default {
        mounted() {
            this.mountInputs();
        },

        data() {
            return {
                item: {},
                users: [],
                sales_orders: [],
                customers: [],
                banks: [],
                documents: [],
                types: [],
                delivery_receipts: [],
            }
        },

        methods: {
            fetchSuccess(data) {
                this.item = data.item ? data.item : this.item;
                this.users = data.users ? data.users : this.users;
                this.sales_orders = data.sales_orders ? data.sales_orders : this.sales_orders;
                this.customers = data.customers ? data.customers : this.customers;
                this.banks = data.banks ? data.banks : this.banks;
                this.documents = data.documents ? data.documents : this.documents;
                this.types = data.types ? data.types : this.types;
                this.delivery_receipts = data.delivery_receipts ? data.delivery_receipts : this.delivery_receipts;
                
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },

            mountInputs() {
                let options = {
                    dateFormat: 'Y-m-d H:i',
                    enableTime: true,
                };

                flatpickr(this.$refs.issue_date, options);
                flatpickr(this.$refs.application_date, options);
                flatpickr(this.$refs.receipt_date, options);
                flatpickr(this.$refs.amendment_on, options);
                flatpickr(this.$refs.expiration_date, options);
                flatpickr(this.$refs.latest_shipment_date, options);
                flatpickr(this.$refs.shipment_date, options);
                flatpickr(this.$refs.shipment_date_to, options);
                flatpickr(this.$refs.actual_maturity_date, options);
                flatpickr(this.$refs.notice_date, options);
            },
        },


        watch: {
            'item.customer_account'(val) {
                _.each(this.customers, (customer) => {
                    if(customer.customer_account == val) {
                        this.item.customer_name = customer.fullname;
                        this.item.customer_address = `${customer.shipping_street}, ${customer.shipping_city}, ${customer.shipping_province}, ${customer.shipping_postal_code}, ${customer.shipping_country}`;
                        this.item.customer_contact_id = customer.fullname;
                    } else {
                        this.item.customer_name = null;
                        this.item.customer_address = null;
                        this.item.customer_contact_id = null;
                    }
                })
            },
        },

        components: {
            'form-request': FormRequest,
            'action-button': ActionButton,
            ModelListSelect,
            'v-select' : Vselect,
        },

        mixins: [ CrudMixin, SetupMixin ],

        props: {
            clients: {
                type: Array,
                default: () => [],
            },
        },
    }

</script>