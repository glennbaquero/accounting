@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Journal Voucher <small>({{ $item->bill_exchange_journal_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bill-of-exchanges.index') }}">Bill of Exchange</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Vouchers</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bill-of-exchange-view
            submit-url="{{ route('bill-of-exchanges.update', $item->id) }}"
            voucher-submit-url="{{ route('bill-of-exchanges.voucher-create', $item->id) }}"
            fetch-url="{{ route('bill-of-exchanges.fetch-item', $item->id) }}"
            :journal-item="{{ $item }}"

            fetch-voucher-url="{{ route('bill-of-exchanges.fetch-vouchers', ['id' => $item->bill_exchange_journal_number]) }}"
            status-update-url="{{ route('bill-of-exchanges.voucher-status-update') }}"
            journal-validate-url="{{ route('bill-of-exchanges.validate', $item->id) }}"
            voucher-validate-url="{{ route('bill-of-exchanges.validate-voucher') }}"
            post-url="{{ route('bill-of-exchanges.post', $item->id) }}"
        ></bill-of-exchange-view>
    </section>
</div>

@endsection