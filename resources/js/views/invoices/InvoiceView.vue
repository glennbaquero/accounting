<template>
	<div>
    	<div class="row">
    		<div class="form-group col-sm-3">
                <label>Customer</label>
                <selectize name="customer">
                    <option>TEst</option>
                    <option><button>TEET</button></option>
                </selectize>
    		</div>
    		<div class="form-group col-sm-3">
    			<label>Customer Email</label>
                <input name="customer_email" type="email" class="form-control">
                <p><input type="checkbox" name=""> Send later</p>
    		</div>
    	</div>

    	<div class="row">
    		<div class="form-group col-sm-3">
    			<label>Billing Address</label>
    			<textarea name="billing_address" class="form-control input-sm"></textarea>
    		</div>
    		<div class="form-group col-sm-3">
                <label>Terms</label>
                <selectize name="term">
                    <option>Due on receipt</option>
                    <option>Net 15</option>
                    <option>Net 30</option>
                    <option>Net 60</option>
                </selectize>
    		</div>
    		<div class="form-group col-sm-3">
    			<label>Invoice Date</label>
              	<datepicker format="M/dd/yyyy" input-class="form-control input-sm"></datepicker>
            </div>
    		<div class="form-group col-sm-3">
    			<label>Due Date</label>
              	<datepicker format="M/dd/yyyy" input-class="form-control input-sm"></datepicker>
            </div>
    	</div>

    	<div class="card">
    		<div class="card-body">
                <div class="row">
                    <div class="col-md-12">
            			<dataTable :is-default="false" :headers="headers" :items="items">
                             <template v-slot:body="{ items }">
                                <tr v-for="item in items">
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.service_date }}</td>
                                    <td>{{ item.product }}</td>
                                    <td>{{ item.description }}</td>
                                    <td>{{ item.qty }}</td>
                                    <td>{{ item.rate }}</td>
                                    <td>{{ item.amount }}</td>
                                    <td>
                                        <button type="button" class="btn btn-flat btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                             </template>         
                        </dataTable>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-outline-secondary btn-sm w-10">Add lines</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm w-10">Clear all lines</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm w-10">Add subtotal</button>
                        </div>
                        <div class="col-md-12 mt-4">
                            <label>Message on invoice</label>
                            <textarea name="billing_address" class="form-control input-sm w-75" placeholder="This will show up on the invoice."></textarea>
                        </div>
                        <div class="col-md-12 mt-4">
                            <label>Message on statement</label>
                            <textarea name="billing_address" class="form-control input-sm w-75" placeholder="If you send statements to customer this will show up as the description for the invoice"></textarea>
                        </div>
                        <div class="col-md-12 mt-4">
                            <label>Attachments</label>
                            <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" @vdropzone-sending="sendingEvent"></vue-dropzone>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12" style="text-align: right;">
                            <label class="pr-3">Subtotal</label>
                            <span>PHP 999,999.00</span>
                        </div>
                        <div class="col-md-12" style="text-align: right;">
                            <span class="pr-3">
                                <select name="customer" class="form-control" style="display: initial;width: 32%;">
                                    <option>Discount percent</option>
                                    <option>Discount value</option>
                                </select>&nbsp;

                                <input type="text" name="" style="width: 12%">
                            </span>
                            
                            <span>PHP 999,999.00</span>
                        </div>
                        <div class="col-md-12" style="text-align: right;">
                            <label class="pr-3">Total</label>
                            <span>PHP 999,999.00</span>
                        </div>
                        <div class="col-md-12" style="text-align: right;">
                            <label class="pr-3">Deposit</label>
                            <input type="text" name="" style="width: 22%">
                        </div>
                        <div class="col-md-12" style="text-align: right;">
                            <label class="pr-3">Balance Due</label>
                            <span>PHP 999,999.00</span>
                        </div>
                    </div>
                </div>
    		</div>
    	</div>
        	
    </div>
</template>

<script>

	import Selectize from 'vue2-selectize';
	import selectizecss from 'selectize/dist/css/selectize.css';

	import Datepicker from 'vuejs-datepicker';
    import DataTable from 'Components/tables/DataTable.vue';

    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

	export default {
        computed: {
            headers() {
                let array = [
                    { text: '#', value: 'id' },
                    { text: 'SERVICE DATE', value: null },
                    { text: 'PRODUCT/SERVICE', value: null },
                    { text: 'DESCRIPTION', value: null },
                    { text: 'QTY', value: null },
                    { text: 'RATE', value: null },
                    { text: 'AMOUNT', value: null },
                    { text: 'ACTION', value: null },
                ];

                return array;
            }
        },

        props: {
            uploadUrl: String
        },

		data() {
			return {
                items: [
                    {
                        id: 1,
                        service_date: 'November 11, 2020',
                        product: 'Lorem ipsum',
                        description: 'Lorem ipsum ...',
                        qty: 1,
                        rate: 100,
                        amount: 1000
                    },
                    {
                        id: 2,
                        service_date: 'November 11, 2020',
                        product: 'Lorem ipsum',
                        description: 'Lorem ipsum ...',
                        qty: 1,
                        rate: 100,
                        amount: 1000
                    },
                ],
                dropzoneOptions: {
                    url: this.uploadUrl,
                    thumbnailWidth: 150,
                    maxFilesize: 0.5,
                    headers: { "Attachments": "header value" }
                  }
	      	}
		},

		components: {
		    'selectize': Selectize,
		    'datepicker': Datepicker,
            'dataTable': DataTable,
            'vue-dropzone': vue2Dropzone
		},

        methods: {
            sendingEvent (file, xhr, formData) {
                console.log(file);
                formData.append('paramName', 'some value or other');
            }
        }

		
	}
</script>