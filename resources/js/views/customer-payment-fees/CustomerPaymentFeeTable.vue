<template>
	<div>
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
					<td>{{ item.id }}</td>
					<td>{{ item.fee_id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.charge_to }}</td>
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
                        :message="'Are you sure you want to archive payment fee ' + item.id + '?'"
                        :alt-message="'Are you sure you want to restore payment fee ' + item.id + '?'"
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
            },
        },

		data() {
			return {
                items: [],
			}
		},

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'v-select' : Vselect,
            'date-range' : DateRange
		},

		computed: {
			headers() {
                let array = [
                    { text: 'ID', value: 'id' },
                    { text: 'Fee ID', value: 'fee_id' },
                    { text: 'Name', value: 'name' },
                    { text: 'Charge To', value: 'charge_to' },
                ];

                return array;
			},

		}
	}
</script>