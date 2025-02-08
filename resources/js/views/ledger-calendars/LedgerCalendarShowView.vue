<template>
	<div>
		<div class="card mt-4">

			<div class="card-header p-2">
				<hr>

				<div class="row">
					<div class="col-md-4">
						Calendar
						<input type="text" class="form-control" name="" value="Financial">
					</div>
					<div class="col-md-4">
						Description
						<textarea class="form-control">Financial</textarea>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label>YEAR</label>
					</div>
					<div class="col-md-12">
						Financial year : 07/01/2020
					</div>
					<div class="col-md-12">
						Start date : 07/01/2020
					</div>
					<div class="col-md-12">
						End date : 06/30/2021
					</div>

					<div class="col-md-12">
						Status : Open
					</div>
				</div>
				
			</div>
			<div class="card-body">
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Periods</b></h3>

			            <div class="card-tools">
			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
				    		<div class="col-sm-12">
				    			<div class="table-responsive">
				    			    <table id="journalTable" class="dataTable table table-striped text-center" :style="{ whiteSpace: showOnlyDatePicker ? 'nowrap' : ''}">
				    			    	<tr>
				    			    		<th v-for="header in headers">{{ header.text }}</th>
				    			    	</tr>
				    			    	<tr v-for="item in items" @click="selectedLine(item)" :class="item.selected ? 'selected-table' : ''">
				    			    		<td>{{ item.period_name }}</td>
				    			    		<td>{{ item.type }}</td>
				    			    		<td @dblclick="item.show_period_start_date = false">
		                                    	<template v-if="item.show_period_start_date">
		                                    		{{ item.period_start }}
		                                    	</template>
		                                    	<template v-else>
									              	<datepicker format="M/dd/yyyy"  v-model="item.period_start" name="date" input-class="form-control input-sm" @closed="datePickerClosed(item, 'period_start')"></datepicker>
		                                    	</template>
				    			    		</td>
				    			    		<td @dblclick="item.show_period_end_date = false">
		                                    	<template v-if="item.show_period_end_date">
		                                    		{{ item.end_date }}
		                                    	</template>
		                                    	<template v-else>
									              	<datepicker format="M/dd/yyyy"  v-model="item.end_date" name="date" input-class="form-control input-sm" @closed="datePickerClosed(item, 'end_date', false)"></datepicker>
		                                    	</template>
				    			    		</td>
				    			    		<td>{{ item.short_name }}</td>
				    			    		<td>{{ item.month }}</td>
				    			    		<td>{{ item.quarter }}</td>
				    			    		<td>{{ item.comments }}</td>
				    			    		<td @dblclick="item.show_period_status = false">
		                                    	<template v-if="item.show_period_status">
		                                    		{{ item.period_status }}
		                                    	</template>
		                                    	<template v-else>
		                                    		<select class="form-control" @change="item.show_period_status = true">
		                                    			<option value="Closed">Closed</option>
		                                    			<option value="Open">Open</option>
		                                    			<option value="On hold">On hold</option>
		                                    		</select>
		                                    	</template>
				    			    		</td>
				    			    	</tr>
				    			    </table>
				    			</div>
				    		</div>
				    	</div>
				    </div>
				</div>
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Module access level</b></h3>

			            <div class="card-tools">
			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
				    		<div class="col-sm-12">
				    			<div class="table-responsive">
				    			    <table class="dataTable table table-striped text-center" style="white-space: nowrap;">
				    			    	<tr>
				    			    		<th v-for="header in module_access_headers">{{ header.text }}</th>
				    			    	</tr>
				    			    	<tr v-for="item in module_access_items" @click="selectedLine(item)" :class="item.selected ? 'selected-table' : ''">
				    			    		<td @dblclick="item.show_name = false">
		                                    	<template v-if="item.show_name">
		                                    		{{ item.name }}
		                                    	</template>
		                                    	<template v-else>
		                                    		<input type="text" name="" v-model="item.name" class="form-control" @keyup.enter="item.show_name = true">
		                                    	</template>
				    			    		</td>
				    			    		<td @dblclick="item.show_access_level = false">
		                                    	<template v-if="item.show_access_level">
		                                    		{{ item.access_level }}
		                                    	</template>
		                                    	<template v-else>
		                                    		<select class="form-control" v-model="item.access_level" @change="item.show_access_level = true">
		                                    			<option value="None">None</option>
		                                    			<option value="All">All</option>
		                                    			<option value="User group">User group</option>
		                                    		</select>
		                                    	</template>
				    			    		</td>
				    			    		<td @dblclick="item.show_user_group = false">
		                                    	<template v-if="item.show_user_group">
		                                    		{{ item.user_group }}
		                                    	</template>
		                                    	<template v-else>
		                                    		<select class="form-control" v-model="item.user_group" @change="item.show_user_group = true">
		                                    			<option value="APMgr">APMgr</option>
		                                    		</select>
		                                    	</template>
				    			    		</td>
				    			    	</tr>
				    			    </table>
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
	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';

	export default {
		props: {
			submitUrl: String
		},

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
		},

		computed: {
		    headers() {
		        let array = [
		            { text: 'Period name', value: '' },
		            { text: 'Type', value: null },
		            { text: 'Period start', value: null },
		            { text: 'End date', value: null },
		            { text: 'Short name', value: null },
		            { text: 'Month', value: null },
		            { text: 'Quarter', value: null },
		            { text: 'Comments', value: null },
		            { text: 'Period status', value: null },
		        ];

		        return array;
		    },

		    module_access_headers() {

		    	let array = [
		    	    { text: 'Name', value: '' },
		    	    { text: 'Access level', value: null },
		    	    { text: 'User group', value: null },
		    	];

		    	return array;
		    }
		},

		data() {
			return {
				items: [
					{
						period_name: 'Period 12',
						type: 'Operating',
						period_start: '06/01/2020',
						end_date: '06/30/2020',
						month: 'Twelve',
						short_name: null,
						quarter: 'Quarter 4',
						comment: null,
						period_status: 'Open',
						show_period_start_date: true,
						show_period_end_date: true,
						show_period_status: true,
					},
					{
						period_name: 'Period 13',
						type: 'Closing',
						period_start: '06/30/2020',
						end_date: '06/30/2020',
						month: 'Twelve',
						short_name: null,
						quarter: 'Quarter 4',
						comment: null,
						period_status: 'Open',
						show_period_start_date: true,
						show_period_end_date: true,
						show_period_status: true,
					},
				],

				module_access_items: [
					{
						id: 1,
						name: 'Ledger',
						access_level: 'All',
						user_group: null,

						show_user_group: true,
						show_access_level: true,
						show_name: true
					},

					{
						id: 2,
						name: 'Customer',
						access_level: 'None',
						user_group: 'APMgr',

						show_user_group: true,
						show_access_level: true,
						show_name: true
					}
				],
				showOnlyDatePicker: false,
			}
		},

		mounted() {
			//
		},

		methods: {
			datePickerClosed(item, row, start = true) {
				item[row] = moment(item[row]).format('MM/DD/YYYY');

				if(start) {
					item.show_period_start_date = true;
				} else {
					item.show_period_end_date = true;
				}
			},
		}
	}
</script>