@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Vendor Payment Journal Vouchers <small>({{ $item->vendor_payment_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-payment-journals.index') }}">Vendor Payment Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        {{-- <subsidiary-view
            invoice-approval-url="{{ route('vendor-payment-journals.fetch-vouchers') }}"
        ></subsidiary-view> --}}
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#vendor-payment-journal" data-toggle="tab">Vendor Payment Journal</a></li>
                        <li class="nav-item"><a class="nav-link" href="#invoice-approval-journal" data-toggle="tab">Invoice Approval Journal</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="vendor-payment-journal">
                            <vendor-payment-journal-view
                                submit-url="{{ route('vendor-payment-journals.update', $item->id) }}"
                                voucher-submit-url="{{ route('vendor-payment-journals.voucher-create', $item->id) }}"
                                fetch-url="{{ route('vendor-payment-journals.fetch-item', $item->id) }}"
                                fetch-voucher-url="{{ route('vendor-payment-journals.fetch-vouchers', ['id' => $item->vendor_payment_journal_number]) }}"
                                :journal-item="{{ $item }}"
                                status-update-url="{{ route('vendor-payment-journals.voucher-status-update') }}"
                                post-url="{{ route('vendor-payment-journals.post', $item->id) }}"
                                journal-validate-url="{{ route('vendor-payment-journals.validate', $item->id) }}"
                                voucher-validate-url="{{ route('vendor-payment-journals.validate-voucher') }}"
                            ></vendor-payment-journal-view>
                        </div>
                        <div class="tab-pane" id="invoice-approval-journal">
                            <invoice-approval-journal-table
                                type="overview" 
                                fetch-url="{{ route('po-invoice-approval-journals.fetch') }}"
                                submit-url="{{ route('po-invoice-approval-journals.store') }}"
                                :cost_centers="{{ $cost_centers }}"
                                :departments="{{ $departments }}"
                                :expense_purposes="{{ $expense_purposes }}"
                                :clients="{{ $clients }}"
                                create-url="{{ route('po-invoice-approval-journals.create') }}"
                                status-update-url="{{ route('po-invoice-approval-journals.header-status-update') }}"
                            ></invoice-approval-journal-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection