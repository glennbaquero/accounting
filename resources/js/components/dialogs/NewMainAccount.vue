<template>
	<div class="modal fade" id="new-mainaccount">
        <div class="modal-dialog modal-dialog-centered modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ modalTitle }}</h5>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary" @click="addMainAccount">Add Main Account</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Main account</label>
                                    <input type="text" class="form-control" v-model="item.main_account" name="main_account">
                                </div>
                                <div class="col-sm-12">
                                    <label>Main account Name</label>
                                    <input type="text" class="form-control" v-model="item.main_account_name" name="main_account_name">
                                </div>                                
                                <div class="col-sm-12">
                                    <label>Main account type</label>
                                    <input type="text" class="form-control" v-model="item.main_account_type" name="main_account_type">
                                </div>
                                <div class="col-sm-12">
                                    <label>Main account category</label>
                                    <input type="text" class="form-control" v-model="item.main_account_category" name="main_account_category">
                                </div>
                                <div class="col-sm-12">
                                    <label>Created by</label>
                                    <input type="text" class="form-control" v-model="selected.created_by" readonly>
                                </div>
                                <div class="col-sm-12">
                                    <label>Created on</label>
                                    <input type="text" class="form-control" ref="created_on" v-model="selected.created_on" readonly>
                                </div>
                                <div class="col-sm-12">
                                    <label>Updated by</label>
                                    <input type="text" class="form-control" v-model="selected.updated_by" readonly>
                                </div>
                                <div class="col-sm-12">
                                    <label>Updated on</label>
                                    <input type="text" class="form-control" ref="updated_on" v-model="selected.updated_on" readonly>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	import { bus }from 'Root/bus.js';
    import NewMainAccount from 'Components/dialogs/NewMainAccount.vue';

	export default {
		props: {
	        // cost_centers: Array,
	        // departments: Array,
	        // expense_purposes: Array,
	        submitUrl: String,
            modalTitle: String,

		},

		data() {
			return {
                item: {},
                selected: {},

			}
		},

        mounted() {
            flatpickr(this.$refs.created_on)
            flatpickr(this.$refs.updated_on)

        },

        methods: {
        	addMainAccount() {
        		axios.post(this.submitUrl, this.item)
        			.then(response => {
        				this.$emit('success');
        			}).catch(error => {

        			})
        	}
        }
	}
</script>