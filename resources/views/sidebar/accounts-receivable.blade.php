<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
		'so-invoice-approval-journals.index', 'so-invoice-approval-journals.show', 'so-invoice-approval-journals.create',
		'customer-payment-journals.index', 'customer-payment-journals.show', 'customer-payment-journals.create',
		'customer-posting-profiles.index' , 'customer-posting-profiles.create', 'customer-posting-profiles.show',
        'setups.index' , 'setups.create', 'setups.show',
        'bill-of-exchanges.index', 'bill-of-exchanges.show', 'bill-of-exchanges.create',
        'bills-exchanges.index' ,'bills-exchanges.create','bills-exchanges.show',
        'customer-payment-methods.index', 'customer-payment-methods.show', 'customer-payment-methods.create',
        'sales-return-journals.index', 'sales-return-journals.show', 'sales-return-journals.create',
        'customer-posting-profile-headers.index' , 'customer-posting-profile-headers.create', 'customer-posting-profile-headers.show',
	]) }}">
	<a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([]) }}">
	    <i class="nav-icon fab fas fa-id-card"></i>
	    <p>
	        Accounts Receivable
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
		 {{-- <li class="nav-item">
		 	<a href="#" class="nav-link {{ $checker->route->areOnRoutes([]) }}">
		 	    <i class="nav-icon far fa-circle"></i>
		 	    <p class="text-truncate w-75">
		 	        Customer Order Confirmation Journal
		 	    </p>
		 	</a>
		 </li> --}}
		 <li class="nav-item">
		 	<a href="{{ route('so-invoice-approval-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'so-invoice-approval-journals.index', 'so-invoice-approval-journals.show', 'so-invoice-approval-journals.create',
		 		]) }} ">
				<i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Invoice Journal
                </p>
            </a>
		 </li>
		 <li class="nav-item">
		 	<a href="{{ route('customer-payment-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'customer-payment-journals.index', 'customer-payment-journals.show', 'customer-payment-journals.create',
		 		]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Payment Journal
                </p>
            </a>
		 </li>
		 <li class="nav-item">
	        <a href="{{ route('customer-posting-profile-headers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
	            'customer-posting-profile-headers.index' , 'customer-posting-profile-headers.create', 'customer-posting-profile-headers.show',
	        ]) }}">
		 	    <i class="nav-icon far fa-circle"></i>
	            <p>
	                Customer Posting Profile
	            </p>
	        </a>
	    </li>
	     <li class="nav-item">
	        <a href="{{ route('bills-exchanges.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
	             'bills-exchanges.index' ,'bills-exchanges.create','bills-exchanges.show',
	        ]) }}">
	            <i class="nav-icon far fa-circle"></i>
	            <p>
	                Bills of Exchange
	            </p>
	        </a>
	    </li>
		 {{-- <li class="nav-item">
		 	<a href="{{ route('bill-of-exchanges.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'bill-of-exchanges.index', 'bill-of-exchanges.show', 'bill-of-exchanges.create',
		 		]) }} ">
				<i class="nav-icon far fa-circle"></i>
                <p>
                    Bill Of Exchange
                </p>
            </a>
		 </li> --}}
		<li class="nav-item">
		 	<a href="{{ route('customer-payment-methods.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'customer-payment-methods.index', 'customer-payment-methods.show', 'customer-payment-methods.create',
		 		]) }} ">
				<i class="nav-icon far fa-circle"></i>
                <p>
                	Methods of Payment - Customer
                </p>
            </a>
		 </li>
		<li class="nav-item">
		 	<a href="{{ route('sales-return-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'sales-return-journals.index', 'sales-return-journals.show', 'sales-return-journals.create',
		 		]) }} ">
                 <i class="nav-icon far fa-circle"></i>
                <p>
                    Sales Order Return Journal
                </p>
            </a>
		 </li>
	</ul>
</li>