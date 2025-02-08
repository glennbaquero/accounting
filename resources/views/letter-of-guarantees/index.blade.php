@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Letter of Guarantees </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Letter of Guarantees</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="mb-4">
            <a href="{{ route('letter-of-guarantees.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#letter-of-guarantees-active" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#letter-of-guarantees-archived" data-toggle="tab">Archive</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="letter-of-guarantees-active">
                            <letter-of-guarantees-table 
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('letter-of-guarantees.fetch') }}"
                                ref="table-1"
                            ></letter-of-guarantees-table>
                        </div>
                        <div class="tab-pane" id="letter-of-guarantees-archived">
                            <letter-of-guarantees-table 
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('letter-of-guarantees.fetch-archive') }}"
                                ref="table-2"
                                disabled
                            ></letter-of-guarantees-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection