@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Cash Register transaction <small>({{ $item->cashflow_transaction_id }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('cashflow-transactions.index') }}">Cash Register transactions</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#cashflow-transactions" data-toggle="tab">Cash Register transactions</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" href="#bank-account-statement-lines" data-toggle="tab">Cashflow transaction Lines</a></li> --}}
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="cashflow-transactions">
                            <cashflow-transaction-view
                                submit-url="{{ route('cashflow-transactions.update', $item->id) }}"
                                fetch-url="{{ route('cashflow-transactions.fetch-item', $item->id) }}"
                            ></cashflow-transaction-view>
                        </div>
                        <div class="tab-pane" id="bank-account-statement-lines">
                            {{-- <div class="mb-4">
                                <a href="{{ route('bank-account-statement-lines.create', $item->id) }}" class="btn btn-primary text-white">
                                    <i class="fa fa-plus"></i>
                                    Create
                                </a>
                            </div>

                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#active" data-toggle="tab">Active</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#archived" data-toggle="tab">Archive</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="active">
                                            <bank-account-statement-line-table
                                                fetch-url="{{ route('bank-account-statement-lines.fetch', ['statement_id' => $item->bank_statement_id]) }}"
                                            ></bank-account-statement-line-table>
                                        </div>
                                        <div class="tab-pane" id="archived">
                                            <bank-account-statement-line-table
                                                fetch-url="{{ route('bank-account-statement-lines.fetch', ['archived' => 1]) }}"
                                            ></bank-account-statement-line-table>
                                        </div>
                                    </div>
                                </div>

                            </div> --}}
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection