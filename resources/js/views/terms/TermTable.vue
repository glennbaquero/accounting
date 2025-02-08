<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <!-- <date-range
                class="mr-2"
                @change="filter($event)"
                ></date-range>

                <selector
                v-if="filterTypes"
                class="mt-2"
                :items="filterTypes"
                @change="filter($event, 'type')"
                placeholder="Filter by type"
                ></selector> -->
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
                    <td>{{ item.terms_of_payment }}</td>
                    <td>{{ item.payment_method }}</td>
                    <td v-html="item.description"></td>
                    <td>{{ item.payment_day }}</td>
                    <td>{{ item.cutoff_day }}</td>
                    <!-- <td>{{ item.ledger_posting_cash }}</td> -->
                    <td>{{ item.created_at }}</td>
                    <td>
                        <view-button :href="item.showUrl"></view-button>
                        
                        <action-button
                        v-if="!hideButtons"
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
                        :message="'Are you sure you want to archive Terms of payment ' + item.terms_of_payment + '?'"
                        :alt-message="'Are you sure you want to restore Terms of payment ' + item.terms_of_payment + '?'"
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
	import { bus }from 'Root/bus.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';

	export default {
	    mixins: [ ListMixin ],

		data() {
			return {
				items: []
			}
		},

		computed: {
			headers() {
				let array = [
				    { text: '#', value: 'id' },
				    { text: 'Terms of payment', value: 'terms_of_payment' },
				    { text: 'Payment Method', value: 'payment_method' },
				    { text: 'Description', value: 'description' },
				    // { text: 'Cash payment', value: 'cash_payment' },
				    { text: 'Payment day', value: 'payment_day' },
				    { text: 'Cutoff day', value: 'cutoff_day' },
				    // { text: 'Ledger posting cash', value: 'ledger_posting_cash' },
				];


				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		},

        components: {
            'search-form': SearchForm,
            'action-button': ActionButton,
            'view-button': ViewButton,
        },
	}
</script>