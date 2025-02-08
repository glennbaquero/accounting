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
        :no-action="noAction"
        :disabled="disabled"
        order-by="id"
        order-desc
        @load="load"
        >
            <template v-slot:body="{ items }">
                <tr :key="item.id" v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.user_count }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>
                        <view-button :href="item.showUrl"></view-button>
                        
                        <action-button
                         v-if="!attach && !detach"
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


                        <action-button
                        v-if="attach"
                        small 
                        color="btn-success"
                        :action-url="addUserId(item.userAttachUrl)"
                        icon="fa fa-plus"
                        confirm-dialog
                        :disabled="loading"
                        title="Add Client"
                        :message="'Are you sure you want to add ' + item.name + 'as a client to this user ?'"
                        @load="load"
                        @success="sync"
                        ></action-button>


                        <action-button
                        v-if="detach"
                        small 
                        color="btn-warning"
                        :action-url="addUserId(item.userDetachUrl)"
                        icon="fa fa-minus"
                        confirm-dialog
                        :disabled="loading"
                        title="Add Client"
                        :message="'Are you sure you want to remove ' + item.name + 'as a client to this user ?'"
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

	export default {
		mixins: [ ListMixin, HelperMixin],

		data() {
			return {
				items: []
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
		},

		computed: {
			headers() {
				let array = [
				    { text: '#', value: 'id' },
				    { text: 'Name', value: 'name' },
				    { text: 'Users Count', value: 'user_count' },
				];

				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

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