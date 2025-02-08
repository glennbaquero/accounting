<template>
	<div>
        <p>
            <b>Oustanding Balance: {{ total.outstanding_balance }}</b> 
            <b>Amount To Reconcile: {{ total.outstanding_balance - total.matched_debit_amount }}</b> 
            <b>Matched Amount: {{ total.matched_debit_amount }}</b>
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
                    <td><input type="checkbox" v-model="items[index].matched" @change="matchCheck(item)"></td>
                    <td><input type="checkbox" :checked="item.adjustment_checkbox" disabled></td>
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
                            <h5 class="modal-title"  id="bank_statement_line_modal_label">Cash Register</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <cashflow-transaction-view
                            :submit-url="'/cash-register-transactions/update/' + item.id"
                            :fetch-url="'/cash-register-transactions/fetch-item/' + item.id"
                            @submit-success="submitSuccess"
                            ></cashflow-transaction-view>
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

        methods: {
            matchCheck(item) {
                this.loading = true;
                axios.post('/cash-register-transactions/match/' + item.id, {
                    matched: item.matched,
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
		
		computed: {
			headers() {
                let array = [
                    { text: 'Matched', value: 'matched' },
                    { text: 'Adjustment', value: 'adjustment_checkbox' },
                    { text: 'Cash Register', value: 'journal_name' },
                    { text: `Debit - (${this.total.debit_amount})`, value: 'debit_amount' },
                    { text: `Credit - (${this.total.credit_amount})`, value: 'credit_amount' },
                    { text: 'Payment Reference', value: 'payment_reference' },
                ];

                return array;
            },

            total() {
                let items = this.items;
                let result = {
                    debit_amount: 0,
                    matched_debit_amount: 0,
                    credit_amount: 0,
                    outstanding_balance: 0,
                };

                items.forEach(item => {
                    let debit_amount = item.debit_amount ? parseFloat(item.debit_amount) : 0;
                    let credit_amount = item.credit_amount ? parseFloat(item.credit_amount) : 0;
                    let outstanding_balance = item.outstanding_balance ? parseFloat(item.outstanding_balance) : 0;

                    result.matched_debit_amount += item.matched ? debit_amount : 0;
                    result.debit_amount += debit_amount;
                    result.credit_amount += credit_amount;
                    // result.outstanding_balance += outstanding_balance;
                });

                result.outstanding_balance = result.debit_amount - result.credit_amount;

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