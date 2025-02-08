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
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Bank Account</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <client-bank-account-view
            :clients="{{ $clients }}"
            fetch-url="{{ route('client-bank-accounts.fetch-item', $item->id) }}"
            submit-url="{{ route('client-bank-accounts.update', $item->id) }}"
            vendor-payments-pending="{{ route('vendor-payments.fetch',  ['pending' => 0, 'bank_account' => $item->bank_account ]) }}"
            vendor-payments-approval="{{ route('vendor-payments.fetch',  ['approved_payment' => 1, 'bank_account' => $item->bank_account ] ) }}"
            vendor-payments-posted="{{ route('vendor-payments.fetch',  ['posted_payment' => 1, 'bank_account' => $item->bank_account ] ) }}"
            customer-payments-pending="{{ route('customer-payments.fetch',  ['pending' => 0, 'bank_account' => $item->bank_account ]) }}"
            customer-payments-approval="{{ route('customer-payments.fetch',  ['approved_payment' => 1, 'bank_account' => $item->bank_account ] ) }}"
            customer-payments-posted="{{ route('customer-payments.fetch',  ['posted_payment' => 1, 'bank_account' => $item->bank_account ] ) }}"
            checks-active="{{ route('checks.fetch', ['client_bank_account_number' => $item->bank_account]) }}"
            checks-archived="{{ route('checks.fetch', ['client_bank_account_number' => $item->bank_account, 'archived' => 1]) }}"
            deposits-active="{{ route('deposits.fetch', ['client_bank_account_number' => $item->bank_account]) }}"
            deposits-archived="{{ route('deposits.fetch', ['client_bank_account_number' => $item->bank_account, 'archived' => 1]) }}"

            bank-transactions-active="{{ route('bank-account-transactions.fetch', ['client_bank_account_number' => $item->bank_account]) }}"
            bank-transactions-archived="{{ route('bank-account-transactions.fetch', ['client_bank_account_number' => $item->bank_account, 'archived' => 1]) }}"
        ></client-bank-account-view>
    </section>
</div>

@endsection