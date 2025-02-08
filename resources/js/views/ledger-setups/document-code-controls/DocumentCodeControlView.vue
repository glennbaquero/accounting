<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Document Code Control Overview 	
				<button v-if="item.id && !item.active" @click="setActive"  type="button" class="btn btn-sm btn-success float-right">Set as Active Code</button>
					<button v-if="item.id && item.active" @click="setInActive" type="button" class="btn btn-sm btn-danger float-right">Set as Inactive Code</button>
				</template>
				<div class="row">
					<div class="form-group col-sm-8">
						<span v-if="item.id && item.active" class="badge badge-success">Status : Active</span>
						<span v-if="item.id && !item.active" class="badge badge-danger">Status : Inactive</span>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Client <b class="text-danger">*</b></label> 
						<v-select :disabled="item.id ? true : false"  :reduce="item => item.id" v-model="item.client_id" label='name' :options="clients"></v-select>
						<input hidden v-model="item.client_id" name="client_id">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Module <b class="text-danger">*</b></label> 
						<v-select v-model="item.module_id" :reduce="item => item.id" label="name" placeholder="Select Modules" :options="modules"></v-select>
						<input hidden v-model="item.module_id" name="module_id">
					</div>
					<div class="form-group col-sm-4">
						<label>Example Code Output <b class="text-danger">*</b></label>
						<div class="input-group">
							<input readonly type="text" class="form-control" v-model="generated_code">
							<div class="input-group-append">
								<button class="btn btn-success" type="button" @click="generateCode">Generate</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Prefix <small>(maximum length 15 character)</small> <b class="text-danger">*</b></label>
						<input name="prefix" class="form-control" v-model="item.prefix" maxlength="15">
					</div>
					<div class="form-group col-sm-4">
						<label>Separated By <small>(accept 1 character only)</small> <b class="text-danger">*</b></label>
						<input name="separated_by" class="form-control" v-model="item.separated_by" maxlength="1">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Column 1 Type <b class="text-danger">*</b></label>
						<v-select @input="firstColumnChange" v-model="item.column_1_type" :selectable="option => option.id != item.column_2_type" :reduce="item => item.id" label="name" placeholder="Select Type" :options="types"></v-select>
						<input hidden v-model="item.column_1_type" name="column_1_type">
					</div>

					<div class="form-group col-sm-4">
						<label>Column 1 <b class="text-danger">*</b></label>
						<input :readonly="item.column_1_type == 3 || item.column_1_type == 2 || !item.column_1_type"  name="column_1" class="form-control" v-model="item.column_1">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Column 2 Type <b class="text-danger">*</b></label>
						<v-select @input="secondColumnChange" v-model="item.column_2_type" :selectable="option => option.id != item.column_1_type" :reduce="item => item.id" label="name" placeholder="Select Type" :options="types"></v-select>
						<input hidden v-model="item.column_2_type" name="column_2_type">
					</div>
					<div class="form-group col-sm-4">
						<label>Column 2 <b class="text-danger">*</b></label>
						<input :readonly="item.column_2_type == 3 ||item.column_2_type == 2 || !item.column_2_type" name="column_2" class="form-control" v-model="item.column_2">
					</div>
				</div>
				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</template>
			</card>
			
		<loader 
        :loading="loading">
        </loader>
		</form-request>
	</div>
</template>

<script>
import CrudMixin from 'Mixins/crud.js';
import TextEditor from 'Components/inputs/TextEditor.vue';
import FormRequest from 'Components/forms/FormRequest.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import Vselect from 'vue-select';

export default {

	props : {
		setActiveUrl : {
			type : String,
			defaul : null,
		},
		setInactiveUrl : {
			type : String,
			defaul : null,
		}
	},

	components: {
		'text-editor': TextEditor,
		'form-request': FormRequest,
		'action-button': ActionButton,
		'v-select' : Vselect
	},

	data() {
		return {
			item: {},
			clients : [],
			modules : [],
			generated_code : null,
			types : [
				{ id : 2 , name : 'Auto Increment'},		
				{ id : 3 , name : 'Date (mm-yyyy)'},
			]
		}
	},

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.modules = data.modules ? data.modules : this.modules;
			this.clients = data.clients ? data.clients : this.clients;
		},

		generateCode() {
			if(this.validateCode()) {
				this.generated_code = this.item.prefix + this.item.separated_by;
				this.generated_code += this.item.column_1 + this.item.separated_by;
				this.generated_code += this.item.column_2; 
			}else  {
				 swal.fire({
                    title: 'Required Fields',
                    text: 'Please fill up all the required fields',
                    icon: 'warning',
                })	
			}
		},

		validateCode() {
			if(!this.item.prefix || !this.item.separated_by || !this.item.column_1 || !this.item.column_2) {
				return false;
			}else{
				return true;
			}
		},
		firstColumnChange(value) {
			if(value == 2) {
				this.item.column_1 = "000001";
			}else if(value == 3){
				this.item.column_1 = moment().format('MMYYYY');
			}else {
				this.item.column_1 = null;
			}
		},
		secondColumnChange(value) {
			if(value == 2) {
				this.item.column_2 = "000001";
			}else if(value == 3){
				this.item.column_2 = moment().format('MMYYYY');
			}else {
				this.item.column_2 = null;
			}
		},

		setActive() {
			swal.fire({
				title: 'Are you sure?',
				text: "You want to set this as active code?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
				}).then((result) => {
				if (result.isConfirmed) {
					if(this.item.id) {
						axios.post(this.setActiveUrl, {id : this.item.id})
						.then(response =>{
							swal.fire(
							'Activated!',
							'Set as active code',
							'success'
							)
							this.fetch();
						}).then(error =>{

						});
					}
				}
			})
		},

		setInActive() {
				swal.fire({
				title: 'Are you sure?',
				text: "You want to set this as inactive code?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
				}).then((result) => {
				if (result.isConfirmed) {
					if(this.item.id) {
						axios.post(this.setInactiveUrl, {id : this.item.id})
						.then(response =>{
							swal.fire(
							'Activated!',
							'Set as inactive code',
							'success'
							)
							this.fetch();
						}).then(error =>{

						});
					}
				}
			})
		}
	},

	mixins: [ CrudMixin ],
}
</script>