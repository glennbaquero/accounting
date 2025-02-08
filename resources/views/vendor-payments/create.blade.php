@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Vendor Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-payments.index') }}">Vendor Payments</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-payment-form 
            vendor-invoice-code="{{ $vendor_invoice_number }}"
            payment-number="{{ $vendor_payment_number }}"
            submit-url="{{ route('vendor-payments.store') }}"
            fetch-url="{{ route('vendor-payments.fetch-item') }}"
        ></vendor-payment-form>
    </section>
</div>

@endsection