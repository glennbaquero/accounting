@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Admins </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Admins</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('admin-users.create', 'company-admin') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create Company Admin
            </a>

            <a href="{{ route('admin-users.create', 'system-admin') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create System Admin
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#company-admins" href="javascript:void(0)" data-toggle="tab">Company Admin</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#system-admins" href="javascript:void(0)" data-toggle="tab">System Admin</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="company-admins">
                            <admin-user-table 
                            ref="table-1"
                            table-type="company-admin"
                            fetch-url="{{ route('admin-users.fetch', ['company-admin' => 1]) }}"
                            ></admin-user-table>
                        </div>
                        <div class="tab-pane show" id="system-admins">
                            <admin-user-table 
                            ref="table-2"
                            disabled
                            type="system-admin"
                            fetch-url="{{ route('admin-users.fetch', ['system-admin' => 1]) }}"
                            ></admin-user-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection