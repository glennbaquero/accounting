<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
        'companies.index' ,'companies.create','companies.show',
        'departments.index' ,'departments.create','departments.show',
        'positions.index' ,'positions.create','positions.show',
        'users.index' ,'users.create','users.show',
        'admin-users.index' ,'admin-users.create','admin-users.show',
        'clients.index' ,'clients.create','clients.show',
        'cost-centers.index' ,'cost-centers.create','cost-centers.show',
    ]) }}">
	<a href="javascript:void(0)" class="nav-link">
        <i class="nav-icon fas fa-user-shield"></i>
	    <p>
	        Company Setup
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('clients.index', ['company' => $self->company() ]) }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'clients.index' ,'clients.create','clients.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Clients
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
            <a href="{{ route('departments.index', ['company' => $self->company() ]) }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'departments.index' ,'departments.create','departments.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Departments
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('positions.index',  ['company' => $self->company() ]) }}" class="nav-link {{ $checker->route->areOnRoutes([
                 'positions.index' ,'positions.create','positions.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                 <p>
                    Positions
                 </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index', ['company' => $self->company() ]) }}" class="nav-link {{ $checker->route->areOnRoutes([
                'users.index' ,'users.create','users.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Users
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin-users.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                'admin-users.index' ,'admin-users.create','admin-users.show',
            ]) }}">
                <i class="nav-icon far fa-circle"></i>
                <p>
                    Admins
                </p>
            </a>
        </li>
	</ul>
</li>