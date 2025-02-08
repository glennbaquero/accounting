@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Customer Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-payments.index') }}">Customer Payments</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-payment-form
            customer-invoice-code="{{ $customer_invoice_number }}"
            payment-number="{{ $customer_payment_number }}"
            submit-url="{{ route('customer-payments.store') }}"
            fetch-url="{{ route('customer-payments.fetch-item') }}"
        ></customer-payment-form>
    </section>
</div>

@endsection