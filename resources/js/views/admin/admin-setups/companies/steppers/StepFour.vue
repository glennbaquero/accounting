<template>
	<div class="row content">
		<div class="col-md-4 padding-left-6"><h4>Address</h4></div>
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-4">
					<span><b>Company address</b></span>
					<span>Address where your company is based. This address is used to calculate applicable taxes for your QBO subscriptions and is your default comapny address</span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="row">
						<div class="col-md-10">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Street address">
							</div>
						</div>
						<div class="col-md-10">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="City">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="State">
							</div>
						</div>
						<div class="col-md-7">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Zip Code">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<span><b>Customer-facing address</b></span>
					<span>Address where customer contact you or send payments. Shown on sales forms</span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="form-check">
						<label class="form-check-label">
							<input id="checkbox_company_name" type="checkbox" class="form-check-input" value="" v-model="sameAsCompanyAddressCustomerFacingGroup">Same as company address
						</label>
					</div>

					<div class="form-group" v-if="!sameAsCompanyAddressCustomerFacingGroup">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Street address">
								</div>
							</div>
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="City">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="State">
								</div>
							</div>
							<div class="col-md-7">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Zip Code">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<span><b>Legal address</b></span>
					<span>Used for filling taxes</span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="form-check">
						<label class="form-check-label">
							<input id="checkbox_company_name" type="checkbox" class="form-check-input" value="" v-model="sameAsCompanyAddressLegalAddressGroup">Same as company address
						</label>
					</div>

					<div class="form-group" v-if="!sameAsCompanyAddressLegalAddressGroup">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Street address">
								</div>
							</div>
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="City">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="State">
								</div>
							</div>
							<div class="col-md-7">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Zip Code">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
				</div>
				<div class="col-md-8">
					<button class="btn btn-outline-secondary button" @click="$emit('previous', 3)">Back</button>
					<button class="btn btn-success button" @click="$emit('save')">Save</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				sameAsCompanyAddressCustomerFacingGroup: false,
				sameAsCompanyAddressLegalAddressGroup: false
			}
		}
	}
</script>