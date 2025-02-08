@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Sales Order Return Journal Voucher <small>({{ $item->journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('sales-return-journals.index') }}">Sales Order Return Approval Journals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{-- <subsidiary-view
            first-tab-name="Sales Order Return Subsidiary"
            second-tab-name="Customer Payment Subsidiary"
            invoice-approval-url="{{ route('sales-return-journals.fetch-vouchers') }}"
        ></subsidiary-view> --}}
        <sales-return-journal-view
        fetch-url="{{ route('sales-return-journals.fetch-item', $item->id) }}"
        fetch-voucher-url="{{ route('sales-return-journals.fetch-vouchers', ['id' => $item->journal_number]) }}"
        submit-url="{{ route('sales-return-journals.update', $item->id) }}"
        voucher-submit-url="{{ route('sales-return-journals.voucher-create', $item->id) }}"
        status-update-url="{{ route('sales-return-journals.voucher-status-update') }}"
        journal-validate-url="{{ route('sales-return-journals.validate', $item->id) }}"
        voucher-validate-url="{{ route('sales-return-journals.validate-voucher',  $item->id) }}"
        post-url="{{ route('sales-return-journals.post', $item->id) }}"
        :journal-item="{{ $item }}"
        ></sales-return-journal-view>
    </section>
</div>

@endsection