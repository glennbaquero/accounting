<template>
	<div>
        <p>
            <b>Oustanding Balance: {{ total.outstanding_balance }}</b> 
            <b>Amount To Reconcile: {{ total.outstanding_balance - total.matched_deposit }}</b> 
            <b>Matched Amount: {{ total.matched_deposit }}</b>
        </p>
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
        @fetch="fetchSuccess"
        >

            <template v-slot:body="{ items }">
                <tr v-for="(item, index) in items">
                    <td><input type="checkbox" v-model="items[index].matched_checkbox" @change="matchCheck(item)"></td>
                    <td><input type="checkbox" :checked="item.adjustment_checkbox" disabled></td>
                    <td><input type="checkbox" :checked="item.reconciled_checkbox" disabled></td>
                    <td>{{ item.payment_reference }}</td>
                    <td>{{ item.statement_id }}</td>
                    <td>{{ item.withdrawal_debit_amount }}</td>
                    <td>{{ item.deposit_credit_amount }}</td>
                    <td>{{ item.transaction_date }}</td>
                    <td>{{ item.bank_reason }}</td>
                    <td>{{ item.deposit_slip_number }}</td>
                    <td>{{ item.check_number }}</td>
                    <td>
                        <button 
                            class="btn btn-sm btn-primary" 
                            @click="edit(item)"
                        >
                            <i class="fas fa-pen"></i>
                        </button>
                        <!-- <view-button :href="item.showUrl"></view-button> -->
<!--                         <action-button
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
                        :message="`Are you sure you want to archive ${item.statement_id}?`"
                        :alt-message="`Are you sure you want to restore ${item.statement_id}?`"
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
                            <bank-account-statement-line-view
                            :submit-url="'/bank-account-statement-lines/update/' + item.id"
                            :fetch-url="'/bank-account-statement-lines/fetch-item/' + item.id"
                            @submit-success="submitSuccess"
                            ></bank-account-statement-line-view>
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
    import ResponseMixin from 'Mixins/response.js';
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
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'date-range' : DateRange,
            'v-select' : Vselect,
            ModelListSelect,
		},

        methods: {
            matchCheck(item){
                this.loading = true;
                axios.post('/bank-account-statement-lines/match/' + item.id, {
                    matched: item.matched_checkbox,
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

            fetchSuccess(data) {
                this.items = data.items ? data.items : this.items;
            },

            submitSuccess() {
                this.$emit('submit-success');
            },
        },

        mounted() {
            
        },
		
		computed: {
			headers() {
                let array = [
                    { text: 'Matched', value: 'matched_checkbox' },
                    { text: 'Adjustment', value: 'adjustment_checkbox' },
                    { text: 'Reconciled', value: 'reconciled_checkbox' },
                    { text: 'Payment Reference', value: 'payment_reference' },
                    { text: 'Bank Statement', value: 'statement_id' },
                    { text: `Withdrawal - (${this.total.withdrawal})`, value: 'withdrawal_debit_amount' },
                    { text: `Deposit  - (${this.total.deposit})`, value: 'deposit_credit_amount' },
                    { text: 'Transaction Date', value: 'transaction_date' },
                    { text: 'Bank Reason', value: 'bank_reason' },
                    { text: 'Deposit Slip Number', value: 'deposit_slip_number' },
                    { text: 'Check Number', value: 'check_number' },
                ];

                return array;
            },

            total() {
                let items = this.items;
                let result = {
                    withdrawal: 0,
                    deposit: 0,
                    matched_deposit: 0,
                    outstanding_balance: 0,
                };

                items.forEach(item => {
                    let withdrawal = item.withdrawal_debit_amount ? parseFloat(item.withdrawal_debit_amount) : 0;
                    let deposit = item.deposit_credit_amount ? parseFloat(item.deposit_credit_amount) : 0;
                    let outstanding_balance = item.outstanding_balance ? parseFloat(item.outstanding_balance) : 0;

                    result.matched_deposit += item.matched_checkbox ? deposit : 0;
                    result.deposit += deposit;
                    result.withdrawal += withdrawal;
                    result.outstanding_balance += outstanding_balance;
                });

                return result;
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