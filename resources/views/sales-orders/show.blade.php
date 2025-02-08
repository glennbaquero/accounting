@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Sales Order <small>({{ $item->sales_order_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('sales-orders.index') }}">Sales Order</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <sales-order-view
            submit-url="{{ route('sales-orders.update', $item->id) }}"
            fetch-url="{{ route('sales-orders.fetch-item', $item->id) }}"
            customer-invoices-approval="{{ route('customer-invoices.fetch',  ['for_approval' => 1, 'so_number' => $item->sales_order_number ]) }}"
            customer-invoices-approved="{{ route('customer-invoices.fetch',  ['approved' => 1, 'so_number' => $item->sales_order_number ] ) }}"
            customer-invoices-posted="{{ route('customer-invoices.fetch',  ['posted' => 1, 'so_number' => $item->sales_order_number ] ) }}"
            customer-payments-approval="{{ route('customer-payments.fetch',  ['pending' => 0, 'so_number' => $item->sales_order_number ]) }}"
            customer-payments-approved="{{ route('customer-payments.fetch',  ['approved_payment' => 1, 'so_number' => $item->sales_order_number ] ) }}"
            customer-payments-posted="{{ route('customer-payments.fetch',  ['posted_payment' => 1, 'so_number' => $item->sales_order_number ] ) }}"
            print-url="{{ route('sales-orders.print',  ['id' => $item->id ] ) }}"

            payment-schedule-url="{{ route('payment-schedules.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-calculation-url="{{ route('interest-calculations.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-note-url="{{ route('interest-notes.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-setup-url="{{ route('interest-setups.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-adjustment-url="{{ route('interest-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"

            collection-url="{{ route('collections.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            boe-url="{{ route('bills-exchanges.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            boe-adjustment-url="{{ route('bills-exchange-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
        ></sales-order-view>
    </section>
</div>

@endsection