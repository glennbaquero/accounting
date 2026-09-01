<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
	'ledgers.index' , 'ledgers.create', 'ledgers.show',
	'chart-of-accounts.index' , 'chart-of-accounts.create',
	'chart-of-accounts.show', 'main-accounts.index' , 'main-accounts.create',
	'main-accounts.show', 'main-accounts-categories.index' , 'main-accounts-categories.create',
	'main-accounts-categories.show', 'linked-main-accounts.index' , 'linked-main-accounts.create',
	'linked-main-accounts.show','account-structures.index' , 'account-structures.create', 'account-structures.show',
	'financial-dimensions.index' , 'financial-dimensions.create', 'financial-dimensions.show','fiscal-calendars.index' ,
	'fiscal-calendars.create', 'fiscal-calendars.show', 'ledger-calendars.create', 'ledger-calendars.show', 'ledger-calendars.index', 'fiscals.index', 'fiscals.create', 'fiscals.show',
	'date-intervals.index' , 'date-intervals.create', 'date-intervals.show',
	'financial-dimension-values.index' , 'financial-dimension-values.create', 'financial-dimension-values.show',
	'main-accounts.create-coa', 'main-accounts.show-coa', 'fiscal-periods.index' , 'fiscal-periods.create', 'fiscal-periods.show',
	'account-structures.create-ledger', 'account-structures.show-ledger', 'account-structures.create-coa', 'account-structures.show-coa',
	'document-code-controls.index' ,'document-code-controls.create','document-code-controls.show',
	'fixed-assets.index', 'fixed-assets.create', 'fixed-assets.show',
    ]) }}">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-money-bill"></i>
	    <p>
	        Ledger Setup
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
		<li class="nav-item">
		    <a href="{{ route('ledgers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'ledgers.index' , 'ledgers.create', 'ledgers.show', 'account-structures.create-ledger', 'account-structures.show-ledger',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Ledger
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('chart-of-accounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'chart-of-accounts.index' , 'chart-of-accounts.create', 'chart-of-accounts.show', 'main-accounts.create-coa', 'main-accounts.show-coa', 'account-structures.create-coa', 'account-structures.show-coa',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Chart of Accounts
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('main-accounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'main-accounts.index' , 'main-accounts.create', 'main-accounts.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Main Account
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('main-accounts-categories.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'main-accounts-categories.index' , 'main-accounts-categories.create', 'main-accounts-categories.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Main Account Categories
		        </p>
		    </a>
		</li>		

		<li class="nav-item">
		    <a href="{{ route('linked-main-accounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'linked-main-accounts.index' , 'linked-main-accounts.create', 'linked-main-accounts.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Linked Main Account 
		        </p>
		    </a>
		</li>		

		<li class="nav-item">
			<a href="{{ route('account-structures.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'account-structures.index' , 'account-structures.create', 'account-structures.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Account Structure
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('financial-dimensions.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'financial-dimensions.index' , 'financial-dimensions.create', 'financial-dimensions.show',
				'financial-dimension-values.index' , 'financial-dimension-values.create', 'financial-dimension-values.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Financial Dimension
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('ledger-calendars.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'ledger-calendars.index', 'ledger-calendars.create', 'ledger-calendars.show', 
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Ledger Calendar
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('fiscal-calendars.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'fiscal-calendars.index' , 'fiscal-calendars.create', 'fiscal-calendars.show', 'fiscal-periods.index' , 'fiscal-periods.create', 'fiscal-periods.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Fiscal Calendar
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('fixed-assets.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'fixed-assets.index', 'fixed-assets.create', 'fixed-assets.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Fixed Assets
		        </p>
		    </a>
		</li>


		<li class="nav-item">
            <a href="{{ route('document-code-controls.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'document-code-controls.index' ,'document-code-controls.create', 'document-code-controls.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Document Code Control
                 </p>
            </a>
        </li>

        {{-- <li class="nav-item">
		    <a href="{{ route('fiscals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'fiscals.index' , 'fiscals.create', 'fiscals.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Fiscal Year
		        </p>
		    </a>
		</li> --}}

		{{-- <li class="nav-item">
		    <a href="{{ route('date-intervals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'date-intervals.index' , 'date-intervals.create', 'date-intervals.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Date Intervals
		        </p>
		    </a>
		</li> --}}

       {{--<li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Period
		        </p>
		    </a>
		</li> --}}

		{{-- <li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Closing Periods
		        </p>
		    </a>
		</li> --}}
	</ul>
</li>