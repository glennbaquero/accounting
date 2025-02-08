@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Customers </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Customers</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('customers.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#active" href="javascript:void(0)" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#archived" href="javascript:void(0)" data-toggle="tab">Archived</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="active">
                            <customer-table 
                                :clients="{{ $clients }}"
                                ref="table-1"
                                fetch-url="{{ route('customers.fetch') }}"
                            ></customer-table>
                        </div>
                       
                        <div class="tab-pane" id="archived">
                            <customer-table 
                            :clients="{{ $clients }}"
                            ref="table-2"
                            fetch-url="{{ route('customers.fetch-archive') }}"
                            ></customer-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection