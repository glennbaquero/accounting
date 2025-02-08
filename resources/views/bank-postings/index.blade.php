@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Bank Postings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Bank Postings</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('bank-postings.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#bank-postings-active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#bank-postings-archived" data-toggle="tab">Archive</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="bank-postings-active">
                            <bank-posting-table
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('bank-postings.fetch') }}"
                            ></bank-posting-table>
                        </div>
                        <div class="tab-pane" id="bank-postings-archived">
                            <bank-posting-table
                                :clients="{{ $clients }}"
                                create-url="{{ route('bank-postings.create') }}"
                                fetch-url="{{ route('bank-postings.fetch', ['archived' => 1]) }}"
                            ></bank-posting-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection