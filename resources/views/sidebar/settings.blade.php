<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
        'terms.index' ,'terms.create','terms.show',
        'payment-methods.index' ,'payment-methods.create','payment-methods.show',
         'products.index' ,'products.create','products.show',
         'departments.index' ,'departments.create','departments.show',
         'positions.index' ,'positions.create','positions.show',
         cost-centers.index' ,'cost-centers.create','cost-centers.show',
         cash-discounts.index' ,'cash-discounts.create','cash-discounts.show',
         'payment-days.index' ,'payment-days.create','payment-days.show',
    ]) }}">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-pen-square"></i>
	    <p>
	        Settings
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
                    Terms
                </p>
            </a>
        </li>
		<li class="nav-item">
            <a href="{{ route('payment-methods.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'payment-methods.index' ,'payment-methods.create','payment-methods.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Payment Methods
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'products.index' ,'products.create','products.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Products
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('departments.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'departments.index' ,'departments.create','departments.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Departments
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('positions.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'positions.index' ,'positions.create','positions.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Positions
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('cost-centers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'cost-centers.index' ,'cost-centers.create','cost-centers.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Cost centers
                 </p>
            </a>
        </li>
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
	</ul>
</li>