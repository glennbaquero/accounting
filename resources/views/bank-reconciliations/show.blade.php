@extends('master')

@section('content')

@php

$item->reconciled_by = $item->renderReconciledBy();

@endphp

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Bank Reconciliation <small>({{ $item->bank_reconciliation_id }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-reconciliations.form') }}">Bank Reconciliations</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Bank Reconciliation</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#bank-reconciliation-header" data-toggle="tab">Bank Reconciliation Header</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bank-reconciliation-lines" data-toggle="tab">Bank Reconciliation Lines</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="bank-reconciliation-header">
                            <bank-reconciliation-view
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('bank-reconciliations.fetch-item', $item->id) }}"
                                submit-url="{{ route('bank-reconciliations.update', $item->id) }}"
                                approved-url="{{ route('bank-reconciliations.approved', $item->id) }}"
                            ></bank-reconciliation-view>
                        </div>
                        <div class="tab-pane" id="bank-reconciliation-lines">
                            <div class="mb-4">
                                <bank-reconciliation-line-create
                                    :clients="{{ $clients }}"
                                    :parent="{{ $item }}"
                                    fetch-url="{{ route('bank-reconciliation-lines.fetch-item') }}"
                                    submit-url="{{ route('bank-reconciliation-lines.store') }}"
                                ></bank-reconciliation-line-create>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#bank-reconciliation-lines-active" data-toggle="tab">Active</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#bank-reconciliation-lines-archived" data-toggle="tab">Archive</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="bank-reconciliation-lines-active">
                                                <bank-reconciliation-line-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('bank-reconciliation-lines.fetch', ['bank_reconciliation_id' => $item->bank_reconciliation_id]) }}"
                                                    post-url="{{ route('bank-reconciliations.post', $item->id) }}"
                                                ></bank-reconciliation-line-table>
                                            </div>
                                            <div class="tab-pane" id="bank-reconciliation-lines-archived">
                                                <bank-reconciliation-line-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('bank-reconciliations.fetch', ['bank_reconciliation_id' => $item->bank_reconciliation_id, 'archived' => 1]) }}"
                                                ></bank-reconciliation-line-table>
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