@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Interest Notes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Interest Notes</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('interest-notes.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#interest-notes-active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#interest-notes-archived" data-toggle="tab">Archive</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="interest-notes-active">
                            <interest-note-table
                                ref='table-1'
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('interest-notes.fetch') }}"
                            ></interest-note-table>
                        </div>
                        <div class="tab-pane" id="interest-notes-archived">
                            <interest-note-table
                                ref='table-2'
                                disabled
                                :clients="{{ $clients }}"
                                create-url="{{ route('interest-notes.create') }}"
                                fetch-url="{{ route('interest-notes.fetch-archive') }}"
                            ></interest-note-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection