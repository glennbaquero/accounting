<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Bank Document Information
                    <div class="float-right">
                        <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                    </div>
                </template>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#active" data-toggle="tab">Bank Document</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#status" data-toggle="tab">Status</a></li>
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
                                        <label class="">Advising Bank</label>
                                        <model-list-select :list="vendor_bank_accounts"
                                            v-model="item.vendor_bank_account_id"
                                            option-value="id"
                                            option-text="name"
                                            placeholder="Select advising bank"
                                            class="form-control">
                                        </model-list-select>
                                        <input name="vendor_bank_account_id" hidden v-model="item.vendor_bank_account_id"> 
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="">Client Bank Account</label>
                                        <model-list-select :list="client_bank_accounts"
                                            v-model="item.client_bank_account_id"
                                            option-value="id"
                                            option-text="customer_account"
                                            placeholder="Select advising bank"
                                            class="form-control">
                                        </model-list-select>
                                        <input name="client_bank_account_id" hidden v-model="item.client_bank_account_id"> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="available_with">Available With</label>
                                        <input type="text" class="form-control" v-model="item.available_with">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="bank_facility_agreement_number">Bank Facility Agreement Number</label>
                                        <select class="form-control" v-model="item.bank_facility_agreement_number">
                                            <option value="Letter Credit">Letter Credit</option>
                                            <option value="Letter Credit">Letter Credit</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="bank_document_type">Bank Document Type</label>
                                        <select class="form-control" v-model="item.bank_document_type">
                                            <option value="Letter Credit">Letter Credit</option>
                                            <option value="Letter Credit">Letter Credit</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="facility_balance">Facility Number</label>
                                        <input type="number" min="0" step="any" class="form-control" v-model="item.facility_balance">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="documentary_credit_type">Documentary Credit Type</label>
                                        <input type="text" class="form-control" v-model="item.documentary_credit_type">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="documentary_credit_nature">Documentary Credit Nature</label>
                                        <input type="text" class="form-control" v-model="item.documentary_credit_nature">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="beneficiary">Beneficiary</label>
                                        <input type="text" class="form-control" v-model="item.beneficiary">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="lc_ic_amount">LC/IC Amount</label>
                                        <input type="number" min="0" step="any" class="form-control" v-model="item.lc_ic_amount">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="lc_tolerance_amount">LC Tolerance Amount</label>
                                        <input type="number" min="0" step="any" class="form-control" v-model="item.lc_tolerance_amount">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="tolerance_percentage">Tolerance Percentage</label>
                                        <input type="number" min="0" class="form-control" v-model="item.tolerance_percentage">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="tolerance_type">Tolerance Type</label>
                                        <input type="text" class="form-control" v-model="item.tolerance_type">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="currency">Currency</label>
                                        <input type="text" class="form-control" v-model="item.currency">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="expiration_date">Expiration Date</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input ref="expiration_date" type="text" class="form-control calendar-form" name="expiration_date" v-model="item.expiration_date" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="place_of_expiration">Place of Expiration</label>
                                        <input type="text" class="form-control" v-model="item.place_of_expiration">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="partial_shipment">Partial Shipment</label>
                                        <select class="form-control" v-model="item.partial_shipment">
                                            <option value="Not Allowed">Not Allowed</option>
                                            <option value="Not Allowed">Not Allowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="transshipment">Trans Shipment</label>
                                        <select class="form-control" v-model="item.transshipment">
                                            <option value="Not Allowed">Not Allowed</option>
                                            <option value="Not Allowed">Not Allowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="port_loading">Port Loading</label>
                                        <input type="text" class="form-control" v-model="item.port_loading">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="latest_shipment_date">Latest Shipment Date</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input ref="latest_shipment_date" type="text" class="form-control calendar-form" name="latest_shipment_date" v-model="item.latest_shipment_date" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="destination_port">Destination Port</label>
                                        <input type="text" class="form-control" v-model="item.destination_port">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="description_goods">Description Goods</label>
                                        <input type="text" class="form-control" v-model="item.description_goods">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="incoterms">Incoterms</label>
                                        <input type="text" class="form-control" v-model="item.incoterms">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="document_required">Document Required</label>
                                        <input type="text" class="form-control" v-model="item.document_required">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="bank_charges">Bank Charges</label>
                                        <select class="form-control" v-model="item.bank_charges">
                                            <option value="Bank">Bank</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="draft">Draft</label>
                                        <select class="form-control" v-model="item.draft">
                                            <option value="At sight">At sight</option>
                                            <option value="At sight">At sight</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="preferred_days">Preferred Days</label>
                                        <input type="text" class="form-control" v-model="item.preferred_days">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="period_of_presentation">Period Of Presentation</label>
                                        <input type="text" class="form-control" v-model="item.period_of_presentation">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="confirmation_instruction">Confirmation Instruction</label>
                                        <input type="text" class="form-control" v-model="item.confirmation_instruction">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="insurance_number">Insurance Number</label>
                                        <input type="text" class="form-control" v-model="item.insurance_number">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="insurance_status">Insurance Status</label>
                                        <input type="text" class="form-control" v-model="item.insurance_status">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="insurance_vendor_number">Insurance Vendor Number</label>
                                        <input type="text" class="form-control" v-model="item.insurance_vendor_number">
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
                vendor_bank_accounts: [],
                client_bank_accounts: [],
            }
        },

        methods: {
            fetchSuccess(data) {
                this.item = data.item ? data.item : this.item;
                this.vendor_bank_accounts = data.vendor_bank_accounts ? data.vendor_bank_accounts : this.vendor_bank_accounts;
                this.client_bank_accounts = data.client_bank_accounts ? data.client_bank_accounts : this.client_bank_accounts;
                
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },

            mountInputs() {
                let options = {
                    dateFormat: 'Y-m-d H:i',
                    enableTime: true,
                };

                flatpickr(this.$refs.expiration_date, options);
                flatpickr(this.$refs.latest_shipment_date, options);
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