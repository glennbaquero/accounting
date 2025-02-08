@extends('master')

@section('content')

@php

    $vendor_invoice_number = $item->vendor_invoice ? $item->vendor_invoice->vendor_invoice_number : '';

@endphp

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Vendor Payment <small>({{$item->vendor_payment_number}})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-payments.index') }}">Vendor Payments</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-payment-form 
            submit-url="{{ route('vendor-payments.update', $item->id) }}"
            fetch-url="{{ route('vendor-payments.fetch-item', $item->id) }}"
            invoice-approval-url="{{ route('po-invoice-approval-journals.fetch-vouchers', ['vendor_invoice_number' => $vendor_invoice_number]) }}"
            vendor-payment-url="{{ route('vendor-payment-journals.fetch-vouchers', ['vendor_invoice_number' => $vendor_invoice_number]) }}"
            general-journal-url="{{ route('general-journal.fetch-vouchers', ['vendor_invoice_number' => $vendor_invoice_number]) }}"
            
            checks-active="{{ route('checks.fetch', ['check_number' => $item->check_number]) }}"
            checks-archived="{{ route('checks.fetch', ['check_number' => $item->check_number, 'archived' => 1]) }}"
        ></vendor-payment-form>
    </section>
</div>

@endsection