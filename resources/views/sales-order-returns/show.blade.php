@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Sales Order Return <small>({{ $item->sales_order_return_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('sales-order-returns.index') }}">Sales Order Return</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <sales-order-returns-view
            submit-url="{{ route('sales-order-returns.update', $item->id) }}"
            fetch-url="{{ route('sales-order-returns.fetch-item', $item->id) }}"
            customer-invoices-approval="{{ route('customer-invoices.fetch',  ['for_approval' => 1, 'so_number' => $item->sales_order_return_number ]) }}"
            customer-invoices-approved="{{ route('customer-invoices.fetch',  ['approved' => 1, 'so_number' => $item->sales_order_return_number ] ) }}"
            customer-invoices-posted="{{ route('customer-invoices.fetch',  ['posted' => 1, 'so_number' => $item->sales_order_return_number ] ) }}"
            customer-payments-approval="{{ route('customer-payments.fetch',  ['pending' => 0, 'so_number' => $item->sales_order_return_number ]) }}"
            customer-payments-approved="{{ route('customer-payments.fetch',  ['approved_payment' => 1, 'so_number' => $item->sales_order_return_number ] ) }}"
            customer-payments-posted="{{ route('customer-payments.fetch',  ['posted_payment' => 1, 'so_number' => $item->sales_order_return_number ] ) }}"
            print-url="{{ route('sales-order-returns.print',  ['id' => $item->id ] ) }}"
        ></sales-order-returns-view>
    </section>
</div>

@endsection