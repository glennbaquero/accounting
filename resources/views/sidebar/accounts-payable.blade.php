<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
		'po-invoice-approval-journals.index', 'po-invoice-approval-journals.show', 'po-invoice-approval-journals.create',
		'vendor-payment-journals.index', 'vendor-payment-journals.show', 'vendor-payment-journals.create',
		'promissory-notes.index', 'promissory-notes.show', 'promissory-notes.create',
		'vendor-posting-profile-headers.index', 'vendor-posting-profile-headers.show', 'vendor-posting-profile-headers.create',
		'vendor-posting-profiles.index', 'vendor-posting-profiles.show', 'vendor-posting-profiles.create',
		'vendor-payment-methods.index', 'vendor-payment-methods.show', 'vendor-payment-methods.create',
		'purchase-return-journals.index', 'purchase-return-journals.show', 'purchase-return-journals.create',
	]) }} ">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-money-bill"></i>
	    <p>
	        Accounts Payable
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
		 <li class="nav-item">
		 	<a href="{{ route('po-invoice-approval-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'po-invoice-approval-journals.index', 'po-invoice-approval-journals.show', 'po-invoice-approval-journals.create',
		 		]) }} ">
                 <i class="nav-icon far fa-circle"></i>
                <p>
                    Invoice Approval Journal
                </p>
            </a>
		 </li>
		 <li class="nav-item">
		 	<a href="{{ route('vendor-payment-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'vendor-payment-journals.index', 'vendor-payment-journals.show', 'vendor-payment-journals.create',
		 		]) }}">
                 <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendor Payment Journal
                </p>
            </a>
		 </li>

		 <li class="nav-item">
	        <a href="{{ route('promissory-notes.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'promissory-notes.index', 'promissory-notes.show', 'promissory-notes.create',
		 		]) }}">
		 	    <i class="nav-icon far fa-circle"></i>
	            <p>
	                Promisory Note Journal
	            </p>
	        </a>
	    </li>

		 <li class="nav-item">
	        <a href="{{ route('vendor-posting-profile-headers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
	        		'vendor-posting-profile-headers.index', 'vendor-posting-profile-headers.show', 'vendor-posting-profile-headers.create',
					'vendor-posting-profiles.index', 'vendor-posting-profiles.show', 'vendor-posting-profiles.create',
	        	]) }}">
		 	    <i class="nav-icon far fa-circle"></i>
	            <p>
	                Vendor Posting Profile
	            </p>
	        </a>
	    </li>

		 <li class="nav-item">
	        <a href="{{ route('vendor-payment-methods.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
	        		'vendor-payment-methods.index', 'vendor-payment-methods.show', 'vendor-payment-methods.create',
	        	]) }}">
		 	    <i class="nav-icon far fa-circle"></i>
	            <p>
	                Methods of Payment - Vendor
	            </p>
	        </a>
	    </li>
		<li class="nav-item">
		 	<a href="{{ route('purchase-return-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		 			'purchase-return-journals.index', 'purchase-return-journals.show', 'purchase-return-journals.create',
		 		]) }} ">
                 <i class="nav-icon far fa-circle"></i>
                <p>
                    Purchase Order Return Journal
                </p>
            </a>
		 </li>
	</ul>
</li>