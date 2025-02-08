<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>

                <v-select 
                class="ml-4 mr-4 select-size" 
                :reduce="item => item.id" 
                v-model="client" 
                @input="filter($event, 'client')" 
                label="name" 
                placeholder="Select Client" :options="clients"
                ></v-select>

                <date-range
                :options="filterColumns"
                class="mr-2"
                @change="filter($event)"
                ></date-range>

            </template>
            <template v-slot:right>
                <search-form
                @search="filter($event, 'search')">
                </search-form>
            </template>
        </filter-box>

        <!-- DATATABLE -->
        <data-table
        ref="data-table"
        :headers="headers"
        :filters="filters"
        :fetch-url="fetchUrl"
        :no-action="noAction"
        :disabled="disabled"
        order-by="id"
        order-desc
        @load="load"
        >

            <template v-slot:body="{ items }">
                <tr v-for="item in items">
                    <td>{{ item.voucher_number }}</td>
                    <td>{{ item.customer_payment_id }}</td>
                    <td>{{ item.customer_payment_issued_date }}</td>
                    <td>{{ item.customer_name }}</td>
                    <td>{{ item.vendor_payment_id }}</td>
                    <td>{{ item.vendor_payment_issued_date }}</td>
                    <td>{{ item.vendor_name }}</td>
                    <td>{{ item.check_id }}</td>
                    <td>{{ item.check_amount }}</td>
                    <td>{{ item.deposit_id }}</td>
                    <td>{{ item.payment_reference }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>

                        <button 
                            class="btn btn-sm btn-primary" 
                            @click="edit(item)"
                        >
                            <i class="fas fa-pen"></i>
                        </button>

                        <action-button
                        small 
                        color="btn-danger"
                        alt-color="btn-warning"
                        :show-alt="item.deleted_at"
                        :action-url="item.archiveUrl"
                        :alt-action-url="item.restoreUrl"
                        icon="fas fa-trash"
                        alt-icon="fas fa-trash-restore-alt"
                        confirm-dialog
                        :disabled="loading"
                        title="Archive Item"
                        alt-title="Restore Item"
                        :message="`Are you sure you want to archive ${item.adjustment_name}?`"
                        :alt-message="`Are you sure you want to restore ${item.adjustment_name}?`"
                        @load="load"
                        @success="sync"
                        ></action-button>
                    </td>
                </tr>
            </template>

        </data-table>

        <template v-if="onEdit">
            <div ref="modal" class="modal fade"  id="ba-lines-modal" tabindex="-1" role="dialog" aria-labelledby="bank_statement_line_modal_label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"  id="bank_statement_line_modal_label">Bank Reconciliation Line</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <bank-reconciliation-journal-voucher-view
                                ref="form"
                                :clients="clients"
                                :parent="parent"
                                :fetch-url="item.showUrl"
                                :submit-url="item.updateUrl"
                                @submit-success="submitSuccess"
                            ></bank-reconciliation-journal-voucher-view>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <loader 
        :loading="loading">
        </loader>
	</div>
</template>
<script>
	import ListMixin from 'Mixins/list.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import { ModelListSelect } from 'vue-search-select';
    import DateRange from 'Components/datepickers/DateRange.vue';
    import "vue-select/dist/vue-select.css";
    import Vselect from "vue-select";

	export default {
        mixins: [ ListMixin ],

		data() {
			return {
                item: {},
                items: [],
                onEdit: false,
                client: null,
			}
		},

        methods: {
            edit(item) {
                this.onEdit = true;
                this.item = item;
                setTimeout(() => {
                    $(this.$refs.modal).modal('show');
                    this.modalOnclose();
                }, 500);
            },

            modalOnclose() {
                $(this.$refs.modal).on('hidden.bs.modal', () => {
                    setTimeout(() => {
                        this.onEdit = false;
                    }, 500);
                });
            },

            submitSuccess() {
                $(this.$refs.modal).modal('hide');
            },
        },

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'date-range' : DateRange,
            'v-select' : Vselect,
            ModelListSelect,
		},

        props: {
            clients: {
                type: Array,
                default: () => [],
            },
            parent: {
                type: Object,
                default: () => {},
            },
        },
		
		computed: {
			headers() {
                let array = [
                    { text: '#', value:'id' },
                    { text: 'Customer Payment ID', value:'customer_payment_id' },
                    { text: 'Customer Payment Issued Date', value:'customer_payment_issued_date' },
                    { text: 'Customer Name', value:'customer_name' },
                    { text: 'Vendor Payment ID', value:'vendor_payment_id' },
                    { text: 'Vendor Payment Issued Date', value:'vendor_payment_issued_date' },
                    { text: 'Vendor Name', value:'vendor_name' },
                    { text: 'Check ID', value:'check_id' },
                    { text: 'Check Amount', value:'check_amount' },
                    { text: 'Deposit ID', value:'deposit_id' },
                    { text: 'Payment Reference', value:'payment_reference' },
                    { text: 'Created At', value:'created_at' },
                ];

                return array;
            },
            
            filterColumns() {
                let array = [
                    { text: 'Created At', value: 'created_at' },
                ];

                return array;
            },
		}
	}
</script>