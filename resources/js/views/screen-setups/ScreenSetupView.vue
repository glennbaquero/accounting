<template>
	<div>
		<div class="card">
			<div class="card-header text-center p-0 mt-2 border-0">
				<p>Setup</p>
			</div>
			<div class="card-body p-0">
				<div class="row">
					<div class="col-md-12">
						<swiper class="swiper swiper-no-swiping" ref="mySwiperRef" :options="swiperOption">
						  	<swiper-slide>
						  		<div class="row m-5 w-50">
						  			<div class="col-sm-12">
						  				<h3>1. Set up your business.</h3>
						  				<div class="mt-5 pl-4">
						  					<p>Legal business name</p>
						  					<div class="form-group">
						  						<input type="text" name="" class="form-control">
						  					</div>	
						  				</div>
						  				<div class="mt-4 pl-4">
						  					<p>What type of business do you have?</p>
						  					<div class="form-group">
						  						<select name="" class="form-control">
						  							<option>TEST</option>
						  						</select>
						  					</div>	
						  				</div>
						  				<div class="mt-4 pl-4">
						  					<p>How long have you been in business?</p>
						  					<div class="form-group">
						  						<select name="" class="form-control">
						  							<option>TEST</option>
						  						</select>
						  					</div>	
						  				</div>
						  				<div class="mt-4 pl-4">
						  					<p>Industry</p>
						  					<div class="form-group">
						  						<select name="" class="form-control">
						  							<option>TEST</option>
						  						</select>
						  					</div>	
						  					<p><i>Select the business category that best describes what your company does.</i></p>
						  				</div>

						  				<div class="mt-4 pl-4">
							  				<button class="w-15 btn btn-success btn-radius-20" @click="nextSlide">Next</button>
							  			</div>
						  			</div>
						  		</div>
						  	</swiper-slide>
						  	<swiper-slide>
						  		<div class="row m-5 w-50">
						  			<div class="col-sm-12">
						  				<h3>2. What would you like to do?</h3>
						  				<div class="pl-4">
						  					<p>Select all that apply</p>
						  				</div>
						  				<div class="row">
						  					<div class="col-md-4 pl-4 h-50" @click="selectedMethods('track_invoice')">
						  						<div class="mt-5 border h-75 img-holder" :class="selected.track_invoice ? 'bg-selected' : ''">
						  							<div class="img mx-auto">
						  								<img :src=" selected.track_invoice ? '/images/check.png' : '/images/invoice.png'" class="w-100 mt-5">
						  							</div>
						  							<div class="pt-3 w-50 mx-auto text-center">
						  								<p>Send and track invoices</p>
						  							</div>
						  						</div>
						  					</div>

						  					<!-- <div class="col-md-4 pl-4 h-50">
						  						<div class="mt-5 border-success bg-selected border h-75 img-holder">
						  							<div class="img mx-auto">
						  								<img src="/images/check.png" class="w-100 mt-5">
						  							</div>
						  							<div class="pt-3 w-50 mx-auto text-center">
						  								<p>Organize your expenses</p>
						  							</div>
						  						</div>
						  					</div> -->

						  					<div class="col-md-4 pl-4 h-50" @click="selectedMethods('organize_expenses')">
						  						<div class="mt-5 border h-75 img-holder" :class="selected.organize_expenses ? 'bg-selected' : ''">
						  							<div class="img mx-auto">
						  								<img :src="selected.organize_expenses ? '/images/check.png' : '/images/seo.png'" class="w-100 mt-5">
						  							</div>
						  							<div class="pt-3 w-50 mx-auto text-center">
						  								<p>Organize your expenses</p>
						  							</div>
						  						</div>
						  					</div>

						  					<div class="col-md-4 pl-4 h-50" @click="selectedMethods('track_vat')">
						  						<div class="mt-5 border h-75 img-holder" :class="selected.track_vat ? 'bg-selected' : ''">
						  							<div class="img mx-auto">
						  								<img :src="selected.track_vat ? '/images/check.png' : '/images/tax.png'" class="w-100 mt-5">
						  							</div>
						  							<div class="pt-3 w-50 mx-auto text-center">
						  								<p>Track your VAT</p>
						  							</div>
						  						</div>
						  					</div>

						  					<div class="col-md-4 pl-4 h-50" @click="selectedMethods('track_sales')">
						  						<div class="mt-5 border h-75 img-holder" :class="selected.track_sales ? 'bg-selected' : ''">
						  							<div class="img mx-auto">
						  								<img :src="selected.track_sales ? '/images/check.png' : '/images/online-shopping.png'" class="w-100 mt-5">
						  							</div>
						  							<div class="pt-3 w-50 mx-auto text-center">
						  								<p>Track your retail sales</p>
						  							</div>
						  						</div>
						  					</div>

						  					<div class="col-md-4 pl-4 h-50" @click="selectedMethods('pay_employees')">
						  						<div class="mt-5 border h-75 img-holder" :class="selected.pay_employees ? 'bg-selected' : ''">
						  							<div class="img mx-auto">
						  								<img :src="selected.pay_employees ? '/images/check.png' : '/images/hire.png'" class="w-100 mt-5">
						  							</div>
						  							<div class="pt-3 w-50 mx-auto text-center">
						  								<p>Pay your employees</p>
						  							</div>
						  						</div>
						  					</div>

						  					<div class="col-md-4 pl-4 h-50" @click="selectedMethods('manage_inventory')">
						  						<div class="mt-5 border h-75 img-holder" :class="selected.manage_inventory ? 'bg-selected' : ''">
						  							<div class="img mx-auto">
						  								<img :src="selected.manage_inventory ? '/images/check.png' : '/images/inventory.png'" class="w-100 mt-5">
						  							</div>
						  							<div class="pt-3 w-50 mx-auto text-center">
						  								<p>Manage your inventory</p>
						  							</div>
						  						</div>
						  					</div>
						  					<div class="col-md-4 pl-4 h-50">
						  					</div>
						  					<div class="col-md-4 pl-4 h-50" @click="selectedMethods('track_bills')">
						  						<div class="mt-5 border h-75 img-holder" :class="selected.track_bills ? 'bg-selected' : ''">
						  							<div class="img mx-auto">
						  								<img :src="selected.track_bills ? '/images/check.png' : '/images/money.png'" class="w-100 mt-5">
						  							</div>
						  							<div class="pt-3 w-50 mx-auto text-center">
						  								<p>Track your bills</p>
						  							</div>
						  						</div>
						  					</div>
						  					<div class="col-md-4 pl-4 h-50">
						  					</div>
						  				</div>
						  				<div class="row mt-5">
						  					<div class="col-md-12">
						  						<button class="btn btn-outline-dark btn-radius-20 w-15" @click="prevSlide">Back</button>
						  						<button class="btn btn-success btn-radius-20 w-15" @click="nextSlide">Next</button>
						  						<!-- <button class="w-15 btn btn-success btn-radius-20" @click="nextSlide">Next</button> -->
						  					</div>
						  				</div>
						  			</div>
						  		</div>
						  	</swiper-slide>
						  	<swiper-slide>
						  		<div class="row m-5 w-50">
						  			<div class="col-sm-12">
						  				<h3>3. Use VAT for invoices and expenses?</h3>
						  				<div class="pl-4">
						  					<div class="form-group">
						  						<label>
						  							<input type="radio" name="vat_include"> 
							  						Yes, add VAT to my invoices and expenses
							  					</label>
						  					</div>
						  				</div>
						  				<div class="row pl-5">
						  					<div class="col-md-12">
						  						<p><input type="checkbox" name=""> 12% VAT</p>
						  					</div>
						  					<div class="col-md-12">
						  						<p><input type="checkbox" name=""> 0%</p>
						  					</div>
						  					<div class="col-md-12">
						  						<p><input type="checkbox" name=""> 20% VAT</p>
						  					</div>
						  					<div class="col-md-12">
						  						<p><input type="checkbox" name=""> 10% VAT</p>
						  					</div>
						  					<div class="col-md-12">
						  						<p><input type="checkbox" name=""> Exempt</p>
						  					</div>
						  					<div class="col-md-12">
						  						<p><input type="checkbox" name=""> Some or all my tax rates are missing</p>
						  					</div>
						  				</div>
						  				<div class="pl-4">
						  					<div class="form-group">
						  						<label>
						  							<input type="radio" name="vat_include"> 
							  						No, don't add VAT
							  					</label>
						  					</div>
						  				</div>

						  				<div class="pl-4">
						  					<div class="row">
						  						<div class="col-md-12">
						  							<button class="btn btn-outline-dark btn-radius-20 w-25" @click="done">Skip this step</button>
						  							<button class="btn btn-success btn-radius-20 w-20" @click="done">Done</button>
						  						</div>
						  					</div>
						  				</div>

						  				<div class="pl-4 mt-4">
						  					<div class="row">
						  						<div class="col-md-12">
						  							<p>
						  								<input type="checkbox" name=""> I'm happy to be contacted for further feedback about VAT by the lovely QuickBooks team 
						  							</p>
						  						</div>
						  					</div>
						  				</div>
						  			</div>
						  		</div>
						  	</swiper-slide>
						  <div class="swiper-pagination" slot="pagination"></div>
						</swiper>
					</div>
					
				</div>
				
			</div>
		</div>
	</div>
	
