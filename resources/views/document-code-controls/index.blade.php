@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Document Code Controls </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Document Code Controls</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('document-code-controls.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#active" href="javascript:void(0)" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#inactive" href="javascript:void(0)" data-toggle="tab">Inactive</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#archived" href="javascript:void(0)" data-toggle="tab">Archived</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="active">
                            <document-code-control-table 
                            ref="table-1"
                            fetch-url="{{ route('document-code-controls.fetch', ['status' => 1]) }}"
                            ></document-code-control-table>
                        </div>

                        <div class="tab-pane show" id="inactive">
                            <document-code-control-table 
                            ref="table-2"
                            fetch-url="{{ route('document-code-controls.fetch', ['status' => 0]) }}"
                            ></document-code-control-table>
                        </div>
                       
                        <div class="tab-pane show" id="archived">
                            <document-code-control-table 
                            ref="table-3"
                            fetch-url="{{ route('document-code-controls.fetch-archive') }}"
                            ></document-code-control-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection