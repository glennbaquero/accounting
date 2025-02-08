<template>
	<div>
		<div class="card mt-4">

			<div class="card-header p-2">
				<hr>

				<div class="row">
					<div class="col-md-4">
						Calendar
						<input type="text" class="form-control" name="" value="Fiscal">
					</div>
					<div class="col-md-4">
						Description
						<textarea class="form-control"></textarea>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label>YEAR</label>
					</div>
					<div class="col-md-12">
						Fiscal year : 2020
					</div>
					<div class="col-md-12">
						Start date : 01/01/2020
					</div>
					<div class="col-md-12">
						End date : 12/31/2020
					</div>
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
		    			    	</tr>
		    			    </table>
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
		        ];

		        return array;
		    },
		},

		data() {
			return {
				items: [
					{
						period_name: 'Period 0',
						type: 'Opening',
						period_start: '01/01/2020',
						end_date: '01/01/2020',
						month: 'One',
						short_name: null,
						quarter: 'Quarter 1',
						comment: null,
						show_period_start_date: true,
						show_period_end_date: true
					},
					{
						period_name: 'Period 1',
						type: 'Operating',
						period_start: '01/01/2020',
						end_date: '01/31/2020',
						month: 'One',
						short_name: null,
						quarter: 'Quarter 1',
						comment: null,
						show_period_start_date: true,
						show_period_end_date: true
					},
					{
						period_name: 'Period 2',
						type: 'Operating',
						period_start: '02/01/2020',
						end_date: '02/28/2020',
						month: 'Two',
						short_name: null,
						quarter: 'Quarter 1',
						comment: null,
						show_period_start_date: true,
						show_period_end_date: true
					},
					{
						period_name: 'Period 3',
						type: 'Operating',
						period_start: '03/31/2020',
						end_date: '01/31/2020',
						month: 'Three',
						short_name: null,
						quarter: 'Quarter 1',
						comment: null,
						show_period_start_date: true,
						show_period_end_date: true
					},
					{
						period_name: 'Period 4',
						type: 'Operating',
						period_start: '04/01/2020',
						end_date: '04/30/2020',
						month: 'Four',
						short_name: null,
						quarter: 'Quarter 2',
						comment: null,
						show_period_start_date: true,
						show_period_end_date: true
					},
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