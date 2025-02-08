<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <v-select class="ml-2 select-size" @input="filter($event, 'status')" :options="statuses" v-model="status" :reduce="item => item.value" label="name" placeholder="Filter by Status"></v-select>
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
                <tr :key="item.id" v-for="item in items">
                    <td>{{ item.client }}</td>
                    <td><span class="badge" :class="item.status.class">{{ item.status.label }}</span></td>
                    <td>{{ item.ledger }}</td>
                    <td>{{ item.main_account }}</td>
                    <td>{{ item.debit_account_number }}</td>
                    <td>{{ item.ledger_posting_debit }}</td>
                    <td>{{ item.credit_account_number }}</td>
                    <td>{{ item.ledger_posting_credit }}</td>
                    <td>{{ item.calendar_type }}</td>
                    <td>{{ item.length }}</td>
                    <td>{{ item.posting_date }}</td>
                    <td>{{ item.approved_date }}</td>
                    <td>
                        <view-button :href="item.showUrl"></view-button>
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
                        :message="'Are you sure you want to archive Cost Center ' + item.accrual_posting + '?'"
                        :alt-message="'Are you sure you want to restore Cost Center ' + item.accrual_posting + '?'"
                        @load="load"
                        @success="sync"
                        ></action-button>
                    </td>
                </tr>
            </template>

        </data-table>

        <loader 
        :loading="loading">
        </loader>
	</div>
</template>
<script>
	import ListMixin from 'Mixins/list.js';
    import HelperMixin from 'Mixins/helpers.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import Vselect from 'vue-select';

	export default {
		mixins: [ ListMixin, HelperMixin],

		data() {
			return {
				items: [],
                status : null,
                statuses : [
                    {'name' : 'Pending', value : 'pending' },
                    {'name' : 'Approved', value : 'approved' },
                    {'name' : 'Rejected', value : 'rejected' },
                ],
			}
		},

        props : {
            user : {
                default : null,
                type : Number,
            },

            attach : {
                default : null,
                type : Boolean,
            },

            detach : {
                default : null,
                type : Boolean,
            },
        },

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'v-select' : Vselect,
		},

		computed: {
			headers() {
				let array = [
				    { text: 'Client', value: 'client' },
                    { text: 'Status', value: 'status' },
                    { text: 'Ledger', value: 'ledger' },
                    { text: 'Main Account', value: 'main_account' },
				    { text: 'Debit Account #', value: 'debit_account_number' },
                    { text: 'Ledger Posting Debit', value: 'ledger_posting_debit' },
                    { text: 'Credit Account #', value: 'credit_account_number' },
                    { text: 'Ledger Posting Credit', value: 'ledger_posting_credit' },
                    { text: 'Calendar Type', value: 'calendar_type' },
                    { text: 'Length', value: 'length' },
                    { text: 'Posting Date', value: 'posting_date' },
                    { text: 'Approved Date', value: 'approved_date' },
				];
				return array;
			}
		},

        methods : {
            addUserId(value) {
                return this.insertParam(value ,'user', this.user);
            }
        },
	}
</script>