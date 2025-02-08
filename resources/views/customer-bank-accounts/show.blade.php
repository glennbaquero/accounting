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
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Bank Account</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-bank-account-view
            :clients="{{ $clients }}"
            :customer="{{ $customer }}"
            fetch-url="{{ route('customer-bank-accounts.fetch-item', $item->id) }}"
            submit-url="{{ route('customer-bank-accounts.update', $item->id) }}"
            customer-payments-pending="{{ route('customer-payments.fetch',  ['pending' => 0, 'bank_account' => $item->bank_account ]) }}"
            customer-payments-approval="{{ route('customer-payments.fetch',  ['approved_payment' => 1, 'bank_account' => $item->bank_account ] ) }}"
            customer-payments-posted="{{ route('customer-payments.fetch',  ['posted_payment' => 1, 'bank_account' => $item->bank_account ] ) }}"
            checks-active="{{ route('checks.fetch', ['customer_bank_account_number' => $item->customer_bank_account_number]) }}"
            checks-archived="{{ route('checks.fetch', ['customer_bank_account_number' => $item->customer_bank_account_number, 'archived' => 1]) }}"
            deposits-active="{{ route('deposits.fetch', ['customer_account' => $item->customer_account]) }}"
            deposits-archived="{{ route('deposits.fetch', ['customer_account' => $item->customer_account, 'archived' => 1]) }}"
            
        ></customer-bank-account-view>
    </section>
</div>

@endsection