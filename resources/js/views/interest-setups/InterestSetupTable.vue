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
                    <td>{{ item.id }}</td>
                    <td>{{ item.interest_code }}</td>
                    <td>{{ item.interest_name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.interest_type }}</td>
                    <td>{{ item.grace_period }}</td>
                    <td>{{ item.effective_date }}</td>
                    <td>{{ item.expiration_date }}</td>
                    <td>{{ item.calculate_interest_every }}</td>
                    <td>{{ item.interest_earning_debit }}</td>
                    <td>{{ item.interest_range_by }}</td>
                    <td>{{ item.interest_amount }}</td>
                    <td>{{ item.minimum_interest_amount }}</td>
                    <td>{{ item.maximum_interest_amount }}</td>
                    <td>{{ item.charge_customer_when_interest_exceeds }}</td>
                    <td>{{ item.fee_amount }}</td>
                    <td>{{ item.fee_account }}</td>
                    <td>{{ item.sales_tax }}</td>
                    <td>{{ item.interest_payment_credit_account }}</td>
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
                        :message="`Are you sure you want to archive ${item.interest_name}?`"
                        :alt-message="`Are you sure you want to restore ${item.interest_name}?`"
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
    import { ModelListSelect } from 'vue-search-select';
    import DateRange from 'Components/datepickers/DateRange.vue';
    import "vue-select/dist/vue-select.css";
    import Vselect from "vue-select";

	export default {
        mixins: [ ListMixin ],

		data() {
			return {
                items: [],
                client: null,
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

        props: {
            clients: {
                type: Array,
                default: () => [],
            },
        },
		
		computed: {
			headers() {
                let array = [
                    { text:'#', value:'id' },
                    { text:'Interet Code', value:'interest_code' },
                    { text:'Interest Name', value:'interest_name' },
                    { text:'Description', value:'description' },
                    { text:'Interest Type', value:'interest_type' },
                    { text:'Grace Period', value:'grace_period' },
                    { text:'EFfective Date', value:'effective_date' },
                    { text:'Expiration Date', value:'expiration_date' },
                    { text:'Calculate Interet Every', value:'calculate_interest_every' },
                    { text:'Interest Eearning Debit', value:'interest_earning_debit' },
                    { text:'Interest Range By', value:'interest_range_by' },
                    { text:'Interest Amount', value:'interest_amount' },
                    { text:'Minimum Interest Amount', value:'minimum_interest_amount' },
                    { text:'Maximum Interest Amount', value:'maximum_interest_amount' },
                    { text:'Charge Customer When Interest Exceeds', value:'charge_customer_when_interest_exceeds' },
                    { text:'Fee Amount', value:'fee_amount' },
                    { text:'Fee Accopunt', value:'fee_account' },
                    { text:'Sales Tax', value:'sales_tax' },
                    { text:'Interest Payment Credit Account', value:'interest_payment_credit_account' },
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