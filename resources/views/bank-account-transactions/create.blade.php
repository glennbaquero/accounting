@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Bank Account Transaction</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bank-account-transactions.index') }}">Bank Account Transactions</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-account-transaction-view
        :clients="{{ $clients }}"
        submit-url="{{ route('bank-account-transactions.store') }}"
        fetch-url="{{ route('bank-account-transactions.fetch-item') }}"
        ></bank-account-transaction-view>
    </section>
</div>

@endsection