@extends('master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Customer Invoice</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('customer-invoices.index') }}">Customer Invoices</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <customer-invoice-view
            submit-url="{{ route('customer-invoices.store', $sales_order->sales_order_number ?? 'null') }}"
            fetch-url="{{ route('customer-invoices.fetch-item', $sales_order->sales_order_number ?? null) }}"
            :sales-order="{{ $sales_order ?? '{}' }}"
        ></customer-invoice-view>
    </section>
</div>

@endsection