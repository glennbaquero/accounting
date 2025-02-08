<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <date-range
                    :options="filterColumns"
                    class="mr-2"
                    @change="filter($event)">
                </date-range>
                <v-select style="margin-left:20px" class="mr-4 select-size"  v-model="client" label="name" placeholder="Select Client" :options="clients"></v-select>
            </template>
            <template v-slot:right>
                <search-form @search="filter($event, 'search')"></search-form>
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
            @load="load">
            <template v-slot:body="{ items }">
                <tr :key="item.id" v-for="item in items">
                    <td>{{ item.client }}</td>
					<td>{{ item.vendor_payment_number }}</td>
					<td>{{ item.method_of_payment_name }}</td>
					<td>{{ item.bank_account }}</td>
					<td>{{ item.payment_status }}</td>
                    <td>PHP {{ item.total_amount | currency }}</td>
					<td>{{ item.issue_date }}</td>
					<td>{{ item.payment_release_date }}</td>
					<td>{{ item.clearing_date }}</td>
					<td>{{ item.due_date }}</td>
                    <td v-if="isApproved">{{ item.approved_date }}</td>
                    <td v-if="isPosted">{{ item.posting_date }}</td>
					<td>{{ item.payee }}</td>
					<td>{{ item.vendor_name }}</td>
					<td>{{ item.created_at }}</td>
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
                        :message="'Are you sure you want to archive Cost Center ' + item.code + '?'"
                        :alt-message="'Are you sure you want to restore Cost Center ' + item.code + '?'"
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
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import Vselect from "vue-select";
    import DateRange from 'Components/datepickers/DateRange.vue';

	export default {
        mixins: [ ListMixin ],
        
        props: {
            isApproved: Boolean,
            isPosted: Boolean,
            clients : {
                default : [],
                type : Array
            }
        },

        watch : {
            'client'(value) {
                this.filter(value, 'client');
            }
        },

		data() {
			return {
                items: [],
                client: null
			}
		},

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'v-select' : Vselect,
            'date-range' : DateRange,
		},

		computed: {
			headers() {
				let array = [
                     { text: 'Client', value: 'client' },
                    { text: 'Payment Number', value: 'vendor_payment_number' },
                    { text: 'Method Of Paayment', value: 'method_of_payment_name' },
                    { text: 'Bank Account', value: 'bank_account' },
					{ text: 'Payment Status', value: 'payment_status' },
					{ text: 'Check Amount', value: 'total_amount' },
					{ text: 'Issue Date', value: 'issue_date' },
					{ text: 'Payment Release Date', value: 'payment_release_date' },
					{ text: 'Clearing Date', value: 'clearing_date' },
					{ text: 'Payment Due Date', value: 'due_date' },
                ];

                if (this.isApproved) {
                    let approved = [
                        { text: 'Approved Date', value: 'approved_date' }
                    ];
                    array = [ ...array, ...approved ];
                } else if (this.isPosted) {
                    let posted = [
                        { text: 'Posting Date', value: 'posting_date' }
                    ];
                    array = [ ...array, ...posted ];
                }
                
                let foot = [
					{ text: 'Payee', value: 'payee' },
					{ text: 'Vendor Account', value: 'vendor_name' },
					{ text: 'Created Date', value: 'created_at' }
                ];

                return [...array, ...foot];
			},
            
            filterColumns() {
                let array = [
                    { text: 'Issue Date', value: 'issue_date' },
                    { text: 'Payment Release Date', value: 'payment_release_date' },
                    { text: 'Clearing Date', value: 'clearing_date' },
                    { text: 'Due Date', value: 'due_date' },
                    { text: 'Maturity Date', value: 'maturity_date' },
                    { text: 'Received Date', value: 'received_date' },
                    { text: 'Created At', value: 'created_at'  },
                    { text: 'Delivery Date', value: 'delivery_date' },
                ];
                return array;
            }
		}
	}
</script>