<template>
	<div class="row content">
		<div class="col-md-4 padding-left-6"><h4>Company Info</h4></div>
		<div class="col-md-8">
			<div class="row margin-bottom-3">
				<div class="col-md-4">
					<span><b>Company email</b></span>
					<span>Where QuickBooks can contact you</span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="col-md-8">
						<div class="form-group">
							<input type="text" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="row margin-bottom-3">
				<div class="col-md-4">
					<span><b>Customer-facing email</b></span>
					<span>Where your customer can contact you. Shown on sales forms</span>
				</div>
				<div class="col-md-8">
					<div class="col-md-8" style="padding-left: 0;">
						<div class="form-check">
							<label class="form-check-label">
								<input id="checkbox_company_email" type="checkbox" class="form-check-input" value="" v-model="sameAsCompanyEmail">Same as company email
							</label>
						</div>
						<div class="form-group" v-if="!sameAsCompanyEmail">
							<input type="text" class="form-control" id="company_email" style="padding-left: 0;">
						</div>
					</div>
				</div>

			</div>
			<div class="row margin-bottom-3">
				<div class="col-md-4">
					<span><b>Company phone</b></span>
					<span>Shown on sales forms.</span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="col-md-8">
						<div class="form-group">
							<!-- <input type="text" > -->
				            <vue-phone-number-input required default-country-code="PH" size="sm" @update="updatePhone" ref="phone_number" no-example/>
						</div>
					</div>
				</div>
			</div>
			<div class="row margin-bottom-7">
				<div class="col-md-4">
					<span><b>Website</b></span>
					<span>Shown on sales forms.</span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="col-md-8">
						<div class="form-group">
							<input type="text" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
				</div>
				<div class="col-md-8">
					<button class="btn btn-outline-secondary button" @click="$emit('previous', 2)">Back</button>
					<button class="btn btn-success button"  @click="$emit('next', 4)">Next</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				sameAsCompanyEmail: false,
			}
		},
		
		methods: {
			updatePhone(e) {

			}
		}
	}
</script>