<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
		'general-journal.index' ,'general-journal.show',
		'opening-transactions.index', 'opening-transactions.show', 'opening-transactions.create', 
		'journal-names.index' , 'journal-names.create', 'journal-names.show',
		'ledger-reasons.index' , 'ledger-reasons.create', 'ledger-reasons.show',
		'general-ledgers.index' ,'general-ledgers.show', 'general-ledgers.create',
		'accrual-postings.index' ,'accrual-postings.show', 'accrual-postings.create',
	]) }}">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-scroll"></i>
	    <p>
	        General Ledger
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>
	<ul class="nav nav-treeview">
	{{-- 	<li class="nav-item">
		    <a href="" class="nav-link {{ $checker->route->areOnRoutes([]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            General Ledger
		        </p>
		    </a>
		</li> --}}

		<li class="nav-item">
		    <a href="{{ route('general-ledgers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'general-ledgers.index' ,'general-ledgers.show', 'general-ledgers.create'
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            General Ledger
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('general-journal.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'general-journal.index' ,'general-journal.show', 'general-journal.create' 
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            General Journal
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('accrual-postings.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		         'accrual-postings.index' ,'accrual-postings.show', 'accrual-postings.create',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Accrual Posting
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Voucher Transactions
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Cash Flow Transactions
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Closing And Adjustments
		        </p>
		    </a>
		</li>
		
		<li class="nav-item">
		    <a href="{{ route('opening-transactions.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'opening-transactions.index', 'opening-transactions.show', 'opening-transactions.create', 
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Opening Transactions
		        </p>
		    </a>
		</li>

		{{-- <li class="nav-item">
		    <a href="{{ route('trial-balance.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'trial-balance.index'
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Trial Balance
		        </p>
		    </a>
		</li> --}}

		<li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Balance Sheet
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Financial Statement
		        </p>
		    </a>
		</li>


		{{-- <li class="nav-item">
		    <a href="{{ route('journal-names.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'journal-names.index' , 'journal-names.create', 'journal-names.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Journal Names
		        </p>
		    </a>
		</li> --}}
		


		{{-- <li class="nav-item">
		    <a href="{{ route('ledger-reasons.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'ledger-reasons.index' , 'ledger-reasons.create', 'ledger-reasons.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Ledger Reasons
		        </p>
		    </a>
		</li> --}}

	</ul>
</li>