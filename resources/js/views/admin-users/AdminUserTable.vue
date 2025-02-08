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
                <tr :key="item.id" v-for="item in items">
                    <td>{{ item.id }}</td>
                    <template v-if="tableType == 'company-admin'">
                        <td>{{ item.company }}</td>
                    </template>
                    <td>{{ item.role }}</td>
                    <td>{{ item.fullname }}</td>
					<td>{{ item.status }}</td>
                    <td>{{ item.active_from }}</td>
                    <td>{{ item.active_to }}</td>
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
                        :message="'Are you sure you want to archive this user? (' + item.fullname + ')'"
                        :alt-message="'Are you sure you want to restore user? ('+ item.fullname + ')'"
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

        props : {
            tableType : {
                default : null,
                type : String,
            }
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
                
				let array = this.tableType == 'company-admin' ?
                [
				    { text: '#', value: 'id' },
                    { text: 'Company', value: 'fullname' },
                    { text: 'Role', value: 'role' },
				    { text: 'Fullname', value: 'fullname' },
					{ text: 'Status', value: 'status' },
				    { text: 'Active from', value: 'active_from' },
				    { text: 'Active to', value: 'active_to' },
				] : 
                [
				    { text: '#', value: 'id' },
                    { text: 'Role', value: 'role' },
				    { text: 'Fullname', value: 'fullname' },
					{ text: 'Status', value: 'status' },
				    { text: 'Active from', value: 'active_from' },
				    { text: 'Active to', value: 'active_to' },
				] 


				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		}
	}
</script>