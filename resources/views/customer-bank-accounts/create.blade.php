@extends('master')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Customer Bank Account</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customer</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Bank Account</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-bank-account-view
            :clients="{{ $clients }}"
            :customer="{{ $customer }}"
            fetch-url="{{ route('customer-bank-accounts.fetch-item') }}"
            submit-url="{{ route('customer-bank-accounts.store') }}"
        ></customer-bank-account-view>
    </section>
</div>

@endsection