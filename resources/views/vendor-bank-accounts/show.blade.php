@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Bank Account <small>({{ $item->bank_name }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Bank Account</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-bank-account-view
            :clients="{{ $clients }}"
            :vendor="{{ $vendor }}"
            fetch-url="{{ route('vendor-bank-accounts.fetch-item', $item->id) }}"
            submit-url="{{ route('vendor-bank-accounts.update', $item->id) }}"
            vendor-payments-pending="{{ route('vendor-payments.fetch',  ['pending' => 0, 'vendor_account' => $item->vendor_account ]) }}"
            vendor-payments-approval="{{ route('vendor-payments.fetch',  ['approved_payment' => 1, 'vendor_account' => $item->vendor_account ] ) }}"
            vendor-payments-posted="{{ route('vendor-payments.fetch',  ['posted_payment' => 1, 'vendor_account' => $item->vendor_account ] ) }}"
            checks-active="{{ route('checks.fetch', ['vendor_bank_account_number' => $item->bank_account]) }}"
            checks-archived="{{ route('checks.fetch', ['vendor_bank_account_number' => $item->bank_account, 'archived' => 1]) }}"
        ></vendor-bank-account-view>
    </section>
</div>

@endsection