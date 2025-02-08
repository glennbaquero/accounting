<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
        'products.index' ,'products.create','products.show',
        'variants.index' ,'variants.create','variants.show',
        'inventory-on-hands.index','inventory-on-hands.show',
        'procurements.index' ,'procurements.create','procurements.show',
        'inventory-journals.index' ,'inventory-journals.create','inventory-journals.show',
        'specifications.index' ,'specifications.create','specifications.show',
    ]) }}">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-pen-square"></i>
	    <p>
	        Product & Inventory
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'products.index' ,'products.create','products.show',
                'variants.index' ,'variants.create','variants.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Items - Products
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('specifications.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'specifications.index' ,'specifications.create','specifications.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Product Specifications
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('inventory-on-hands.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
               'inventory-on-hands.index','inventory-on-hands.show'
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Inventory On Hand
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('inventory-journals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'inventory-journals.index' ,'inventory-journals.create','inventory-journals.show',
                
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Inventory Journal
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('procurements.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'procurements.index' ,'procurements.create','procurements.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Procurement
                </p>
            </a>
        </li>
	</ul>
</li>