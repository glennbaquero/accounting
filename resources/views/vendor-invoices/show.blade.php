@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Vendor Invoice <small>({{ $item->vendor_invoice_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-invoices.index') }}">Vendor Invoices</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-invoice-view
            submit-url="{{ route('vendor-invoices.update', $item->id) }}"
            fetch-url="{{ route('vendor-invoices.fetch-item', [ $item->purchase_order_number ?? 'null', $item->id ]) }}"
            generate-invoice-payment-url="{{ route('vendor-payments.create', $item->id) }}"
            :show-confirm-button="true"
            vendor-payments-approval="{{ route('vendor-payments.fetch',  ['pending' => 0, 'invoice_id' => $item->id ]) }}"
            vendor-payments-approved="{{ route('vendor-payments.fetch',  ['approved_payment' => 1, 'invoice_id' => $item->id ] ) }}"
            vendor-payments-posted="{{ route('vendor-payments.fetch',  ['posted_payment' => 1, 'invoice_id' => $item->id ] ) }}"
            invoice-approval-url="{{ route('po-invoice-approval-journals.fetch-vouchers', ['vendor_invoice_number' => $item->vendor_invoice_number]) }}"
            vendor-payment-url="{{ route('vendor-payment-journals.fetch-vouchers', ['vendor_invoice_number' => $item->vendor_invoice_number]) }}"
            general-journal-url="{{ route('general-journal.fetch-vouchers', ['vendor_invoice_number' => $item->vendor_invoice_number]) }}"
            print-url="{{ route('vendor-invoices.print',  ['id' => $item->id ] ) }}"

            payment-schedule-url="{{ route('payment-schedules.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-calculation-url="{{ route('interest-calculations.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-note-url="{{ route('interest-notes.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-setup-url="{{ route('interest-setups.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            interest-adjustment-url="{{ route('interest-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"

            collection-url="{{ route('collections.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            boe-url="{{ route('bills-exchanges.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
            boe-adjustment-url="{{ route('bills-exchange-adjustments.fetch',  ['vendor_invoice_id' => $item->id ] ) }}"
        ></vendor-invoice-view>
    </section>
</div>

@endsection