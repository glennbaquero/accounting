<template>
	<div>
        <button class="btn btn-warning mb-4" @click="post">Post</button>
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
        showSelect
        @selectAll="selectAll(...arguments)"
        >

            <template v-slot:body="{ items }">
                <tr v-for="item in items">
                    <td>
                        <input type="checkbox" :checked="item.alreadyInSelectedItem" @change="dataSelected(item)">
                    </td>
                    <td><input type="checkbox" :checked="item.posted_checkbox" disabled></td>
                    <td><input type="checkbox" :checked="item.approved_checkbox" disabled></td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.operation_type }}</td>
                    <td>{{ item.source }}</td>
                    <td>{{ item.statement_adjustment_id }}</td>
                    <td>{{ item.cash_register_adjustment_id }}</td>
                    <td>{{ item.bank_posting_id }}</td>
                    <td>{{ item.adjustment_name }}</td>
                    <td>{{ item.amount }}</td>
                    <td>
                        <!-- <view-button :href="item.showUrl"></view-button> -->

                        <button 
                            class="btn btn-sm btn-primary" 
                            @click="edit(item)"
                        >
                            <i class="fas fa-pen"></i>
                        </button>

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
                        :message="`Are you sure you want to archive ${item.adjustment_name}?`"
                        :alt-message="`Are you sure you want to restore ${item.adjustment_name}?`"
                        @load="load"
                        @success="sync"
                        ></action-button>
                    </td>
                </tr>
            </template>

        </data-table>

        <template v-if="onEdit">
            <div ref="modal" class="modal fade"  id="ba-lines-modal" tabindex="-1" role="dialog" aria-labelledby="bank_statement_line_modal_label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"  id="bank_statement_line_modal_label">Bank Reconciliation Line</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <bank-reconciliation-line-view
                                ref="form"
                                :clients="clients"
                                :parent="parent"
                                :fetch-url="item.showUrl"
                                :submit-url="item.updateUrl"
                                @submit-success="submitSuccess"
                            ></bank-reconciliation-line-view>
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
                item: {},
                items: [],
                onEdit: false,
                client: null,
			}
		},

        methods: {
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

            submitSuccess() {
                $(this.$refs.modal).modal('hide');
            },

            post() {
                var $this = this;
                swal.fire({
                  title: 'Are you sure?',
                  text: 'Are you sure you want to mark this bank reconcialition as posted?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Confirm',
                  cancelButtonText: 'Cancel'
                }).then((result) => {
                  if (result.value) {
                    $this.$loading.show(true);

                    var ids = _.reduce(this.$refs['data-table'].items, (results, item) => {
                        if(item.alreadyInSelectedItem) {
                            results.push(item.id);
                        }

                        return results;
                    }, [])

                    var selected = {
                        ids: ids
                    }

                    axios.post($this.postUrl, selected)
                        .then(response => {
                            // $this.$notification.show(response.data.message, 'Success')
                            // window.location.href = response.data.redirect;
                            $this.$loading.show(false);
                        }).catch(errors => {

                            $this.$loading.show(false);
                        })
                  }
                })
            },



            selectAll(selected) {
                this.$loading.show(true);
                _.map(this.$refs['data-table'].items, (line) => {
                    line.alreadyInSelectedItem = selected;

                    return line;
                });
                // this.voucher_lines = this.$refs['data-table'].items;
                this.$loading.show(false);
            },

            dataSelected(item) {
                item.alreadyInSelectedItem = !item.alreadyInSelectedItem;
            },
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
            parent: {
                type: Object,
                default: () => {},
            },

            postUrl: String,
        },
		
		computed: {
			headers() {
                let array = [
                    { text: 'Posted', value: 'posted_checkbox'},
                    { text: 'Approved', value: 'approved_checkbox'},
                    { text: 'Description', value: 'description'},
                    { text: 'Operation Type', value: 'operation_type'},
                    { text: 'Source', value: 'source'},
                    { text: 'Statement Adjustment ID', value: 'statement_adjustment_id'},
                    { text: 'Cash Register Adjustment ID', value: 'cash_register_adjustment_id'},
                    { text: 'Bank Posting ID', value: 'bank_posting_id'},
                    { text: 'Adjustment Name', value: 'adjustment_name'},
                    { text: 'Amount', value: 'amount'},
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