@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Bank Reconciliations</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Bank Reconciliations</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#bank-reconciliations" data-toggle="tab">Bank Reconciliations</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bank-reconciliation-details" data-toggle="tab">Bank Reconciliation Details</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="bank-reconciliations">
                            <div class="mb-4">
                                {{-- <a href="{{ route('bank-reconciliations.create') }}" class="btn btn-primary text-white"> --}}
                                <a href="{{ route('bank-reconciliations.form') }}" class="btn btn-primary text-white">
                                    <i class="fa fa-plus"></i>
                                    Create
                                </a>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#bank-reconciliations-active" data-toggle="tab">Active</a></li>
                                            <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#bank-reconciliations-archived" data-toggle="tab">Archive</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="bank-reconciliations-active">
                                                <bank-reconciliation-table
                                                    :clients="{{ $clients }}"
                                                    fetch-url="{{ route('bank-reconciliations.fetch') }}"
                                                ></bank-reconciliation-table>
                                            </div>
                                            <div class="tab-pane" id="bank-reconciliations-archived">
                                                <bank-reconciliation-table
                                                    :clients="{{ $clients }}"
                                                    create-url="{{ route('bank-reconciliations.create') }}"
                                                    fetch-url="{{ route('bank-reconciliations.fetch', ['archived' => 1]) }}"
                                                ></bank-reconciliation-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="bank-reconciliation-details">
                            <bank-reconciliation-details
                                fetch-url="{{ route('bank-reconciliations.fetch-details') }}"
                            ></bank-reconciliation-details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection