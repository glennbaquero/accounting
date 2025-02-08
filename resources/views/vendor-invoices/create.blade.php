@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Vendor Invoice</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vendor-invoices.index') }}">Vendor Invoices</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <vendor-invoice-view
            submit-url="{{ route('vendor-invoices.store', $purchase_order->purchase_order_number ?? 'null') }}"
            fetch-url="{{ route('vendor-invoices.fetch-item', $purchase_order->purchase_order_number ?? null) }}"
            :purchase-order="{{ $purchase_order ?? '{}' }}"
        ></vendor-invoice-view>
    </section>
</div>

@endsection