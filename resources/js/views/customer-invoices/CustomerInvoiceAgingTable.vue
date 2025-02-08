<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <date-range
                    :options="filterColumns"
                    class="mr-2"
                    @change="filter($event)">
                </date-range>                
                <v-select style="margin-left:20px" class="mr-4 select-size"  v-model="client" label="name" placeholder="Select Date Type" :options="agingSelections"></v-select>
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
        :no-action="true"
        :disabled="disabled"
        order-by="id"
        order-desc
        @load="load"
        >

            <template v-slot:body="{ items }">
                <tr v-for="item in items" v-bind:key="item.id">
                    <td> {{ item.customer }} </td>
                    <td> {{ item.invoice_number }} </td>
                    <td> {{ item.due_date }} </td>
                    <td> {{ item.thirty_days_old }} </td>
                    <td> {{ item.sixty_days_old }} </td>
                    <td> {{ item.ninety_days_old }} </td>
                    <td> {{ item.older_90_days_old }} </td>
                    <td> {{ item.grand_total }} </td>
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
	import { bus }from 'Root/bus.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import Vselect from "vue-select";
    import DateRange from 'Components/datepickers/DateRange.vue';

	export default {
	    mixins: [ ListMixin ],

		data() {
			return {
				items: [],
                client: null,
                agingSelections: [
                    {
                        name: "This year",
                    },
                    {
                        name: "Last year",
                    },
                    {
                        name: "This month",
                    },
                    {
                        name: "Last month",
                    },
                    {
                        name: "Today",
                    },
                    {
                        name: "Yesterday",
                    },
                    {
                        name: "Custom Range",
                    },
                ]
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
                    { text: 'Customer', value: 'customer' },
                    { text: 'Invoice number', value: 'invoice_number' },
                    { text: 'Due date', value: 'due_date' },
                    { text: '0 to 30 days old', value: '' },
                    { text: '31 to 60 days old', value: '' },
                    { text: '61 to 90 days old', value: '' },
                    { text: 'Older than 90 days old', value: '' },
                    { text: 'Grand total', value: '' },
                ];
                return array;
			},
		}
	}
</script>