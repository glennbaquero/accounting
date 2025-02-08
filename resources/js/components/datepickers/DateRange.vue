 <template>
	<div class="form-group">
		<div class="input-group col-sm-12">
			<v-select class="mr-4 select-size"  v-model="selected" @input="change" label="text" placeholder="Select Date Type" :options="options"></v-select>
			<input v-model="startdate" type="hidden" name="start_date">
			<input v-model="enddate" type="hidden" name="end_date">
			<button :disabled="!selected" ref="elem" type="button" class="btn btn-light border-bottom-right-radius-0 border-top-right-radius-0 border d-inline-block">
				<i class="far fa-calendar mr-2"></i>
				<span>{{ startdate && enddate ? startDisplay + ' to ' + endDisplay : ' Set date range here...' }}</span>
				<i class="fa fa-caret-down ml-2"></i>
			</button>
            <span @click="clear" class="btn btn-light border border-bottom-left-radius-0 border-top-left-radius-0 d-inline-block">
				<i class="fa fa-times"></i>
			</span>
		</div>
	</div>
</template>

<script type="text/javascript">
import dayjs from 'dayjs';
import daterangepicker from 'bootstrap-daterangepicker';
import daterangepickercss from 'bootstrap-daterangepicker/daterangepicker.css';
import "vue-select/dist/vue-select.css";
import Vselect from "vue-select";

export default {
	mounted() {
		this.$nextTick(() => {
			if(!this.loaded) {
				this.init();
			}
		});
	},

	components: {
		'v-select' : Vselect
	},

	methods: {
		init() {
			let daterange = $(this.$refs.elem);
			
			let startDate = dayjs().startOf('year').format('YYYY-MM-DD');
			let endDate = dayjs().endOf('year').format('YYYY-MM-DD');

			setTimeout(() => {
				this.elem = daterange.daterangepicker({
					locale: {
			    		format: 'YYYY-MM-DD',
				    },
					startDate: startDate,
					endDate: endDate,
					opens: 'right',
					ranges: {
						'This Year': [dayjs().startOf('year').format('YYYY-MM-DD'), dayjs().endOf('year').format('YYYY-MM-DD')],
						'Last Year': [dayjs().subtract(1, 'year').startOf('year').format('YYYY-MM-DD'), dayjs().subtract(1, 'year').endOf('year').format('YYYY-MM-DD')],
						'This Month': [dayjs().startOf('month').format('YYYY-MM-DD'), dayjs().endOf('month').format('YYYY-MM-DD')],
						'Last Month': [dayjs().subtract(1, 'month').startOf('month').format('YYYY-MM-DD'), dayjs().subtract(1, 'month').endOf('month').format('YYYY-MM-DD')],
						'Today': [dayjs().startOf('day').format('YYYY-MM-DD'), dayjs().endOf('day').format('YYYY-MM-DD')],
						'Yesterday': [dayjs().startOf('day').subtract(1, 'day').format('YYYY-MM-DD'), dayjs().endOf('day').subtract(1, 'day').format('YYYY-MM-DD')],
					},
				}, (start, end) => {
					this.sync(start, end);
				});

				this.sync(startDate, endDate);
			}, 500);

			setTimeout(() => {
				this.loaded = true;
			}, 600);	
		},

		selectType() {
			if(this.startdate && this.enddate) {
				this.change();
			}
		},

		change() {
			if(this.loaded) {
				this.$emit('change', {
					column : this.selected.value,
					start_date: this.startdate,
					end_date: this.enddate,
				});
			}
		},

		sync: function(startDate, endDate) {
			this.startdate = dayjs(startDate).format('YYYY-MM-DD');
			this.enddate = dayjs(endDate).format('YYYY-MM-DD');

			this.startDisplay = dayjs(startDate).format('MM/D/YYYY');
			this.endDisplay = dayjs(endDate).format('MM/D/YYYY');

			this.change();
       	},

		clear() {
			this.startdate = null;
			this.end_date = null;

			this.change();
		},
	},

	data() {
		return {
			elem: null,
			startdate: null,
			enddate: null,

			startDisplay: null,
			endDisplay: null,

			loaded : false,
			selected : null,
		}
	},

	props: {
		id: {
			default: 'date-range'
		},

		options : {
			default: () => [],
			type : Array,
		},

		name: String,
	}
}
</script>

<style scoped>
.btn {
	cursor: pointer;
}

.select-size {
	width: 200px;
}

.border-top-left-radius-0 {
	border-top-left-radius: 0px;
}

.border-top-right-radius-0 {
	border-top-right-radius: 0px;
}

.border-bottom-left-radius-0 {
	border-bottom-left-radius: 0px;
}

.border-bottom-right-radius-0 {
	border-bottom-right-radius: 0px;
}
</style>