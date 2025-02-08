<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Letter of Guarantee Information
                    <div class="float-right">
                        <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                        <button class="btn btn-primary btn-sm" :class="item.approved_checkbox || !item  ? 'disabled' : '' " @click="actionHandler('liquidate')">Liquidate</button>
                        <button class="btn btn-warning btn-sm" :class="item.approved_checkbox || !item  ? 'disabled' : '' " @click="actionHandler('extend')">Extend</button>
                        <button class="btn btn-success btn-sm" :class="item.approved_checkbox || !item  ? 'disabled' : '' " @click="actionHandler('approve')">Approve</button>
                    </div>
                </template>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#active" data-toggle="tab">Letter of Guarantee</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#status" data-toggle="tab">Status</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="active">
                                <div class="row">
                                     <div class="col-md-6">
                                         <div class="row">
                                             <div class="form-group col-md-12">
                                                 <h4 class="mb-2"><i class="fas fa-tags"></i> Letter Of Guarantee</h4><hr>
                                             </div>
                                             <div class="form-group col-md-4">
                                                 <label for="letter_of_guarantee_number">Letter of Guarantee Number</label>
                                                 <input type="text" class="form-control" name="letter_of_guarantee_number" v-model="item.letter_of_guarantee_number">
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label class="">Document</label>
                                                 <model-list-select :list="documents"
                                                     v-model="item.document_id"
                                                     option-value="id"
                                                     option-text="name"
                                                     placeholder="Select Document"
                                                     class="form-control">
                                                 </model-list-select>
                                                 <input name="document_id" hidden v-model="item.document_id"> 
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label class="">Document Type</label>
                                                 <model-list-select :list="documents"
                                                     v-model="item.document_type_id"
                                                     option-value="id"
                                                     option-text="name"
                                                     placeholder="Select Document Type"
                                                     class="form-control">
                                                 </model-list-select>
                                                 <input name="document_type_id" hidden v-model="item.document_type_id"> 
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label class="">Requested By</label>
                                                 <select class="form-control" v-model="item.requested_by" name="requested_by">
                                                     <option value="Client">Client</option>
                                                     <option value="Customer">Customer</option>
                                                     <option value="Vendor">Vendor</option>
                                                 </select>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label class="">Transaction Type</label>
                                                 <select class="form-control" v-model="item.transaction_type" name="transaction_type">
                                                     <option value="Purchase Order">Purchase Order</option>
                                                     <option value="Sales Order">Sales Order</option>
                                                 </select>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="received_date">Received Date</label>
                                                 <div class="input-group mb-2">
                                                     <div class="input-group-prepend">
                                                         <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                     </div>
                                                     <input ref="received_date" type="text" class="form-control calendar-form" name="received_date" v-model="item.received_date" readonly>
                                                 </div>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="issue_date">Issue Date</label>
                                                 <div class="input-group mb-2">
                                                     <div class="input-group-prepend">
                                                         <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                     </div>
                                                     <input ref="issue_date" type="text" class="form-control calendar-form" name="issue_date" v-model="item.issue_date" readonly>
                                                 </div>
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
                                                 <label for="amount">Amount</label>
                                                 <input type="number" step="any" class="form-control" name="amount" v-model="item.amount">
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="currency">Currency</label>
                                                 <input type="text" class="form-control" name="currency" v-model="item.currency">
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="row">
                                             <div class="form-group col-md-12">
                                                 <h4 class="mb-2"><i class="fas fa-tags"></i> Client</h4><hr>
                                             </div>
                                             <div class="form-group col-md-4">
                                                 <label class="form-label">Client <b class="text-danger">*</b></label>
                                                 <model-list-select :list="clients"
                                                     v-model="item.client_id"
                                                     option-value="id"
                                                     option-text="name"
                                                     placeholder="Select a client"
                                                     class="form-control">
                                                 </model-list-select>
                                                 <input name="client_id" hidden v-model="item.client_id"> 
                                             </div>
                                             <div class="form-group col-md-4">
                                                 <label class="form-label">Client Bank Account <b class="text-danger">*</b></label>
                                                 <model-list-select :list="client_bank_accounts"
                                                     v-model="item.client_bank_account_id"
                                                     option-value="id"
                                                     option-text="name"
                                                     placeholder="Select a client bank account"
                                                     class="form-control">
                                                 </model-list-select>
                                                 <input name="client_bank_account_id" hidden v-model="item.client_bank_account_id"> 
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="row">
                                             <div class="form-group col-md-12">
                                                 <h4 class="mb-2"><i class="fas fa-tags"></i> Customer</h4><hr>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label class="form-label">Sales Order Number <b class="text-danger">*</b></label>
                                                 <model-list-select :list="sales_orders"
                                                     v-model="item.sales_order_id"
                                                     option-value="id"
                                                     option-text="sales_order_number"
                                                     placeholder="Select a Sales Order"
                                                     class="form-control">
                                                 </model-list-select>
                                                 <input name="sales_order_id" hidden v-model="item.sales_order_id"> 
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="so_date">SO Date</label>
                                                 <div class="input-group mb-2">
                                                     <div class="input-group-prepend">
                                                         <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                     </div>
                                                     <input type="text" class="form-control calendar-form" readonly>
                                                 </div>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="customer">Customer</label>
                                                 <input type="text" class="form-control">
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="customer_bank">Customer Bank</label>
                                                 <input type="text" class="form-control">
                                             </div>
                                         </div>
                                     </div>

                                     <div class="col-md-6">
                                         <div class="row">
                                             <div class="form-group col-md-12">
                                                 <h4 class="mb-2"><i class="fas fa-tags"></i> Status</h4><hr>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="status">Status</label>
                                                 <input type="text" class="form-control" name="status" v-model="item.status" disabled>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="approved">Approved</label>
                                                 <input type="checkbox" class="mt-3" v-model="item.approved" disabled>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="approved_date">Approved Date</label>
                                                 <div class="input-group mb-2">
                                                     <div class="input-group-prepend">
                                                         <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                     </div>
                                                     <input type="text" class="form-control calendar-form" v-model="item.approved_date" disabled>
                                                 </div>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="approved_by">Approved By</label>
                                                 <input type="text" class="mt-3" v-model="item.approved_by" disabled>
                                             </div>
                                         </div>
                                     </div>

                                     <div class="col-md-6">
                                         <div class="row">
                                             <div class="form-group col-md-12">
                                                 <h4 class="mb-2"><i class="fas fa-tags"></i> Action</h4><hr>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="liquidated">Liquidated</label>
                                                 <input type="checkbox" class="mt-3" v-model="item.liquidated" disabled>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="liquidated_on">Liquidated On</label>
                                                 <div class="input-group mb-2">
                                                     <div class="input-group-prepend">
                                                         <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                     </div>
                                                     <input type="text" class="form-control calendar-form" v-model="item.liquidated_on" disabled>
                                                 </div>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="extended">Extended</label>
                                                 <input type="checkbox" class="mt-3" v-model="item.extended" disabled>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="extended_on">Extended On</label>
                                                 <div class="input-group mb-2">
                                                     <div class="input-group-prepend">
                                                         <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                     </div>
                                                     <input type="text" class="form-control calendar-form" v-model="item.extended_on" disabled>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="row">
                                             <div class="form-group col-md-12">
                                                 <h4 class="mb-2"><i class="fas fa-tags"></i> Vendor</h4><hr>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label class="form-label">Purchase Order Number <b class="text-danger">*</b></label>
                                                 <model-list-select :list="purchase_orders"
                                                     v-model="item.purchase_order_id"
                                                     option-value="id"
                                                     option-text="purchase_order_number"
                                                     placeholder="Select a Purchase Order"
                                                     class="form-control">
                                                 </model-list-select>
                                                 <input name="purchase_order_id" hidden v-model="item.purchase_order_id"> 
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="po_date">PO Date</label>
                                                 <div class="input-group mb-2">
                                                     <div class="input-group-prepend">
                                                         <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                     </div>
                                                     <input type="text" class="form-control calendar-form" readonly>
                                                 </div>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="vendor">Vendor</label>
                                                 <input type="text" class="form-control" disabled>
                                             </div>

                                             <div class="form-group col-md-4">
                                                 <label for="vendor_bank">Vendor Bank</label>
                                                 <input type="text" class="form-control" disabled>
                                             </div>
                                         </div>
                                     </div>
                                </div>
                                
                                
                                <!-- <div class="row">
                                    <text-editor
                                    v-model="item.description"
                                    class="col-sm-12"
                                    label="Description"
                                    name="description"
                                    row="5"
                                    ></text-editor>
                                </div> -->
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
    import TextEditor from 'Components/inputs/TextEditor.vue';

    export default {

        data() {
            return {
                item: {},
                sales_orders: [],
                purchase_orders: [],
            }
        },

        methods: {
            fetchSuccess(data) {
                this.item = data.item ? data.item : this.item;
                this.sales_orders = data.sales_orders ? data.sales_orders : this.sales_orders;
                this.purchase_orders = data.purchase_orders ? data.purchase_orders : this.purchase_orders;
                
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },

            actionHandler(action) {
                var $this = this;
                var url = '';

                switch(action) {
                    case 'liquidate': 
                        url = this.item.liquidateUrl;
                        break;
                    case 'extend': 
                        url = this.item.extendUrl;
                        break;
                    case 'approve': 
                        url = this.item.approveUrl;
                        break;
                }

                swal.fire({
                  title: 'Are you sure?',
                  text: 'Are you sure you want to continue this process?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Confirm',
                  cancelButtonText: 'Cancel'
                }).then((result) => {
                  if (result.value) {
                    axios.post(url)
                    .then(response => {
                        $this.$notification.show(response.data.message, 'Success')
                        $this.fetch();
                    }).catch(error => {

                    })
                  }
                })
            },
        },

        components: {
            'form-request': FormRequest,
            'action-button': ActionButton,
            ModelListSelect,
            'v-select' : Vselect,
            'text-editor': TextEditor,
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