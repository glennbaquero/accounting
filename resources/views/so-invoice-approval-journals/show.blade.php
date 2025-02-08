@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Customer Invoice Journal Voucher <small>({{ $item->customer_invoice_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('so-invoice-approval-journals.index') }}">Customer Invoice Approval Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{-- <subsidiary-view
            first-tab-name="Customer Invoice Subsidiary"
            second-tab-name="Customer Payment Subsidiary"
            invoice-approval-url="{{ route('so-invoice-approval-journals.fetch-vouchers') }}"
        ></subsidiary-view> --}}
        <customer-invoice-approval-journal-view
        fetch-url="{{ route('so-invoice-approval-journals.fetch-item', $item->id) }}"
        fetch-voucher-url="{{ route('so-invoice-approval-journals.fetch-vouchers', ['id' => $item->customer_invoice_journal_number]) }}"
        submit-url="{{ route('so-invoice-approval-journals.update', $item->id) }}"
        voucher-submit-url="{{ route('so-invoice-approval-journals.voucher-create', $item->id) }}"
        status-update-url="{{ route('so-invoice-approval-journals.voucher-status-update') }}"
        journal-validate-url="{{ route('so-invoice-approval-journals.validate', $item->id) }}"
        voucher-validate-url="{{ route('so-invoice-approval-journals.validate-voucher',  $item->id) }}"
        post-url="{{ route('so-invoice-approval-journals.post', $item->id) }}"
        :journal-item="{{ $item }}"
        ></customer-invoice-approval-journal-view>
    </section>
</div>

@endsection