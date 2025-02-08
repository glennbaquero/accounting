<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="" class="brand-link text-center">
        @include('partials.brand')
    </a>

    <div class="sidebar">
        @if (auth()->check())
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('images/default-image.jpg') }}" class="img-circle elevation-2" style="width: 35px; height: 35px;">
                </div>
                <div class="info">
                    <a href="" class="d-block">
                        {{ $self->renderName() }}
                    </a>
                </div>
            </div>
        @endif

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{-- <li class="nav-item">
                    <a href="{{ route('invoices.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'invoices.index' ,'invoices.create','invoices.show',
                    ]) }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                            Invoices
                        </p>
                    </a>
                </li> --}}
                
                @if(!$self->hasRole(['Super Admin', 'Admin']))
                <li class="nav-item">
                    <a href="{{ route('dashboards.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'dashboards.index'
                    ]) }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @include('sidebar.product-inventory')
                
                <li class="nav-item">
                    <a href="{{ route('services.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'services.index' , 'services.create', 'services.show'
                    ]) }}">
                        <i class="nav-icon fas fa-house-user"></i>
                        <p>
                            Services
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('charges.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'charges.index' , 'charges.create', 'charges.show'
                    ]) }}">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>
                            Charges
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('discounts.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'discounts.index' , 'discounts.create', 'discounts.show'
                    ]) }}">
                        <i class="nav-icon fas fa-percentage"></i>
                        <p>
                            Discount
                        </p>
                    </a>
                </li>
 
                @include('sidebar.purchasing')
                @include('sidebar.sales')
                @include('sidebar.banks')
                @include('sidebar.accounts-payable')
                @include('sidebar.accounts-receivable')
                @include('sidebar.gl-menus')
                @include('sidebar.ledger-setups')
                @include('sidebar.journal-setup')

                @if($self->hasRole(['Company Admin']))
                @include('sidebar.company-setup')
                @endif

                @endif

                @if($self->hasRole(['Super Admin', 'Admin']))
                    @include('sidebar.admin-setup')
                @endif
            </ul>
        </nav>

    </div>
</aside>