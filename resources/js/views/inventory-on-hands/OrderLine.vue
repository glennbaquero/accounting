<template>
		<div>
			<filter-box @refresh="fetch">
	            <template v-slot:left>

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
	                <tr v-for="item in items">
	                    <td>{{ item[lineNumber] }}</td>
	                    <td>{{ item[orderNumber] }}</td>
	                    <td>{{ item.order_date }}</td>
	                    <td>{{ item.confirmed_delivery_date }}</td>
	                    <td>{{ item[userType] }}</td>
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

	export default {
		props: {
			lineNumber: String,
			orderNumber: String,
			type: String,
			user: String,
			userType: String
		},

		mixins: [ ListMixin ],

		data() {
			return {
				items: []
			}
		},

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
		},

		computed: {
			headers() {
				let array = [
				    { text: this.type + ' Order Line Number', value: this.lineNumber },
                    { text: this.type + ' Order Number', value: this.orderNumber },
				    { text: this.type + ' Date', value: 'order_date' },
                    { text: 'Confirmed Date', value: 'confirmed_delivery_date' },
				    { text: this.user, value: this.userType },
                    
				];

				return array;
			}
		},

	}
</script>
