@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Payment Cancellation Journal <small>({{ $item->payment_cancellation_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('payment-cancellation-journals.index') }}">Payment Cancellation Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Payment Cancellation Journal</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="#payment-cancellation-journal-header" data-toggle="tab">Payment Cancellation Journal Header</a></li>
                        <li class="nav-item"><a class="nav-link" href="#payment-cancellation-journal-vouchers" data-toggle="tab">Payment Cancellation Journal Vouchers</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="payment-cancellation-journal-header">
                            <payment-cancellation-journal-view
                                :clients="{{ $clients }}"
                                fetch-url="{{ route('payment-cancellation-journals.fetch-item', $item->id) }}"
                                submit-url="{{ route('payment-cancellation-journals.update', $item->id) }}"
                            ></payment-cancellation-journal-view>
                        </div>
                        <div class="tab-pane" id="payment-cancellation-journal-vouchers">
                            <div class="mb-4">
                                <payment-cancellation-journal-voucher-create
                                    :clients="{{ $clients }}"
                                    :parent="{{ $item }}"
                                    fetch-url="{{ route('payment-cancellation-journal-vouchers.fetch-item') }}"
                                    submit-url="{{ route('payment-cancellation-journal-vouchers.store') }}"
                                ></payment-cancellation-journal-voucher-create>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#payment-cancellation-journal-voucher-active" data-toggle="tab">Active</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#payment-cancellation-journal-voucher-archived" data-toggle="tab">Archive</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="payment-cancellation-journal-voucher-active">
                                                <payment-cancellation-journal-voucher-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('payment-cancellation-journal-vouchers.fetch', ['bank_reconciliation_id' => $item->bank_reconciliation_id]) }}"
                                                ></payment-cancellation-journal-voucher-table>
                                            </div>
                                            <div class="tab-pane" id="payment-cancellation-journal-voucher-archived">
                                                <payment-cancellation-journal-voucher-table
                                                    :clients="{{ $clients }}"
                                                    :parent="{{ $item }}"
                                                    fetch-url="{{ route('payment-cancellation-journal-vouchers.fetch', ['bank_reconciliation_id' => $item->bank_reconciliation_id, 'archived' => 1]) }}"
                                                ></payment-cancellation-journal-voucher-table>
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