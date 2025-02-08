@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Department <small>({{ $item->name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Department</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#department" data-toggle="tab">Department Information</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#positions" data-toggle="tab">Department Positions</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#users" data-toggle="tab">Department Users</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="department">
                            <department-view
                                company="{{ $company }}"
                                submit-url="{{ route('departments.update', $item->id) }}"
                                fetch-url="{{ route('departments.fetch-item', $item->id) }}"
                            ></department-view>
                        </div>
                        <div class="tab-pane" id="positions">
                            <position-table
                                ref="table-2" 
                                disabled
                                fetch-url="{{ route('positions.fetch', ['department' => $item->id]) }}"
                            ></position-table>
                        </div>
                        <div class="tab-pane" id="users">
                            <user-table
                                ref="table-3" 
                                disabled
                                fetch-url="{{ route('users.fetch', ['department' => $item->id]) }}"
                            ></user-table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection