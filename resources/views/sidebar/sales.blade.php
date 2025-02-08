<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
        'sales-orders.index' ,'sales-orders.create','sales-orders.show',
        'customer-invoices.index' ,'customer-invoices.create','customer-invoices.show',
        'customer-payments.index' ,'customer-payments.create','customer-payments.show',
        'customers.index' ,'customers.create','customers.show',
        'sales-order-returns.index' ,'sales-order-returns.create','sales-order-returns.show',
        'customer-payment-fee-setups.index' ,'customer-payment-fee-setups.create','customer-payment-fee-setups.show',
        'interest-calculations.index' ,'interest-calculations.create','interest-calculations.show',
        'interest-notes.index' ,'interest-notes.create','interest-notes.show',
        {{-- 'payment-schedules.index' ,'payment-schedules.create','payment-schedules.show', --}}
        'sales-delivery-receipts.index' ,'sales-delivery-receipts.create','sales-delivery-receipts.show',
        'customer-summaries.index' ,'customer-summaries.create','customer-summaries.show',
    ]) }}">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-chart-line"></i>
	    <p>
	        Sales
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
		 <li class="nav-item">
		 	<a href="{{ route('sales-orders.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'sales-orders.index' ,'sales-orders.create','sales-orders.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Sales Order
                </p>
            </a>
		 </li>
         <li class="nav-item">
            <a href="{{ route('letter-credit-sales.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'letter-credit-sales.index' ,'letter-credit-sales.create','letter-credit-sales.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Letter of Credit - Sales 
                </p>
            </a>
         </li>
		 <li class="nav-item">
		 	<a href="{{ route('sales-order-returns.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'sales-order-returns.index' ,'sales-order-returns.create','sales-order-returns.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Sales Order Return
                </p>
            </a>
		 </li>
		 <li class="nav-item">
		 	<a href="{{ route('sales-delivery-receipts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'sales-delivery-receipts.index' ,'sales-delivery-receipts.create','sales-delivery-receipts.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Sales Delivery Receipt
                </p>
            </a>
		 </li>
         <li class="nav-item">
            <a href="{{ route('customer-invoices.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'customer-invoices.index' ,'customer-invoices.create','customer-invoices.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Invoice
                </p>
            </a>
         </li>
         <li class="nav-item">
            <a href="{{ route('customer-invoice-aging.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'customer-invoice-aging.index'
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Invoice Aging Report
                </p>
            </a>
         </li>
         <li class="nav-item">
             <a href="{{ route('payment-schedules.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'payment-schedules.index' ,'payment-schedules.create','payment-schedules.show',
             ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                     Payment Schedules
                 </p>
             </a>
         </li>
		 <li class="nav-item">
		 	<a href="{{ route('customer-payments.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'customer-payments.index' ,'customer-payments.create','customer-payments.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Payment
                </p>
            </a>
		 </li>
		 {{-- <li class="nav-item">
		 	<a href="#" class="nav-link">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Settlement
                </p>
            </a>
		 </li> --}}
         <li class="nav-item">
             <a href="{{ route('customer-payment-fee-setups.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'customer-payment-fee-setups.index' ,'customer-payment-fee-setups.create','customer-payment-fee-setups.show',
             ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Payment Fee Setup
                </p>
            </a>
         </li>
         <li class="nav-item">
            <a href="{{ route('customer-summaries.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'customer-summaries.index' ,'customer-summaries.create','customer-summaries.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Summary
                </p>
            </a>
         </li>
		 <li class="nav-item">
		     <a href="{{ route('customers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		         'customers.index' ,'customers.create','customers.show',
		     ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		         <p>
		             Customers
		         </p>
		     </a>
		 </li>
         <li class="nav-item">
            <a href="{{ route('customer-payment-fees.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'customer-payment-fees.index' ,'customer-payment-fees.create','customer-payment-fees.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Payment Fee
                </p>
            </a>
         </li>
         <li class="nav-item">
             <a href="{{ route('interest-calculations.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'interest-calculations.index' ,'interest-calculations.create','interest-calculations.show',
             ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                     Interest Calculations
                 </p>
             </a>
         </li>
         <li class="nav-item">
             <a href="{{ route('interest-notes.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'interest-notes.index' ,'interest-notes.create','interest-notes.show',
             ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                     Interest Notes
                 </p>
             </a>
         </li>
	</ul>
</li>