@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Invoice Approval Journal Vouchers <small>({{ $item->invoice_approval_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('po-invoice-approval-journals.index') }}">Invoice Approval Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <invoice-approval-journal-view
            submit-url="{{ route('po-invoice-approval-journals.update', $item->id) }}"
            voucher-submit-url="{{ route('po-invoice-approval-journals.voucher-create', $item->id) }}"
            fetch-url="{{ route('po-invoice-approval-journals.fetch-item', $item->id) }}"
            fetch-voucher-url="{{ route('po-invoice-approval-journals.fetch-vouchers', ['id' => $item->invoice_approval_journal_number, 'status' => 'pending']) }}"
            status-update-url="{{ route('po-invoice-approval-journals.voucher-status-update') }}"
            journal-validate-url="{{ route('po-invoice-approval-journals.validate', $item->id) }}"
            voucher-validate-url="{{ route('po-invoice-approval-journals.validate-voucher', $item->id) }}"
            post-url="{{ route('po-invoice-approval-journals.post', $item->id) }}"
            :journal-item="{{ $item }}"
        ></invoice-approval-journal-view>

    </section>
</div>

@endsection