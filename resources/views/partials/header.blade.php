<nav class="main-header navbar navbar-expand border-bottom navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-dark" data-widget="pushmenu" href="javascript:void(0)"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link" href="">
                <i class="fa fa-bell mr-2 fa-lg text-warning mt-2"></i>
                <span class="badge badge-pill badge-danger navbar-badge ml-1">0</span>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
           

            <div class="dropdown nav-link dropleft">
                <i class="far fa-caret-square-down fa-lg mt-2"  data-toggle="dropdown"></i>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item disabled" href="#">My Account</a>
                  <a class="dropdown-item disabled" href="#">Activity Logs</a>
                  <a class="dropdown-item" href="{{ route('logout')}}">Logout</a>
                </div>
            </div>
              
           
        </li>

    </ul>
</nav>
