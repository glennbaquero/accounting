<template>
	<div>
		<div class="card">
			<div class="card-header">
				<b>Parent Product Overview</b>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Name</label>
						<input readonly  v-model="product.name" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-4">
						<label>Product #</label>
						<input readonly  v-model="product.product_number" type="text" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label>Batch Number</label>
						<input readonly v-model="product.batch_number" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-4">
						<label>Serial Number</label>
						<input readonly v-model="product.serial_number" type="text" class="form-control">
					</div>
				</div>
			</div>
			<div class="card-footer text-right">
				<a class="btn btn-primary" :href="showUrl" target="_blank"><i class="fas fa-external-link-alt"></i> Go to Parent Product</a>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	export default {
		props: {
			product: Object,
			showUrl: String
		}
	}
</script>