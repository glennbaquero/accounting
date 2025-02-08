<template>
	<div class="row content">
		<div class="col-md-4 padding-left-6"><h4>Company Type</h4></div>
		<div class="col-md-8">
			<div class="row margin-bottom-3">
				<div class="col-md-4">
					<span><b>Tax form</b></span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="col-md-4">
						<div class="form-group">
							<select class="form-control">
								<option>Sole Proprietor</option>
								<option>Partnership or Limited Liability Company</option>
								<option>Small Business Corporation, two or more owners</option>
								<option>Corporation, one or more shareholders</option>
								<option>Nonprofit Organisation</option>
								<option>Limited Liability</option>
								<option>Not Secure</option>
								<option>Other</option>
								<option>None</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row margin-bottom-7">
				<div class="col-md-4">
					<span><b>Industry</b></span>
				</div>
				<div class="col-md-8" style="padding-left: 0;">
					<div class="col-md-8">
						<div class="form-group">
							<select class="form-control">
								<option>Accommodation and Food Services</option>
								<option>Administrative and Support Services</option>
								<option>Arts and Recreation Services</option>
								<option>Construction / Builder</option>
								<option>Education and Training</option>
								<option>Farming, Forestry, and Fishing</option>
								<option>Financial Services and Insurance</option>
								<option>Manufacturing</option>
								<option>Medical / Health Care / Community Services</option>
								<option>Personal, Beauty, Wellbeing and Other Services</option>
								<option>Professional Services (e.g. Legal, Accounting, Marketing, Consulting)</option>
								<option>Property Operators and Real Estate Services</option>
								<option>Rental and Hiring Services</option>
								<option>Repair and Maintenance (Automotive and Property)</option>
								<option>Retail Trade (Food and Beverage)</option>
								<option>Retail Trade and E-Commerce (Non-Food)</option>
								<option>Technology / Telecommunications Services</option>
								<option>Trades Work (e.g. Plumber, Carpenter, Electrician)</option>
								<option>Transport, Logistics, Postal, Warehousing</option>
								<option>Wholesale Trade</option>
								<option>Others</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
				</div>
				<div class="col-md-8">
					<button class="btn btn-outline-secondary button" @click="$emit('previous', 1)">Back</button>
					<button class="btn btn-success button" @click="$emit('next', 3)">Next</button>
				</div>
			</div>
		</div>
	</div>
</template>