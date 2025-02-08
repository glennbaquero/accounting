@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Promissory Notes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Promissory Notes</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('purchase-promissory-notes.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#purchase-promissory-notes-active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#purchase-promissory-notes-archived" data-toggle="tab">Archive</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="purchase-promissory-notes-active">
                            <purchase-promissory-note-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('purchase-promissory-notes.fetch') }}"
                            ></purchase-promissory-note-table>
                        </div>
                        <div class="tab-pane" id="purchase-promissory-notes-archived">
                            <purchase-promissory-note-table
                                :clients="{{ $clients }}"
                                create-url="{{ route('purchase-promissory-notes.create') }}"
                                fetch-url="{{ route('purchase-promissory-notes.fetch', ['archived' => 1]) }}"
                            ></purchase-promissory-note-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection