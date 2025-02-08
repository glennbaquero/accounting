@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> Transaction Posting Lines</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Transaction Posting Lines</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('transaction-postings.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#table-1" href="javascript:void(0)" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#table-2" href="javascript:void(0)" data-toggle="tab">Archived</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="table-1">
                            <transaction-posting-table 
                                ref="table-1"
                                fetch-url="{{ route('transaction-postings.fetch') }}"
                            ></ransaction-posting-table>
                        </div>
                        <div class="tab-pane" id="table-2">
                            <transaction-posting-table 
                                ref="table-2"
                                disabled
                                fetch-url="{{ route('transaction-postings.fetch-archive') }}"
                            ></transaction-posting-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection