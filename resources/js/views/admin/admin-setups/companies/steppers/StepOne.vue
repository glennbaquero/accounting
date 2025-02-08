<template>
	<div class="row content">
		<div class="col-md-4 padding-left-6"><h4>Company name</h4></div>
		<div class="col-md-8">
			<div class="row margin-bottom-3">
				<div class="col-md-4">
					<span><b>Company logo</b></span>
					<br />
					<span>Shown on sales form and purchase orders.</span>
				</div>
				<div class="col-md-8">
					<div class="card logo">
						<div class="card-body">
							<img src="/images/pay_employee.png">
							<button class="btn btn-outline-secondary img-btn" data-toggle="modal" data-target="#myModal"><b>+</b></button>
						</div> 
					</div>
				</div>
				<div class="modal" id="myModal">
					<div class="modal-dialog">
						<div class="modal-content">
						<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title">Modal Heading</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<!-- Modal body -->
							<div class="modal-body">
								Modal body..
							</div>
							<!-- Modal footer -->
							<div class="modal-footer">
								<button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row margin-bottom-3">
				<div class="col-md-4">
					<span><b>Company name</b></span>
					<br />
					<span>Shown on sales form and purchase orders.</span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="col-md-8">
						<div class="form-group">
							<input type="text" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="row margin-bottom-7">
				<div class="col-md-4">
					<span><b>Legal name</b></span>
					<br />
					<span>Used on tax forms.</span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="col-md-8">
						<div class="form-check">
							<label class="form-check-label">
								<input id="checkbox_company_name" type="checkbox" class="form-check-input" value="" v-model="sameAsCompanyName">Same as company name
							</label>
						</div>
						<div class="form-group" v-if="!sameAsCompanyName">
							<input type="text" class="form-control" id="company_name">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<span><b>Business ID No.</b></span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="col-md-4">
						<div class="form-group">
							<input type="text" class="form-control" id="company_name" placeholder="99.1111110">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
				</div>
				<div class="col-md-8">
					<!-- <button class="btn btn-outline-secondary button">Cancel</button> -->
					<button class="btn btn-success button" @click="$emit('next', 2)">Next</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				sameAsCompanyName: false,
			}
		},
	}
</script>