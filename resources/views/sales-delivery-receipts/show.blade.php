@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Sales Delivery Recepit <small>({{ $item->sales_delivery_receipt_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('sales-delivery-receipts.index') }}">Sales Delivery Recepit</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <sales-delivery-receipt-view
            submit-url="{{ route('sales-delivery-receipts.update', $item->id) }}"
            fetch-url="{{ route('sales-delivery-receipts.fetch-item', [$item->sales_order_number ?? 'null', $item->id]) }}"
            generate-invoice-payment-url="{{ route('customer-payments.create', $item->id) }}"
            :show-confirm-button="true"
            customer-payments-approval="{{ route('customer-payments.fetch',  ['pending' => 0, 'invoice_id' => $item->id ]) }}"
            customer-payments-approved="{{ route('customer-payments.fetch',  ['approved_payment' => 1, 'invoice_id' => $item->id ] ) }}"
            customer-payments-posted="{{ route('customer-payments.fetch',  ['posted_payment' => 1, 'invoice_id' => $item->id ] ) }}"
            invoice-approval-url="{{ route('so-invoice-approval-journals.fetch-vouchers', ['sales_delivery_receipt_number' => $item->sales_delivery_receipt_number]) }}"
            customer-payment-url="{{ route('customer-payment-journals.fetch-vouchers', ['sales_delivery_receipt_number' => $item->sales_delivery_receipt_number]) }}"
            general-journal-url="{{ route('general-journal.fetch-vouchers', ['sales_delivery_receipt_number' => $item->sales_delivery_receipt_number]) }}"
            print-url="{{ route('sales-delivery-receipts.print',  ['id' => $item->id ] ) }}"

            payment-schedule-url="{{ route('payment-schedules.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-calculation-url="{{ route('interest-calculations.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-note-url="{{ route('interest-notes.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-setup-url="{{ route('interest-setups.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-adjustment-url="{{ route('interest-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"

            collection-url="{{ route('collections.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            boe-url="{{ route('bills-exchanges.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            boe-adjustment-url="{{ route('bills-exchange-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
        ></sales-delivery-receipt-view>
    </section>
</div>

@endsection