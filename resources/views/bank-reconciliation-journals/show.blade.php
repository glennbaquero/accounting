@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Bank Reconciliation Journal <small>({{ $item->bank_reconciliation_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-reconciliation-journals.index') }}">Bank Reconciliation Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Bank Reconciliation Journal</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#bank-reconciliation-journal-header" data-toggle="tab">Bank Reconciliation Journal Header</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bank-reconciliation-journal-vouchers" data-toggle="tab">Bank Reconciliation Journal Vouchers</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="bank-reconciliation-journal-header">
                            <bank-reconciliation-journal-view
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('bank-reconciliation-journals.fetch-item', $item->id) }}"
                                submit-url="{{ route('bank-reconciliation-journals.update', $item->id) }}"
                            ></bank-reconciliation-journal-view>
                        </div>
                        <div class="tab-pane" id="bank-reconciliation-journal-vouchers">
                            <div class="mb-4">
                                <bank-reconciliation-journal-voucher-create
                                    :clients="{{ $clients }}"
                                    :parent="{{ $item }}"
                                    fetch-url="{{ route('bank-reconciliation-journal-vouchers.fetch-item') }}"
                                    submit-url="{{ route('bank-reconciliation-journal-vouchers.store') }}"
                                ></bank-reconciliation-journal-voucher-create>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#bank-reconciliation-journal-voucher-active" data-toggle="tab">Active</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#bank-reconciliation-journal-voucher-archived" data-toggle="tab">Archive</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="bank-reconciliation-journal-voucher-active">
                                                <bank-reconciliation-journal-voucher-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('bank-reconciliation-journal-vouchers.fetch', ['bank_reconciliation_id' => $item->id]) }}"
                                                ></bank-reconciliation-journal-voucher-table>
                                            </div>
                                            <div class="tab-pane" id="bank-reconciliation-journal-voucher-archived">
                                                <bank-reconciliation-journal-voucher-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('bank-reconciliation-journal-vouchers.fetch', ['bank_reconciliation_id' => $item->id, 'archived' => 1]) }}"
                                                ></bank-reconciliation-journal-voucher-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </section>
</div>

@endsection