</template>

<script>
	import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
	import 'swiper/css/swiper.css'
	import Card from 'Components/containers/Card.vue';

	export default {
		components: {
		    Swiper,
		    SwiperSlide,
		    Card,
		},


		data() {
			return {
				swiperOption: {
				    pagination: {
				        el: '.swiper-pagination',
				        type: 'progressbar'
				    },
				    navigation: {
				        nextEl: '.swiper-button-next',
				        prevEl: '.swiper-button-prev'
				    }
				},

				selected: {
					track_invoice: false,
					organize_expenses: false,
					track_vat: false,
					track_sales: false,
					pay_employees: false,
					manage_inventory: false,
					track_bills: false,
				}
			}
		},

		methods: {
			nextSlide() {
				this.$refs.mySwiperRef.$swiper.slideNext();
			},

			prevSlide() {
				this.$refs.mySwiperRef.$swiper.slidePrev();
			},

			selectedMethods(refs) {
				this.selected[refs] = !this.selected[refs];
			},

			done() {
				window.location.href = '/dashboard'
			}
		}
	}
</script>

<style lang="scss" scoped>
	
	.swiper {
	  height: 100%;
	  width: 100%;

	  .swiper-slide {
	  	padding: 10px;
	  	height: auto;
	  	width: 100vh;
	    display: flex;
	    justify-content: center;
	    background-color: #fff;
	  }
	}

	.img-holder {
		width: 100%;
		.img {
			width: 40%;
		}
	}

	.btn-radius-20 {
		border-radius: 20px;
	}

	.w-20 {
		width: 20%;
	}

	.w-15 {
		width: 15%;
	}

	.bg-selected {
		background: #F5F5F5
	}

	img {
		-webkit-user-drag: none;
	  	-khtml-user-drag: none;
	  	-moz-user-drag: none;
  		-o-user-drag: none;
  		user-drag: none;
	}
</style>