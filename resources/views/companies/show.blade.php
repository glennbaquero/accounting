@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Company <small>({{ $item->name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Company</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#company" data-toggle="tab">Company Information</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#departments" data-toggle="tab">Departments</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#positions" data-toggle="tab">Positions</a></li>
                        <li class="nav-item"><a @click="initList('table-4')" class="nav-link" href="#users" data-toggle="tab">Users</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="company">
                            <company-view
                                submit-url="{{ route('companies.update', $item->id) }}"
                                fetch-url="{{ route('companies.fetch-item', $item->id) }}"
                            ></company-view>
                        </div>
                        <div class="tab-pane" id="departments">
                            <department-table
                                fetch-url="{{ route('departments.fetch', ['company' => $item->id ])}}"
                                ref="table-2" 
                                data-table-id="table-2"
                            ></department-table>
                        </div>

                        <div class="tab-pane" id="positions">
                            <position-table
                                fetch-url="{{ route('positions.fetch', ['company' => $item->id ])}}"
                                ref="table-3" 
                                data-table-id="table-3"
                            ></position-table>
                        </div>
                    
                        <div class="tab-pane" id="users">
                            <user-table
                                fetch-url="{{ route('users.fetch', ['company' => $item->id ])}}"
                                ref="table-4" 
                                data-table-id="table-4"
                            ></user-table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection