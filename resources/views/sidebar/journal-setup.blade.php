<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
        'terms.index' ,'terms.create','terms.show',
        'term-customers.index' ,'term-customers.create','term-customers.show',
        'payment-methods.index' ,'payment-methods.create','payment-methods.show',
        'cash-discounts.index' ,'cash-discounts.create','cash-discounts.show',
        'payment-days.index' ,'payment-days.create','payment-days.show',
        'tax-tables.index' ,'tax-tables.create','tax-tables.show',
        'interest-setups.index' ,'interest-setups.create','interest-setups.show',
        'withholding-taxes.index' ,'withholding-taxes.create','withholding-taxes.show',
        'transaction-posting-headers.index' ,'transaction-posting-headers.create','transaction-posting-headers.show',
        'transaction-postings.index' ,'transaction-postings.create','transaction-postings.show',
    ]) }}">
	<a href="javascript:void(0)" class="nav-link">
        <i class="nav-icon fas fa-book"></i>
	    <p>
	        Journal Setup
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('terms.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'terms.index' ,'terms.create','terms.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Terms Of Payment - Vendor
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('term-customers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'term-customers.index' ,'term-customers.create','term-customers.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Terms Of Payment - Customer
                </p>
            </a>
        </li>
		{{-- <li class="nav-item">
            <a href="{{ route('payment-methods.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'payment-methods.index' ,'payment-methods.create','payment-methods.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Payment Methods
                </p>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ route('cash-discounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'cash-discounts.index' ,'cash-discounts.create','cash-discounts.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Cash discounts
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('payment-days.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'payment-days.index' ,'payment-days.create','payment-days.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Payment days
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tax-tables.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'tax-tables.index' ,'tax-tables.create','tax-tables.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Tax Posting
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('interest-setups.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'interest-setups.index' ,'interest-setups.create','interest-setups.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Interest Setups
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('withholding-taxes.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'withholding-taxes.index' ,'withholding-taxes.create','withholding-taxes.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Withholding Tax Posting
                 </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('transaction-posting-headers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'transaction-posting-headers.index' ,'transaction-posting-headers.create','transaction-posting-headers.show',
                 'transaction-postings.index' ,'transaction-postings.create','transaction-postings.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Transaction Posting
                 </p>
            </a>
        </li>
        
        {{-- <li class="nav-item">
            <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
                 
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Auto Charges
                 </p>
            </a>
        </li> --}}

        {{-- <li class="nav-item">
            <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
                 
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Charges Code
                 </p>
            </a>
        </li> --}}

        <li class="nav-item">
            <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
                 
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Item Charges Group
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
                 
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Fees
                 </p>
            </a>
        </li>
	</ul>
</li>