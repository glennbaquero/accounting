<li class="nav-item has-treeview">
	<a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([]) }}">
	    <i class="nav-icon fas fa-pen-square"></i>
	    <p>
	        Content Management
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>
	<ul class="nav nav-treeview">
		 <li class="nav-item">
		     <a href="{{ route('chart-of-accounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		         'chart-of-accounts.index' , 'chart-of-accounts.create', 'chart-of-accounts.show',
		         'main-accounts.index' , 'main-accounts.create', 'main-accounts.show',
		     ]) }}">
		         <i class="nav-icon fas fa-file-invoice"></i>
		         <p>
		             Chart of accounts
		         </p>
		     </a>
		 </li>
	</ul>
</li>