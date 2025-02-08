@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sales Order Receipt</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('sales-delivery-receipts.index') }}">Sales Order Receipts</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <sales-delivery-receipt-view
            submit-url="{{ route('sales-delivery-receipts.store', $item->sales_order_number ?? 'null') }}"
            fetch-url="{{ route('sales-delivery-receipts.fetch-item', $item->sales_order_number ?? null) }}"
            :sales-order="{{ $item ?? '{}' }}"
        ></sales-delivery-receipt-view>
    </section>
</div>

@endsection