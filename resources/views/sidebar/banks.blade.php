<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
        'vendor-bank-accounts.index' ,'vendor-bank-accounts.create','vendor-bank-accounts.show',
        'customer-bank-accounts.index' ,'customer-bank-accounts.create','customer-bank-accounts.show',
        'bank-reasons.index' ,'bank-reasons.create','bank-reasons.show',
        'client-bank-accounts.index' ,'client-bank-accounts.create','client-bank-accounts.show',
        'deposits.index' ,'deposits.create','deposits.show',
        'checks.index' ,'checks.create','checks.show',
        'bank-account-transactions.index' ,'bank-account-transactions.create','bank-account-transactions.show',
        'bank-account-statements.index' ,'bank-account-statements.create','bank-account-statements.show',
        'bank-account-statement-lines.index' ,'bank-account-statement-lines.create','bank-account-statement-lines.show',
        'cashflow-transactions.index' ,'cashflow-transactions.create','cashflow-transactions.show',
        'bank-reconciliations.index', 'bank-reconciliations.index', 'bank-reconciliations.create', 'bank-reconciliations.show', 'bank-reconciliations.form',
        'bank-postings.index' ,'bank-postings.create','bank-postings.show',
        'bank-reconciliation-journals.index' ,'bank-reconciliation-journals.create','bank-reconciliation-journals.show',
        'payment-reversals.index' ,'payment-reversals.create','payment-reversals.show',
        'payment-cancellation-journals.index' ,'payment-cancellation-journals.create','payment-cancellation-journals.show',
        
        'collections.index' ,'collections.create','collections.show',
        'interest-adjustments.index' ,'interest-adjustments.create','interest-adjustments.show',
        'bank-documents.index' ,'bank-documents.create','bank-documents.show',
    ]) }}">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-university"></i>
	    <p>
	        Cash and Bank
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('client-bank-accounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'client-bank-accounts.index' ,'client-bank-accounts.create','client-bank-accounts.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Client Bank
                 </p>
            </a>
        </li>
		<li class="nav-item">
		 	<a href="{{ route('vendor-bank-accounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'vendor-bank-accounts.index' ,'vendor-bank-accounts.create','vendor-bank-accounts.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Vendor Bank
                </p>
            </a>
		</li>
        <li class="nav-item">
            <a href="{{ route('customer-bank-accounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'customer-bank-accounts.index' ,'customer-bank-accounts.create','customer-bank-accounts.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Customer Bank
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bank-documents.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'bank-documents.index' ,'bank-documents.create','bank-documents.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Bank Document
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bank-account-transactions.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'bank-account-transactions.index' ,'bank-account-transactions.create','bank-account-transactions.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Bank Account Transactions
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bank-account-statements.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'bank-account-statements.index' ,'bank-account-statements.create','bank-account-statements.show',
                'bank-account-statement-lines.index' ,'bank-account-statement-lines.create','bank-account-statement-lines.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Bank Account Statements
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('cashflow-transactions.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'cashflow-transactions.index' ,'cashflow-transactions.create','cashflow-transactions.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Cash Register Transactions
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bank-reconciliations.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'bank-reconciliations.index','bank-reconciliations.show','bank-reconciliations.create', 'bank-reconciliations.form',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Bank Reconciliations
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bank-reconciliation-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'bank-reconciliation-journals.index','bank-reconciliation-journals.show','bank-reconciliation-journals.create', 'bank-reconciliation-journals.form',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Bank Reconciliation Journal
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('deposits.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'deposits.index' ,'deposits.create','deposits.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Deposit Slips
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('checks.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'checks.index' ,'checks.create','checks.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Checks
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bank-reasons.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'bank-reasons.index' ,'bank-reasons.create','bank-reasons.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Bank Reasons
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bank-postings.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'bank-postings.index' ,'bank-postings.create','bank-postings.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Bank Postings
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('payment-reversals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'payment-reversals.index' ,'payment-reversals.create','payment-reversals.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Payment Reversals
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('payment-cancellation-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'payment-cancellation-journals.index' ,'payment-cancellation-journals.create','payment-cancellation-journals.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Payment Cancellation Journals
                </p>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="{{ route('bills-exchanges.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'bills-exchanges.index' ,'bills-exchanges.create','bills-exchanges.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Bills of Exchange
                </p>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ route('collections.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'collections.index' ,'collections.create','collections.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Collections
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('interest-adjustments.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'interest-adjustments.index' ,'interest-adjustments.create','interest-adjustments.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Interest Adjustments
                 </p>
            </a>
        </li>
	</ul>
</li>