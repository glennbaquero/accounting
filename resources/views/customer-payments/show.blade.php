@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Customer Payment <small>({{$item->customer_payment_number}})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-payments.index') }}">Customer Payments</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-payment-form
            submit-url="{{ route('customer-payments.update', $item->id) }}"
            fetch-url="{{ route('customer-payments.fetch-item', $item->id) }}"
            invoice-approval-url="{{ route('so-invoice-approval-journals.fetch-vouchers', ['customer_invoice_number' => $item->customer_invoice_number]) }}"
            customer-payment-url="{{ route('customer-payment-journals.fetch-vouchers', ['customer_invoice_number' => $item->customer_invoice_number]) }}"
            general-journal-url="{{ route('general-journal.fetch-vouchers', ['customer_invoice_number' => $item->customer_invoice_number]) }}"

            checks-active="{{ route('checks.fetch', ['check_number' => $item->check_number]) }}"
            checks-archived="{{ route('checks.fetch', ['check_number' => $item->check_number, 'archived' => 1]) }}"
        ></customer-payment-form>
    </section>
</div>

@endsection