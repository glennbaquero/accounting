@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Purchase Delivery Receipts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('purchase-delivery-receipts.index') }}">Purchase Delivery Receiptss</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <purchase-delivery-receipt-view
            submit-url="{{ route('purchase-delivery-receipts.store', $vendor_invoice->id ?? 'null') }}"
            fetch-url="{{ route('purchase-delivery-receipts.fetch-item', $vendor_invoice->id ?? null) }}"
            :purchase-order="{{ $vendor_invoice ?? '{}' }}"
        ></purchase-delivery-receipt-view>
    </section>
</div>

@endsection