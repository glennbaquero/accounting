<template>
	<div>
		<filter-box @refresh="fetch">
            
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
                    <td><input type="checkbox" v-model="item.adjustment_checkbox" @change="adjustment(item)" :disabled="item.approved_by"></td>
                    <td>{{ item.approved_date }}</td>
                    <td>{{ item.approved_by }}</td>
                    <td>{{ item.journal_name }}</td>
                    <td>{{ item.debit_amount }}</td>
                    <td>{{ item.credit_amount }}</td>
                    <td>{{ item.payment_reference }}</td>
                    <td>
                        <button 
                            class="btn btn-sm btn-primary" 
                            @click="edit(item)"
                        >
                            <i class="fas fa-pen"></i>
                        </button>

                        <action-button
                            small 
                            color="btn-success"
                            :action-url="'/cash-register-transaction-adjustments/approve/' + item.id"
                            icon="fas fa-check"
                            confirm-dialog
                            title="Approve Item"
                            message="Are you sure you want to approve this?"
                            @load="load"
                            @success="sync"
                            :disabled="!item.adjustment_checkbox || loading || item.approved_by"
                        ></action-button>

                        <!-- <view-button :href="item.showUrl"></view-button> -->
   <!--                      <action-button
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
                        :message="`Are you sure you want to archive ${item.journal_name}?`"
                        :alt-message="`Are you sure you want to restore ${item.journal_name}?`"
                        @load="load"
                        @success="sync"
                        ></action-button> -->
                    </td>
                </tr>
            </template>

        </data-table>

        <template v-if="onEdit">
            <div ref="modal" class="modal fade"  id="ba-lines-modal" tabindex="-1" role="dialog" aria-labelledby="bank_statement_line_modal_label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"  id="bank_statement_line_modal_label">Bank Statement Line</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <cash-register-adjustment-view
                            :submit-url="'/cash-register-transaction-adjustments/update/' + item.id"
                            :fetch-url="'/cash-register-transaction-adjustments/fetch-item/' + item.id"
                            @submit-success="submitSuccess"
                            ></cash-register-adjustment-view>
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
    import CashRegisterAdjustmentView from './CashRegisterAdjustmentView.vue';
	import ResponseMixin from 'Mixins/response.js';
    import ListMixin from 'Mixins/list.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import { ModelListSelect } from 'vue-search-select';
    import DateRange from 'Components/datepickers/DateRange.vue';
    import "vue-select/dist/vue-select.css";
    import Vselect from "vue-select";

	export default {
        mixins: [ ListMixin, ResponseMixin ],

        props : {
            createUrl: String,
            clientBank: String,
            filterMatched: {
                type: Boolean,
                default: false,
            },
        },

		data() {
			return {
                item: {},
                items: [],
                onEdit: false,
                filters:{
                    order:"desc",
                    orderBy:"id",
                    page:1,
                    per_page:10,
                    matched: this.filterMatched,
                }
			}
		},

		components: {
            'cash-register-adjustment-view': CashRegisterAdjustmentView,
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'date-range' : DateRange,
            'v-select' : Vselect,
            ModelListSelect,
		},

        methods: {
            adjustment(item) {
                this.loading = true;
                axios.post('/cash-register-transaction-adjustments/adjustment/' + item.id, {
                    adjustment: item.adjustment_checkbox,
                }).then(response => {
                    this.parseSuccess(response);
                    this.submitSuccess();
                    this.loading = false;
                }).catch(error => {
                    this.parseError(error);
                    this.loading = false;
                });
            },

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
                this.$emit('submit-success');
            },
        },
		
		computed: {
			headers() {
                let array = [
                    { text: 'Adjustment', value: 'adjustment_checkbox' },
                    { text: 'Approved Date', value: 'approved_date' },
                    { text: 'Approved By', value: 'approved_by' },
                    { text: 'Cash Register', value: 'journal_name' },
                    { text: 'Debit', value: 'debit_amount' },
                    { text: 'Credit', value: 'credit_amount' },
                    { text: 'Payment Reference', value: 'payment_reference' },
                ];

                return array;
            },
            
            filterColumns() {
                let array = [
                    { text: 'Created At', value: 'created_at' },
                ];

                return array;
            },

		},

        watch: {
            clientBank() {
                this.filter(this.clientBank, 'bank_account');
            },
        },
	}
</script>