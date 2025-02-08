<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
        'purchase-orders.index' ,'purchase-orders.create','purchase-orders.show',
        'purchase-order-returns.index' ,'purchase-order-returns.create','purchase-order-returns.show',
        'vendor-invoices.index' ,'vendor-invoices.create','vendor-invoices.show',
        'vendor-payments.index' ,'vendor-payments.create','vendor-payments.show',
        'vendors.index',
        'vendor-payment-fees.index' ,'vendor-payment-fees.create','vendor-payment-fees.show',
        'vendor-payment-fee-setups.index' ,'vendor-payment-fee-setups.create','vendor-payment-fee-setups.show',
        'letter-credit-purchases.index' ,'letter-credit-purchases.create','letter-credit-purchases.show',
        'payment-schedules.create-pn'
    ]) }}">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-shopping-cart"></i>
	    <p>
	        Purchase
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
		 <li class="nav-item">
		 	<a href="{{ route('purchase-orders.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'purchase-orders.index' ,'purchase-orders.create','purchase-orders.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Purchase Orders
                </p>
            </a>
		 </li>
         <li class="nav-item">
            <a href="{{ route('letter-credit-purchases.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'letter-credit-purchases.index' ,'letter-credit-purchases.create','letter-credit-purchases.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Letter of Credit - Purchase
                </p>
            </a>
         </li>
         <li class="nav-item">
            <a href="{{ route('purchase-order-returns.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'purchase-order-returns.index' ,'purchase-order-returns.create','purchase-order-returns.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Purchase Order Returns
                </p>
            </a>
         </li>
		 <li class="nav-item">
		 	<a href="{{ route('purchase-delivery-receipts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'purchase-delivery-receipts.index' ,'purchase-delivery-receipts.create','purchase-delivery-receipts.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Purchase Delivery Receipt
                </p>
            </a>
		 </li>
         <li class="nav-item">
            <a href="{{ route('vendor-invoices.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'vendor-invoices.index' ,'vendor-invoices.create','vendor-invoices.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendor Invoice
                </p>
            </a>
         </li>
         <li class="nav-item">
            <a href="{{ route('vendor-invoice-aging.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'vendor-invoice-aging.index'
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendor Invoice Aging Report
                </p>
            </a>
         </li>
         <li class="nav-item">
             <a href="{{ route('payment-schedules.index-pn') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'payment-schedules.create-pn'
             ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                     Payment Schedules
                 </p>
             </a>
         </li>
		 <li class="nav-item">
		 	<a href="{{ route('vendor-payments.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'vendor-payments.index' ,'vendor-payments.create','vendor-payments.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendor Payment
                </p>
            </a>
		 </li>
         {{-- <li class="nav-item">
            <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
                
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendor Settlement
                </p>
            </a>
         </li> --}}
         <li class="nav-item">
            <a href="{{ route('vendors.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'vendors.index' ,'vendors.create','vendors.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendors
                </p>
            </a>
         </li>
         <li class="nav-item">
            <a href="{{ route('vendor-payment-fees.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'vendor-payment-fees.index' ,'vendor-payment-fees.create','vendor-payment-fees.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendor Payment Fee
                </p>
            </a>
         </li>
         <li class="nav-item">
            <a href="{{ route('vendor-payment-fee-setups.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'vendor-payment-fee-setups.index' ,'vendor-payment-fee-setups.create','vendor-payment-fee-setups.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendor Payment Fee Setup
                </p>
            </a>
         </li>
         <li class="nav-item">
            <a href="{{ route('purchase-promissory-notes.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'purchase-promissory-notes.index' ,'purchase-promissory-notes.create','purchase-promissory-notes.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Promissory Notes
                </p>
            </a>
         </li>
	</ul>
</li>