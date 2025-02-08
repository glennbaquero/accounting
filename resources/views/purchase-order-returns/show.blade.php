@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Purchase Order Return <small>({{ $item->purchase_order_return_number }})</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('purchase-order-returns.index') }}">Purchase Order Return</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <purchase-order-return-view
            submit-url="{{ route('purchase-order-returns.update', $item->id) }}"
            {{-- confirmation-url="{{ route('purchase-order-returns.confirmation', $item->id) }}" --}}
            fetch-url="{{ route('purchase-order-returns.fetch-item', $item->id) }}"
            vendor-invoices-approval="{{ route('vendor-invoices.fetch',  ['for_approval' => 1, 'po_number' => $item->purchase_order_number ]) }}"
            vendor-invoices-approved="{{ route('vendor-invoices.fetch',  ['approved' => 1, 'po_number' => $item->purchase_order_number ] ) }}"
            vendor-invoices-posted="{{ route('vendor-invoices.fetch',  ['posted' => 1, 'po_number' => $item->purchase_order_number ] ) }}"
            vendor-payments-approval="{{ route('vendor-payments.fetch',  ['pending' => 0, 'po_number' => $item->purchase_order_number ]) }}"
            vendor-payments-approved="{{ route('vendor-payments.fetch',  ['approved_payment' => 1, 'po_number' => $item->purchase_order_number ] ) }}"
            vendor-payments-posted="{{ route('vendor-payments.fetch',  ['posted_payment' => 1, 'po_number' => $item->purchase_order_number ] ) }}"
            print-url="{{ route('purchase-order-returns.print',  ['id' => $item->id ] ) }}"
        ></purchase-order-return-view>
    </section>
</div>

@endsection