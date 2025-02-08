@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Position <small>({{ $item->name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('positions.index') }}">Positions</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#position" data-toggle="tab">Position Information</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#users" data-toggle="tab">Position Users</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="position">
                            <position-view
                                company="{{ $company }}"
                                submit-url="{{ route('positions.update', $item->id) }}"
                                fetch-url="{{ route('positions.fetch-item', $item->id) }}"
                            ></position-view>
                        </div>
                        <div class="tab-pane show" id="users">
                            <user-table
                                ref="table-2"
                                disabled
                                fetch-url="{{ route('users.fetch', ['position' => $item->id]) }}"
                            ></user-table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection