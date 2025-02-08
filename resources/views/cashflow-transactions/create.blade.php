@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Cash Regsiter Transaction</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('cashflow-transactions.index') }}">Cash Regsiter Transactions</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <cashflow-transaction-view
        submit-url="{{ route('cashflow-transactions.store') }}"
        fetch-url="{{ route('cashflow-transactions.fetch-item') }}"
        ></cashflow-transaction-view>
    </section>
</div>

@endsection